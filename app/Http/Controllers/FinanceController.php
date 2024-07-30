<?php

namespace App\Http\Controllers;

use App\Models\M_currency;
use App\Models\M_tender_status_journey;
use App\Models\M_uom;
use App\Models\M_delpoint;
use App\Models\M_term;
use App\Models\User;
use App\Models\TrxTender as Sales;
use App\Models\TrxTender;
use App\Models\TrxTenderBgGuarantee;
use App\Models\TrxTenderMaterial as ProcurementMaterial;
use App\Models\TrxTenderMaterialPrice;
use App\Models\TrxTenderRfq;
use App\Models\TrxTenderRfqPrinciple;
use App\Models\TrxTenderRfqDelpoint;
use App\Models\TrxTenderRfqTermComp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\RfqMail;
use App\Models\M_cusprin;
use App\Models\TrxTenderPo;
use App\Models\TrxTenderPoDelpoint;
use App\Models\TrxTenderPoTerm;
use Illuminate\Support\Facades\Session;
use PDF;
use Storage;
use Mail;

class FinanceController extends Controller
{

    public $user;


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        // TrxTenderMaterial
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('finance.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }
        if ($request->ajax()) {
            $data = Sales::with(['customer', 'userCreator'])->latest();
            if (!in_array($this->user->roles->pluck('id')->toArray()[0], [1,5])) {
                $data = $data->where('created_by', $this->user->id);
            }
            // Check if category is present in the request and filter accordingly
            if ($request->has('category') && $request->category == 'pra-quotation') {
                $data = $data->whereIn('status_journey', ['2', '3', '4']); // Adjust 'category' to match your database column
            }else{
                $data = $data->whereNotIn('status_journey', ['0', '1', '2', '3', '4']);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('deadline', function($row) {
                    $return = '-';
                    if (!empty($row)) {
                        $return = date('Y-m-d H:i:s', strtotime($row->deadline));
                    }
                    return $return; // Replace with your custom logic
                })
                ->addColumn('tanggal_keluar', function($row) {
                    $return = '-';
                    if (!empty($row)) {
                        $return = date('Y-m-d', strtotime($row->tanggal_keluar));
                    }
                    return $return; // Replace with your custom logic
                })
                ->addColumn('name', function($row) {
                    $return = '<a class="tips-1" href="'.route('finance.detail-tender', [ 'id'=> encrypt($row->id)]).'" title="Detail '.$row->name.'">'.$row->name.'</a>';

                    return $return; // Replace with your custom logic
                })
                ->addColumn('win_lost', function($row) {
                    $return = '-';
                    $return = '<span class="badge badge-info">Waiting</span>';
                    if (!empty($row->win_lost)) {
                        $return = '<span class="badge badge-success">'.$row->win_lost.'</span>';
                    }
                    return $return; // Replace with your custom logic
                })
                ->addColumn('doc_rfq_from_customer', function($row) {
                    $return = '-';
                    if (!empty($row->doc_rfq_from_customer)) {
                        $return = '<a href="'.asset('storage/'.$row->doc_rfq_from_customer).'" target="_blank" class="btn btn-xs btn-danger"><i class="fa fa-file-pdf-o"></i></a>';
                    }
                    return $return; // Replace with your custom logic
                })
                ->addColumn('deadline', function($row) {
                    $return = '-';
                    if (!empty($row)) {
                        $return = date('d M Y H:i:s', strtotime($row->deadline));
                    }
                    return $return; // Replace with your custom logic
                })
                ->addColumn('tanggal_keluar', function($row) {
                    $return = '-';
                    if (!empty($row)) {
                        $return = date('d M Y', strtotime($row->tanggal_keluar));
                    }
                    return $return; // Replace with your custom logic
                })
                ->addColumn('currency', function($row) {
                    $return = '-';
                    if (!empty($row->currency)) {
                        $return = '<span class="badge badge-light">'.$row->currency->currency.' '.thousandSeparator($row->total_price_tender).'</span>';
                    }
                    return $return; // Replace with your custom logic
                })
                // ->addColumn('status', function($row) {
                //     $return = '-';
                //     if ($row->status_journey == 2) {
                //         $return = '<span class="badge badge-warning"><i class="fa fa-building-o"></i> Pemilihan Principle</span>';
                //     }
                //     if ($row->status_journey == 3) {
                //         $return = '<span class="badge badge-success"><i class="fa fa-money"></i> Pengisian Harga</span>';
                //     }
                //     return $return; // Replace with your custom logic
                // })
                ->addColumn('status', function($row) {
                    $return = '<span class="badge badge-'.$row->statusJourney->color.'"><i class="fa fa-'.$row->statusJourney->icon.'"></i> '.$row->statusJourney->name.'</span>';

                    return $return; // Replace with your custom logic
                })
                ->rawColumns(['action', 'status', 'currency', 'name', 'win_lost', 'doc_rfq_from_customer', 'deadline', 'tanggal_keluar']) // Render the action column as raw HTML
                ->make(true);
        }
        return view('finance.index');
    }
    

    public function detailTender($id)
    {
        if (is_null($this->user) || !$this->user->can('sales.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }

        $id = decrypt($id);

        $currencies = M_currency::get();
        $uom = M_uom::get();
        $sales = Sales::find($id);

        // Ambil principle yang sesuai dengan tipe 'customer'
        $data = TrxTenderRfqPrinciple::whereHas('tenderRfq', function ($query) use ($id) {
            $query->where('trx_tender_id', $id);
        })->with('principle', 'tenderRfq')->get();

        $poNumber = $this->generatePoNumber();

        // $rfq = TrxTenderRfq::get();
        // dd($rfq);
        // Filter principle dengan type 'customer'
        // $principle = $data->filter(function($item) {
        //     return $item->principle && $item->principle->type === 'customer';
        // })->first();
        // dd($principle);

        // $principleData = $principle ? $principle->principle : null;
        $status_journey = M_tender_status_journey::get();
        $is_null_bid = is_null($sales->is_bb_bond)?true:false;
        $type = 'material';
        $is_detail_tender = !empty($request->is_detail_tender)?true:false;

        $existingData = [
            'delivery_point' => [1],
            'price_validity' => [9],
            'delivery_time' => [10],
            'payment_terms' => [11],
            'additional_value' => [8],
            'terms' => !empty($sales->tenderRfq->term)?$sales->tenderRfq->term->pluck('m_term_compwith_id'):[],
        ];

        $termsType1 = M_delpoint::get();
        $termsType2 = M_term::where('type', 'additional_value')->where('is_quo', true)->get();
        $termsType3 = M_term::where('type', 'price_validity')->where('is_quo', true)->get();
        $termsType4 = M_term::where('type', 'delivery_time')->where('is_quo', true)->get();
        $termsType5 = M_term::where('type', 'payment_terms')->where('is_quo', true)->get();
        $termsType6 = M_term::where('type', 'complete_with')->where('is_quo', true)->get();
        $terms = M_term::where('type', 'term')->where('is_quo', true)->get();
        if ($type == 'tender') {
            return view('finance.edittender', compact('principle','sales', 'type', 'currencies', 'uom', 'is_null_bid', 'is_detail_tender'));
        }else {
            $check_price = $this->checkMaterialPrices($sales->id);
            $price = [];
            $priceSum = [];
            $margin = '';
            $percentageMargin = '';
            if ($sales->status_journey == '3' && !empty($sales->tenderRfq->RfqPrinciple->firstWhere('status', 'win')->currency)) {
                $priceSum['Principle'] = $sales->tenderMaterial->flatMap(function($tenderMaterial) {
                    return $tenderMaterial->price->where('type', 'Principle');
                })->sum('price');
                $priceSum['KZ'] = $sales->tenderMaterial->flatMap(function($tenderMaterial) {
                    return $tenderMaterial->price->where('type', 'KZ');
                })->sum('price');
                // Calculate the absolute margin
                $margin = $priceSum['KZ'] - $priceSum['Principle'];

                // Calculate the percentage margin
                $percentageMargin = ($priceSum['Principle'] > 0) ? ($margin / $priceSum['Principle']) * 100 : 0;
                $percentageMargin = number_format($percentageMargin, 2, '.', ',');

                $price['id'] = !empty($sales->tenderRfq->RfqPrinciple->firstWhere('status', 'win')->currency->id)?$sales->tenderRfq->RfqPrinciple->firstWhere('status', 'win')->currency->id:null;
                $price['text'] = !empty($sales->tenderRfq->RfqPrinciple->firstWhere('status', 'win')->currency->currency)?$sales->tenderRfq->RfqPrinciple->firstWhere('status', 'win')->currency->currency:null;
            }
            // dd($price);
            return view('finance.detail-tender', compact('sales', 'type', 'currencies', 'uom', 'check_price', 'price', 'priceSum', 'margin', 'percentageMargin', 'status_journey', 'is_null_bid',
                'termsType1', 'termsType2','termsType3','termsType4', 'termsType5','termsType6', 'terms','existingData', 'data', 'poNumber'
            ));
        }
    }

    private function generatePoNumber()
    {
        // Ambil tahun dan bulan saat ini
        $year = date('Y');
        $month = date('n');

        // Format bulan Roman
        $monthsRoman = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $monthRoman = $monthsRoman[$month]; // Convert month to Roman numeral

        // Fetch the last used sequence number from the database
        $lastNumber = \App\Models\TrxTenderRfq::max('sequence_number');
        $sequence = $lastNumber ? $lastNumber + 1 : 1; // Increment the last number or start from 1 if none found

        // Format the number according to the specified pattern
        $number = "{$sequence}/KZ-PO/{$monthRoman}/{$year}";

        // Check if the generated number already exists and regenerate if necessary
        while (\App\Models\TrxTenderPo::where('principle_po_no', $number)->exists()) {
            $sequence++; // Increment sequence number if the number already exists
            $number = "{$sequence}/KZ-PO/{$monthRoman}/{$year}";
        }
        // return sprintf('%02d/KZ-PO/%s/%d', $number, $monthsRoman, $year);
        return $number;
    }
    

    function checkMaterialPrices($trx_tender_id)
    {
        // Get the material IDs associated with the given trx_tender_id
        $material_list = ProcurementMaterial::where('trx_tender_id', $trx_tender_id)->pluck('id');

        // Count 'Principle' and 'KZ' types for each material ID
        $count_prices = TrxTenderMaterialPrice::whereIn('trx_tender_material_id', $material_list)
            ->whereIn('type', ['Principle', 'KZ'])
            ->groupBy('trx_tender_material_id', 'type')
            ->selectRaw('trx_tender_material_id, type, COUNT(*) as count')
            ->get();

        $reload = false;

        // Check each material ID
        foreach ($material_list as $material_id) {
            $has_principle = false;
            $has_kz = false;

            // Check if there is exactly one 'Principle' and one 'KZ' for this material ID
            foreach ($count_prices as $count_price) {
                if ($count_price->trx_tender_material_id == $material_id) {
                    if ($count_price->type == 'Principle' && $count_price->count == 1) {
                        $has_principle = true;
                    }
                    if ($count_price->type == 'KZ' && $count_price->count == 1) {
                        $has_kz = true;
                    }
                }
            }

            // If any material ID does not have exactly one 'Principle' and one 'KZ', set $reload to true and break
            if (!$has_principle || !$has_kz) {
                $reload = true;
                break;
            }
        }

        // If $reload is still false here, it means all material IDs met the requirement
        return !$reload;
    }
}
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use Storage;
use Mail;

class ProcurementController extends Controller
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
        if (is_null($this->user) || !$this->user->can('procurement.view')) {
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
                    $return = '<a class="tips-1" href="'.route('procurement.detail-tender', [ 'id'=> encrypt($row->id)]).'" title="Detail '.$row->name.'">'.$row->name.'</a>';

                    return $return; // Replace with your custom logic
                })
                ->addColumn('win_lost', function($row) {
                    $return = '-';
                    $return = '<span class="badge bg-info">Waiting</span>';
                    if (!empty($row->win_lost)) {
                        $return = '<span class="badge bg-success">'.$row->win_lost.'</span>';
                    }
                    return $return; // Replace with your custom logic
                })
                ->addColumn('doc_rfq_from_customer', function($row) {
                    $return = '-';
                    if (!empty($row->doc_rfq_from_customer)) {
                        $return = '<a href="'.asset('storage/'.$row->doc_rfq_from_customer).'" target="_blank" class="btn btn-sm btn-danger"><i class="fa fa-file-pdf text-light"></i></a>';
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
                        $return = '<span class="badge text-bg-light">'.$row->currency->currency.' '.thousandSeparator($row->total_price_tender).'</span>';
                    }
                    return $return; // Replace with your custom logic
                })
                // ->addColumn('status', function($row) {
                //     $return = '-';
                //     if ($row->status_journey == 2) {
                //         $return = '<span class="badge bg-warning"><i class="fa fa-building-o"></i> Pemilihan Principle</span>';
                //     }
                //     if ($row->status_journey == 3) {
                //         $return = '<span class="badge bg-success"><i class="fa fa-money"></i> Pengisian Harga</span>';
                //     }
                //     return $return; // Replace with your custom logic
                // })
                ->addColumn('status', function($row) {
                    $return = '<span class="badge bg-'.$row->statusJourney->color.'"><i class="fa fa-'.$row->statusJourney->icon.'"></i> '.$row->statusJourney->name.'</span>';

                    return $return; // Replace with your custom logic
                })
                ->addColumn('action', function($row) {
                    $detailUrl = route('procurement.detail', [ 'id'=> encrypt($row->id)]);
                    $detail_btn = '<a href="'.$detailUrl.'" title="Detail Procurement '.$row->name.'" class="btn btn-sm m-1 btn-primary"><i class="fa fa-info-circle"></i> Detail</a>';
                    $logUrl = route('fetch.get-tender-log', ['id' => encrypt($row->id)]);
                    $log_btn = '<button id="tender-log-modal" data-url="'.$logUrl.'" type="button" title="History Tender" class="btn btn-sm m-1 btn-info" data-toggle="modal" data-target="#tenderLogModal"><i class="fa fa-history"></i></button>';
                    // $material_btn = ''; // Initialize variable
                    // if (in_array($row->status_journey, [1, 2, 3])) {
                    //     $editUrlMaterial = route('procurement.edit', [ 'id'=> encrypt($row->id), 'type' => 'material']);
                    //     $material_btn = '<a class="btn btn-sm m-1 btn-warning" title="Edit Material" href="'.$editUrlMaterial.'"><i class="fa fa-industry"></i> Material</a>';
                    // }

                    return $detail_btn.$log_btn;
                })
                ->rawColumns(['action', 'status', 'currency', 'name', 'win_lost', 'doc_rfq_from_customer', 'deadline', 'tanggal_keluar']) // Render the action column as raw HTML
                ->make(true);
        }
        return view('procurement.index');
    }

    public function reviewPrices($id)
    {
        if (is_null($this->user) || !$this->user->can('sales.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }

        $id = decrypt($id);

        $currencies = M_currency::get();
        $uom = M_uom::get();
        $sales = Sales::find($id);
        return view('procurement.review-prices', compact('sales', 'type', 'currencies', 'uom'));
    }

    public function updateReview(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('sales.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to update any sales data!');
        }

        $id = decrypt($id);
        $sales = Sales::findOrFail($id);
        if (!is_null($sales)) {
            $sales->status_journey = 4;
            $sales->save(); // This will now perform a soft delete
        }

        $log = [
            'name' => 'tender',
            'description' => 'Data tender telah di update ke status review oleh : '.auth()->user()->name,
            'trx_tender_id' => $sales->id,
        ];
        saveTenderLog($log);

        return response()->json(['status'=>true, 'message' => 'tender success', 'redirect' => route('sales.detail-tender', ['id' => encrypt($sales->id)])]);
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

        // $principleData = $principle ? $principle->principle : null;
        $status_journey = M_tender_status_journey::get();
        $is_null_bid = is_null($sales->is_bb_bond)?true:false;
        $type = 'material';
        $is_detail_tender = !empty($request->is_detail_tender)?true:false;

        $termsType1 = M_delpoint::get();
        $termsType2 = M_term::where('type', 'additional_value')->where('is_quo', true)->get();
        $termsType3 = M_term::where('type', 'price_validity')->where('is_quo', true)->get();
        $termsType4 = M_term::where('type', 'delivery_time')->where('is_quo', true)->get();
        $termsType5 = M_term::where('type', 'payment_terms')->where('is_quo', true)->get();
        $termsType6 = M_term::where('type', 'complete_with')->where('is_quo', true)->get();
        $terms = M_term::where('type', 'term')->where('is_quo', true)->get();

        $tenderPoTerms = TrxTenderPoTerm::where('trx_tender_po_id', $sales->id)
        ->pluck('term_id')
        ->toArray();

    // Saring terms untuk form
        $termsForForm = M_term::whereIn('id', $tenderPoTerms)->get();

        if ($type == 'tender') {
            return view('procurement.edittender', compact('principle','sales', 'type', 'currencies', 'uom', 'is_null_bid', 'is_detail_tender'));
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
            return view('procurement.detail-tender', compact('sales', 'type', 'currencies', 'uom', 'check_price', 'price', 'priceSum', 'margin', 'percentageMargin', 'status_journey', 'is_null_bid',
                'termsType1', 'termsType2','termsType3','termsType4', 'termsType5','termsType6', 'terms', 'data', 'poNumber','termsForForm'
            ));
        }
    }
    public function detailTenderPo($id)
    {
        if (is_null($this->user) || !$this->user->can('sales.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }

        $id = decrypt($id);

        $currencies = M_currency::get();
        $uom = M_uom::get();
        $sales = Sales::find($id);
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
        $terms = M_term::where('type', 'term')->where('is_quo', true)->get();
        if ($type == 'tender') {
            return view('procurement.edittender', compact('sales', 'type', 'currencies', 'uom', 'is_null_bid', 'is_detail_tender'));
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
            return view('procurement.detail-tender-post', compact('sales', 'type', 'currencies', 'uom', 'check_price', 'price', 'priceSum', 'margin', 'percentageMargin', 'status_journey', 'is_null_bid',
                'termsType1', 'termsType2', 'termsType3', 'termsType4','termsType5','terms','existingData',
            ));
        }
    }


    public function detailTenderPost($id)
    {
        //
    }

    public function edit(Request $request, $id, $type)
    {
        if (is_null($this->user) || !$this->user->can('sales.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }

        $id = decrypt($id);
        $is_detail_tender = false;
        if (!empty($request->is_detail_tender)) {
            $is_detail_tender = true;
        }

        $currencies = M_currency::get();
        $uom = M_uom::get();
        $sales = Sales::find($id);
        $bid_bond = TrxTenderBgGuarantee::where('trx_tender_id', $id)->where('type', 'bb')->first();
        $pb_bond = TrxTenderBgGuarantee::where('trx_tender_id', $id)->where('type', 'pb')->first();
        if ($type == 'tender') {
            return view('procurement.edittender', compact('sales', 'type', 'currencies', 'uom', 'is_detail_tender', 'bid_bond', 'pb_bond', 'is_detail_tender'));
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
            return view('procurement.editmaterial', compact('sales', 'type', 'currencies', 'uom', 'check_price', 'price', 'priceSum', 'margin', 'percentageMargin'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function detail(Request $request, $id)
    {

        $id = decrypt($id);

        $currencies = M_currency::get();
        $uom = M_uom::get();
        $sales = Sales::with('tenderRfq.rfqPrinciple.principle')->find($id);
        $rfq_id = null;
        if (!empty($sales->tenderRfq->id)) {
            $rfq_id = encrypt($sales->tenderRfq->id);
        }

        return view('procurement.detail', compact('sales', 'currencies', 'uom', 'rfq_id'));
    }

    public function storeRfq(Request $request, $id, $rfq_id = null)
    {
        // Validate the request data
        $request->validate([
            'rfq_no' => 'required|string|max:255',
            // 'date_created' => 'required|date',
            'date_deadline' => 'required|date',
            // 'date_delivery_time' => 'required|date', // Ensure correct datetime format
            // 'delivery_point_ids' => 'required|array',
            // 'delivery_point_ids.*' => 'integer|exists:m_term_delpoint,id',
            // 'term_comp_ids' => 'required|array',
            // 'term_comp_ids.*' => 'integer|exists:m_term,id',
        ]);

        // try {
            $id = decrypt($id);
            if (!empty($rfq_id)) {
                $rfq_id = decrypt($rfq_id);
                $rfq = TrxTenderRfq::find($rfq_id);
            } else {
                $rfq = new TrxTenderRfq();
                $rfq->date_created = date('Y-m-d');
            }

            // Create or update RFQ record
            $rfq->trx_tender_id = $id;
            $rfq->rfq_no = $request->input('rfq_no');
            $rfq->date_deadline = $request->input('date_deadline');
            // $rfq->date_delivery_time = $request->input('date_delivery_time');
            $rfq->save();

            // Get existing principle_ids

            // Find the principle_ids to add
            if (!empty($request->input('principle_ids'))) {
                $existingPrincipleIds = TrxTenderRfqPrinciple::where('trx_tender_rfq_id', $rfq->id)->pluck('principle_id')->toArray();
                $principleIdsToAdd = array_diff($request->input('principle_ids'), $existingPrincipleIds);

                // Attach new principles to RFQ
                foreach ($principleIdsToAdd as $principleId) {
                    $rfq_principle = new TrxTenderRfqPrinciple();
                    $rfq_principle->trx_tender_rfq_id = $rfq->id;
                    $rfq_principle->principle_id = $principleId;
                    $rfq_principle->save();
                }
            }
            if (!empty($request->input('delivery_point_ids'))) {
                // Delete existing delpoints for the RFQ
                TrxTenderRfqDelpoint::where('trx_tender_rfq_id', $rfq->id)->delete();

                // Attach new delpoints to RFQ
                foreach ($request->input('delivery_point_ids') as $delpointId) {
                    $rfq_delpoint = new TrxTenderRfqDelpoint();
                    $rfq_delpoint->trx_tender_rfq_id = $rfq->id;
                    $rfq_delpoint->m_term_delpoint_id = $delpointId;
                    $rfq_delpoint->save();
                }
            }

            if (!empty($request->input('term_comp_ids'))) {
                // Delete existing termcomps for the RFQ
                TrxTenderRfqTermComp::where('trx_tender_rfq_id', $rfq->id)->delete();

                // Attach new termcomps to RFQ
                foreach ($request->input('term_comp_ids') as $termcompId) {
                    $rfq_termcomp = new TrxTenderRfqTermComp();
                    $rfq_termcomp->trx_tender_rfq_id = $rfq->id;
                    $rfq_termcomp->m_term_compwith_id = $termcompId;
                    $rfq_termcomp->save();
                }
            }

            $log = [
                'name' => 'procurement',
                'description' => 'Data RFQ Principles telah ditambahkan oleh procurement : ' . auth()->user()->name,
                'trx_tender_id' => $id,
            ];
            saveTenderLog($log);

            // Flash success message to session
            session()->flash('success', 'RFQ Tender has been created successfully.');

            return response()->json(['data' => $rfq, 'status' => true]);
        // } catch (\Exception $e) {
        //     // Log the error for debugging
        //     \Log::error('Error storing RFQ: ' . $e->getMessage());

        //     return response()->json(['data' => $rfq, 'status' => false]);
        // }
    }


    public function rfqList(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('procurement.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }
        $id = decrypt($id);
        $data = TrxTenderRfqPrinciple::whereHas('tenderRfq', function ($query) use ($id) {
            $query->where('trx_tender_id', $id);
        })->with(['principle', 'tenderRfq.tender.tenderPo', 'currency']);
        // $data = TrxTenderPo::get();
        if (!in_array($this->user->roles->pluck('id')->toArray()[0], [1,5])) {
            $data = $data->where('created_by', $this->user->id);
        }

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('reviewed_at', function($row) use ($request) {
                $return = '';
                if (!empty($row->tenderRfq->tender->reviewed_at) && $row->status == 'win') {
                    $return = $row->tenderRfq->tender->reviewed_at;
                }
                return $return; // Replace with your custom logic
            })
            ->addColumn('reviewed_by', function($row) use ($request) {
                $return = '';
                if (!empty($row->tenderRfq->tender->reviewed_by) && $row->status == 'win') {
                    $return = $row->tenderRfq->tender->reviewer->name;
                }
                return $return; // Replace with your custom logic
            })
            ->addColumn('payment_method', function($row) use ($request) {
                $return = '';
                if (empty($request->detail_tender)) {
                    $return = '<button type="button" class="btn btn-sm btn-warning mx-1 btn-sm proc-update-payment-method" data-toggle="modal" data-target="#updatePaymentMethodModal" data-id="'.encrypt($row->id).'" id="updateDate"><i class="fa fa-credit-card"></i></button>';
                }
                if (!empty($row->payment_method)) {
                    $return .= $row->payment_method;
                }

                return $return; // Replace with your custom logic
            })
            ->addColumn('price', function($row) use ($request) {
                $return = '';
                if (empty($request->detail_tender)) {
                    $return = '<button type="button" class="btn btn-sm btn-warning mx-1 btn-sm proc-update-price-from-principle" data-toggle="modal" data-target="#updatePriceFromPrincipleModal" data-id="'.encrypt($row->id).'" data-name="'.$row->principle->name.'" id="updatePriceFromPrinciple"><i class="fa fa-money"></i></button>';
                    if (!empty($row->price)) {
                        $return = '<button type="button" class="btn btn-sm btn-warning mx-1 btn-sm proc-update-price-from-principle" data-toggle="modal" data-target="#updatePriceFromPrincipleModal" data-id="'.encrypt($row->id).'" data-name="'.$row->principle->name.'" id="updatePriceFromPrinciple"><i class="fa fa-money"></i></button>';
                        $return .= '<span class="badge text-bg-light">'.thousandSeparator($row->price).' '.$row->currency->currency.'</span>';
                    }
                }else{
                    if ($row->status == 'win') {
                        $price = 0;
                        $materials = $row->tenderRfq->tender->tenderMaterial;
                        foreach ($materials as $key => $value) {
                            $unit_price = !empty($value->price->where('type', 'Principle')->first()->price)?$value->price->where('type', 'Principle')->first()->price:0;
                            $price += $unit_price;
                        }

                        if (!empty($price)) {
                            $return = '<span class="badge text-bg-light">' . thousandSeparator($price) . ' ' . $row->currency->currency . '</span>';
                        } else {
                            $return = '<span class="badge text-bg-light">' . thousandSeparator($row->price) . ' ' . $row->currency->currency . '</span>';
                        }
                    } else {
                        if (!empty($row->price)) {
                            $return = '<span class="badge text-bg-light">' . thousandSeparator($row->price) . ' ' . $row->currency->currency . '</span>';
                        }
                    }
                }
                return $return; // Replace with your custom logic
            })
            ->addColumn('date_delivery', function($row) use ($request) {
                $return = '';
                if (empty($request->detail_tender)) {
                    $return = '<button type="button" class="btn btn-sm btn-warning mx-1 btn-sm proc-update-date" data-toggle="modal" data-target="#updateDateModal" data-id="'.encrypt($row->id).'" id="updateDate"><i class="fa fa-calendar"></i></button>';
                }
                if (!empty($row->date_delivery)) {
                    $return .= date('Y-m-d', strtotime($row->date_delivery));
                }

                return $return; // Replace with your custom logic
            })
            ->addColumn('status', function($row) {
                if (!empty($row->status)) {
                    if ($row->status == 'win') {
                        $return = '<span class="badge bg-success"><i class="fa fa-trophy"></i> Win</span>';
                    }else{
                        $return = '<span class="badge bg-danger"><i class="fa fa-ban"></i> Lose</span>';
                    }
                }else{
                    $return = '<span class="badge bg-info">Waiting update</span>';
                }
                return $return; // Replace with your custom logic
            })
            ->addColumn('doc_quo_from_principle', function($row) use ($request) {
                $return = '';
                if($row->tenderRfq->tender->statusJourney->order > 3){
                    $return = '<a target="_blank" href="'.asset('storage/'.$row->doc_quo_from_principle).'" class="btn btn-sm btn-danger"><i class="fa fa-file-pdf text-light"></i></a>';
                }
                if (empty($request->detail_tender)) {
                    $return = '<button type="button" class="btn btn-sm btn-warning mx-1 btn-sm proc-update-doc-quo-from-principle" data-toggle="modal" data-target="#updateDocQuoFromPrincipleModal" data-id="'.encrypt($row->id).'" data-name="'.$row->principle->name.'" id="updateDocQuoFromPrinciple"><i class="fa fa-calendar"></i> Update Doc Quo</button>';
                    if (!empty($row->doc_quo_from_principle)) {
                        $return = '<button type="button" class="btn btn-sm btn-warning mx-1 btn-sm proc-update-doc-quo-from-principle" data-toggle="modal" data-target="#updateDocQuoFromPrincipleModal" data-id="'.encrypt($row->id).'" data-name="'.$row->principle->name.'" id="updateDocQuoFromPrinciple"><i class="fa fa-pencil"></i></button>';
                        $return .= '<a target="_blank" href="'.asset('storage/'.$row->doc_quo_from_principle).'" class="btn btn-sm btn-danger"><i class="fa fa-file-pdf text-light"></i></a>';
                    }
                }
                return $return; // Replace with your custom logic
            })
            ->addColumn('doc_po_principle', function($row) {
                // error_log(print_r($row->tenderRfq->tender->tenderPo, true));
                $return = '-';



                return $return;

            })
            ->addColumn('action', function($row) use($data) {
                $hasIncompleteData = $data->get()->filter(function ($value) {
                    return is_null($value->doc_quo_from_principle) || $value->doc_quo_from_principle === '' ||
                    is_null($value->price) || $value->price === '' ||
                    is_null($value->date_delivery) || $value->date_delivery === '' ||
                    is_null($value->payment_method) || $value->payment_method === '';
                })->isNotEmpty();

                $winner_btn = '';
                $winnerUrl = route('procurement.winner-principle', encrypt($row->id));
                if (!$hasIncompleteData && empty($row->status)) {
                    $winner_btn = '<button type="button" class="btn btn-sm btn-success m-1 btn-sm" data-name="'.$row->principle->name.'" data-name="'.$row->price.'" onclick="confirmWinner(\''.$winnerUrl.'\', \''.$row->principle->name.'\', \''.thousandSeparator($row->price).'\', \''.$row->currency->currency.'\')"><i class="fa fa-trophy"></i> Winner</button>';
                }

                // dd($hasIncompleteData);
                $rfq_button = '<a target="_blank" href="'.route('procurement.rfq-to-principle-pdf', ['id' => encrypt($row->id)]).'" class="btn btn-sm btn-danger m-1 btn-sm"><i class="fa fa-file-pdf text-light"></i> RFQ</a>';
                $send_rfq_url = route('procurement.send-rfq', ['id' => encrypt($row->id)]);
                $send_rfq_button = '<button title="Send Mail to Procurement" id="sendRfqButton" class="btn btn-primary btn-sm m-1" onclick="confirmAndSendRfq(\''.$send_rfq_url.'\')"><i class="fa fa-envelope"></i> Mail</button>';
                $deleteUrl = route('procurement.destroy-principle', encrypt($row->id));
                $delete_btn = '<button type="button" class="btn btn-sm btn-danger m-1 btn-sm" onclick="confirmDelete(\''.$deleteUrl.'\')"><i class="fa fa-trash"></i></button>';
                if (!$this->user->can('procurement.edit') || !empty($row->status)) {
                    $delete_btn = '';
                }
                $material_btn = ''; // Initialize variable
                if ($row->status == 'win') {
                    $editUrlMaterial = route('procurement.edit', [ 'id'=> encrypt($row->tenderRfq->tender->id), 'type' => 'material']);
                    $material_btn = '<a class="btn btn-sm m-1 btn-warning" title="Edit Material" href="'.$editUrlMaterial.'"><i class="fa fa-industry"></i> Material</a>';
                }

                return $winner_btn.$send_rfq_button.$rfq_button.$delete_btn.$material_btn;
            })
            ->rawColumns(['action', 'status', 'payment_method', 'price', 'doc_quo_from_principle', 'date_delivery']) // Render the action column as raw HTML
            ->make(true);
    }

    public function principleList(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = decrypt($id);
            $data = TrxTenderPo::where('trx_tender_id', $id)->with(['userCreator', 'tender', 'statusJourney', 'tender.tenderRfq.rfqPrinciple']);

            if (!in_array($this->user->roles->pluck('id')->toArray()[0], [1,5])) {
                $data = $data->where('created_by', $this->user->id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('principle', function($row) {
                    // $return ='-';
                    // dd($row->tender->tenderRfq->rfqPrinciple);
                    $principles = $row->tender->tenderRfq->rfqPrinciple->map(function($rfqPrinciple) {
                        return $rfqPrinciple->principle ? $rfqPrinciple->principle->name : 'No Principle';
                    })->implode(', ');

                    return $principles ? $principles : '-';
                })
                ->addColumn('status', function($row) {
                    $statusList = '';
                    dd($row->tenderRfq->rfqPrinciple);
                    if ($row->tenderRfq && $row->tenderRfq->rfqPrinciple) {
                        foreach ($row->tenderRfq->rfqPrinciple as $rfqPrinciple) {
                            // Pastikan ada property status dalam rfqPrinciple
                            $statusList .= '<li>' . ($rfqPrinciple->status ?? 'No Status') . '</li>';
                        }
                    }

                    return $statusList ? '<ul>' . $statusList . '</ul>' : 'No Status';
                })
                ->addColumn('doc_po_principle', function($row) {
                    // dd($row->term);
                    if (isset($row->id)) {
                        return '<a target="_blank" href="'.route('procurement.princple-pdf', encrypt($row->id)).'" class="btn btn-sm btn-danger"><i class="fa fa-file-pdf text-light"></i></a>';
                    }
                    return '<span>No Document</span>';
                })
                ->rawColumns(['doc_po_principle', 'status'])
                ->make('true');
        }
    }

    // MATERIAL

    // public function materialList(Request $request, $id)
    // {
    //     // $data = ProcurementMaterial::where('trx_tender_id', $id)->with(['uom', 'material', 'price', 'tender']);

        // $data = TrxTender::where('trx_tender_id', $id)->with(['uom', 'material', 'price', 'tender']);
        // return DataTables::eloquent($data)
        // ->addIndexColumn()
        // ->addC
    // }
    public function poCustomerList(Request $request, $id)
    {
        $id = decrypt($id);

        $data = TrxTenderPo::where('trx_tender_id', $id)->with(['userCreator', 'tender', 'statusJourney']);

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('customer_po_doc', function($row) {
            $url = Storage::url($row->customer_po_doc); // Menghasilkan URL yang benar
            $filename = basename($row->doc_po);
            // return '<a href="' . $url . '" target="_blank"><i class="fa fa-file document-icon"></i>' . $filename . '</a>';
            return '<a class="btn btn-sm btn-danger" href="' . $url . '" target="_blank"><i class="fa fa-file-pdf text-light"></a>';

        })
        ->addColumn('created_by', function($row) {
            $return = '-';

            if (!empty($row->userCreator)) {
                $return = $row->userCreator->name;
            }

            return $return;
        })
        ->addColumn('customer_po_date', function($row) {
            if (!empty($row)) {
                $return = date('d M Y', strtotime($row->date_po));
            }

            return $return;
        })
        ->rawColumns(['customer_po_doc'])
        ->make(true);
    }

    public function poCustomerStore(Request $request, $id)
    {
        $request->validate([
            'customer_po_no' => 'required',
            'customer_po_doc' => 'required',
            'customer_po_doc' => 'required',
            // 'customer_po_note' => 'required'
        ]);

        $id = decrypt($id);

        $po = new TrxTenderPo();
        $po->trx_tender_id = $id;
        $po->customer_po_no = $request->input('customer_po_no');
        $po->customer_po_doc = $request->input('customer_po_doc');
        $po->customer_po_date = $request->input('customer_po_date');
        $po->customer_po_note = $request->input('customer_po_note');
        if ($request->hasFile('customer_po_doc')) {
            $file = $request->file('customer_po_doc');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('doc_po_customers', $filename, 'public');
            $po->customer_po_doc = $path;
        }

        $po->save();

        $tender = TrxTender::find($id);
        $tender->status_journey = 5;
        $tender->win_lost = 'win';
        $tender->save();


        $log = [
            'name' => 'tender',
            'description' => 'PO customer di tambah oleh : '.auth()->user()->name,
            'trx_tender_id' => $po->id,
        ];

        saveTenderLog($log);

        return response()->json(['message' => 'created po customer successfully']);
    }

    // public function quotationToCustomerPdf(Request $request, $id, $role=null){
    //     $id = decrypt($id);
    //     $sales = Sales::with(['customer', 'tenderMaterial.price'])->find($id);
    //     // dd($sales);
    //     $user = Auth::user();

    //     $pdfData = Session::get('pdfData');
    //     $user = Auth::user();
    //     if (!empty($role)) {
    //         if ($role == 'sales') {
    //             $receiver = User::role('sales')->pluck('email')->toArray();
    //             $cc_receiver = User::role('procurement')->pluck('email')->toArray();
    //         } else {
    //             $receiver = User::role('procurement')->pluck('email')->toArray();
    //             $cc_receiver = User::role('sales')->pluck('email')->toArray();
    //         }
    //     }

    //     $pdfDataTerm = [
    //         'delivery_point' => M_delpoint::whereIn('id', $request->input('delivery_point'))->pluck('name')->toArray(),
    //         'additional_value' => M_term::whereIn('id', $request->input('additional_value'))->pluck('name')->toArray(),
    //         'price_validity' => M_term::whereIn('id', $request->input('price_validity'))->pluck('name')->toArray(),
    //         'delivery_time' => M_term::whereIn('id', $request->input('delivery_time'))->pluck('name')->toArray(),
    //         'payment_terms' => M_term::whereIn('id', $request->input('payment_terms'))->pluck('name')->toArray(),
    //         'terms' => M_term::whereIn('id', $request->input('terms',[]))->pluck('name')->toArray(),
    //         'note' => $request->input('note')
    //     ];
    //     $title = 'quotation_to_'.strtolower(str_replace(' ','_', $sales->customer->name)).'_'.date('YmdHis').'.pdf';
    //     // return view('procurement.rfq-to-principle-pdf', compact('rfqp', 'title', 'user'));
    //     $pdfData = [
    //         'sales' => $sales,
    //         'user' => $user,
    //         'title' => $title,
    //         'pdfDataTerm' => $pdfDataTerm
    //     ];
    //     $pdf = PDF::loadView('sales.quotation-to-customer-pdf', compact('sales', 'user', 'title', 'pdfData', 'pdfDataTerm'));

    //     if (!empty($role)) {
    //         $pdfPath = 'quo/'.$title;
    //         // dd($request->all());$pdfPath = 'rfqp/'.$title;
    //         Storage::put('public/' . $pdfPath, $pdf->output());

    //         // Send email to each sales user
    //         // foreach ($receiver as $receiver) {
    //         //     Mail::to($receiver->email)->send(new QuoMail($pdfPath, $sales));
    //         // }
    //         Mail::to($receiver)->cc($cc_receiver)->send(new QuoMail($pdfPath, $sales));

    //         // Optionally, delete the temporary PDF file after sending the emails
    //         Storage::delete('public/' . $pdfPath);

    //         return response()->json(['message' => 'Quotation sent successfully', 'status' => true]);
    //     }else{
    //         return $pdf->stream($title);
    //     }
    // }

    public function poToprinciplePdf(Request $request, $id, $role=null){
        $id = decrypt($id);
        $sales = Sales::with(['customer', 'tenderMaterial.price'])->find($id);
        $user = Auth::user();

        $request->validate([
            // Validasi lain sesuai kebutuhan
        ]);

        // Generate nomor PO
        $poNumber = $this->generatePoNumber();

        $data = TrxTenderRfqPrinciple::whereHas('tenderRfq', function ($query) use ($id) {
            $query->where('trx_tender_id', $id);
        })->with('principle', 'tenderRfq')->get();

        $rfq = TrxTenderPo::updateOrCreate(
            ['trx_tender_id' => $id], // Kriteria pencarian
            [
                'principle_po_no' => $poNumber,
                'principle_po_date' => date('Y-m-d'),
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id
            ]
        );
        // Data tambahan untuk PDF
        $delpoint = [
            'delivery_point' => M_delpoint::whereIn('id', $request->input('delivery_point', []))->pluck('id')->toArray(),
        ];
        $pdfDataTerm = [
            'additional_value' => M_term::whereIn('id', $request->input('additional_value', []))->pluck('id')->toArray(),
            'price_validity' => M_term::whereIn('id', $request->input('price_validity', []))->pluck('id')->toArray(),
            'delivery_time' => M_term::whereIn('id', $request->input('delivery_time', []))->pluck('id')->toArray(),
            'payment_terms' => M_term::whereIn('id', $request->input('payment_terms', []))->pluck('id')->toArray(),
            'complete_with' => M_term::whereIn('id', $request->input('complete_with', []))->pluck('id')->toArray(),
            'terms' => M_term::whereIn('id', $request->input('terms', []))->pluck('id')->toArray(),
            'note' => $request->input('note')
            // 'additional_value' => $request->input('additional_value', []),
            // 'price_validity' => $request->input('price_validity', []),
            // 'delivery_time' => $request->input('delivery_time', []),
            // 'payment_terms' => $request->input('payment_terms', []),
            // 'complete_with' => $request->input('complete_with', []),
            // 'terms' => $request->input('terms', [])
        ];

        $terms = M_term::whereIn('id', array_merge(
            $pdfDataTerm['additional_value'],
            $pdfDataTerm['price_validity'],
            $pdfDataTerm['delivery_time'],
            $pdfDataTerm['payment_terms'],
            $pdfDataTerm['complete_with'],
            $pdfDataTerm['terms']
        ))->get();

        $tenderPoId = $rfq->id;

        $term = M_term::get();

        foreach ($delpoint as $key => $ids) {
            if (is_array($ids) || $ids instanceof \Traversable) { // Pastikan $ids adalah array atau objek yang bisa diiterasi
                foreach ($ids as $id) {
                    if (!is_null($id)) { // Pastikan $id tidak null
                        TrxTenderPoDelpoint::updateOrCreate(
                            [
                                'trx_tender_po_id' => $tenderPoId,
                            ],
                            [
                                // Tidak perlu kolom lain jika hanya menyimpan trx_tender_po_id dan term_id
                                'trx_tender_po_id' => $tenderPoId,
                                'delpoint_id' => $id,
                                'created_by' => Auth::user()->id,
                                'updated_by' => Auth::user()->id
                            ]
                        );
                    }
                }
            }
        }
        $existingTermIds = TrxTenderPoTerm::where('trx_tender_po_id', $tenderPoId)
        ->pluck('term_id')
        ->toArray();

        $inputTermIds = array_merge(
            $pdfDataTerm['additional_value'],
            $pdfDataTerm['price_validity'],
            $pdfDataTerm['delivery_time'],
            $pdfDataTerm['payment_terms'],
            $pdfDataTerm['complete_with'],
            $pdfDataTerm['terms']
        );

        $termsToDelete = array_diff($existingTermIds, $inputTermIds);

        if (!empty($termsToDelete)) {
            TrxTenderPoTerm::where('trx_tender_po_id', $tenderPoId)
                ->whereIn('term_id', $termsToDelete)
                ->delete();
        }

        // Tambah atau perbarui term yang ada di input
        foreach ($pdfDataTerm as $key => $ids) {
            if (is_array($ids) || $ids instanceof \Traversable) {
                foreach ($ids as $id) {
                    if (!is_null($id)) {
                        TrxTenderPoTerm::updateOrCreate(
                            [
                                'trx_tender_po_id' => $tenderPoId,
                                'term_id' => $id
                            ],
                            [
                                'created_by' => Auth::user()->id,
                                'updated_by' => Auth::user()->id
                            ]
                        );
                    }
                }
            }
        }
        // dd($pdfDataTerm);
        $pdfData = [
            'additional_value' => $terms->whereIn('id', $pdfDataTerm['additional_value']),
            'price_validity' => $terms->whereIn('id', $pdfDataTerm['price_validity']),
            'delivery_time' => $terms->whereIn('id', $pdfDataTerm['delivery_time']),
            'payment_terms' => $terms->whereIn('id', $pdfDataTerm['payment_terms']),
            'complete_with' => $terms->whereIn('id', $pdfDataTerm['complete_with']),
            'terms' => $terms->whereIn('id', $pdfDataTerm['terms']),
            'note' => $request->input('note')
        ];

        // dd($pdfDataTerm);

        $title = 'po_to_' . strtolower(str_replace(' ', '_', $data->first()->principle->name)) . '_' . date('YmdHis') . '.pdf';

        // Buat data untuk PDF
        $pdfData = [
            'sales' => $sales,
            'user' => $user,
            'title' => $title,
            'pdfDataTerm' => $pdfDataTerm,
            'pdfData' => $pdfData,
            'data' => $data,
            'rfq' => $rfq,
            'delpoint' => $delpoint,
        ];
        session(['pdfData' => $pdfData]);

        if ($request->ajax()) {
            $pdfUrl = route('procurement.view-pdf', ['id' => encrypt($id), 'role' => $role]);
            return response()->json(['pdf_url' => $pdfUrl]);
        }
        // Generate PDF
        // $pdf = PDF::loadView('procurement.po-to-principle-pdf', $pdfData);

        // Return PDF
        return PDF::loadView('procurement.po-to-principle-pdf', $pdfData)->stream($title);
        // dd('a');
    }

    public function viewPdf($id)
    {
        $id = decrypt($id);
        $pdfData = session('pdfData');
        // dd($pdfData);
        if (!$pdfData) {
            abort(404, 'PDF data not found in session.');
        }

        // Buat data untuk PDF
        $title = $pdfData['title'];
        $filename = $title;

        // Generate PDF
        $pdf = PDF::loadView('procurement.po-to-principle-pdf', $pdfData);


        Storage::put('public/doc_po_to_Principle/' . $filename, $pdf->output());

        return $pdf->stream($title);
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

    public function updatePaymentMethod(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'payment_method' => 'required',
        ]);

        try {
            $id = decrypt($id);
            $rfqp = TrxTenderRfqPrinciple::with(['principle', 'tenderRfq', 'userUpdator'])->find($id);

            // Create or update RFQ record
            $rfqp->payment_method = $request->input('payment_method');
            $rfqp->updated_by = Auth::user()->id;
            $rfqp->save();

            $log = [
                'name' => 'procurement',
                'description' => 'Payment Method Principle '.$rfqp->principle->name.' telah di update oleh procurement : '.auth()->user()->name,
                'trx_tender_id' => $rfqp->tenderRfq->trx_tender_id,
            ];
            saveTenderLog($log);

            // Flash success message to session
            session()->flash('success', 'RFQ Tender has been updated successfully.');

            return response()->json(['data' => $rfqp,'status' => true]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error storing RFQ: ' . $e->getMessage());

            return response()->json(['status' => false]);
        }
    }

    public function updateDateDelivery(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'date_delivery' => 'required|date',
        ]);

        try {
            $id = decrypt($id);
            $rfqp = TrxTenderRfqPrinciple::with(['principle', 'tenderRfq', 'userUpdator'])->find($id);

            // Create or update RFQ record
            $rfqp->date_delivery = $request->input('date_delivery');
            $rfqp->updated_by = Auth::user()->id;
            $rfqp->save();

            $log = [
                'name' => 'procurement',
                'description' => 'Delivery date Principle '.$rfqp->principle->name.' telah di update oleh procurement : '.auth()->user()->name,
                'trx_tender_id' => $rfqp->tenderRfq->trx_tender_id,
            ];
            saveTenderLog($log);

            // Flash success message to session
            session()->flash('success', 'RFQ Tender has been updated successfully.');

            return response()->json(['data' => $rfqp,'status' => true]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error storing RFQ: ' . $e->getMessage());

            return response()->json(['status' => false]);
        }
    }

    public function updateGoodsDelivery(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'delivery_goods_days' => 'required',
        ]);

        try {
            $id = decrypt($id);
            $rfqp = TrxTenderRfqPrinciple::with(['principle', 'tenderRfq', 'userUpdator'])->find($id);

            // Create or update RFQ record
            $rfqp->delivery_goods_days = $request->input('delivery_goods_days');
            $rfqp->updated_by = Auth::user()->id;
            $rfqp->save();

            $log = [
                'name' => 'procurement',
                'description' => 'Delivery date Principle '.$rfqp->principle->name.' telah di update oleh procurement : '.auth()->user()->name,
                'trx_tender_id' => $rfqp->tenderRfq->trx_tender_id,
            ];
            saveTenderLog($log);

            // Flash success message to session
            session()->flash('success', 'RFQ Tender has been updated successfully.');

            return response()->json(['data' => $rfqp,'status' => true]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error storing RFQ: ' . $e->getMessage());

            return response()->json(['status' => false]);
        }
    }

    public function updatePricePrincipleKz(Request $request, $id, $type)
    {
        // Validate the request data
        $request->validate([
            'price' => 'required',
            'currency_id' => 'required',
        ]);

        // try {
            $id = decrypt($id);

            $currency = M_currency::find($request->currency_id);
            $tenderMaterial = TrxTenderMaterial::find($id);
            $calculatedPrice = thousandToNumber($request->input('price'));
            $totalIdrConvert = ($calculatedPrice * $currency->price_rate) * $tenderMaterial->qty;
            $price = TrxTenderMaterialPrice::updateOrCreate(
                [
                    'trx_tender_material_id' => $id,
                    'type' => $type
                ],
                [
                    'm_currency_id' => $request->input('currency_id'),
                    'price' => thousandToNumber($request->input('price')),
                    'price_rate' => $currency->price_rate,
                    'date_rate' => $currency->date_rate,
                    'total_idr_convert' => $totalIdrConvert,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id
                ]
            );

            $log = [
                'name' => 'procurement',
                'description' => 'Price '.$type.' '.$price->tenderMaterial->material->material_code.' telah di update oleh : '.auth()->user()->name,
                'trx_tender_id' => $price->tenderMaterial->trx_tender_id,
            ];
            saveTenderLog($log);

            // Flash success message to session
            session()->flash('success', 'Material Price '.$type.' has been updated successfully.');

            $reload = $this->checkMaterialPrices($price->tenderMaterial->trx_tender_id);
            // $reload = false;

            return response()->json(['data' => $price,'status' => true, 'reload' => $reload]);

        // } catch (\Exception $e) {
        //     // Log the error for debugging
        //     \Log::error('Error storing RFQ: ' . $e->getMessage());

        //     return response()->json(['status' => false]);
        // }
    }

    public function updatePriceFromPrinciple(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'price' => 'required',
            'currency_id' => 'required',
        ]);

        try {
            $id = decrypt($id);
            $rfqp = TrxTenderRfqPrinciple::with(['principle', 'tenderRfq', 'userUpdator'])->find($id);

            // Create or update RFQ record
            $rfqp->price = thousandToNumber($request->input('price'));
            $rfqp->m_currency_id = $request->input('currency_id');
            $rfqp->updated_by = Auth::user()->id;
            $rfqp->save();

            if ($rfqp->status == 'win') {
                # code...
            }

            $log = [
                'name' => 'procurement',
                'description' => 'Price Principle '.$rfqp->principle->name.' telah di update oleh procurement : '.auth()->user()->name,
                'trx_tender_id' => $rfqp->tenderRfq->trx_tender_id,
            ];
            saveTenderLog($log);

            // Flash success message to session
            session()->flash('success', 'RFQ Tender has been updated successfully.');

            return response()->json(['data' => $rfqp,'status' => true]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error storing RFQ: ' . $e->getMessage());

            return response()->json(['status' => false]);
        }
    }

    public function updateDocQuoFromPrinciple(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'doc_quo_from_principle' => 'required|file|mimes:pdf|max:2048', // Example validation for PDF files up to 2MB
        ]);

        // try {
            $id = decrypt($id);
            $rfqp = TrxTenderRfqPrinciple::with(['principle', 'tenderRfq', 'userUpdator'])->find($id);

            // Handle file upload
            if ($request->hasFile('doc_quo_from_principle')) {
                $file = $request->file('doc_quo_from_principle');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('doc_rfq_from_principles', $filename, 'public'); // Adjust storage path as needed
                $rfqp->doc_quo_from_principle = $path;
            }

            // Update other fields as needed
            $rfqp->updated_by = Auth::user()->id;
            $rfqp->save();

            // Log the update action
            $log = [
                'name' => 'procurement',
                'description' => 'File for Principle '.$rfqp->principle->name.' has been updated by procurement: '.auth()->user()->name,
                'trx_tender_id' => $rfqp->tenderRfq->trx_tender_id,
            ];
            saveTenderLog($log);

            // Flash success message to session
            session()->flash('success', 'RFQ Tender file has been updated successfully.');

            return response()->json(['data' => $rfqp, 'status' => true]);
        // } catch (\Exception $e) {
        //     // Log the error for debugging
        //     \Log::error('Error updating RFQ file: ' . $e->getMessage());

        //     return response()->json(['status' => false]);
        // }
    }

    public function rfqToPrinciplePdf(Request $request, $id){
        $id = decrypt($id);
        $rfqp = TrxTenderRfqPrinciple::with(['principle', 'tenderRfq.delpoint', 'tenderRfq.term', 'tenderRfq.tender.tenderMaterial'])->find($id);

        $user = Auth::user();

        // dd($rfqp->tenderRfq->tender->tenderMaterial);
        $title = 'rfq_to_'.strtolower(str_replace(' ','_', $rfqp->principle->name)).'_'.date('YmdHis').'.pdf';
        // return view('procurement.rfq-to-principle-pdf', compact('rfqp', 'title', 'user'));
        $pdf = PDF::loadView('procurement.rfq-to-principle-pdf', compact('rfqp', 'user', 'title'));

        return $pdf->stream($title);
    }

    public function destroyPrinciple($id)
    {
        if (is_null($this->user) || !$this->user->can('procurement.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any proc data!');
        }

        $id = decrypt($id);

        $rfqp = TrxTenderRfqPrinciple::with(['principle', 'tenderRfq', 'userUpdator'])->findOrFail($id);
        if (!is_null($rfqp)) {
            // Log the update action
            $log = [
                'name' => 'procurement',
                'description' => 'RFQ Principle '.$rfqp->principle->name.' has been deleted by procurement: '.auth()->user()->name,
                'trx_tender_id' => $rfqp->tenderRfq->trx_tender_id,
            ];
            saveTenderLog($log);

            $rfqp->delete(); // This will now perform a soft delete
        }

        return response()->json(['message' => 'RFQ Principle deleted']);
    }

    public function winnerPrinciple($id)
    {
        if (is_null($this->user) || !$this->user->can('procurement.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any proc data!');
        }

        $id = decrypt($id);

        $rfqp = TrxTenderRfqPrinciple::with(['principle', 'tenderRfq', 'userUpdator'])->findOrFail($id);

        if (!is_null($rfqp)) {
            // Set all related records to 'lose'
            TrxTenderRfqPrinciple::where('trx_tender_rfq_id', $rfqp->trx_tender_rfq_id)
                ->update(['status' => 'lose']);

            // Set the specified $id to 'win'
            $rfqp->status = 'win';
            $rfqp->save(); // This will now perform a soft delete

            $trxTender = TrxTender::findOrFail($rfqp->tenderRfq->trx_tender_id);
            $trxTender->status_journey = '3';
            $trxTender->save();

            // Log the update action
            $log = [
                'name' => 'procurement',
                'description' => 'RFQ Principle '.$rfqp->principle->name.' has been set to winner by procurement: '.auth()->user()->name,
                'trx_tender_id' => $rfqp->tenderRfq->trx_tender_id,
            ];
            saveTenderLog($log);
        }


        return response()->json(['message' => 'RFQ Principle deleted']);
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
    public function sendRfq(Request $request, $id)
    {
        // Get all users with the sales role
        $id = decrypt($id);
        $procurement_user = User::role('procurement')->get();
        $rfqp = TrxTenderRfqPrinciple::with(['principle', 'tenderRfq.delpoint', 'tenderRfq.term', 'tenderRfq.tender.tenderMaterial'])->find($id);

        $user = Auth::user();

        // dd($rfqp->tenderRfq->tender->tenderMaterial);
        $title = 'rfq_to_'.strtolower(str_replace(' ','_', $rfqp->principle->name)).'_'.date('YmdHis').'.pdf';

        // Generate the PDF
        $pdf = PDF::loadView('procurement.rfq-to-principle-pdf', compact('rfqp', 'user', 'title'));
        $pdfPath = 'rfqp/'.$title;
        Storage::put('public/' . $pdfPath, $pdf->output());

        // Send email to each sales user
        foreach ($procurement_user as $user) {
            Mail::to($user->email)->send(new RfqMail($pdfPath, $rfqp));
        }

        // Optionally, delete the temporary PDF file after sending the emails
        Storage::delete('public/' . $pdfPath);

        return response()->json(['message' => 'RFQ sent successfully', 'status' => true]);
    }
}

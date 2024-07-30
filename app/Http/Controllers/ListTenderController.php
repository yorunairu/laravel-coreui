<?php

namespace App\Http\Controllers;

use App\Models\M_cusprin;
use App\Models\M_currency;
use App\Models\M_uom;
use App\Models\M_material;
use App\Models\M_tender_status_journey;
use App\Models\TrxTender as Sales;
use App\Models\TrxTenderMaterial as SalesMaterial;
use App\Models\TrxTenderMaterialPrice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\Journey1to2Mail;
use Illuminate\Support\Facades\Log;
use Mail;
use Sabberworm\CSS\CSSList\KeyFrame;

class ListTenderController extends Controller
{
    public $user;


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('sales.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }
        if ($request->ajax()) {
            $data = Sales::with(['customer', 'userCreator'])->latest();
            if (!in_array($this->user->roles->pluck('id')->toArray()[0], [1,5])) {
                $data = $data->where('created_by', $this->user->id);
            }
            // Check if category is present in the request and filter accordingly
            if ($request->has('category') && $request->category == 'pra-quotation') {
                $data = $data->whereIn('status_journey', ['2', '3', '4', '5']); // Adjust 'category' to match your database column
            }else{
                $data = $data->whereNotIn('status_journey', ['0', '1', '2', '3', '4', '5']);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($row) {
                    $return = '<a class="tips-1" href="'.route('list-tender.detail-tender', [ 'id'=> encrypt($row->id), 'is_draft' => true]).'" title="Detail '.$row->name.'">'.$row->name.'</a>';

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
                ->addColumn('r-days', function($row) {
                    $return = '-';
                    if (!empty($row->deadline)) {
                        $deadline = strtotime($row->deadline);
                        $now = time();
                        $diff = $deadline - $now;

                        // Menghitung selisih hari
                        $daysDiff = floor($diff / (60 * 60 * 24));

                        // Format tanggal
                        $deadlineFormatted = date('d/m/Y', $deadline);
                        $nowFormatted = date('d/m/Y');

                        // $statusJourney = $row->statusJourney->status_code;

                        // Membuat teks informasi
                        if ($daysDiff > 1 && $daysDiff <= 3) {
                            $return = "<span class='badge badge-danger blink'>$daysDiff Days</span>";
                        } elseif ($daysDiff >= 4 && $daysDiff <= 7) {
                            $return = "<span class='badge badge-warning'>$daysDiff Days</span>";
                        } elseif ($daysDiff >= 8) {
                            $return = "<span class='badge badge-success'>$daysDiff Days</span>";
                        } elseif ($daysDiff <= 0) {
                            $return = "<span class='badge badge-danger'>$daysDiff Days</span>";

                        } else {
                            $return = "$daysDiff days"; // Jika tidak ada kondisi khusus
                        }

                        if ($row->statusJourney->status_code == 4) {
                            $return = str_replace('blink', '', $return);
                        }
                    }
                    return $return; // Mengembalikan nilai dengan informasi tanggal dan selisih hari
                })
                ->addColumn('created_at', function($row) {
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
                        $return = '<span class="badge badge-light">'.thousandSeparator($row->total_price_tender).' '.$row->currency->currency.'</span>';
                    }
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
                ->addColumn('status', function($row) {
                    $return = '<span class="badge badge-'.$row->statusJourney->color.'"><i class="fa fa-'.$row->statusJourney->icon.'"></i> '.$row->statusJourney->name.'</span>';

                    return $return; // Replace with your custom logic
                })
                ->addColumn('action', function($row) {
                    $detailUrl = route('sales.detail-tender', [ 'id'=> encrypt($row->id)]);
                    $detail_btn = '<a href="'.$detailUrl.'" title="Detail Tender '.$row->name.'" class="btn btn-xs m-1 btn-primary"><i class="fa fa-info-circle"></i> Detail</a>';
                    $editUrlTender = route('sales.edit', [ 'id'=> encrypt($row->id), 'type' => 'tender']);
                    $editUrlMaterial = route('sales.edit', [ 'id'=> encrypt($row->id), 'type' => 'material']);
                    $tender_btn = '<a class="dropdown-item" title="Edit Tender" href="'.$editUrlTender.'"><i class="fa fa-gavel"></i> Tender</a>';
                    $material_btn = '';
                    $finalUrl = route('sales.final', encrypt($row->id));
                    $final_btn = '';
                    if (in_array($row->status_journey, [1,2, 3])) {
                        $material_btn = '<a class="dropdown-item" title="Edit Material" href="'.$editUrlMaterial.'"><i class="fa fa-industry"></i> Material</a>';
                        if ($row->tenderMaterial->isNotEmpty() && $row->status_journey == 1) {
                            $final_btn = '<button type="button" title="Submit" class="btn btn-xs m-1 btn-warning btn-sm" onclick="confirmFinal(\''.$finalUrl.'\')"><i class="fa fa-send-o"></i></button>';
                        }
                    }
                    $deleteUrl = route('sales.destroy', encrypt($row->id));
                    $delete_btn = '<button type="button" title="Delete" class="btn btn-xs m-1 btn-danger btn-sm" onclick="confirmDelete(\''.$deleteUrl.'\')"><i class="fa fa-trash"></i></button>';
                    if (!$this->user->can('sales.edit')) {
                        $tender_btn = '';
                        $material_btn = '';
                        $delete_btn = '';
                    }
                    $logUrl = route('fetch.get-tender-log', ['id' => encrypt($row->id)]);
                    $log_btn = '<button id="tender-log-modal" data-url="'.$logUrl.'" type="button" title="History Tender" class="btn btn-xs m-1 btn-info" data-toggle="modal" data-target="#tenderLogModal"><i class="fa fa-history"></i></button>';

                    return '<div class="btn-group">
                                <button type="button" title="Edit" class="btn btn-xs m-1 btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <div class="dropdown-menu">
                                    '.$tender_btn.'
                                    '.$material_btn.'
                                </div>
                            </div>`
                            '.$delete_btn.$final_btn.$log_btn.$detail_btn;
                })
                ->rawColumns(['action', 'status', 'currency', 'name', 'win_lost', 'doc_rfq_from_customer', 'r-days']) // Render the action column as raw HTML
                ->make(true);
        }
        return view('list-tender.index');
    }
    public function postIndex(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('sales.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }
        if ($request->ajax()) {
            $data = Sales::with(['customer', 'userCreator'])->whereIn('status_journey', ['4'])->latest();
            if (!in_array($this->user->roles->pluck('id')->toArray()[0], [1,5])) {
                $data = $data->where('created_by', $this->user->id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($row) {
                    $return = '<a class="tips-1" href="'.route('list-tender.detail-tender', [ 'id'=> encrypt($row->id), 'is_draft' => true]).'" title="Detail '.$row->name.'">'.$row->name.'</a>';

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
                ->addColumn('r-days', function($row) {
                    $return = '-';
                    if (!empty($row->deadline)) {
                        $deadline = strtotime($row->deadline);
                        $now = time();
                        $diff = $deadline - $now;

                        // Menghitung selisih hari
                        $daysDiff = floor($diff / (60 * 60 * 24));

                        // Format tanggal
                        $deadlineFormatted = date('d/m/Y', $deadline);
                        $nowFormatted = date('d/m/Y');

                        // $statusJourney = $row->statusJourney->status_code;

                        // Membuat teks informasi
                        if ($daysDiff <= 3) {
                            $return = "<span class='badge badge-danger'>$daysDiff Days</span>";
                        } elseif ($daysDiff >= 4 && $daysDiff <= 7) {
                            $return = "<span class='badge badge-warning'>$daysDiff Days</span>";
                        } elseif ($daysDiff >= 8) {
                            $return = "<span class='badge badge-success'>$daysDiff Days</span>";
                        } else {
                            $return = "$daysDiff days"; // Jika tidak ada kondisi khusus
                        }

                        if ($row->statusJourney->status_code == 4) {
                            $return = str_replace('blink', '', $return);
                        }
                    }
                    return $return; // Mengembalikan nilai dengan informasi tanggal dan selisih hari
                })
                ->addColumn('created_at', function($row) {
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
                        $return = '<span class="badge badge-light">'.thousandSeparator($row->total_price_tender).' '.$row->currency->currency.'</span>';
                    }
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
                ->addColumn('status', function($row) {
                    $return = '<span class="badge badge-'.$row->statusJourney->color.'"><i class="fa fa-'.$row->statusJourney->icon.'"></i> '.$row->statusJourney->name.'</span>';

                    return $return; // Replace with your custom logic
                })
                ->addColumn('action', function($row) {
                    $detailUrl = route('sales.detail-tender', [ 'id'=> encrypt($row->id)]);
                    $detail_btn = '<a href="'.$detailUrl.'" title="Detail Tender '.$row->name.'" class="btn btn-xs m-1 btn-primary"><i class="fa fa-info-circle"></i> Detail</a>';
                    $editUrlTender = route('sales.edit', [ 'id'=> encrypt($row->id), 'type' => 'tender']);
                    $editUrlMaterial = route('sales.edit', [ 'id'=> encrypt($row->id), 'type' => 'material']);
                    $tender_btn = '<a class="dropdown-item" title="Edit Tender" href="'.$editUrlTender.'"><i class="fa fa-gavel"></i> Tender</a>';
                    $material_btn = '';
                    $finalUrl = route('sales.final', encrypt($row->id));
                    $final_btn = '';
                    if (in_array($row->status_journey, [1,2, 3])) {
                        $material_btn = '<a class="dropdown-item" title="Edit Material" href="'.$editUrlMaterial.'"><i class="fa fa-industry"></i> Material</a>';
                        if ($row->tenderMaterial->isNotEmpty() && $row->status_journey == 1) {
                            $final_btn = '<button type="button" title="Submit" class="btn btn-xs m-1 btn-warning btn-sm" onclick="confirmFinal(\''.$finalUrl.'\')"><i class="fa fa-send-o"></i></button>';
                        }
                    }
                    $deleteUrl = route('sales.destroy', encrypt($row->id));
                    $delete_btn = '<button type="button" title="Delete" class="btn btn-xs m-1 btn-danger btn-sm" onclick="confirmDelete(\''.$deleteUrl.'\')"><i class="fa fa-trash"></i></button>';
                    if (!$this->user->can('sales.edit')) {
                        $tender_btn = '';
                        $material_btn = '';
                        $delete_btn = '';
                    }
                    $logUrl = route('fetch.get-tender-log', ['id' => encrypt($row->id)]);
                    $log_btn = '<button id="tender-log-modal" data-url="'.$logUrl.'" type="button" title="History Tender" class="btn btn-xs m-1 btn-info" data-toggle="modal" data-target="#tenderLogModal"><i class="fa fa-history"></i></button>';

                    return '<div class="btn-group">
                                <button type="button" title="Edit" class="btn btn-xs m-1 btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <div class="dropdown-menu">
                                    '.$tender_btn.'
                                    '.$material_btn.'
                                </div>
                            </div>`
                            '.$delete_btn.$final_btn.$log_btn.$detail_btn;
                })
                ->rawColumns(['action', 'status', 'currency', 'name', 'win_lost', 'doc_rfq_from_customer', 'r-days']) // Render the action column as raw HTML
                ->make(true);
        }
        return view('list-tender.index');
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
        $status_journey = M_tender_status_journey::get();
        $type = 'material';
        if ($type == 'tender') {
            return view('sales.edittender', compact('sales', 'type', 'currencies', 'uom'));
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
            return view('list-tender.detail-tender', compact('sales', 'type', 'currencies', 'uom', 'check_price', 'price', 'priceSum', 'margin', 'percentageMargin', 'status_journey'));
        }
    }

    function checkMaterialPrices($trx_tender_id)
    {
        // Get the material IDs associated with the given trx_tender_id
        $material_list = SalesMaterial::where('trx_tender_id', $trx_tender_id)->pluck('id');

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

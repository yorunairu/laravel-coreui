<?php

namespace App\Http\Controllers;

use App\Models\M_cusprin;
use App\Models\M_currency;
use App\Models\M_uom;
use App\Models\M_material;
use App\Models\M_tender_status_journey;
use App\Models\TrxTender as Sales;
use App\Models\TrxTenderMaterial as SalesMaterial;
use App\Models\TrxTenderMaterial;
use App\Models\TrxTenderMaterialPrice;
use App\Models\TrxTenderBgGuarantee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\Journey1to2Mail;
use App\Models\M_delpoint;
use App\Models\M_term;
use App\Models\TrxTender;
use App\Models\TrxTenderSalesActivity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Mail\QuoMail;
use App\Models\TrxTenderPo;
use App\Models\TrxTenderPoTerm;
use App\Models\TrxTenderQuo;
use App\Models\TrxTenderQuoDelpoint;
use App\Models\TrxTenderQuoTerm;
use App\Models\TrxTenderRfqPrinciple;
use Mail;
use PDF;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class SalesController extends Controller
{
    public $user;


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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
                $data = $data->whereIn('status_journey', ['0', '1', '2', '3', '4']); // Adjust 'category' to match your database column
            }else{
                $data = $data->whereNotIn('status_journey', ['0', '1', '2', '3', '4']);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($row) {
                    $return = '<a class="tips-1" href="'.route('sales.detail-tender', [ 'id'=> encrypt($row->id)]).'" title="Detail '.$row->name.'">'.$row->name.'</a>';

                    return $return; // Replace with your custom logic
                })
                ->addColumn('doc_rfq_from_customer', function($row) {
                    $return = '-';
                    if (!empty($row->doc_rfq_from_customer)) {
                        $return = '<a href="'.asset('storage/'.$row->doc_rfq_from_customer).'" target="_blank" class="btn btn-sm btn-danger"><i class="fa fa-file-pdf-o"></i></a>';
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
                        $return = '<span class="badge badge-light text-dark">'.thousandSeparator($row->total_price_tender).' '.$row->currency->currency.'</span>';
                    }
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
                ->addColumn('status', function($row) {
                    $return = '<span class="badge bg-'.$row->statusJourney->color.'"><i class="fa fa-'.$row->statusJourney->icon.'"></i> '.$row->statusJourney->name.'</span>';

                    return $return; // Replace with your custom logic
                })
                ->addColumn('action', function($row) {
                    $detailUrl = route('sales.detail-tender', [ 'id'=> encrypt($row->id)]);
                    $detail_btn = '<a href="'.$detailUrl.'" title="Detail Tender '.$row->name.'" class="btn btn-sm m-1 btn-primary"><i class="fa fa-info-circle"></i> Detail</a>';
                    $editUrlTender = route('sales.edit', [ 'id'=> encrypt($row->id), 'type' => 'tender']);
                    $editUrlMaterial = route('sales.edit', [ 'id'=> encrypt($row->id), 'type' => 'material']);
                    $tender_btn = '<a class="dropdown-item" title="Edit Tender" href="'.$editUrlTender.'"><i class="fa fa-gavel"></i> Tender</a>';
                    $material_btn = '';
                    $finalUrl = route('sales.final', encrypt($row->id));
                    $final_btn = '';
                    if (in_array($row->status_journey, [1,2, 3])) {
                        $material_btn = '<a class="dropdown-item" title="Edit Material" href="'.$editUrlMaterial.'"><i class="fa fa-industry"></i> Material</a>';
                        if ($row->tenderMaterial->isNotEmpty() && $row->status_journey == 1) {
                            $final_btn = '<button type="button" title="Submit" class="btn btn-sm m-1 btn-warning btn-sm" onclick="confirmFinal(\''.$finalUrl.'\')"><i class="fa fa-send-o"></i></button>';
                        }
                    }
                    $deleteUrl = route('sales.destroy', encrypt($row->id));
                    $delete_btn = '<button type="button" title="Delete" class="btn btn-sm m-1 btn-danger btn-sm" onclick="confirmDelete(\''.$deleteUrl.'\')"><i class="fa fa-trash"></i></button>';
                    if (!$this->user->can('sales.edit')) {
                        $tender_btn = '';
                        $material_btn = '';
                        $delete_btn = '';
                    }
                    $logUrl = route('fetch.get-tender-log', ['id' => encrypt($row->id)]);
                    $log_btn = '<button id="tender-log-modal" data-url="'.$logUrl.'" type="button" title="History Tender" class="btn btn-sm m-1 btn-info" data-toggle="modal" data-target="#tenderLogModal"><i class="fa fa-history"></i></button>';

                    return '<div class="btn-group">
                                <button type="button" title="Edit" class="btn btn-sm m-1 btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <div class="dropdown-menu">
                                    '.$tender_btn.'
                                    '.$material_btn.'
                                </div>
                            </div>`
                            '.$delete_btn.$final_btn.$log_btn.$detail_btn;
                })
                ->rawColumns(['action', 'status', 'currency', 'name', 'win_lost', 'doc_rfq_from_customer']) // Render the action column as raw HTML
                ->make(true);
        }
        return view('sales.index');
    }

    public function activityList(Request $request, $id)
    {
        $id = decrypt($id);

        $sales = TrxTender::find($id);
        $sales_activity = TrxTenderSalesActivity::where('trx_tender_id', $id)->with(['tenders', 'userCreator'])->orderBy('date', 'desc')->orderBy('id', 'desc');

        $user = Auth::user();

        if ($request->ajax()) {
            return DataTables::of($sales_activity)
            ->addIndexColumn()
            ->addColumn('description', function($row) {
                return $row->description; // Pastikan description mengandung tag HTML jika diperlukan
            })
            ->addColumn('doc_evidence', function($row) {
                $return = '<a class="btn btn-sm btn-danger" href="'.asset('storage/'.$row->doc_evidence).'" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
                return $return; // Pastikan description mengandung tag HTML jika diperlukan
            })
            ->addColumn('action', function($row){
                $return = '';
                if($row->created_by == auth()->user()->id){
                    $return = '<button class="btn btn-sm btn-danger delete" onClick="deleteModal(this)" data-id="' .$row->id. '"><i class="fa fa-trash"></i></button>';
                }

                return $return;
            })
            ->rawColumns(['doc_evidence', 'action', 'description'])
            ->make(true);
        }

        return view('sales.activity', compact('sales'));
    }

    public function activityStore(Request $request, $id)
    {
        $request->validate([
            'doc_evidence' => 'required',
            'description' => 'required',
            'date' => 'required|date'
        ]);
        $id = decrypt($id);

        $sales = new TrxTenderSalesActivity();
        $sales->trx_tender_id = $id;
        $sales->date = $request->date;
        $sales->description = $request->description;
        $sales->user_id = Auth::id();
        if ($request->hasFile('doc_evidence')) {
            $file = $request->file('doc_evidence');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('doc_evidence', $filename, 'public'); // Adjust storage path as needed
        } else {
            // Handl    e case where attachment is required but not provided
            return back()->with('error', 'doc evidence  file is required.');
        }
        $sales->doc_evidence = $path; // Save file path to database

        $sales->save();

        return response()->json([
            'message' => 'Created Acitivity successfully',
            'status' => 'created'
        ]);
    }

    public function activityUpdate(Request $request, $id)
    {
        $validateData = $request->validate([
            'doc_evidence' => 'required',
            'description' => 'required',
            'date' => 'required|date'
        ]);

        $sales = TrxTenderSalesActivity::findOrFail($id);
        $sales->description = $validateData['description'];
        // $sales->price_rate = thousandToNumber($validateData['price_rate']);
        if ($request->hasFile('doc_evidence')) {
            $file = $request->file('doc_evidence');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('doc_evidence', $fileName, 'public'); // Store file
            $sales->doc_evidence = $fileName; // Update document field
        }
        $sales->date = $validateData['date'];
        $sales->save();
    }

    // public function activityEdit($id)
    // {
    //     $sa
    // }

    public function activityDestroy($id)
    {
        $sales = TrxTenderSalesActivity::find($id);
        $sales->delete();

        return response()->json([
            'message' => 'Deleted activity successfully'
        ]);
    }

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
            return '<a class="btn btn-sm btn-danger" href="' . $url . '" target="_blank"><i class="fa fa-file-pdf-o"></a>';

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

    public function materialList(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('sales.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }

        $id = decrypt($id);
        $data = SalesMaterial::where('trx_tender_id', $id)
            ->with(['uom', 'material', 'price', 'tender']);

        if (!in_array($this->user->roles->pluck('id')->toArray()[0], [1, 5])) {
            $data = $data->where('created_by', $this->user->id);
        }

        $sales = Sales::find($id);
        $currency = null;

        if ($sales->status_journey > 2) {
            $currency = !empty(@$sales->tenderRfq->RfqPrinciple->firstWhere('status', 'win')->currency->currency)
                ? @$sales->tenderRfq->RfqPrinciple->firstWhere('status', 'win')->currency->currency
                : null;
        }

        // Calculate total KZ price
        $totalKz = $data->get()->sum(function ($row) {
            $total = 0;
            foreach ($row->price as $item) {
                if ($item->type === 'KZ') {
                    $total += $item->price * $row->qty;
                }
            }
            return $total;
        });

        // Initialize totals
        $totalPrinciple = 0;
        $totalPrincipleOther = 0;

        // Calculate total Principle price
        $totalPrinciple = $data->get()->sum(function ($row) use (&$totalPrincipleOther) {
            $total = 0;
            foreach ($row->price as $item) {
                if ($item->type === 'Principle') {
                    if ($item->currency->currency == 'IDR') {
                        $total += $item->price * $row->qty;
                    } else {
                        $totalPrincipleOther += $item->price * $row->qty;
                        $total += $item->price * $row->qty * $item->currency->price_rate;
                    }
                }
            }
            return $total;
        });

        // Calculate total Margin based on KZ price
        $totalMargin = $totalKz-$totalPrinciple;

        // Calculate the margin percentage based on KZ price
        $totalMarginPercentage = ($totalKz > 0) ? ($totalMargin / $totalKz) * 100 : 0;

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('material_code', function($row) use ($request) {
                $return = $row->material->material_code;
                $deleteUrl = route('sales.material-destroy', encrypt($row->id));
                $delete_btn = '<button type="button" class="btn btn-sm btn-danger btn-sm" onclick="confirmDelete(\''.$deleteUrl.'\')"><i class="fa fa-trash"></i></button> ';
                if (!$this->user->can('sales.edit') || !in_array($row->tender->status_journey, ['0', '1'])) {
                    $delete_btn = '';
                }

                return $delete_btn.$return;
            })
            ->addColumn('reviewed_at', function($row) use ($request) {
                $return = '';
                if (in_array($row->tender->status_journey, ['4', '5'])) {
                    $return = $row->tender->reviewed_at;
                }

                return $return;
            })
            ->addColumn('reviewed_by', function($row) use ($request) {
                $return = '';
                if (in_array($row->tender->status_journey, ['4', '5'])) {
                    $return = $row->tender->reviewer->name;
                }

                return $return;
            })
            ->addColumn('unit_price_principle', function($row) use ($request) {
                $return = '';
                if (in_array($row->tender->status_journey, ['3', '4', '5'])) {
                    $url = route('sales.form-update-price-material');
                    if (empty($request->is_detail_tender)) {
                        $return .= '<button type="button" class="btn btn-sm btn-warning mx-1 btn-sm proc-update-price-principle-kz" data-toggle="modal" data-target="#updatePricePrincipleKzModal" data-type="Principle" data-id="'.encrypt($row->id).'" data-url="'.$url.'" data-name="'.$row->material->material_code.'" id="updatePricePrincipleKz"><i class="fa fa-money-bill"></i></button>';
                    }

                    foreach ($row->price as $item) {
                        if ($item->type === 'Principle') {
                            $return .= '<div>';
                            $return .= '<span class="badge text-bg-light">' . thousandSeparator($item->price) . ' '.$item->currency->currency.'</span><br>';
                            if ($item->currency->currency != 'IDR') {
                                $idrConvert = $item->price * $item->currency->price_rate;
                                $return .= '<span class="badge text-bg-light">' . thousandSeparator($idrConvert) . ' IDR</span>';
                            }
                            $return .= '</div>';
                            break;
                        }
                    }
                }

                return $return;
            })
            ->addColumn('unit_price_kz', function($row) use ($request) {
                $return = '';
                if (in_array($row->tender->status_journey, ['3', '4', '5'])) {
                    $url = route('sales.form-update-price-material');
                    if (empty($request->is_detail_tender)) {
                        $return .= '<button type="button" class="btn btn-sm btn-warning mx-1 btn-sm proc-update-price-principle-kz" data-toggle="modal" data-target="#updatePricePrincipleKzModal" data-type="KZ" data-id="'.encrypt($row->id).'" data-url="'.$url.'" data-name="'.$row->material->material_code.'" id="updatePricePrincipleKz"><i class="fa fa-money-bill"></i></button>';
                    }

                    foreach ($row->price as $item) {
                        if ($item->type === 'KZ') {
                            $return .= '<div>';
                            $return .= '<span class="badge text-bg-light">' . thousandSeparator($item->price) . ' '.$item->currency->currency.'</span><br>';
                            if ($item->currency->currency != 'IDR') {
                                $idrConvert = $item->price * $item->currency->price_rate;
                                $return .= '<span class="badge text-bg-light">' . thousandSeparator($idrConvert) . ' IDR</span>';
                            }
                            $return .= '</div>';
                            break;
                        }
                    }
                }

                return $return;
            })
            ->addColumn('price_margin', function($row) {
                $return = '-';

                if (in_array($row->tender->status_journey, ['3', '4'])) {
                    $principlePrice = null;
                    $kzPrice = null;
                    $principleCurrencyRate = null;
                    $kzCurrencyRate = null;

                    foreach ($row->price as $item) {
                        if ($item->type === 'Principle') {
                            $principlePrice = $item->price;
                            $principleCurrencyRate = $item->currency->price_rate;
                        }
                        if ($item->type === 'KZ') {
                            $kzPrice = $item->price;
                            $kzCurrencyRate = $item->currency->price_rate;
                        }
                    }

                    if ($principlePrice !== null && $kzPrice !== null) {
                        // Convert both prices to IDR
                        $principlePriceInIDR = $principlePrice * $principleCurrencyRate;
                        $kzPriceInIDR = $kzPrice * $kzCurrencyRate;

                        // Calculate the margin in IDR
                        $margin = $kzPriceInIDR - $principlePriceInIDR;
                        $marginPercentage = ($kzPriceInIDR != 0) ? ($margin / $kzPriceInIDR) * 100 : 0;

                        $badgeClass = $margin < 0 ? 'badge-danger' : '';
                        $return = '<span class="badge ' . $badgeClass . '">' . thousandSeparator($margin) . ' IDR (' . number_format($marginPercentage, 2) . '%)</span>';
                    } else {
                        $return = '-';
                    }
                }

                return $return;
            })
            ->addColumn('total_price_principle', function($row) {
                $totalPrinciple = 0;
                $principleCurrency = '';
                $principlePriceInIDR = 0;

                foreach ($row->price as $item) {
                    if ($item->type === 'Principle') {
                        $principleCurrency = $item->currency->currency;
                        if ($principleCurrency == 'IDR') {
                            $totalPrinciple = $item->price * $row->qty; // Direct total if currency is IDR
                            $principlePriceInIDR = $totalPrinciple;
                        } else {
                            $totalPrinciple = $item->price * $row->qty; // Total in original currency
                            $principlePriceInIDR = $totalPrinciple * $item->currency->price_rate; // Convert to IDR
                        }
                        break; // Only one principle price should be found
                    }
                }

                $principleTotalBadge = $principleCurrency != 'IDR'
                    ? '<span class="badge text-bg-light">' . thousandSeparator($totalPrinciple) . ' ' . $principleCurrency . '</span><br>'
                    : '';

                return $principleTotalBadge . '<span class="badge text-bg-light">' . thousandSeparator($principlePriceInIDR) . ' IDR</span>';
            })
            ->addColumn('total_price_kz', function($row) {
                $totalKz = 0;
                $kzCurrency = '';
                $kzPriceInIDR = 0;

                foreach ($row->price as $item) {
                    if ($item->type === 'KZ') {
                        $kzCurrency = $item->currency->currency;
                        if ($kzCurrency == 'IDR') {
                            $totalKz = $item->price * $row->qty; // Direct total if currency is IDR
                            $kzPriceInIDR = $totalKz;
                        } else {
                            $totalKz = $item->price * $row->qty; // Total in original currency
                            $kzPriceInIDR = $totalKz * $item->currency->price_rate; // Convert to IDR
                        }
                        break; // Only one KZ price should be found
                    }
                }

                $kzTotalBadge = $kzCurrency != 'IDR'
                    ? '<span class="badge text-bg-light">' . thousandSeparator($totalKz) . ' ' . $kzCurrency . '</span><br>'
                    : '';

                return $kzTotalBadge . '<span class="badge text-bg-light">' . thousandSeparator($kzPriceInIDR) . ' IDR</span>';
            })
            ->addColumn('total_price_margin', function($row) {
                $totalMargin = 0;
                $principlePrice = null;
                $kzPrice = null;
                $principleCurrencyRate = 1;
                $kzCurrencyRate = 1;

                foreach ($row->price as $item) {
                    if ($item->type === 'Principle') {
                        $principlePrice = $item->price;
                        $principleCurrencyRate = $item->currency->price_rate;
                    }
                    if ($item->type === 'KZ') {
                        $kzPrice = $item->price;
                        $kzCurrencyRate = $item->currency->price_rate;
                    }
                }

                if ($principlePrice !== null && $kzPrice !== null) {
                    // Convert both prices to IDR
                    $principlePriceInIDR = $principlePrice * $principleCurrencyRate * $row->qty;
                    $kzPriceInIDR = $kzPrice * $kzCurrencyRate * $row->qty;

                    // Calculate the margin in IDR
                    $totalMargin = $kzPriceInIDR - $principlePriceInIDR;
                    $marginPercentage = ($kzPriceInIDR != 0) ? ($totalMargin / $kzPriceInIDR) * 100 : 0;

                    $badgeClass = $totalMargin < 0 ? 'text-bg-danger' : 'text-bg-light';
                    return '<span class="badge ' . $badgeClass . '">' . thousandSeparator($totalMargin) . ' IDR (' . number_format($marginPercentage, 2) . '%)</span>';
                }

                return '<span class="badge">-</span>';
            })
            ->addColumn('action', function($row) {
                $deleteUrl = route('sales.material-destroy', encrypt($row->id));
                $delete_btn = '<button type="button" class="btn btn-sm btn-danger btn-sm" onclick="confirmDelete(\''.$deleteUrl.'\')"><i class="fa fa-trash"></i></button>';
                if (!$this->user->can('sales.edit') || !in_array($row->tender->status_journey, ['0', '1'])) {
                    $delete_btn = '';
                }

                return $delete_btn;
            })
            ->rawColumns(['action', 'status', 'unit_price_principle', 'unit_price_kz', 'total_amount', 'description', 'price_margin', 'material_code', 'total_price_principle', 'total_price_kz', 'total_price_margin'])
            ->with([
                'totalPrinciple' => $totalPrinciple,
                'totalPrincipleOther' => $totalPrincipleOther,
                'totalKz' => $totalKz,
                'totalMargin' => $totalMargin,
                'totalMarginPercentage' => $totalMarginPercentage,
                'currency' => $currency
            ])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('sales.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }

        $currencies = M_currency::get();

        // $all_permissions  = Permission::all();
        // $permission_groups = User::getpermissionGroups();
        // return view('roles.create', compact('all_permissions', 'permission_groups'));

        return view('sales.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'no_rfq' => 'required|max:255', // RFQ number
        'name' => 'required|max:255', // Tender name
        'customer_id' => 'required|exists:m_cusprins,id',
        'email' => 'required|email',
        'deadline' => 'required|date',
        'tanggal_keluar' => 'required|date',
        // 'doc_rfq_from_customer' => 'required|file|max:10240', // Max size in kilobytes (10MB)
        'action' => 'required|in:draft,submit' // Action type
    ]);

    // Handle file upload (attachment)
    if ($request->hasFile('doc_rfq_from_customer')) {
        $file = $request->file('doc_rfq_from_customer');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('doc_rfq_from_customers', $filename, 'public'); // Adjust storage path as needed
    } else {
        // Handle case where attachment is required but not provided
        return back()->with('error', 'doc_rfq_from_customer file is required.');
    }

    // Create or update Tender record
    $sales = new Sales();
    $sales->no_rfq = $request->input('no_rfq');
    $sales->name = $request->input('name');
    $sales->customer_id = $request->input('customer_id');
    $sales->currency_id = $request->input('currency_id');
    $sales->total_price_tender = thousandToNumber($request->input('total_price_tender'));
    $sales->email = $request->input('email');
    $sales->deadline = $request->input('deadline');
    $sales->tanggal_keluar = $request->input('tanggal_keluar');
    $sales->notes = $request->input('note');
    $sales->doc_rfq_from_customer = $path; // Save file path to database
    $sales->status = $request->input('action'); // Save file path to database
    $sales->tender_type = $request->input('tender_type'); // Save file path to database
    $sales->status_win_lost = $request->input('status_win_lost', null); // Handle null if not provided
    if ($request->input('action') === 'submit') {
        $sales->status_journey = '1'; // Save file path to database
    } elseif ($request->input('action') === 'draft') {
        $sales->status_journey = '0'; // Save file path to database
    }

    if ($request->filled('is_bb_bond')) {
        $sales->is_bb_bond = $request->input('is_bb_bond', null);
    }
    if ($request->filled('is_pb_bond')) {
        $sales->is_pb_bond = $request->input('is_pb_bond', null);
    }
    $sales->save();

    // Update or create TrxTenderBgGuarantee records for bid bond
    if ($request->input('is_bb_bond') === 'true') {
        $bb = [
            'no_guarantee' => $request->input('bb_no_guarantee'),
            'price' => thousandToNumber($request->input('bb_price')),
            'note' => $request->input('bb_note'),
            'time_period' => $request->input('bb_time_periode')
        ];
        if ($request->hasFile('bb_doc_bg')) {
            $file = $request->file('bb_doc_bg');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bb_doc_bgs', $filename, 'public');
            $bb['doc_bg'] = $path;
        }
        TrxTenderBgGuarantee::updateOrCreate(
            ['trx_tender_id' => $sales->id, 'type' => 'bb'],
            $bb
        );
    }

    // Update or create TrxTenderBgGuarantee records for perf bond
    if ($request->input('is_pb_bond') === 'true') {
        $pg = [
            'no_guarantee' => $request->input('pb_no_guarantee'),
            'price' => thousandToNumber($request->input('pb_price')),
            'note' => $request->input('pb_note'),
            'time_period' => $request->input('pb_time_periode')
        ];
        if ($request->hasFile('pb_doc_bg')) {
            $file = $request->file('pb_doc_bg');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('pb_doc_bgs', $filename, 'public');
            $pg['doc_bg'] = $path;
        }
        TrxTenderBgGuarantee::updateOrCreate(
            ['trx_tender_id' => $sales->id, 'type' => 'pb'],
            $pg
        );
    }

    // Delete TrxTenderBgGuarantee records if bid bond or perf bond is not true
    if ($request->input('is_bb_bond') !== 'true') {
        TrxTenderBgGuarantee::where('trx_tender_id', $sales->id)->where('type', 'bb')->delete();
    }

    if ($request->input('is_pb_bond') !== 'true') {
        TrxTenderBgGuarantee::where('trx_tender_id', $sales->id)->where('type', 'pb')->delete();
    }

    $log = [
        'name' => 'tender',
        'description' => 'Tender dengan no RFQ : '.$sales->no_rfq.' telah dibuat oleh : '.auth()->user()->name,
        'trx_tender_id' => $sales->id,
    ];
    saveTenderLog($log);

    // Optionally handle additional actions based on 'action' parameter (draft/submit)

    // Flash success message to session
    session()->flash('success', 'Sales Tender has been created successfully.');

    $return['status'] = true;
    $return['id'] = encrypt($sales->id);

    echo json_encode($return);exit;
}

    public function storeMaterial(Request $request, $id)
    {

        // \Log::info($request->all());

        // Validate the request data
        $request->validate([
            'material_code' => '', // RFQ number
            'description' => 'required', // Tender name
            'quantity' => 'required',
            'uom' => 'required',
            // 'unitPriceKZ' => 'required',
            // 'totalAmount' => 'required',
        ]);

        $id = decrypt($id);

        if ($request->input('material_is_exist') == 'no') {
            // Create new material record
            $material = new M_material();
            if (!empty($request->material_code)) {
                $material->material_code = $request->material_code;
            }else{
                $material->material_code = $this->generateUniqueMaterialCode();
            }
            $material->description = $request->input('description');
            $material->save();
        }

        // Create or update Tender record
        $sales = new SalesMaterial();
        $sales->trx_tender_id = $id;
        $sales->m_material_id = !empty($request->input('material_code_id'))?$request->input('material_code_id'):$material->id;
        $sales->description = $request->input('description');
        $sales->qty = $request->input('quantity');
        $sales->m_uom_id = $request->input('uom');
        // $sales->unit_price_kz = $request->input('unitPriceKZ');
        // $sales->total_amount = $request->input('totalAmount');
        $sales->save();

        $log = [
            'name' => 'tender',
            'description' => 'Tender material telah ditambahkan oleh : '.auth()->user()->name,
            'trx_tender_id' => $sales->id,
        ];
        saveTenderLog($log);

        // Optionally handle additional actions based on 'action' parameter (draft/submit)

        // Flash success message to session
        session()->flash('success', 'Sales Tender has been created successfully.');

        $return['status']=true;

        echo json_encode($return);exit;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
            return view('sales.edittender', compact('sales', 'type', 'currencies', 'uom', 'is_detail_tender', 'bid_bond', 'pb_bond'));
        }else {
            $check_price = $this->checkMaterialPrices($sales->id);
            $price = [];
            $priceSum = [];
            $margin = '';
            $percentageMargin = '';
            if (in_array($sales->status_journey, ['3', '4']) && !empty($sales->tenderRfq->RfqPrinciple->firstWhere('status', 'win')->currency)) {
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
            return view('sales.editmaterial', compact('sales', 'type', 'currencies', 'uom', 'check_price', 'price', 'priceSum', 'margin', 'percentageMargin', 'is_detail_tender'));
        }
    }

    public function detailTender($id)
    {
        if (is_null($this->user) || !$this->user->can('sales.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }

        $id = decrypt($id);

        $currencies = M_currency::get();
        $uom = M_uom::get();
        $sales = Sales::with('tenderRfq.rfqPrinciple')->find($id);
        $status_journey = M_tender_status_journey::get();
        // $status = TrxTenderRfqPrinciple::get();
        $is_null_bid = is_null($sales->is_bb_bond)?true:false;
        $type = 'material';
        if ($type == 'tender') {
            return view('sales.edittender', compact('sales', 'type', 'currencies', 'uom', 'is_null_bid'));
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
            $sales = Sales::find($id);

            $termsType1 = M_delpoint::get();

            $termsType1 = M_delpoint::get();
            $termsType2 = M_term::where('type', 'additional_value')->where('is_quo', true)->get();
            $termsType3 = M_term::where('type', 'price_validity')->where('is_quo', true)->get();
            $termsType4 = M_term::where('type', 'delivery_time')->where('is_quo', true)->get();
            $termsType5 = M_term::where('type', 'payment_terms')->where('is_quo', true)->get();
            $terms = M_term::where('type', 'term')->where('is_quo', true)->get();
            // dd($price);
            return view('sales.detail-tender', compact('sales', 'type', 'currencies', 'uom', 'check_price', 'price', 'priceSum', 'margin', 'percentageMargin', 'status_journey', 'is_null_bid', 'termsType1', 'termsType2', 'termsType3', 'termsType4', 'termsType5', 'terms'));
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
        if ($type == 'tender') {
            return view('sales.edittender', compact('sales', 'type', 'currencies', 'uom', 'is_null_bid'));
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
            $sales = Sales::find($id);

        // $termsType1 = M_term::get();
            $termsType1 = M_delpoint::get();
            // dd($termsType1);

            // $existingData = [
            //     'delivery_point' => [1],
            //     'price_validity' => [9],
            //     'delivery_time' => [10],
            //     'payment_terms' => [11],
            //     'additional_value' => [8],
            //     'terms' => !empty($sales->tenderRfq->term)?$sales->tenderRfq->term->pluck('m_term_compwith_id'):[],
            // ];
            $termsType1 = M_delpoint::get();
            $termsType2 = M_term::where('type', 'additional_value')->where('is_quo', true)->get();
            $termsType3 = M_term::where('type', 'price_validity')->where('is_quo', true)->get();
            $termsType4 = M_term::where('type', 'delivery_time')->where('is_quo', true)->get();
            $termsType5 = M_term::where('type', 'payment_terms')->where('is_quo', true)->get();
            $terms = M_term::where('type', 'term')->where('is_quo', true)->get();
            // dd($price);
            return view('sales.detail-tender-post', compact('sales', 'type', 'currencies', 'uom', 'check_price', 'price', 'priceSum', 'margin', 'percentageMargin', 'status_journey', 'is_null_bid',
            'termsType1',
            'termsType2',
            'termsType3',
            'termsType4',
            'termsType5',
            'terms',
        ));
        }
    }

    public function poToPrinciplePdf(Request $request, $id)
    {
        //
    }

    public function quotationToCustomerPdf(Request $request, $id, $role=null){
        $id = decrypt($id);
        $sales = Sales::with(['customer', 'tenderMaterial.price'])->find($id);
        // dd($sales);
        $user = Auth::user();

        $pdfData = Session::get('pdfData');
        $user = Auth::user();
        if (!empty($role)) {
            if ($role == 'sales') {
                $receiver = User::role('sales')->pluck('email')->toArray();
                $cc_receiver = User::role('procurement')->pluck('email')->toArray();
            } else {
                $receiver = User::role('procurement')->pluck('email')->toArray();
                $cc_receiver = User::role('sales')->pluck('email')->toArray();
            }
        }

        // $poNumber = $this->generatePoNumber();
        // $tenderPoId = $rfq->id;

        $quo = new TrxTenderQuo();
        // $rfq->principle_po_no = $poNumber;
        $quo->trx_tender_id = $id;
        $quo->note = $request->note;
        // Simpan data lain sesuai kebutuhan
        $quo->save();

        $delpoint = [
            'delivery_point' => M_delpoint::whereIn('id', $request->input('delivery_point', []))->pluck('name')->toArray(),
        ];
        $pdfDataTerm = [
            // 'delivery_point' => M_delpoint::whereIn('id', $request->input('delivery_point'))->pluck('name')->toArray(),
            'additional_value' => M_term::whereIn('id', $request->input('additional_value'))->pluck('name')->toArray(),
            'price_validity' => M_term::whereIn('id', $request->input('price_validity'))->pluck('name')->toArray(),
            'delivery_time' => M_term::whereIn('id', $request->input('delivery_time'))->pluck('name')->toArray(),
            'payment_terms' => M_term::whereIn('id', $request->input('payment_terms'))->pluck('name')->toArray(),
            'terms' => M_term::whereIn('id', $request->input('terms',[]))->pluck('name')->toArray(),
            'note' => $request->input('note')
        ];
        $title = 'quotation_to_'.strtolower(str_replace(' ','_', $sales->customer->name)).'_'.date('YmdHis').'.pdf';
        // return view('procurement.quo-to-principle-pdf', compact('rfqp', 'title', 'user'));
        $pdfData = [
            'sales' => $sales,
            'user' => $user,
            'title' => $title,
            'pdfDataTerm' => $pdfDataTerm,
            'delpoint' => $delpoint
        ];

        $tenderIdQuo = $quo->id;

        $pdf = PDF::loadView('sales.quotation-to-customer-pdf', compact('sales', 'user', 'title', 'pdfData', 'pdfDataTerm', 'delpoint'));

        foreach ($pdfDataTerm as $key => $ids) {
            if (is_array($ids) || $ids instanceof \Traversable) { // Pastikan $ids adalah array atau objek yang bisa diiterasi
                foreach ($ids as $id) {
                    if (!is_null($id)) { // Pastikan $id tidak null
                        $tenderTerm = new TrxTenderQuoTerm();
                        $tenderTerm->trx_tender_quo_id = $tenderIdQuo; // ID yang diambil dari input
                        $tenderTerm->term_id = $id; // ID term yang diambil dari input
                        $tenderTerm->save();
                    }
                }
            }
        }
        foreach ($pdfDataTerm as $key => $ids) {
            if (is_array($ids) || $ids instanceof \Traversable) { // Pastikan $ids adalah array atau objek yang bisa diiterasi
                foreach ($ids as $id) {
                    if (!is_null($id)) { // Pastikan $id tidak null
                        $tenderTerm = new TrxTenderQuoDelpoint();
                        $tenderTerm->trx_tender_quo_id = $tenderIdQuo; // ID yang diambil dari input
                        $tenderTerm->delpoint_id = $id; // ID term yang diambil dari input
                        $tenderTerm->save();
                    }
                }
            }
        }

        if (!empty($role)) {
            $pdfPath = 'quo/'.$title;
            // dd($request->all());$pdfPath = 'rfqp/'.$title;
            Storage::put('public/' . $pdfPath, $pdf->output());

            // Send email to each sales user
            // foreach ($receiver as $receiver) {
            //     Mail::to($receiver->email)->send(new QuoMail($pdfPath, $sales));
            // }
            Mail::to($receiver)->cc($cc_receiver)->send(new QuoMail($pdfPath, $sales));

            // Optionally, delete the temporary PDF file after sending the emails
            Storage::delete('public/' . $pdfPath);

            return response()->json(['message' => 'Quotation sent successfully', 'status' => true]);
        }else{
            return $pdf->stream($title);
        }
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
        return view('sales.review-prices', compact('sales', 'type', 'currencies', 'uom'));
    }

    public function updateReview(Request $request, $id)
    {
        // Check if the user is authorized to update sales data
        if (is_null($this->user) || !$this->user->can('sales.edit')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized access'], 403);
        }

        // Decrypt the ID
        $id = decrypt($id);

        // Find the sales record
        $sales = Sales::findOrFail($id);

        if (!is_null($sales)) {
            // Update the sales record
            $sales->status_journey = 4;
            $sales->reviewed_at = date('Y-m-d');
            $sales->reviewed_by = auth()->user()->id;
            $sales->save();
        }

        // Log the review update
        $log = [
            'name' => 'tender',
            'description' => 'Data tender telah di update ke status review oleh: ' . auth()->user()->name,
            'trx_tender_id' => $sales->id,
        ];
        saveTenderLog($log);

        // Return a JSON response
        return response()->json(['status' => true, 'message' => 'Tender review updated successfully', 'redirect' => route('sales.detail-tender', ['id' => encrypt($id)])]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $type)
    {
        // Validate the request data
        $request->validate([
            // 'no_rfq' => 'required|max:255',
            // 'name' => 'required|max:255',
            // 'customer_id' => 'required|exists:m_cusprins,id',
            // 'email' => 'required|email',
            // 'deadline' => 'required|date',
            // 'tanggal_keluar' => 'required|date',
            // // 'attachment' => 'nullable|file|max:10240',
            // 'action' => 'required|in:draft,submit',
            // 'tender_type' => 'required|in:non_direct,direct,non_tender',
            // 'currency_id' => 'required|exists:m_currencies,id',
            // 'total_price_tender' => 'required',
            // 'note' => 'nullable|string',
            // // 'bank_guarantee' => 'nullable|string',
            // 'status_win_lost' => 'nullable|string'
        ]);

        $id = decrypt($id);

        // Find the Sales record by ID
        $sales = Sales::findOrFail($id);

        // Handle file upload (attachment)
        if ($request->hasFile('doc_rfq_from_customer')) {
            $file = $request->file('doc_rfq_from_customer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('doc_rfq_from_customers', $filename, 'public');
            $sales->doc_rfq_from_customer = $path;
        }

        // Update the Sales record only if the input exists
        if ($request->filled('no_rfq')) {
            $sales->no_rfq = $request->input('no_rfq');
        }
        if ($request->filled('name')) {
            $sales->name = $request->input('name');
        }
        if ($request->filled('customer_id')) {
            $sales->customer_id = $request->input('customer_id');
        }
        if ($request->filled('currency_id')) {
            $sales->currency_id = $request->input('currency_id');
        }
        if ($request->filled('total_price_tender')) {
            $sales->total_price_tender = thousandToNumber($request->input('total_price_tender'));
        }
        if ($request->filled('email')) {
            $sales->email = $request->input('email');
        }
        if ($request->filled('deadline')) {
            $sales->deadline = $request->input('deadline');
        }
        if ($request->filled('tanggal_keluar')) {
            $sales->tanggal_keluar = $request->input('tanggal_keluar');
        }
        if ($request->filled('note')) {
            $sales->notes = $request->input('note');
        }
        if ($request->filled('win_lost')) {
            $sales->win_lost = $request->input('win_lost');
        }
        if ($request->filled('action')) {
            $sales->status = $request->input('action');
        }
        if ($request->filled('tender_type')) {
            $sales->tender_type = $request->input('tender_type');
        }
        if ($request->filled('status_win_lost')) {
            $sales->status_win_lost = $request->input('status_win_lost', null);
        }

        $sales->is_bb_bond = $request->input('is_bb_bond', null);
        if ($request->input('is_bb_bond') === 'true') {
            $sales->is_pb_bond = $request->input('is_pb_bond', null);
        } else {
            $sales->is_pb_bond = 'false';
        }

        if ($sales->status_journey <= 1) {
            if ($request->input('action') === 'submit') {
                $sales->status_journey = '1';
            } elseif ($request->input('action') === 'draft') {
                $sales->status_journey = '0';
            }
        }

        $sales->update();

        // Update or create TrxTenderBgGuarantee records for bid bond
        if ($request->input('is_bb_bond') === 'true') {
            $bb = [
                'no_guarantee' => $request->input('bb_no_guarantee'),
                'price' => thousandToNumber($request->input('bb_price')),
                'note' => $request->input('bb_note'),
                'time_period' => $request->input('bb_time_periode')
            ];
            if ($request->hasFile('bb_doc_bg')) {
                $file = $request->file('bb_doc_bg');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('bb_doc_bgs', $filename, 'public');
                $bb['doc_bg'] = $path;
            }
            TrxTenderBgGuarantee::updateOrCreate(
                ['trx_tender_id' => $sales->id, 'type' => 'bb'],
                $bb
            );
        } else {
            TrxTenderBgGuarantee::where('trx_tender_id', $sales->id)->where('type', 'bb')->delete();
        }

        // Update or create TrxTenderBgGuarantee records for perf bond
        if ($request->input('is_pb_bond') === 'true') {
            $pg = [
                'no_guarantee' => $request->input('pb_no_guarantee'),
                'price' => thousandToNumber($request->input('pb_price')),
                'note' => $request->input('pb_note'),
                'time_period' => $request->input('pb_time_periode')
            ];
            if ($request->hasFile('pb_doc_bg')) {
                $file = $request->file('pb_doc_bg');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('pb_doc_bgs', $filename, 'public');
                $pg['doc_bg'] = $path;
            }
            TrxTenderBgGuarantee::updateOrCreate(
                ['trx_tender_id' => $sales->id, 'type' => 'pb'],
                $pg
            );
        } else {
            TrxTenderBgGuarantee::where('trx_tender_id', $sales->id)->where('type', 'pb')->delete();
        }

        $log = [
            'name' => 'tender',
            'description' => 'Data tender telah di update oleh : ' . auth()->user()->name,
            'trx_tender_id' => $sales->id,
        ];
        saveTenderLog($log);

        // Flash success message to session
        session()->flash('success', 'Sales Tender has been updated successfully.');

        // Return response
        return response()->json(['status' => true]);
    }


    public function updateMaterial(Request $request, $id)
    {
        $validatedData = $request->validate([
            'no_rfq_id' => 'required|exists:trx_list_tender,id',
            'material_code' => 'required|max:255',
            'description' => 'required',
            'qty' => 'required',
            'm_oum_id' => 'required|exists:m_oum,id',
            'unit_price_principle' => 'required',
            'total_amount_principle' => 'required',
            'unit_price_kz' => 'required',
            'total_amount' => 'required',
        ]);

        $tenderDetail = new TrxTenderMaterial();
        $tenderDetail->no_rfq_id = $validatedData['no_rfq_id'];
        $tenderDetail->material_code = $validatedData['material_code'];
        $tenderDetail->description = $validatedData['description'];
        $tenderDetail->qty = $validatedData['qty'];
        $tenderDetail->m_oum_id = $validatedData['m_oum_id'];
        $tenderDetail->unit_price_principle = $validatedData['unit_price_principle'];
        $tenderDetail->total_amount_principle = $validatedData['total_amount_principle'];
        $tenderDetail->unit_price_kz = $validatedData['unit_price_kz'];
        $tenderDetail->total_amount = $validatedData['total_amount'];
        $tenderDetail->save();
        return response()->json(['message' => 'create tender successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('sales.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any sales data!');
        }

        $id = decrypt($id);

        $sales = Sales::findOrFail($id);
        if (!is_null($sales)) {
            $sales->deleted_by = auth()->user()->id;
            $sales->save(); // Save the changes to the deleted_by field
            $sales->delete(); // This will now perform a soft delete
        }

        $log = [
            'name' => 'tender',
            'description' => 'Data tender telah di hapus oleh : '.auth()->user()->name,
            'trx_tender_id' => $sales->id,
        ];
        saveTenderLog($log);

        return response()->json(['message' => 'Sales data deleted']);
    }

    public function materialDestroy($id)
    {
        if (is_null($this->user) || !$this->user->can('sales.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any sales data!');
        }

        $id = decrypt($id);

        $sales = SalesMaterial::findOrFail($id);
        if (!is_null($sales)) {
            $sales->delete(); // This will now perform a soft delete
        }

        $log = [
            'name' => 'tender',
            'description' => 'Data material telah di hapus oleh : '.auth()->user()->name,
            'trx_tender_id' => $sales->tender->id,
        ];
        saveTenderLog($log);

        return response()->json(['message' => 'tender success']);
    }

    public function final(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('sales.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to update any sales data!');
        }

        $id = decrypt($id);
        $procurementUsers = User::role('procurement')->get()->pluck('email')->toArray();

        $sales = Sales::findOrFail($id);
        if (!is_null($sales)) {
            $sales->status_journey = 2;
            $sales->save(); // This will now perform a soft delete

            // Send email notification to procurement users
            Mail::to($procurementUsers)->send(new Journey1to2Mail($sales));
        }

        $log = [
            'name' => 'tender',
            'description' => 'Data tender telah di diteruskan ke procurement oleh : '.auth()->user()->name,
            'trx_tender_id' => $sales->id,
        ];
        saveTenderLog($log);

        return response()->json(['message' => 'tender success']);
    }

    private function generateUniqueMaterialCode()
    {
        // Fetch the last inserted material code
        $lastMaterial = M_material::orderBy('material_code', 'desc')->first();

        if ($lastMaterial) {
            // Increment the numeric part of the material code
            $lastCode = (int) substr($lastMaterial->material_code, 2);
            $newCode = $lastCode + 1;
            $materialCode = 'MT' . str_pad($newCode, 8, '0', STR_PAD_LEFT);
        } else {
            // If no material exists, start with MT00000001
            $materialCode = 'MT00000001';
        }

        return $materialCode;
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

    function checkMaterialPrices($trx_tender_id)
    {
        // Get the material IDs associated with the given trx_tender_id
        $material_list = TrxTenderMaterial::where('trx_tender_id', $trx_tender_id)->pluck('id');

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
    public function getDefaultValues(Request $request)
    {
        // Logika untuk mendapatkan data default dengan nilai pertama dari masing-masing jenis
        $defaultValues = [
            'delivery_point' => M_delpoint::first()->id ?? null,
            'additional_value' => M_term::where('type', 'additional_value')->first()->id ?? null,
            'price_validity' => M_term::where('type', 'price_validity')->first()->id ?? null,
            'delivery_time' => M_term::where('type', 'delivery_time')->first()->id ?? null,
            'payment_terms' => M_term::where('type', 'payment_terms')->first()->id ?? null,
            'terms' => M_term::where('type', 'terms')->first()->id ?? null
        ];

        return response()->json($defaultValues);
    }
    public function formUpdatePriceMaterial(Request $request){
        $id = decrypt($request->material_id);
        $material = SalesMaterial::find($id);
        $price = $material->price->where('type', $request->type)->first();
        if ($request->type == 'Principle') {
            $currency = $material->tender->tenderRfq->rfqPrinciple->where('status', 'win')->pluck('currency');
        }else{
            $currency = M_currency::where('currency', 'IDR')->get();
        }

        $return['html'] = view('sales.form-update-price-material', compact('request', 'material', 'price', 'currency'))->render();

        return response()->json($return);
    }
}

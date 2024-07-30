<?php

namespace App\Http\Controllers;

use App\Models\TrxTender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
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
     */
    public function tenderMargin(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('sales.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }
        if ($request->ajax()) {
            $tenders = TrxTender::with(['currency', 'review', 'tenderMaterial.price', 'tenderRfq.rfqPrinciple'])->where('reviewed_by', $this->user->id)->latest();

            // dd($tender);
            // Log::info('Permintaan AJAX diterima.');
            return DataTables::of($tenders)
            ->addIndexColumn()
            ->addColumn('price_principle', function($row) {
                $return = '-';
                // dd($row->price->price);
                $pricePrinciple = $row->tenderMaterial->pluck('price')->where('type', 'Principle')->first();

                foreach ($row->tenderMaterial as $material) {
                    $pricePrinciple = $material->price->where('type', 'Principle')->first();
                    if ($pricePrinciple) {
                        $return = '<span class="badge badge-light">' . thousandSeparator($pricePrinciple->price) . ' '.$pricePrinciple->currency->currency.'</span>';
                        break; // Stop loop if we find the first price
                        // thousandSeparator($pricePrinciple->price)
                    }
                }
                return $return;

            })
            ->addColumn('price_kz', function($row) {
                $return = '-';
                // dd($row->price->price);
                $priceKZ = $row->tenderMaterial->pluck('price')->where('type', 'KZ')->first();

                foreach ($row->tenderMaterial as $material) {
                    $priceKZ = $material->price->where('type', 'KZ')->first();
                    if ($priceKZ) {
                        $return = '<span class="badge badge-light">' . thousandSeparator($priceKZ->price) . ' '.$priceKZ->currency->currency.'</span>';
                        break; // Stop loop if we find the first price
                    }
                }
                return $return;
            })
            ->addColumn('margin', function($row) {
                $return = '-';
                $pricePrinciple = null;
                $priceKZ = null;

                // Loop through tender materials to find the relevant prices
                foreach ($row->tenderMaterial as $material) {
                    if (!$pricePrinciple) {
                        $pricePrinciple = $material->price->where('type', 'Principle')->first();
                    }
                    if (!$priceKZ) {
                        $priceKZ = $material->price->where('type', 'KZ')->first();
                    }

                    // If both prices are found, break the loop
                    if ($pricePrinciple && $priceKZ) {
                        break;
                    }
                }

                // Calculate the amount
                if ($pricePrinciple && $priceKZ) {
                    // $amount = $priceKZ->price - $pricePrinciple->price;
                    $return = '<span class="badge badge-light">' . thousandSeparator($priceKZ->price - $pricePrinciple->price) . ' '.$priceKZ->currency->currency.'</span>';
                    // $return = thousandSeparator();
                }

                return $return;
            })
            ->addColumn('percentage', function($row) {
                $pricePrinciple = null;
                $priceKZ = null;

                // Loop through tender materials to find the relevant prices
                foreach ($row->tenderMaterial as $material) {
                    if (!$pricePrinciple) {
                        $pricePrinciple = $material->price->where('type', 'Principle')->first();
                    }
                    if (!$priceKZ) {
                        $priceKZ = $material->price->where('type', 'KZ')->first();
                    }

                    // If both prices are found, break the loop
                    if ($pricePrinciple && $priceKZ) {
                        break;
                    }
                }

                // Calculate the percentage
                if ($pricePrinciple && $priceKZ) {
                    $percentage = (($priceKZ->price - $pricePrinciple->price) / $pricePrinciple->price) * 100;
                    return number_format($percentage, 2, '.', ',') . '%';
                }

                return '-';
            })
            ->addColumn('review_status', function($row) {
                $return = '<span class="badge badge-'.$row->statusJourney->color.'"><i class="fa fa-'.$row->statusJourney->icon.'"></i> '.$row->statusJourney->name.'</span>';

                return $return;
            })
            // ->addColumn('review_by', function($row) {
            //     $return = '-';

            //     if (!empty($row->reviewed_by)) {
            //         $return = $row->reviewed_by;
            //     }

            //     return $return;
            // })
            ->addColumn('review_date', function($row) {
                $return = '-';

                if (!empty($row->reviewed_at)) {
                    $return = date('d/m/Y', strtotime($row->reviewed_at));
                }

                return $return;
            })
            ->rawColumns(['price_principle', 'price_kz', 'review_date', 'review_by', 'review_status', 'margin'])
            ->make(true);

        }

        return view('report.tender-margin');
    }
    public function tenderMarginPra(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('sales.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }
        if ($request->ajax()) {
            $tenders = TrxTender::with(['currency', 'review', 'tenderMaterial.price'])->whereIn('status_journey', ['3'])->where('reviewed_by', null)->latest();

            // dd($tender);
            // Log::info('Permintaan AJAX diterima.');
            return DataTables::of($tenders)
            ->addIndexColumn()
            ->addColumn('price_principle', function($row) {
                $return = '-';
                // dd($row->price->price);
                $pricePrinciple = $row->tenderMaterial->pluck('price')->where('type', 'Principle')->first();

                foreach ($row->tenderMaterial as $material) {
                    $pricePrinciple = $material->price->where('type', 'Principle')->first();
                    if ($pricePrinciple) {
                        $return = '<span class="badge badge-light">' . thousandSeparator($pricePrinciple->price) . ' '.$pricePrinciple->currency->currency.'</span>';
                        break; // Stop loop if we find the first price
                        // thousandSeparator($pricePrinciple->price)
                    }
                }
                return $return;

            })
            ->addColumn('price_kz', function($row) {
                $return = '-';
                // dd($row->price->price);
                $priceKZ = $row->tenderMaterial->pluck('price')->where('type', 'KZ')->first();

                foreach ($row->tenderMaterial as $material) {
                    $priceKZ = $material->price->where('type', 'KZ')->first();
                    if ($priceKZ) {
                        $return = '<span class="badge badge-light">' . thousandSeparator($priceKZ->price) . ' '.$priceKZ->currency->currency.'</span>';
                        break; // Stop loop if we find the first price
                    }
                }
                return $return;
            })
            ->addColumn('margin', function($row) {
                $return = '-';
                $pricePrinciple = null;
                $priceKZ = null;

                // Loop through tender materials to find the relevant prices
                foreach ($row->tenderMaterial as $material) {
                    if (!$pricePrinciple) {
                        $pricePrinciple = $material->price->where('type', 'Principle')->first();
                    }
                    if (!$priceKZ) {
                        $priceKZ = $material->price->where('type', 'KZ')->first();
                    }

                    // If both prices are found, break the loop
                    if ($pricePrinciple && $priceKZ) {
                        break;
                    }
                }

                // Calculate the amount
                if ($pricePrinciple && $priceKZ) {
                    // $amount = $priceKZ->price - $pricePrinciple->price;
                    $return = '<span class="badge badge-light">' . thousandSeparator($priceKZ->price - $pricePrinciple->price) . ' '.$priceKZ->currency->currency.'</span>';
                    // $return = thousandSeparator();
                }

                return $return;
            })
            ->addColumn('percentage', function($row) {
                $pricePrinciple = null;
                $priceKZ = null;

                // Loop through tender materials to find the relevant prices
                foreach ($row->tenderMaterial as $material) {
                    if (!$pricePrinciple) {
                        $pricePrinciple = $material->price->where('type', 'Principle')->first();
                    }
                    if (!$priceKZ) {
                        $priceKZ = $material->price->where('type', 'KZ')->first();
                    }

                    // If both prices are found, break the loop
                    if ($pricePrinciple && $priceKZ) {
                        break;
                    }
                }

                // Calculate the percentage
                if ($pricePrinciple && $priceKZ) {
                    $percentage = (($priceKZ->price - $pricePrinciple->price) / $pricePrinciple->price) * 100;
                    return number_format($percentage, 2, '.', ',') . '%';
                }

                return '-';
            })
            ->addColumn('review_status', function($row) {
                $return = '<span class="badge badge-'.$row->statusJourney->color.'"><i class="fa fa-'.$row->statusJourney->icon.'"></i> '.$row->statusJourney->name.'</span>';

                return $return;
            })
            // ->addColumn('review_by', function($row) {
            //     $return = '-';

            //     if (!empty($row->reviewed_by)) {
            //         $return = $row->reviewed_by;
            //     }

            //     return $return;
            // })
            ->addColumn('review_date', function($row) {
                $return = '-';

                if (!empty($row->reviewed_at)) {
                    $return = date('d/m/Y', strtotime($row->reviewed_at));
                }

                return $return;
            })
            ->addColumn('reviewed_by', function($row) {
                $return = '-';
                if (!empty($row->reviewer)) {
                    // dd($row->reviewer);
                    $return = $row->reviewer->name;
                }

                return $return;
            })
            ->rawColumns(['price_principle', 'price_kz', 'review_date', 'review_by', 'review_status', 'margin'])
            ->make(true);

        }

        return view('report.tender-margin');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function bankGuarantee()
    {
        return view('report.bank-guarantee');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function paymentStatus(Request $request)
    {

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

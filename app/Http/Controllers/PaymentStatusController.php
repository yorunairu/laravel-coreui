<?php

namespace App\Http\Controllers;

use App\Models\TrxTender;
use App\Models\TrxTenderRfq;
use App\Models\TrxTenderRfqPrinciple;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentStatusController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $payment = TrxTender::with(['principle', 'tenderMaterial', 'poPrinciple'])->get();
            $data = TrxTender::with(['tenderRfq.rfqPrinciple' => function ($query) {
                $query->where('status', 'win');
            }, 'tenderPo', 'tenderMaterial'])->whereHas('tenderRfq.rfqPrinciple', function ($query) {
                $query->where('status', 'win');
            });
            // dd($data->get());
            return DataTables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('principle_name', function($row) {
                $return = '-';
                if (!empty($row->tenderRfq->rfqPrinciple)) {
                    $return = $row->tenderRfq->rfqPrinciple[0]->principle->name;
                }

                return $return;
            })
            ->addColumn('material_name', function($row) {
                if ($row->tenderMaterial->isNotEmpty()) {
                    $materialCodes = $row->tenderMaterial->map(function($tenderMaterial) {
                        return $tenderMaterial->material ? $tenderMaterial->material->material_code : '-';
                    });
            
                    $materialList = '<ul style="list-style-type: disc !important;"><li>' . $materialCodes->implode('</li><li>') . '</li></ul>';
                    return $materialList;
                }
                return '-';
            })            
            ->addColumn('value', function($row) {
                $totalValue = $row->tenderMaterial->reduce(function($carry, $tenderMaterial) {
                    return $carry + $tenderMaterial->price->where('type', 'Principle')->sum('total_idr_convert');
                }, 0);
            
                if ($totalValue > 0) {
                    $currencySymbol = $row->tenderMaterial->first()->price->first()->currency->currency ?? 'IDR';
                    return '<span class="badge">' . $currencySymbol . ' ' . thousandSeparator($totalValue) . '</span>';
                }
            
                return '-';
            })            
            ->addColumn('production_period', function($row) {
                // dd($row->tenderRfq->rfqPrinciple);
                if ($row->tenderRfq->rfqPrinciple->isNotEmpty()) {
                    return $row->tenderRfq->rfqPrinciple->pluck('delivery_goods_days')->implode(', ') ?? '-';
                }
                // dd($row->tenderRfq->rfqPrinciple);
                return '-';
            })
            ->addColumn('top', function($row) {
                if ($row->tenderPo) {
                    $terms = $row->tenderPo->term->filter(function($term) {
                        return $term->mTerm && $term->mTerm->type === 'payment_terms';
                    })->map(function($term) {
                        return $term->mTerm->name;
                    })->implode(', ');
            
                    return $terms ?? '-';
                }
                return '-';
            })            
            ->addColumn('payment_date', function($row) {
                //
            })
            ->addColumn('note', function($row) {
                //
            })
            ->rawColumns(['principle_name', 'no_po', 'date_po', 'material_name', 'value', 'production_period', 'top'])
            ->make(true);
        }
        return view('report.payment-status');
    }
}

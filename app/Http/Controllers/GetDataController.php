<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\M_cusprin;
use App\Models\M_currency;
use App\Models\M_uom;
use App\Models\M_material;
use App\Models\TrxTenderLog;
use App\Models\TrxTender;

class GetDataController extends Controller
{
    public function getTenderLog(Request $request, $id) {
        $id = decrypt($id);
        $return['data'] = TrxTender::with(['tenderLogs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($id);
        $return['html'] = view('tender-logs.content', compact('return'))->render();

        return response()->json($return);
    }
}


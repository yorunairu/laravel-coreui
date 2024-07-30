<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\M_cusprin;
use App\Models\M_currency;
use App\Models\M_uom;
use App\Models\M_material;
use App\Models\M_delpoint;
use App\Models\M_term;

class Select2Controller extends Controller
{
    public function getCusPrin(Request $request, $type) {
        $search = $request->input('q');

        $query = M_cusprin::where('type', $type);

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $data = $query->get(['id', 'name', 'email']);

        $formattedData = $data->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
                'email' => $item->email
            ];
        });

        return response()->json($formattedData);
    }

    public function getCurrency(Request $request) {
        $search = $request->input('q');

        $query = M_currency::query();

        if (!empty($search)) {
            $query->where('currency', 'like', '%' . $search . '%');
        }

        $data = $query->get(['id', 'currency']);

        $formattedData = $data->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->currency,
            ];
        });

        return response()->json($formattedData);
    }

    public function getUom(Request $request) {
        $search = $request->input('q');

        $query = M_uom::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $data = $query->get(['id', 'name']);

        $formattedData = $data->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });

        return response()->json($formattedData);
    }

    public function getMaterialCode(Request $request) {
        $search = $request->input('q');

        $query = M_material::query();

        if (!empty($search)) {
            $query->where('description', 'like', '%' . $search . '%');
        }

        $data = $query->get(['id', 'material_code', 'description']);

        $formattedData = $data->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->material_code.' - '.strip_tags($item->description),
                'description' => $item->description,
            ];
        });

        return response()->json($formattedData);
    }

    public function getTermDeliveryPoint(Request $request)
    {
        $type = 'quo'; // Tipe yang diinginkan ('quo')
        $category = 'Delivery_Point'; // Kategori yang diinginkan ('Delivery_Point')
        $search = $request->input('q'); // Optional: Query pencarian dari request

        $query = M_term::query()->where('type', $type)->where('category', $category);
        // dd($category);
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $data = $query->get(['id', 'name']);

        $formattedData = $data->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });

        return response()->json($formattedData);
    }

    public function getDeliveryPoint(Request $request) {
        $search = $request->input('q');

        $query = M_delpoint::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $data = $query->get(['id', 'name']);

        $formattedData = $data->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });

        return response()->json($formattedData);
    }

    public function getTermComp(Request $request) {
        $search = $request->input('q');

        $query = M_term::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if (!empty($request->category)) {
            $query->where('is_'.$request->category, true);
        }

        if (!empty($request->type)) {
            $query->where('type', $request->type);
        }

        $data = $query->get(['id', 'name']);

        $formattedData = $data->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });

        return response()->json($formattedData);
    }

    public function getTermForMasterOnly(Request $request, $category=null) {
        $search = $request->input('q');

        $query = M_term::query();
        if (!empty($category) && $category == 'quo') {
            $query = $query->where('category', $category);

            $data = $query->get(['id', 'type']);
    
            $formattedData = $data->map(function($item) {
                return [
                    'id' => $item->type,
                    'text' => $item->type,
                ];
            });
        }else{
            $formattedData = [
                'id' => 'term',
                'text' => 'term',
            ];
        }

        return response()->json($formattedData);
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\M_currency;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class M_currencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currency = M_currency::with('userUpdator')->get();

            return DataTables::of($currency)
            ->addIndexColumn()
            ->addColumn('price_rate', function($row) {
                $return = '-';
                if (!empty($row->price_rate)) {
                    $return = '<span class="bg-success badge badge-success"><i class="fa fa-money"></i> '.thousandSeparator($row->price_rate).'</span>';
                };

                return $return;
            })
            ->addColumn('updated_by', function($row) {
                $return = '-';

                if (!empty($row->userUpdator)) {
                    // dd($row->userUpdator);
                    $return = $row->userUpdator->name;
                }

                return $return;
            })
            ->addColumn('updated_at', function($row) {
                $return = '-';
                if (!empty($row->updated_at)) {
                    $return = date('d M Y', strtotime($row->updated_at));
                }

                return $return;
            })
            ->addColumn('action', function($row) {
                return '<div>
                            <button class="btn btn-xs btn-primary edit" onClick="editModal(this)" data-id="' .$row->id. '"><i class="fa fa-pencil"></i> Edit</button>
                            <button class="btn btn-xs btn-danger delete" onClick="deleteModal(this)" data-id="' .$row->id. '"><i class="fa fa-trash"></i> Delete</button>
                        </div>';
            })
            ->rawColumns(['action', 'price_rate', 'updated_by', 'updated_at'])
            ->make();

        }
        return view('currency.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'currency' => 'required|min:3',
            'price_rate' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $currency = new M_currency();
            $currency->currency = $request->currency;
        $currency->price_rate = $request->price_rate;
            $currency->save();

            DB::commit();

            return response()->json([
                'message' => 'Created currency successfully',
                'status' => 'created'
            ]);
            session()->flash('success', 'Created currency successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()],500);
        }
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
        $currency = M_currency::findOrFail($id);

        return response()->json(['data' => $currency]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'currency' => 'required',
            'price_rate' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $currency = M_currency::findOrFail($id);
            $currency->currency = $validateData['currency'];
            $currency->price_rate = thousandToNumber($validateData['price_rate']);
            $currency->date_rate = date('Y-m-d');
            $currency->save();

            DB::commit();

            return response()->json([
                'message' => 'Update Currency successfully',
                'status' => 'updated'
            ]);

            session()->flash('success', 'Updated currency successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $currency = M_currency::findOrFail($id);
        $currency->delete();

        return response()->json([
            'message' => 'Deleted Currency Successfully',
            'status' => 'deleted',
        ]);

        session()->flash('success', 'Deleted currency successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\M_delpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class M_delpointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currency = M_delpoint::get();

            return DataTables::of($currency)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<div>
                            <button class="btn btn-xs btn-primary edit" onClick="editModal(this)" data-id="' .$row->id. '"><i class="fa fa-pencil"></i> Edit</button>
                            <button class="btn btn-xs btn-danger delete" onClick="deleteModal(this)" data-id="' .$row->id. '"><i class="fa fa-trash"></i> Delete</button>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make();

        }
        return view('delivery-point.index');
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
        $request->validate(['name' => 'required']);
        DB::beginTransaction();
        try {
            $delpoint = new M_delpoint();
            $delpoint->name = $request->name;
            $delpoint->save();

            DB::commit();

            return response()->json([
                'message' => 'Created Delevery point',
                'status' => 'created'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
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
        $delpoint = M_delpoint::findOrFail($id);

        return response()->json(['data' => $delpoint]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate(['name' => 'required']);
        
        DB::beginTransaction();
        try {
            $delpoint = M_delpoint::findOrFail($id);
            $delpoint->name = $validateData['name'];

            $delpoint->save();

            DB::commit();

            return response()->json([
                'message' => 'Updated delevery point successfully',
                'status' => 'updated'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delpoint = M_delpoint::findOrFail($id);
        $delpoint->delete();

        return response()->json([
            'message' => 'DELETED Delevery Point successfully',
            'status' => 'deleted'
        ]);
    }
}

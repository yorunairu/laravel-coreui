<?php

namespace App\Http\Controllers;

use App\Models\M_term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class M_termController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currency = M_term::get();

            return DataTables::of($currency)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<div>
                            <button class="btn btn-xs btn-primary edit" onClick="editModal(this)" data-id="' .$row->id. '" data-is_rfq="'. ($row->is_rfq ? 'true' : 'false') .'" data-name="' . $row->name . '" data-is_quo="'. ($row->is_quo ? 'true' : 'false') .'" data-type="'.$row->type.'"><i class="fa fa-pencil"></i> Edit</button>
                            <button class="btn btn-xs btn-danger delete" onClick="deleteModal(this)" data-id="' .$row->id. '"><i class="fa fa-trash"></i> Delete</button>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make();

        }
        return view('term.index');
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
            'name' => 'required',
            'type' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $compwith = new M_term();
            $compwith->name = $request->name;
            $compwith->is_rfq = $request->has('is_rfq') ? true : false;
            $compwith->is_quo = $request->has('is_quo') ? true : false;
            $compwith->type = $request->type;
            $compwith->save();

            DB::commit();

            return response()->json([
                'message' => 'Created compwith successfully',
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
        $compwith = M_term::findOrFail($id);

        return response()->json([
            'data' => $compwith
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $compwith = M_term::findOrFail($id);
            $compwith->name = $validateData['name'];
            $compwith->is_rfq = $request->has('is_rfq') ? true : false;
            $compwith->is_quo = $request->has('is_quo') ? true : false;
            $compwith->type = $validateData['type'];
            $compwith->save();

            DB::commit();

            return response()->json([
                'message' => 'Edit compwith successfully',
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
        $compwith = M_term::findOrFail($id);
        $compwith->delete();

        return response()->json([
            'message' => 'Deleted compwith successfully',
            'status' => 'deleted'
        ]);
    }
}

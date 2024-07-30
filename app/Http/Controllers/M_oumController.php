<?php

namespace App\Http\Controllers;

use App\Models\M_uom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class M_oumController extends Controller
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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $uom = M_uom::get();

            return DataTables::of($uom)
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

        return view('m_uom.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('role.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }

        // return view('m_o')
        // return view('m')
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $uom = new M_uom();
            $uom->name = $request->input('name');
            $uom->save();

            DB::commit();

            session()->flash('success', 'Craeted unit of measure successfully');
            return response()->json(['message' => 'Create Unit of measure successfully', 'status' => 'created']);
        } catch (\Exception $e) {
            DB::rollBack();
            //throw $th;
            throw $e;
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
    public function edit($id)
    {
        try {
            // $id = decrypt($id);

            $uom = M_uom::findOrFail($id);

            return response()->json(['data' => $uom]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to decrypt ID'], 400);
        } catch(\Exception $e) {
            return response()->json(['error' => 'UOM not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'name' => 'required'
        ]);
        DB::beginTransaction();
        try {
            // $id = decrypt($id);

            $uom = M_uom::findOrFail($id);
            $uom->name = $validateData['name'];
            $uom->save();

            DB::commit();
            return response()->json(['message' => 'Update Unit of measure successfully', 'status' => 'updated']);
            session()->flash('success', 'Update unit of measure successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            //throw $th;
            throw $e;
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $uom = M_uom::findOrFail($id);
            $uom->delete();
            DB::commit();
            return response()->json(['message' => 'Deleted unit of measure Successfully', 'status' => 'deleted']);
            session()->flash('success', 'Delete unit of measure successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'message: ', $e->getMessage()]);
        }

    }
}

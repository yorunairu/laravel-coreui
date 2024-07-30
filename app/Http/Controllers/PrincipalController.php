<?php

namespace App\Http\Controllers;

use App\Models\M_cusprin;
use App\Models\M_uom;
use App\Models\Principal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PrincipalController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('master-principle.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }
        if ($request->ajax()) {
            $principle = M_cusprin::where('type', 'principle')->get();

            return DataTables::of($principle)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<div>
                            <button class="btn btn-xs btn-primary edit" onClick="editModal(this)" data-id="' .encrypt($row->id). '"><i class="fa fa-pencil"></i> Edit</button>
                            <button class="btn btn-xs btn-danger delete" onClick="deleteModal(this)" data-id="' .encrypt($row->id). '"><i class="fa fa-trash"></i> Delete</button>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('principal.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('role.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }

        return view('backend.pages.principal.create');


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('role.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'pic_name' => 'required',
            'email_pic' => 'required|email',
            'position' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $customer = new M_cusprin();
            $customer->name = $request->input('name');
            $customer->type = 'principle';
            $customer->address = $request->input('address');
            $customer->email = $request->input('email');
            $customer->phone = $request->input('phone');
            $customer->pic_name = $request->input('pic_name');
            $customer->email_pic = $request->input('email_pic');
            $customer->position = $request->input('position');

            $customer->save();

            DB::commit();
            return response()->json(['message' => 'Create principle Successfully']);
            session()->flash('success', 'Created principle successfullly');

        } catch (\Exception $e) {
            DB::rollBack();
            //throw $th;
            return response()->json(['message'=> 'Created principle Errror'], 500);
        }
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
    public function edit($id)
    {
        // if (is_null($this->user) || !$this->user->can('role.edit')) {
        //     abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        // }
        try {
            //code...
            $id = decrypt($id);

            $principle = M_cusprin::find($id);

            return view('backend.pages.principal.edit', compact('principle'));
        } catch (\Exception $e) {
            return response()->back()->with('error', 'Principle not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('role.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }

        // TODO: You can delete this in your local. This is for heroku publish.
        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.
        if ($id === 1) {
            session()->flash('error', 'Sorry !! You are not authorized to edit this role !');
            return back();
        }

        // Validation Data
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'pic_name' => 'required',
            'email_pic' => 'required|email',
            'position' => 'required',
            // 'type' => 'customer'
            // 'type' => 'required',
            // 'status' => 'required',
        ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 422);
            }
            DB::beginTransaction();
            try {

                $id = decrypt($id);

                $customer = M_cusprin::findOrFail($id);
                // Update data customer
                $customer->name = $request->input('name');
                $customer->type = 'principle';
                $customer->address = $request->input('address');
                $customer->email = $request->input('email');
                $customer->phone = $request->input('phone');
                $customer->pic_name = $request->input('pic_name');
                $customer->email_pic = $request->input('email_pic');
                $customer->position = $request->input('position');

                // Simpan perubahan
                $customer->save();
                DB::commit();
                return response()->json(['message' => 'Edit Principle Successfuly']);
                session()->flash('success', 'Edit principle successfulyy');
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'error'.$e->getMessage()]);
            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !$this->user->can('role.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any role !');
        }

        // TODO: You can delete this in your local. This is for heroku publish.
        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.
        if ($id === 1) {
            session()->flash('error', 'Sorry !! You are not authorized to delete this role !');
            return back();
        }

        $uom = M_cusprin::findOrFail($id);
        $uom->delete();

        return response()->json(['message' => 'Principle deleted successfully']);

        session()->flash('success', 'Delete principle successfully');
        // session()->flash('success', 'Role has been deleted !!');
        // return back();
    }
}

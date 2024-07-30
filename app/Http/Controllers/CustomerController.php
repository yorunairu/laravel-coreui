<?php

namespace App\Http\Controllers;

use session;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Crypt;
use App\Models\M_cusprin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
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
        if (is_null($this->user) || !$this->user->can('master-customer.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }
        if ($request->ajax()) {
            $customer = M_cusprin::where('type', 'customer');
            // dd($customer);

            return DataTables::of($customer)
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

        return view('customer.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pages.customer.create');
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
            // 'type' => 'required',
            // 'status' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $customer = new M_cusprin();
            $customer->name = $request->input('name');
            $customer->type = 'customer';
            $customer->address = $request->input('address');
            $customer->email = $request->input('email');
            $customer->phone = $request->input('phone');
            $customer->pic_name = $request->input('pic_name');
            $customer->email_pic = $request->input('email_pic');
            $customer->position = $request->input('position');

            $customer->save();

            DB::commit();
            session()->flash('success', 'Created customer successfully');
            // session()->flash('success', 'Customer has been created !!');
            return response()->json(['message' => 'Created customer successfully']);
            return back();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
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
        $id = decrypt($id);

        $customer = M_cusprin::find($id);

        return view('backend.pages.customer.edit', compact('customer'));
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
                $customer->type = 'customer';
                $customer->address = $request->input('address');
                $customer->email = $request->input('email');
                $customer->phone = $request->input('phone');
                $customer->pic_name = $request->input('pic_name');
                $customer->email_pic = $request->input('email_pic');
                $customer->position = $request->input('position');

                // Simpan perubahan
                $customer->save();

                DB::commit();

                return response()->json(['message' => 'Edit customer successfuly']);
                session()->flash('success', 'Edit customer successfully');

            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['error' => $e->getMessage()]);
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
        $customer = M_cusprin::findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Deleted customer successfully']);
        session()->flash('success', 'Deleted customer successfully');

    }
}

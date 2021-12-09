<?php

namespace App\Http\Controllers\Backend;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Backend\CustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'manage_customers, show_customers')) {
            return redirect('admin/index');
        }
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limt_by ?? 10);

        return view('backend.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'create_customers')) {
            return redirect('admin/index');
        }
        return view('backend.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'create_customers')) {
            return redirect('admin/index');
        }

        $input['first_name']        = $request->first_name;
        $input['last_name']         = $request->last_name;
        $input['username']          = $request->username;
        $input['email']             = $request->email;
        $input['mobile']            = $request->mobile;
        $input['status']            = $request->status;
        $input['password']          = bcrypt($request->password);

        // Image Upload
        if ($image = $request->file('user_image')) {
            $file_name = Str::slug($request->username) . '.' . $image->getClientOriginalExtension();
            $path = public_path('/assets/users/' . $file_name);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);

            $input['user_image'] = $file_name;
        }

        // Create the record in database
        $customer = User::create($input);
        $customer->markEmailAsVerified();
        $customer->attachRole(Role::whereName('customer')->first()->id);
        $customer->save();

        return redirect()->route('admin.customers.index')->with([
            'message' => 'Customer Created Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $customer)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'display_customers')) {
            return redirect('admin/index');
        }

        return view('backend.customers.show', \compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $customer)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'update_customers')) {
            return redirect('admin/index');
        }

        return view('backend.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, User $customer)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'update_customers')) {
            return redirect('admin/index');
        }

        $input['first_name']        = $request->first_name;
        $input['last_name']         = $request->last_name;
        $input['username']          = $request->username;
        $input['email']             = $request->email;
        $input['mobile']            = $request->mobile;
        $input['status']            = $request->status;

        if (\trim($request->passowrd) != '') {
            $input['password']      = bcrypt($request->password);
        }

        // Image Upload
        if ($image = $request->file('user_image')) {

            if ($customer->user_image != null && File::exists('assets/users/' . $customer->user_image)) {
                unlink('assets/users/' . $customer->user_image);
            }

            $file_name = Str::slug($request->username) . '.' . $image->getClientOriginalExtension();
            $path = public_path('/assets/users/' . $file_name);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);

            $input['user_image'] = $file_name;
        }

        $customer->update($input);

        return redirect()->route('admin.customers.index')->with([
            'message' => 'Customer Updated Successfully',
            'alert-type' => 'success'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $customer)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'delete_customers')) {
            return redirect('admin/index');
        }

        if (File::exists('assets/users/' . $customer->user_image) && $customer->user_image != null) {
            unlink('assets/users/' . $customer->user_image);
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')->with([
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the Image resource from storage.
     *
     * @param  int  $id
     * @param  int  Request
     * @return \Illuminate\Http\Response
     */

    public function remove_image(Request $request)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'delete_customers')) {
            return redirect('admin/index');
        }

        $customer = User::findOrFail($request->customer_id);

        if (File::exists('assets/users/' . $customer->user_image)) {
            unlink('assets/users/' . $customer->user_image);
            $customer->user_image = null;
            $customer->save();
        }
        return true;
    }

    public function get_customers()
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
            ->when(request()->input('query') != '', function ($query) {
                $query->search(request()->input('query'));
            })
            ->get(['id', 'first_name', 'last_name', 'email'])->toArray();

        return response()->json($customers);
    }
}

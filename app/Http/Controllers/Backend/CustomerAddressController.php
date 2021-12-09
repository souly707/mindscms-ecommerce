<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CustomerAddressRequest;
use App\Models\Country;
use App\Models\User;
use App\Models\UserAddress;

class CustomerAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'manage_customer_addresses, show_customer_addresses')) {
            return redirect('admin/index');
        }
        $customer_addresses = UserAddress::with('user')
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereDefaultAddress(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limt_by ?? 10);

        return view('backend.customer_addresses.index', compact('customer_addresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'create_customer_addresses')) {
            return redirect('admin/index');
        }

        // $customer = User::whereHas('roles', function ($query) {
        //     $query->whereName('customer');
        // })->get();
        $countries = Country::whereStatus(1)->get(['id', 'name']);

        return view('backend.customer_addresses.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerAddressRequest $request)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'create_customer_addresses')) {
            return redirect('admin/index');
        }

        // Create the record in database
        UserAddress::create($request->validated());

        return \redirect()->route('admin.customer_addresses.index')->with([
            'message' => 'Address Created Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(UserAddress $customer_address)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'display_customer_addresses')) {
            return redirect('admin/index');
        }

        return view('backend.customer_addresses.show', compact('customer_address'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserAddress $customer_address)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'update_customer_addresses')) {
            return redirect('admin/index');
        }

        $countries = Country::whereStatus(1)->get(['id', 'name']);

        return view('backend.customer_addresses.edit', compact('customer_address', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerAddressRequest $request, UserAddress $customer_address)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'update_customer_addresses')) {
            return redirect('admin/index');
        }

        $customer_address->update($request->validated());

        return \redirect()->route('admin.customer_addresses.index')->with([
            'message' => 'Address Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAddress $customer_address)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'delete_customer_addresses')) {
            return redirect('admin/index');
        }

        $customer_address->delete();

        return \redirect()->route('admin.customer_addresses.index')->with([
            'message' => 'Address Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}

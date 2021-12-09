<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\ProductCoupon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductCouponRequest;

class ProductCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'manage_productCoupons, show_productCoupons')) {
            return redirect('admin/index');
        }
        $coupons = ProductCoupon::query()
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 10);

        return view('backend.product_coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Roles And Permissions Granted To View This Page
        if (!Auth()->user()->ability('admin', 'create_productCoupons')) {
            return redirect('admin/index');
        }

        return view('backend.product_coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCouponRequest $request)
    {
        // Roles And Permissions Granted To View This Page
        if (!Auth()->user()->ability('admin', 'create_productCoupons')) {
            return redirect('admin/index');
        }

        ProductCoupon::create($request->validated());

        return redirect()->route('admin.product_coupons.index')->with([
            'message' => 'Coupon Created Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Roles And Permissions Granted To View This Page
        if (!Auth()->user()->ability('admin', 'display_productCoupons')) {
            return redirect('admin/index');
        }
        return view('backend.products.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCoupon $productCoupon)
    {
        // Roles And Permissions Granted To View This Page
        if (!Auth()->user()->ability('admin', 'update_productCoupons')) {
            return redirect('admin/index');
        }

        return view('backend.product_coupons.edit', compact('productCoupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCouponRequest $request, ProductCoupon $productCoupon)
    {
        // Roles And Permissions Granted To View This Page
        if (!Auth()->user()->ability('admin', 'update_productCoupons')) {
            return redirect('admin/index');
        }

        $productCoupon->update($request->validated());

        return redirect()->route('admin.product_coupons.index')->with([
            'message' => 'Coupon Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCoupon $productCoupon)
    {
        // Roles And Permissions Granted To View This Page
        if (!Auth()->user()->ability('admin', 'delete_productCoupons')) {
            return redirect('admin/index');
        }

        $productCoupon->delete();

        return redirect()->route('admin.product_coupons.index')->with([
            'message' => 'Coupon Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}
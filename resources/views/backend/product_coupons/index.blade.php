@extends('layouts.admin')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Coupons</h6>
        <div class="ml-auto">
            @ability('admin', 'create_product_coupons')
            <a href="{{ route('admin.product_coupons.create') }}" class="btn btn-outline-primary">
                <span class="icon">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Add new Coupon</span>
            </a>
            @endability
        </div>
    </div>
    {{-- Filter --}}
    @include('backend.product_coupons.filter.filter')
    {{-- End Filter --}}

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Value</th>
                    <th>Use Times</th>
                    <th>Validity Date</th>
                    <th>Greater Than</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->value }} {{ $coupon->type == 'fixed' ? '$' : '%' }}</td>
                    <td>{{ $coupon->used_times . '/' . $coupon->use_times }}</td>
                    <td>
                        {{ $coupon->start_date != '' ? $coupon->start_date->format('Y-m-d') . '-' . $coupon->expire_date->format('Y-m-d') : '-' }}
                    </td>
                    <td>{{ $coupon->greater_than ?? '-' }}</td>
                    <td>{{ $coupon->status() }}</td>
                    <td>{{ $coupon->created_at->format('Y-m-d h:i a') }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.product_coupons.edit', $coupon->id) }}"
                                class="btn btn-outline-dark rounded mr-2">Edit</a>

                            <a href="javascript:void(0);" onclick="if (confirm('Are you sure to delete this record')) 
                                    {document.getElementById('delete-product-coupon-{{ $coupon->id }}').submit()} 
                                    else {return false}" class="btn btn-outline-danger rounded">
                                Delete
                            </a>
                        </div>
                        <form action="{{ route('admin.product_coupons.destroy', $coupon->id) }}" method="POST"
                            id="delete-product-coupon-{{ $coupon->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="8">No Couponss Found</td>
                </tr>
                @endforelse


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8">
                        <div class="float-right">{{ $coupons->appends(request()->all())->links() }}</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
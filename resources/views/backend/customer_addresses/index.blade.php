@extends('layouts.admin')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Customer Addresses</h6>
        <div class="ml-auto">
            @ability('admin', 'create_customer_addresses')
            <a href="{{ route('admin.customer_addresses.create') }}" class="btn btn-outline-primary">
                <span class="icon">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Add new Address</span>
            </a>
            @endability
        </div>
    </div>
    {{-- Filter --}}
    @include('backend.customer_addresses.filter.filter')
    {{-- End Filter --}}

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Title</th>
                    <th>Shipping Info</th>
                    <th>Location</th>
                    <th>Address</th>
                    <th>Zip Code</th>
                    <th>Po Box</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customer_addresses as $customer_address)
                <tr>
                    <td>
                        <a href="{{ route('admin.customer_addresses.show', $customer_address->user_id) }}">
                            {{ $customer_address->user->full_name }}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.customer_addresses.show', $customer_address->id) }}">
                            {{ $customer_address->address_title }}</a>
                        <p class="text-gray-400"><b>{{ $customer_address->defaultAddress() }}</b></p>
                    </td>
                    <td>
                        {{ $customer_address->user->full_name }}<br>
                        <span class="text-gray-400">{{ $customer_address->email }}</span><br>
                        <span class="text-gray-400">{{ $customer_address->mobile }}</span>
                    </td>
                    <td>
                        {{ $customer_address->country->name . '-' . $customer_address->state->name . '-' . $customer_address->city->name }}
                    </td>
                    <td>
                        {{ $customer_address->address}}
                    </td>
                    <td>
                        {{ $customer_address->zip_code}}
                    </td>
                    <td>
                        {{ $customer_address->po_box}}
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.customer_addresses.edit', $customer_address->id) }}"
                                class="btn btn-outline-dark rounded mr-2">Edit</a>

                            <a href="javascript:void(0);" onclick="if (confirm('Are you sure to delete this record')) 
                                    {document.getElementById('delete-customer_addresses-{{ $customer_address->id }}').submit()} 
                                    else {return false}" class="btn btn-outline-danger rounded">
                                Delete
                            </a>
                        </div>
                        <form action="{{ route('admin.customer_addresses.destroy', $customer_address->id) }}"
                            method="POST" id="delete-customer_addresses-{{ $customer_address->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="8">No Addresses Found</td>
                </tr>
                @endforelse


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8">
                        <div class="float-right">{{ $customer_addresses->appends(request()->all())->links() }}</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
@extends('layouts.admin')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Products</h6>
        <div class="ml-auto">
            @ability('admin', 'create_products')
            <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary">
                <span class="icon">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Add new Product</span>
            </a>
            @endability
        </div>
    </div>
    {{-- Filter --}}
    @include('backend.products.filter.filter')
    {{-- End Filter --}}

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Feature</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Tags</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($products as $product)
                <tr>
                    <td>
                        @if ($product->firstMedia)
                        <img src="{{ asset('assets/products/' . $product->firstMedia->file_name) }}" width="60"
                            height="60" class="rounded" alt="{{ $product->name }}">
                        @else
                        <img src="{{ asset('assets/no-image-found.png') }}" width="60" height="60" class="rounded"
                            alt="No Image">
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->featured() }}</td>
                    <td>{{ $product->quantity}}</td>
                    <td>{{ $product->price}}</td>
                    <td>{{ $product->tags->pluck('name')->join(', ')}}</td>
                    <td>{{ $product->status() }}</td>
                    <td>{{ $product->created_at->format('M, d Y') }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                class="btn btn-outline-dark rounded mr-2">Edit</a>

                            <a href="javascript:void(0);" onclick="if (confirm('Are you sure to delete this record')) 
                                    {document.getElementById('delete-products-{{ $product->id }}').submit()} 
                                    else {return false}" class="btn btn-outline-danger rounded">
                                Delete
                            </a>
                        </div>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                            id="delete-products-{{ $product->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="9">No products Found</td>
                </tr>
                @endforelse


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9">
                        <div class="float-right">{{ $products->appends(request()->all())->links() }}</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
@extends('layouts.admin')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Product Categories</h6>
        <div class="ml-auto">
            @ability('admin', 'create_product_categories')
            <a href="{{ route('admin.product_categories.create') }}" class="btn btn-outline-primary">
                <span class="icon">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Add new category</span>
            </a>
            @endability
        </div>
    </div>
    {{-- Filter --}}
    @include('backend.product_categories.filter.filter')
    {{-- End Filter --}}

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Products Count</th>
                    <th>Parent</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>{{ $category->parent != null ? $category->parent->name : '-' }}</td>
                    <td>{{ $category->status() }}</td>
                    <td>{{ $category->created_at->format('M, d Y') }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.product_categories.edit', $category->id) }}"
                                class="btn btn-outline-dark rounded mr-2">Edit</a>

                            <a href="javascript:void(0);" onclick="if (confirm('Are you sure to delete this record')) 
                                    {document.getElementById('delete-product-category-{{ $category->id }}').submit()} 
                                    else {return false}" class="btn btn-outline-danger rounded">
                                Delete
                            </a>
                        </div>
                        <form action="{{ route('admin.product_categories.destroy', $category->id) }}" method="POST"
                            id="delete-product-category-{{ $category->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="6">No Categories Found</td>
                </tr>
                @endforelse


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="float-right">{{ $categories->appends(request()->all())->links() }}</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
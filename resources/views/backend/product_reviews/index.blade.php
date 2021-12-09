@extends('layouts.admin')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Product Review</h6>
        <div class="ml-auto">
            {{-- @ability('admin', 'create_product_reviews')
            <a href="{{ route('admin.product_reviews.create') }}" class="btn btn-outline-primary">
            <span class="icon">
                <i class="fa fa-plus"></i>
            </span>
            <span class="text">Add new Review</span>
            </a>
            @endability --}}
        </div>
    </div>
    {{-- Filter --}}
    @include('backend.product_reviews.filter.filter')
    {{-- End Filter --}}

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="">
                    <th>Name</th>
                    <th>User</th>
                    <th>title</th>
                    <th>Rating</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th width="12%">Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($reviews as $review)
                <tr class="">
                    <td>
                        {{ $review->name }}
                        <p class="">{{ $review->email }}</p>
                    </td>
                    @if ($review->user_id != null)
                    <td>
                        <span class="badge badge-primary">{{ $review->user->full_name }}</span>
                    </td>
                    @endif
                    <td>
                        <span>{{ $review->title }}</span>
                    </td>
                    <td>
                        <span class="badge badge-success">{{ $review->rating  }}</span>
                    </td>
                    <td>{{ $review->product->name  }}</td>
                    <td>{{ $review->status() }}</td>
                    <td>{{ $review->created_at->format('M, d Y') }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.product_reviews.edit', $review->id) }}"
                                class="btn btn-outline-dark rounded mr-2">Edit</a>

                            <a href="javascript:void(0);" onclick="if (confirm('Are you sure to delete this record')) 
                                    {document.getElementById('delete-product-review-{{ $review->id }}').submit()} 
                                    else {return false}" class="btn btn-outline-danger rounded">
                                Delete
                            </a>
                        </div>
                        <form action="{{ route('admin.product_reviews.destroy', $review->id) }}" method="POST"
                            id="delete-product-review-{{ $review->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="7">No Reviews Found</td>
                </tr>
                @endforelse


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8">
                        <div class="float-right">{{ $reviews->appends(request()->all())->links() }}</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
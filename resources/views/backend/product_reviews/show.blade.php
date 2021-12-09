@extends('layouts.admin')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Product Review</h6>
        <div class="ml-auto">
            @ability('admin', 'manange_product_reviews,show_product_reviews')
            <a href="{{ route('admin.product_reviews.index') }}" class="btn btn-outline-primary">
                <span class="icon">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Reviews</span>
            </a>
            @endability
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr>
                    <th>Name</th>
                    <td>
                        <span lass="badge badge-success px-1 py-1 text-black-50 font-size">
                            {{ $productReview->name }}
                        </span>
                    </td>

                    <th>Email</th>
                    <td>{{ $productReview->email }}</td>
                </tr>
                <tr>
                    <th>Customer Name</th>
                    <td>{{ $productReview->user_id != null ? $productReview->user->full_name : '' }}</td>

                    <th>Rating</th>
                    <td>{{ $productReview->rating }}</td>
                </tr>

                <tr>
                    <th>Title</th>
                    <td colspan="3">{{ $productReview->title }}</td>
                </tr>

                <tr>
                    <th>Message</th>
                    <td colspan="3">{{ $productReview->message }}</td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td colspan="3">{{ $productReview->created_at->format('Y-m-d') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
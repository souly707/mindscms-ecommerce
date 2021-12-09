@extends('layouts.admin')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Edit Review On Product {{ $productReview->product->name }}</h6>
        <div class="ml-auto">
            @ability('admin', 'manage_product_reviews, show_product_reviews')
            <a href="{{ route('admin.product_reviews.index') }}" class="btn btn-outline-primary">
                <span class="icon">
                    <i class="fa fa-home"></i>
                </span>
                <span class="text">Reviews</span>
            </a>
            @endability
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.product_reviews.update', $productReview->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" value="{{ old('name',$productReview->name) }}"
                            class="form-control">
                        @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="email" name="email" value="{{ old('email',$productReview->email) }}"
                            class="form-control">
                        @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="col-4">
                    <label for="rating">Rating</label>
                    <select name="rating" class="form-control">
                        <option value="1" {{ old('rating', $productReview->rating == '1' ? 'selected' : null) }}>1
                        </option>
                        <option value="2" {{ old('rating', $productReview->rating == '2' ? 'selected' : null) }}>2
                        </option>
                        <option value="3" {{ old('rating', $productReview->rating == '3' ? 'selected' : null) }}>3
                        </option>
                        <option value="4" {{ old('rating', $productReview->rating == '4' ? 'selected' : null) }}>4
                        </option>
                        <option value="5" {{ old('rating', $productReview->rating == '5' ? 'selected' : null) }}>5
                        </option>
                    </select>
                    @error('rating')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="row pt-4">

                <div class="col-4">
                    <div class="form-group">
                        <label for="product_id">Product</label>
                        <input type="text" name="product_name"
                            value="{{ old('product_name',$productReview->product->name) }}" class="form-control"
                            readonly>

                        <input type="hidden" name="product_id"
                            value="{{ old('product_id',$productReview->product_id) }}" readonly>
                        @error('product_id')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="user_id">Customer</label>
                        <input type="text"
                            value="{{ $productReview->user_id != null ? $productReview->user->full_name : '' }}"
                            class="form-control" readonly>

                        <input type="hidden" name="user_id" value="{{ $productReview->user_id ?? '' }}" readonly>
                        @error('user_id')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="col-4">
                    <label for="status">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ old('status', $productReview->status) == 1 ? 'selected' : null }}>Active
                        </option>
                        <option value="0" {{ old('status', $productReview->status) == 0 ? 'selected' : null }}>
                            Inactive</option>
                    </select>
                    @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="row pt-4">
                <div class="col-12">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <textarea name="title" class="form-control" id="title" cols="3"
                            rows="2">{{ old('title', $productReview->title) }}</textarea>
                        @error('title')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="row pt-4">
                <div class="col-12">
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea name="message" class="form-control" id="title" cols="5"
                            rows="7">{{ old('message', $productReview->message) }}</textarea>
                        @error('message')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" name="submit" class="btn btn-outline-primary">Update Review</button>
            </div>
        </form>
    </div>
</div>

@endsection
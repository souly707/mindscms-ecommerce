@extends('layouts.admin')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Edit Tag {{ $tag->name }}</h6>
        <div class="ml-auto">
            <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-primary">
                <span class="icon">
                    <i class="fa fa-home"></i>
                </span>
                <span class="text">Tag</span>
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-9">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" value="{{ old('name',$tag->name) }}" class="form-control">
                        @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="col-3">
                    <label for="status">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ old('status', $tag->status) == 1 ? 'selected' : null }}>Active
                        </option>
                        <option value="0" {{ old('status', $tag->status) == 0 ? 'selected' : null }}>
                            Inactive</option>
                    </select>
                    @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" name="submit" class="btn btn-outline-primary">Update Tag</button>
            </div>
        </form>
    </div>
</div>

@endsection
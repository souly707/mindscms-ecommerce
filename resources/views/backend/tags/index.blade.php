@extends('layouts.admin')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Tags</h6>
        <div class="ml-auto">
            @ability('admin', 'create_tags')
            <a href="{{ route('admin.tags.create') }}" class="btn btn-outline-primary">
                <span class="icon">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Add new Tag</span>
            </a>
            @endability
        </div>
    </div>
    {{-- Filter --}}
    @include('backend.tags.filter.filter')
    {{-- End Filter --}}

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Products Count</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($tags as $tag)
                <tr>
                    <td>{{ $tag->name }}</td>
                    <td>{{ $tag->products->count() }}</td>
                    <td>{{ $tag->status() }}</td>
                    <td>{{ $tag->created_at->format('M, d Y') }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.tags.edit', $tag->id) }}"
                                class="btn btn-outline-dark rounded mr-2">Edit</a>

                            <a href="javascript:void(0);" onclick="if (confirm('Are you sure to delete this record')) 
                                    {document.getElementById('delete-tags-{{ $tag->id }}').submit()} 
                                    else {return false}" class="btn btn-outline-danger rounded">
                                Delete
                            </a>
                        </div>
                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST"
                            id="delete-tags-{{ $tag->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="5">No Categories Found</td>
                </tr>
                @endforelse


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <div class="float-right">{{ $tags->appends(request()->all())->links() }}</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
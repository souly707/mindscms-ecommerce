@extends('layouts.admin')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Supervisor</h6>
        <div class="ml-auto">
            @ability('admin', 'create_supervisors')
            <a href="{{ route('admin.supervisors.create') }}" class="btn btn-outline-primary">
                <span class="icon">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Add new Supervisor</span>
            </a>
            @endability
        </div>
    </div>
    {{-- Filter --}}
    @include('backend.supervisors.filter.filter')
    {{-- End Filter --}}

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Email & Mobile</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($supervisors as $supervisor)
                <tr>
                    <td>
                        @if ($supervisor->user_image != '')
                        <img src="{{ asset('assets/users/' . $supervisor->user_image) }}"
                            alt="{{ $supervisor->full_name }}" width="60" height="60" class="rounded">
                        @else
                        <img src="{{ asset('assets/users/default-avatar.png') }}" alt="default-avatar" width="60"
                            height="60" class="rounded">
                        @endif
                    </td>
                    <td>
                        {{ $supervisor->full_name }} <br>
                        <strong>{{ $supervisor->username }}</strong>
                    </td>

                    <td>
                        {{ $supervisor->email }} <br>
                        {{ $supervisor->mobile }}
                    </td>
                    <td>{{ $supervisor->status() }}</td>
                    <td>{{ $supervisor->created_at->format('M, d Y') }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.supervisors.edit', $supervisor->id) }}"
                                class="btn btn-outline-dark rounded mr-2">Edit</a>

                            <a href="javascript:void(0);" onclick="if (confirm('Are you sure to delete this record')) 
                                    {document.getElementById('delete-supervisor-{{ $supervisor->id }}').submit()} 
                                    else {return false}" class="btn btn-outline-danger rounded">
                                Delete
                            </a>
                        </div>
                        <form action="{{ route('admin.supervisors.destroy', $supervisor->id) }}" method="POST"
                            id="delete-supervisor-{{ $supervisor->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="6">No supervisors Found</td>
                </tr>
                @endforelse


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="float-right">{{ $supervisors->appends(request()->all())->links() }}</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
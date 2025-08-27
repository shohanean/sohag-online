@extends('layouts.dashboard')

@section('server.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'dollar rate' => 'dollar.rate',
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-17">
            <!--begin::Row-->
            <div class="row mb-3">
                <!--begin::Col-->
                <div class="col-md-12 pe-lg-10">
                    <!--begin::Form-->
                    <form action="{{ route('add.server') }}" class="form mb-15" method="post" id="kt_contact_form">
                        @csrf
                        <h1 class="fw-bolder text-dark mb-9">
                            Add New Server
                        </h1>
                        @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endsession
                        <!--begin::Input group-->
                        <div class="row mb-5">
                            <!--begin::Col-->
                            <div class="col-md-8 fv-row">
                                <!--begin::Label-->
                                <label class="fs-5 fw-bold mb-2 required">Server Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text"
                                    class="form-control form-control-solid @error('name') border-danger is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" autofocus />
                                <!--end::Input-->
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4 fv-row">
                                <label class="fs-5 fw-bold mb-2"> &nbsp; </label>
                                <!--begin::Submit-->
                                <button type="submit" class="btn btn-primary form-control" id="kt_contact_submit_button">
                                    <!--begin::Indicator-->
                                    <span class="indicator-label">Submit</span>
                                    <!--end::Indicator-->
                                </button>
                                <!--end::Submit-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row g-5 mb-5 mb-lg-15">
                <h1 class="fw-bolder text-dark mb-9">
                    Server List
                </h1>
                @session('update_success')
                    <div class="alert alert-info" role="alert">
                        {{ session('update_success') }}
                    </div>
                @endsession
                @session('delete_success')
                    <div class="alert alert-danger" role="alert">
                        {{ session('delete_success') }}
                    </div>
                @endsession
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Server Name</th>
                                <th>Total Domain</th>
                                <th>Updated At</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servers as $server)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $server->name }}</td>
                                    <td>{{ $server->subscription->count() }}</td>
                                    <td>{{ $server->updated_at->diffForHumans() }}</td>
                                    <td>{{ $server->created_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('server.destroy', $server->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    onclick="return confirm('Are you sure you want to delete this item?')"
                                                    class="btn btn-sm btn-danger" type="submit">Delete</button>
                                            </form>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal_{{ $server->id }}">
                                                Edit
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal_{{ $server->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" style="display: none;"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit -
                                                                {{ $server->name }}</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('server.update', $server->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label class="form-label">Server Name</label>
                                                                    <input type="text" class="form-control"
                                                                        name="name" value="{{ $server->name }}">
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    Changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Body-->
    </div>
@endsection

@extends('layouts.dashboard')

@section('home')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'active clients' => 'active.clients',
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-17">
            <!--begin::Row-->
            <div class="row g-5 mb-5 mb-lg-15">
                <h1 class="fw-bolder text-dark mb-9">
                    Active Clients List
                </h1>
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endforeach
                @session('success')
                    <div class="alert alert-info">
                        {{ session('success') }}
                    </div>
                @endsession
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>New Password</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <form action="{{ route('change.client.info', $client->id) }}" method="POST">
                                    @csrf
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <input class="form-control" type="text" name="new_name"
                                                value="{{ $client->name }}">
                                        </td>
                                        <td>
                                            <input class="form-control" type="email" name="new_email"
                                                value="{{ $client->email }}">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" name="new_password">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary">Change</button>
                                        </td>
                                    </tr>
                                </form>
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

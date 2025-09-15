@extends('layouts.dashboard')

@section('package.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'package' => 'package.index',
            'server details' => 'server.details',
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-17">
            <!--begin::Row-->
            <div class="row g-5 mb-5 mb-lg-15">
                <div class="d-flex justify-content-between align-items-center mb-9">
                    <h1 class="fw-bolder text-dark mb-0">
                        Package Details - {{ $package->name }}
                    </h1>
                    <a href="{{ route('package.index') }}" class="btn btn-primary">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Domain Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($package->subscription as $subscription)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $subscription->domain_name }}</td>
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

@extends('layouts.dashboard')

@section('work.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'work' => 'work.index',
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
                    Work List
                </h1>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Subscription Details</th>
                                <th>Charge</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($works as $work)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        {{ $work->subscription->package_name }}
                                        <br>
                                        {{ $work->subscription->package_price }}
                                        <br>
                                        {{ $work->subscription->domain_name }}
                                    </td>
                                    <td>{{ $work->charge }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $work->status }}</span>
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

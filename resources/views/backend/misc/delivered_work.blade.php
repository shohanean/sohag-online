@extends('layouts.dashboard')

@section('delivered.work')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'delivered work' => 'delivered.work',
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
                    Delivered Work
                </h1>
                <div class="table-responsive">
                    @session('update_success')
                        <div class="alert alert-success">{{ session('update_success') }}</div>
                    @endsession
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Subscription Details</th>
                                <th>Taken By</th>
                                <th>Charge</th>
                                <th>Trx ID</th>
                                <th>Screenshot</th>
                                <th>Worker Wage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($works as $work)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        Package Name: {{ $work->subscription->package_name ?? '' }}
                                        <br>
                                        Package Price: {{ $work->subscription->package_price ?? '' }}
                                        <br>
                                        Domain Name: {{ $work->subscription->domain_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $work->user->name }}
                                        <br>
                                        <i class="fa fa-wallet">৳{{ $work->user->worker_wage->wallet }}</i>
                                    </td>
                                    <td>{{ $work->charge }}</td>
                                    <td>{{ $work->trx_id }}</td>
                                    <td>{{ $work->screenshot }}</td>
                                    <td>
                                        <form action="{{ route('work.mark.as.done', $work->id) }}" method="POST">
                                            <div class="d-flex gap-2">
                                                @csrf
                                                <input name="worker_wage" class="form-control" type="number"
                                                    value="{{ $work->user->worker_wage->wage }}">
                                                <button class="btn btn-sm bg-success">Mark As Done</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="50" class="text-center text-danger">Nothing to show here</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Body-->
    </div>
@endsection

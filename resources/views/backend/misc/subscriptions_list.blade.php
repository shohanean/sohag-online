@extends('layouts.dashboard')

@section('home')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'client wise subscriptions' => 'subscriptions',
            'subscriptions list' => 'subscriptions.list',
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-17">
            <div class="row mb-3">
                <!--begin::Col-->
                <div class="col-md-12 pe-lg-10">
                    <!--begin::Form-->
                    <form action="{{ route('subscription.store') }}" class="form mb-15" method="POST" id="kt_contact_form">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-9">
                            <h1 class="fw-bolder text-dark mb-0">
                                Add Subscription for {{ $user->name }}
                            </h1>
                            <a href="{{ route('subscriptions') }}" class="btn btn-primary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                        @session('success')
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endsession
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="row mb-5">
                            <div class="col-md-4 fv-row">
                                <label class="fs-5 fw-bold mb-2 required">Package Name</label>
                                <select class="form-select " name="package_id">
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }} (৳{{ $package->price }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="fs-5 fw-bold mb-2 required">Server Name</label>
                                <select class="form-select " name="server_id">
                                    <option value="">-Select One Server-</option>
                                    @foreach ($servers as $server)
                                        <option value="{{ $server->id }}">{{ $server->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="fs-5 fw-bold mb-2 required">Domain Name</label>
                                <input type="text" class="form-control" name="domain_name">
                            </div>
                        </div>
                        <!--begin::Submit-->
                        <button type="submit" class="btn btn-primary" id="kt_contact_submit_button">
                            <!--begin::Indicator-->
                            <span class="indicator-label">Add Subscription</span>
                            <!--end::Indicator-->
                        </button>
                        <!--end::Submit-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Col-->
            </div>
            <!--begin::Row-->
            <div class="row g-5 mb-5 mb-lg-15">
                <h1 class="fw-bolder text-dark mb-9">
                    Subscriptions List
                </h1>
                <div class="table-responsive">
                    @session('update_success')
                        <div class="alert alert-info">{{ session('update_success') }}</div>
                    @endsession
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Package Name</th>
                                <th>Package Price</th>
                                <th>Server Name</th>
                                <th>Domain Name</th>
                                <th>Next Billing Date</th>
                                <th>Updated At</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($user->subscriptions->sortByDesc('created_at') as $subscription)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $subscription->package_name }}</td>
                                    <td>{{ $subscription->package_price }}</td>
                                    <td>{{ $subscription->server?->name }}</td>
                                    <td>
                                        <a href="{{ $subscription->domain_name }}"
                                            target="_blank">{{ $subscription->domain_name }}</a>
                                    </td>
                                    {{-- <td>{{ $subscription->billing_date?->format('jS') }} of the month</td> --}}
                                    <td>{{ \Carbon\Carbon::now()->diffInDays($subscription->billing_date, false) }} days
                                        left
                                        <br>
                                        {{ $subscription->billing_date->format('d M, Y') }}
                                    </td>
                                    <td>{{ $subscription->updated_at->diffForHumans() }}</td>
                                    <td>{{ $subscription->created_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal_{{ $subscription->id }}">
                                                Edit
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal_{{ $subscription->id }}"
                                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit -
                                                                {{ $subscription->package_name }}</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('subscription.update', $subscription->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label class="form-label">Package Name</label>
                                                                    <select class="form-select" name="package_id">
                                                                        @foreach ($packages as $package)
                                                                            <option
                                                                                @if ($subscription->package_id == $package->id) selected @endif
                                                                                value="{{ $package->id }}">
                                                                                {{ $package->name }}
                                                                                (৳{{ $package->price }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Server Name</label>
                                                                    <select class="form-select" name="server_id">
                                                                        <option value="">-Select One Server Name-
                                                                        </option>
                                                                        @foreach ($servers as $server)
                                                                            <option
                                                                                @if ($subscription->server_id == $server->id) selected @endif
                                                                                value="{{ $server->id }}">
                                                                                {{ $server->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Domain
                                                                        Name</label>
                                                                    <input type="text" class="form-control"
                                                                        name="domain_name"
                                                                        value="{{ $subscription->domain_name }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Billing Date</label>
                                                                    <input type="date" class="form-control"
                                                                        name="billing_date" value=""
                                                                        min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
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
                            @empty
                                <tr class="text-center">
                                    <td colspan="50" class="text-danger">Nothing to show here</td>
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

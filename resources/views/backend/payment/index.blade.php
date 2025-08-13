@extends('layouts.dashboard')

@section('campaign.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'campaign list' => 'campaign.index',
            'payment details' => 'payment.index',
        ],
    ])
@endsection

@section('content')
    <div class="card mb-5">
        <!--begin::Body-->
        <div class="card-body">
            <!--begin::Row-->
            <div class="row mb-3">
                <!--begin::Col-->
                <div class="col-md-12 pe-lg-10">
                    <div class="d-flex justify-content-between align-items-center mb-9">
                        <h1 class="fw-bolder text-dark mb-0">
                            Payment Details - {{ $client_wallet->user->name }}
                        </h1>
                        <a href="{{ route('campaign.index') }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="border fw-bold">
                            <tr>
                                <th>Client Name</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <tr>
                                <td>{{ $client_wallet->user->name }}</td>
                                <td>{{ $client_wallet->total }}</td>
                                <td>{{ $client_wallet->paid }}</td>
                                <td>{{ $client_wallet->due }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Body-->
    </div>
    <div>
        <div class="row mt-9">
            <!--begin::Col-->
            <div class="col-xxl-12">
                <!--begin::Earnings-->
                <div class="card card-xxl-stretch mb-5 mb-xxl-10">
                    <!--begin::Header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h3>Add Payment</h3>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pb-0">
                        @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endsession

                        <form action="{{ route('payment.store', $client_wallet->id) }}" method="POST">
                            @csrf
                            <div class="row pb-9">
                                <div class="col-md-4">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold mb-2 required">Payment Method</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select" name="payment_method">
                                        <option value="Bank">Bank</option>
                                        <option value="bKash">bKash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="ROCKET">ROCKET</option>
                                        <option value="CellFin">CellFin</option>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <div class="col-md-4">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold mb-2 required">Payment Amount</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control @error('payment_amount') is-invalid @enderror"
                                        name="payment_amount" step="0.01" />
                                    <!--end::Input-->
                                    @error('payment_amount')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold mb-2">Transaction ID</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control @error('transaction_id') is-invalid @enderror"
                                        name="transaction_id" value="" />
                                    <!--end::Input-->
                                </div>
                                <div class="col-md-4">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold mb-2">Account Info</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea class="form-control" name="account_info" rows="1"></textarea>
                                    <!--end::Input-->
                                </div>
                                <div class="col-md-4">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold mb-2">Remarks</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea class="form-control" name="remarks" rows="1"></textarea>
                                    <!--end::Input-->
                                </div>
                                <div class="col-md-4">
                                    <label class="fs-5 fw-bold mb-2"> &nbsp; </label>
                                    <!--begin::Submit-->
                                    <button type="submit" class="btn btn-info form-control">
                                        <!--begin::Indicator-->
                                        <span class="indicator-label">Add Payment</span>
                                        <!--end::Indicator-->
                                    </button>
                                    <!--end::Submit-->
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Earnings-->
            </div>
            <!--end::Col-->
        </div>
    </div>
    <div class="card mb-xl-8">
        <!--begin::Header-->
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">Campaign Payment Details</span>
            </h3>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body py-3">
            @session('update_success')
                <div class="alert alert-info" role="alert">
                    {{ session('update_success') }}
                </div>
            @endsession
            <!--begin::Table container-->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="fw-bold">
                        <tr>
                            <th>SL. No.</th>
                            <th>Payment Method</th>
                            <th>Payment Amount</th>
                            <th>Transaction ID</th>
                            <th>Account Info</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Added By</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>{{ $payment->payment_amount }}</td>
                                <td>{{ $payment->transaction_id }}</td>
                                <td>{{ $payment->account_info }}</td>
                                <td>{{ $payment->remarks }}</td>
                                <td>
                                    @if ($payment->status == 'approved')
                                        <span class="badge badge-success">{{ $payment->status }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $payment->status }}</span>
                                    @endif
                                </td>
                                <td><i class="fa fa-user"></i> {{ \App\Models\User::find($payment->added_by)->name }}</td>
                                <td>{{ $payment->created_at->diffForHumans() }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal_{{ $payment->id }}">
                                            Edit
                                        </button>
                                        @if ($payment->status == 'pending')
                                            <form action="{{ route('payment.status.change', $payment->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal_{{ $payment->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit -
                                                        {{ $payment->transaction_id }}</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('payment.update', $payment->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="payment_amount" class="form-label">Payment
                                                                Amount</label>
                                                            <input type="text" class="form-control"
                                                                id="payment_amount" name="payment_amount"
                                                                value="{{ $payment->payment_amount }}">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Save
                                                            Changes</button>
                                                    </form>
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
            <!--end::Table container-->
        </div>
        <!--begin::Body-->
    </div>
@endsection

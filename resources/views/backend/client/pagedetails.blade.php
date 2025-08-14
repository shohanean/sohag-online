@extends('layouts.dashboard')

@section('home')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'page details' => 'page.details',
        ],
    ])
@endsection

@section('content')
    <div class="card mb-5">
        <!--begin::Body-->
        <div class="card-body">
            <div class="row mb-3">
                <!--begin::Col-->
                <div class="col-md-12 pe-lg-10">
                    <!--begin::Form-->
                    <form action="{{ route('payment.store', $client_wallet->id) }}" class="form mb-15" method="post"
                        id="kt_contact_form">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-9">
                            <h1 class="fw-bolder text-dark mb-0">
                                Page Details - {{ $page->page_name }}
                            </h1>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                        @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endsession
                        <div class="row">
                            <input type="hidden" name="source" value="client">
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
                                <button @if ($campaigns->count() == 0) disabled @endif type="submit"
                                    class="btn btn-info form-control">
                                    <!--begin::Indicator-->
                                    <span class="indicator-label">Add Payment</span>
                                    <!--end::Indicator-->
                                </button>
                                <!--end::Submit-->
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Col-->
            </div>
            <div class="row mb-9">
                <!--begin::Accordion-->
                <div class="accordion" id="kt_accordion_1">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="kt_accordion_1_header_2">
                            <button class="bg-secondary text-dark accordion-button fs-4 fw-semibold collapsed"
                                type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2"
                                aria-expanded="false" aria-controls="kt_accordion_1_body_2">
                                Click to see the payment list
                            </button>
                        </h2>
                        <div id="kt_accordion_1_body_2" class="accordion-collapse collapse"
                            aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_1">
                            <div class="accordion-body">
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
                                                <td><i class="fa fa-user"></i>
                                                    {{ \App\Models\User::find($payment->added_by)->name }}</td>
                                                <td>
                                                    {{ $payment->created_at->format('jS F, Y') }}
                                                    <br>
                                                    <div class="badge bg-secondary text-dark">
                                                        {{ $payment->created_at->diffForHumans() }}
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
                    </div>
                </div>
                <!--end::Accordion-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-3">
                <!--begin::Col-->
                <div class="col-md-12 pe-lg-10">
                    <div class="d-flex justify-content-between align-items-center mb-9">
                        <h1 class="fw-bolder text-dark mb-0">
                            Campaigns List
                        </h1>
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
                                <th>Total Campaigns</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <tr>
                                <td>{{ $campaigns->count() }}</td>
                                <td>{{ $client_wallet->total }}</td>
                                <td>{{ $client_wallet->paid }}</td>
                                <td>{{ $client_wallet->due }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="border fw-bold">
                            <tr>
                                <th>SL. No.</th>
                                <th>Campaign Name</th>
                                <th>Ad ID</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            @forelse ($campaigns as $campaign)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $campaign->name }}</td>
                                    <td>
                                        <span class="badge bg-secondary text-dark">{{ $campaign->ad_id }}</span>
                                        {{-- <i class="fa fa-copy"></i> --}}
                                    </td>
                                    <td>{{ $campaign->total }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-success"
                                            href="{{ route('campaign.details', $campaign->id) }}">Show</a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-danger">
                                    <td colspan="50">Nothing to show here</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Body-->
    </div>
@endsection

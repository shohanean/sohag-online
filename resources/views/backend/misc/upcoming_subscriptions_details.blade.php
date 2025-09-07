@extends('layouts.dashboard')

@section('upcoming.subscriptions')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'upcoming subscriptions' => 'upcoming.subscriptions',
            'add subscription' => 'add.subscription',
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
                    <form action="{{ route('subscription.payment', $subscription->id) }}" class="form mb-15" method="POST"
                        id="kt_contact_form">
                        @csrf
                        <h1 class="fw-bolder text-dark mb-9">
                            Add Subscription for {{ $subscription->domain_name }}
                            <a href="{{ route('upcoming.subscriptions') }}" class="btn btn-primary float-end">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </h1>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead class="border fw-bold">
                                        <tr>
                                            <th>Page Owner</th>
                                            <th>Page Owner Email</th>
                                            <th>Domain Name</th>
                                            <th>Package Name</th>
                                            <th>Package Price</th>
                                            <td>Billing Date</td>
                                        </tr>
                                    </thead>
                                    <tbody class="border">
                                        <tr>
                                            <td>{{ $subscription->user->name }}</td>
                                            <td>{{ $subscription->user->email }}</td>
                                            <td>{{ $subscription->domain_name }}</td>
                                            <td>{{ $subscription->package_name }}</td>
                                            <td>{{ $subscription->package_price }}</td>
                                            <td>
                                                @php
                                                    $today = \Carbon\Carbon::today();
                                                    $billingDate = \Carbon\Carbon::parse($subscription->billing_date);
                                                    $diff = $today->diffInDays($billingDate, false);
                                                @endphp

                                                @if ($billingDate->isToday())
                                                    <span class="badge bg-warning">Due Today</span>
                                                @elseif ($billingDate->isTomorrow())
                                                    <span class="badge bg-info">Due Tomorrow</span>
                                                @elseif ($billingDate->isYesterday())
                                                    <span class="badge bg-danger">Expired Yesterday</span>
                                                @elseif ($diff > 0)
                                                    <span class="badge bg-success">{{ $diff }} days left</span>
                                                @else
                                                    <span class="badge bg-danger">Expired {{ abs($diff) }} days
                                                        ago</span>
                                                @endif
                                                <br>
                                                {{ $subscription->billing_date?->format('d M, Y') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @session('update')
                            <div class="alert alert-success" role="alert">
                                {{ session('update') }}
                            </div>
                        @endsession
                        <!--begin::Input group-->
                        <div class="row mb-5">
                            <!--begin::Col-->
                            <div class="col-md-8 fv-row">
                                <!--begin::Label-->
                                <label class="fs-5 fw-bold mb-2 required">For How Many Month?</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select" name="no_of_month">
                                    <option value="1">1 Month</option>
                                    <option value="2">2 Months</option>
                                    <option value="3">3 Months</option>
                                    <option value="4">4 Months</option>
                                    <option value="5">5 Months</option>
                                    <option value="6">6 Months</option>
                                    <option value="7">7 Months</option>
                                    <option value="8">8 Months</option>
                                    <option value="9">9 Months</option>
                                    <option value="10">10 Months</option>
                                    <option value="11">11 Months</option>
                                    <option value="12">12 Months</option>
                                </select>
                                <!--end::Input-->
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
                    Previous Subscription History
                </h1>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Package Name</th>
                                <th>Package Price</th>
                                <th>Paid Date</th>
                                <th>Added By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscription->subscription_fees->sortByDesc('created_at') as $subscription_fee)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $subscription_fee->package_name }}</td>
                                    <td>{{ $subscription_fee->package_price }}</td>
                                    <td>{{ $subscription_fee->paid_date }}</td>
                                    <td>
                                        <i class="fa fa-user-tie"></i>
                                        {{ App\Models\User::find($subscription_fee->generated_by)->name }}
                                    </td>
                                    <td>
                                        @if ($subscription_fee->status == 'paid')
                                            <div class="badge bg-success">Paid</div>
                                        @endif
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

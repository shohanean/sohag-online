@extends('layouts.dashboard')

@section('home')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'subscription fees' => 'subscription.fees',
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
                            Subscription Fees
                        </h1>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <div class="row">
                <div class="table-responsive">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table table-bordered text-center">
                        <thead class="border fw-bold">
                            <tr>
                                <th>Package Name</th>
                                <th>Package Price</th>
                                <th>Domain Name</th>
                                <th>Next Billing Date</th>
                                <th>Created At</th>
                                <th>Pay Your Bill</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <tr>
                                <td>{{ $subscription->package_name }}</td>
                                <td>{{ $subscription->package_price }}</td>
                                <td>{{ $subscription->domain_name ?? 'Not Set Yet' }}</td>
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
                                        <span class="badge bg-danger">Expired {{ abs($diff) }} days ago</span>
                                    @endif
                                    <br>
                                    {{ $subscription->billing_date?->format('d M, Y') }}
                                </td>
                                <td>{{ $subscription->created_at->diffForHumans() }}</td>
                                <td>
                                    {{-- <form action="{{ route('pay', $subscription->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            style="align-items:center;padding:5px 10px;border:none;border-radius:5px;">
                                            <img src="https://cdn.worldvectorlogo.com/logos/bkash.svg" alt="bKash"
                                                style="height:20px;margin-right:8px;">
                                            Pay with bKash
                                        </button>
                                    </form>
                                    <br> --}}
                                    <form action="{{ route('uddoktapay.checkout', $subscription->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            style="align-items:center;padding:5px 10px;border:none;border-radius:5px;">
                                            <img src="https://uddoktapay.com/assets/images/logo.png" alt="uddoktapay"
                                                style="height:20px;margin-right:8px;">
                                            Pay with uddoktapay
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="border fw-bold">
                            <tr>
                                <th>SL. No.</th>
                                <td>Generated Date</td>
                                <td>Due Date</td>
                                <td>Paid Date</td>
                                <th>Status</th>
                                <th>Remarks</th>
                                <td>Generated By</td>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            @forelse ($subscription->subscription_fees->sortByDesc('created_at') as $subscription_fee)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $subscription_fee->generated_date }}</td>
                                    <td>
                                        {{ $subscription_fee->due_date }}
                                        @if ($subscription_fee->status == 'unpaid')
                                            <br>
                                            <span class="badge bg-{{ $subscription_fee->due_date_status['class'] }}">
                                                {{ $subscription_fee->due_date_status['text'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($subscription_fee->paid_date)->format('d M, Y') }}
                                    </td>
                                    <td>
                                        @if ($subscription_fee->status == 'paid')
                                            <span
                                                class="badge bg-success text-dark">{{ Str::title($subscription_fee->status) }}</span>
                                        @else
                                            <span
                                                class="badge bg-warning text-dark">{{ Str::title($subscription_fee->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{!! $subscription_fee->remarks !!}</td>
                                    <td>{{ $subscription_fee->user->name }}</td>
                                    <td>৳{{ $subscription_fee->package_price }}</td>
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

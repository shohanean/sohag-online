@extends('layouts.dashboard')

@section('upcoming.subscriptions')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'upcoming subscriptions' => 'upcoming.subscriptions',
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
                    <div class="d-flex gap-2 align-items-center">
                        Upcoming Subscriptions
                        <input type="text" id="searchInput" placeholder="Search Here..." style="margin-left:10px;">
                        <form action="" method="GET">
                            <input type="text" id="date_range" name="date_range" />
                            <button class="btn btn-sm bg-primary">Search By Date</button>
                        </form>
                    </div>
                </h1>
                <a href="{{ route('upcoming.subscriptions') }}?date_range=1900-01-01+-+{{ \Carbon\Carbon::yesterday()->format('Y-m-d') }}"
                    class="btn btn-sm bg-warning">Show Expired Only</a>
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Page Name</th>
                                <th>Package Name</th>
                                <th>Package Price</th>
                                <th>Billing Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $userId => $userSubscriptions)
                                @if (empty(App\Models\User::withTrashed()->where('id', $userId)->first()->deleted_at))
                                    <tr>
                                        <td colspan="50">
                                            <i class="fa fa-user-circle text-dark"></i>
                                            {{ App\Models\User::withTrashed()->where('id', $userId)->first()->name }}
                                        </td>
                                    </tr>
                                    @foreach ($userSubscriptions as $subscription)
                                        <tr>
                                            <td>
                                                <div class="mx-5">
                                                    <i class="fa fa-globe"></i> {{ $subscription->domain_name }}
                                                    <br>
                                                    <i class="fa fa-envelope"></i> {{ $subscription->user->email }}
                                                </div>
                                            </td>
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
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a target="_blank"
                                                        href="{{ route('upcoming.subscriptions.details', $subscription->id) }}"
                                                        class="btn btn-sm bg-success text-white">Details</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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

@section('footer_scripts')
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#myTable tbody tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
        $(function() {
            $('#date_range').daterangepicker({
                opens: 'left',
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
        });
    </script>
@endsection

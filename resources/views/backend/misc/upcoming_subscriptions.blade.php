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
                    Upcoming Subscriptions
                    <input type="text" id="searchInput" placeholder="Search Here..." style="margin-left:10px;">
                </h1>
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Subscriptions</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $subscription->package_name }}</td>
                                    <td>{{ $subscription->billing_date->format('d/M/Y') }}</td>
                                    {{-- <td>{{ $subscription->subscription_fees }}</td> --}}
                                    <td>
                                        @if ($subscription->subscription_fees->count() == 0)
                                            <div class="alert alert-danger"></div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="" class="btn btn-sm btn-success">Details</a>
                                        </div>
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
    </script>
@endsection

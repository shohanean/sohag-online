@extends('layouts.dashboard')

@section('home')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'campaign details' => 'campaign.show',
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
                            Campaign Details
                        </h1>
                        <a href="{{ route('page.details', $campaign->page_id) }}" class="btn btn-primary">
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
                                <th>Page Owner</th>
                                <th>Page Name</th>
                                <th>Campaign Name</th>
                                <th>Ad ID</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <tr>
                                <td>{{ $campaign->page->user->name }}</td>
                                <td>{{ $campaign->page->page_name }}</td>
                                <td>{{ $campaign->name }}</td>
                                <td>
                                    <span id="copyText" class="badge bg-secondary text-dark">{{ $campaign->ad_id }}</span>
                                    <i id="copy_btn" class="fa fa-copy ms-2" style="cursor:pointer;"
                                        onclick="copyText()"></i>
                                    <script>
                                        function copyText() {
                                            const text = document.getElementById("copyText").innerText;
                                            navigator.clipboard.writeText(text).then(() => {
                                                document.getElementById("copy_btn").classList.add('text-success');
                                            }).catch(err => {
                                                console.error("Failed to copy: ", err);
                                            });
                                        }
                                    </script>
                                </td>
                                <td>{{ $campaign->total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Body-->
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
            <!--begin::Table container-->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="fw-bold">
                        <tr>
                            <th>SL. No.</th>
                            <th>Date</th>
                            <th>Old Amount</th>
                            <th>Current Amount</th>
                            <th>Spent Amount</th>
                            <th>Dollar Rate</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transections as $transection)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $transection->date }}</td>
                                <td>{{ $transection->old_amount }}</td>
                                <td>{{ $transection->current_amount }}</td>
                                <td>{{ $transection->spent_amount }}</td>
                                <td>{{ $transection->dollar_rate }}</td>
                                <td>{{ $transection->amount }}</td>
                                <td>
                                    <span
                                        class="badge @if ($transection->transaction_type == 'expense') bg-info
                                    @else
                                        bg-success @endif">{{ Str::title($transection->transaction_type) }}</span>
                                </td>
                                <td>
                                    {{ $transection->created_at->diffForHumans() }}
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

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
            <div class="row g-5 mb-5">
                <h1 class="fw-bolder text-dark mb-9">
                    Delivered Work
                </h1>
                <div class="table-responsive">
                    @session('update_success')
                        <div class="alert alert-success">{{ session('update_success') }}</div>
                    @endsession
                    <table id="delivered_work_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Subscription Details</th>
                                <th>Taken By</th>
                                <th>Payment Method</th>
                                <th>Charge</th>
                                <th>Trx ID</th>
                                <th>Screenshot</th>
                                <th>Worker Wage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($works as $work)
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
                                    <td>{{ $work->payment_method }}</td>
                                    <td>{{ $work->charge }}</td>
                                    <td>{{ $work->trx_id }}</td>
                                    <td>
                                        @if ($work->screenshot)
                                            <a target="_blank"
                                                href="{{ asset('uploads/work_screenshots') . '/' . $work->screenshot }}"><i
                                                    class="fa fa-2x fa-image"></i></a>
                                        @else
                                            -
                                        @endif
                                    </td>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Body-->
    </div>
    <div class="card mt-5">
        <!--begin::Body-->
        <div class="card-body p-lg-17">
            <!--begin::Row-->
            <div class="row g-5 mb-5">
                <h1 class="fw-bolder text-dark mb-9">
                    Done Work
                </h1>
                <div class="table-responsive">
                    <table id="done_work_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Subscription Details</th>
                                <th>Taken By</th>
                                <th>Payment Method</th>
                                <th>Charge</th>
                                <th>Trx ID</th>
                                <th>Screenshot</th>
                                <th>Done At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($done_works as $done_work)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        Package Name: {{ $done_work->subscription->package_name ?? '' }}
                                        <br>
                                        Package Price: {{ $done_work->subscription->package_price ?? '' }}
                                        <br>
                                        Domain Name: {{ $work->subscription->domain_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $done_work->user->name }}
                                    </td>
                                    <td>{{ $done_work->payment_method }}</td>
                                    <td>{{ $done_work->charge }}</td>
                                    <td>{{ $done_work->trx_id }}</td>
                                    <td>
                                        @if ($done_work->screenshot)
                                            <a target="_blank"
                                                href="{{ asset('uploads/work_screenshots') . '/' . $done_work->screenshot }}"><i
                                                    class="fa fa-2x fa-image"></i></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $done_work->updated_at->format('d M, Y h:i:s A') }}</td>
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
        let delivered_work_table = new DataTable('#delivered_work_table', {
            pageLength: 10, // default rows per page
            searching: true, // enables search box
            ordering: true, // enables sorting
            paging: true, // enables pagination
            lengthMenu: [5, 10, 25, 50, 100], // dropdown to change page size
        });
        let done_work_table = new DataTable('#done_work_table', {
            pageLength: 10, // default rows per page
            searching: true, // enables search box
            ordering: true, // enables sorting
            paging: true, // enables pagination
            lengthMenu: [5, 10, 25, 50, 100], // dropdown to change page size
        });
    </script>
@endsection

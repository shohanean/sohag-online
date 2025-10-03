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
                    <input type="text" id="searchInput" placeholder="Search Here..." style="margin-left:10px;">
                </h1>
                <div class="table-responsive">
                    @session('update_success')
                        <div class="alert alert-success">{{ session('update_success') }}</div>
                    @endsession
                    <table id="myTable" class="table table-bordered table-striped">
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
    <div class="card mt-5">
        <!--begin::Body-->
        <div class="card-body p-lg-17">
            <!--begin::Row-->
            <div class="row g-5 mb-5">
                <h1 class="fw-bolder text-dark mb-9">
                    Done Work
                    <input type="text" id="searchInput1" placeholder="Search Here..." style="margin-left:10px;">
                </h1>
                <div class="table-responsive">
                    <table id="myTable1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Subscription Details</th>
                                <th>Taken By</th>
                                <th>Charge</th>
                                <th>Trx ID</th>
                                <th>Screenshot</th>
                                <th>Done At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($done_works as $done_work)
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
        document.getElementById('searchInput1').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#myTable1 tbody tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
@endsection

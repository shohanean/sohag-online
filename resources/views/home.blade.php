@extends('layouts.dashboard')

@section('home')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
        ],
    ])
@endsection

@section('content')
    @if (auth()->user()->getRoleNames()->first() == 'Worker')
        <div class="row gy-5 g-xl-8">
            <div class="col-xl-6">
                <!--begin: Stats Widget 19-->
                <div class="card card-custom bg-light-success card-stretch gutter-b">
                    <!--begin::Body-->
                    <div class="card-body my-3">
                        <i class="fa fa-wallet fa-2x pb-3"></i>
                        <h1>৳{{ auth()->user()->worker_wage->wallet ?? '-' }}</h1>
                        <div class="font-weight-bold text-muted font-size-sm">Your Wallet Balance</div>
                    </div>
                    <!--end:: Body-->
                </div>
                <!--end: Stats:Widget 19-->
            </div>
            <div class="col-xl-6">
                <!--begin: Stats Widget 19-->
                <div class="card card-custom bg-light-warning card-stretch gutter-b">
                    <!--begin::Body-->
                    <div class="card-body my-3">
                        <i class="fa fa-handshake fa-2x pb-3"></i>
                        <h1>৳{{ auth()->user()->worker_wage->wage ?? '-' }}</h1>
                        <div class="font-weight-bold text-muted font-size-sm">Per Completed Work</div>
                    </div>
                    <!--end:: Body-->
                </div>
                <!--end: Stats:Widget 19-->
            </div>
            @if (empty(auth()->user()->worker_wage))
                <div class="col-xl-12">
                    <div class="alert alert-danger">Please contact with admin, your per completed work value hasn't set yet!
                    </div>
                </div>
            @endif
        </div>
        @if (auth()->user()->worker_wage)
            <div class="row gy-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-12">
                    <!--begin::Tables Widget 9-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Your running work
                                    ({{ $worker_works->whereNotNull('user_id')->where('status', 'running')->count() }})</span>
                                <br>
                                <div class="badge bg-secondary text-dark">Work Process: Open > Running > Delivered > Done
                                </div>
                            </h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                @session('update_success')
                                    <div class="alert alert-success">{{ session('update_success') }}</div>
                                @endsession
                                <!--begin::Table-->
                                <input type="text" id="mySearch" placeholder="Search...">

                                <table id="worker_work_taken_table"
                                    class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr>
                                            <th>SL. No.</th>
                                            <th>Subscription Details</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @forelse ($worker_works->whereNotNull('user_id')->where('status', 'running') as $worker_work)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>
                                                    Package Name: {{ $worker_work->subscription->package_name ?? '-' }}
                                                    <br>
                                                    Package Price: {{ $worker_work->subscription->package_price ?? '-' }}
                                                    <br>
                                                    Domain Name: {{ $worker_work->subscription->domain_name ?? '-' }}
                                                </td>
                                                <td>
                                                    @if ($worker_work->status == 'open')
                                                        <span
                                                            class="badge bg-info">{{ Str::title($worker_work->status) }}</span>
                                                    @elseif ($worker_work->status == 'running')
                                                        <span
                                                            class="badge bg-primary">{{ Str::title($worker_work->status) }}</span>
                                                    @elseif ($worker_work->status == 'delivered')
                                                        <span
                                                            class="badge bg-warning">{{ Str::title($worker_work->status) }}</span>
                                                    @else
                                                        <span
                                                            class="badge bg-success">{{ Str::title($worker_work->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @if ($worker_work->status == 'running')
                                                            <form action="{{ route('work.update', $worker_work->id) }}"
                                                                method="POST" style="display:inline-block;">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" value="" name="user_id">
                                                                <button
                                                                    onclick="return confirm('Are you sure you want to leave this work?')"
                                                                    type="submit" class="btn btn-sm bg-warning">
                                                                    Leave The Work
                                                                </button>
                                                            </form>
                                                            <a class="btn btn-sm bg-success"
                                                                href="{{ route('deliver.the.work', $worker_work->id) }}">Deliver
                                                                The Work</a>
                                                        @else
                                                            <span class="badge bg-dark">You Already Delivered The
                                                                Work</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="50" class="text-center text-danger">Nothing to show here</td>
                                            </tr>
                                        @endforelse
                                    <tbody>
                                        <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                        <!--begin::Body-->
                    </div>
                    <!--end::Tables Widget 9-->
                </div>
                <!--end::Col-->
            </div>
            <div class="row gy-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-12">
                    <!--begin::Tables Widget 9-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Your delivered/done work
                                    ({{ $worker_works->whereNotNull('user_id')->whereIn('status', ['delivered', 'done'])->count() }})</span>
                                <br>
                                <div class="badge bg-secondary text-dark">Work Process: Open > Running > Delivered > Done
                                </div>
                            </h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr>
                                            <th>SL. No.</th>
                                            <th>Subscription Details</th>
                                            <th>Charge</th>
                                            <th>Trx ID</th>
                                            <th>Screenshot</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @forelse ($worker_works->whereNotNull('user_id')->whereIn('status', ['delivered', 'done'])->sortBy('status') as $worker_work)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>
                                                    Package Name: {{ $worker_work->subscription->package_name ?? '-' }}
                                                    <br>
                                                    Package Price: {{ $worker_work->subscription->package_price ?? '-' }}
                                                    <br>
                                                    Domain Name: {{ $worker_work->subscription->domain_name ?? '-' }}
                                                </td>
                                                <td>{{ $worker_work->charge }}</td>
                                                <td>{{ $worker_work->trx_id ?? '-' }}</td>
                                                <td>{{ $worker_work->screenshot ?? '-' }}</td>
                                                <td>
                                                    @if ($worker_work->status == 'open')
                                                        <span
                                                            class="badge bg-info">{{ Str::title($worker_work->status) }}</span>
                                                    @elseif ($worker_work->status == 'running')
                                                        <span
                                                            class="badge bg-primary">{{ Str::title($worker_work->status) }}</span>
                                                    @elseif ($worker_work->status == 'delivered')
                                                        <span
                                                            class="badge bg-warning">{{ Str::title($worker_work->status) }}</span>
                                                    @else
                                                        <span
                                                            class="badge bg-success">{{ Str::title($worker_work->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="50" class="text-center text-danger">Nothing to show here</td>
                                            </tr>
                                        @endforelse
                                    <tbody>
                                        <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                        <!--begin::Body-->
                    </div>
                    <!--end::Tables Widget 9-->
                </div>
                <!--end::Col-->
            </div>
            <div class="row gy-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-12">
                    <!--begin::Tables Widget 9-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Work Pool
                                    ({{ $worker_works->whereNull('user_id')->count() }})</span>
                            </h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr>
                                            <th>SL. No.</th>
                                            <th>Subscription Details</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @forelse ($worker_works->whereNull('user_id')->sortByDesc('created_at') as $worker_work)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>
                                                    Package Name: {{ $worker_work->subscription->package_name ?? '-' }}
                                                    <br>
                                                    Package Price: {{ $worker_work->subscription->package_price ?? '-' }}
                                                    <br>
                                                    Domain Name: {{ $worker_work->subscription->domain_name ?? '-' }}
                                                </td>
                                                <td>
                                                    @if ($worker_work->status == 'open')
                                                        <span
                                                            class="badge bg-info">{{ Str::title($worker_work->status) }}</span>
                                                    @elseif ($worker_work->status == 'running')
                                                        <span
                                                            class="badge bg-primary">{{ Str::title($worker_work->status) }}</span>
                                                    @else
                                                        <span
                                                            class="badge bg-success">{{ Str::title($worker_work->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <form action="{{ route('work.update', $worker_work->id) }}"
                                                            method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" value="{{ auth()->id() }}"
                                                                name="user_id">
                                                            <button
                                                                onclick="return confirm('Are you sure you want to take this work?')"
                                                                type="submit" class="btn btn-sm bg-info text-white">
                                                                Take This Work
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="50" class="text-center text-danger">Nothing to show here
                                                </td>
                                            </tr>
                                        @endforelse
                                    <tbody>
                                        <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                        <!--begin::Body-->
                    </div>
                    <!--end::Tables Widget 9-->
                </div>
                <!--end::Col-->
            </div>
        @endif
    @endif
    @if (auth()->user()->getRoleNames()->first() == 'Client')
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <a href="https://adifyiq.com/sohag-online" target="_blank">
                        <h4 class="mt-3">See your ad result</h4>
                    </a>
                </div>
            </div>
            <div class="row g-4">
                <!-- Total Campaigns -->
                <div class="col-6 col-md-3">
                    <div class="card text-center shadow-sm border-0 bg-primary text-white">
                        <div class="card-body">
                            <i class="fa fa-bullhorn fa-2x mb-2 text-dark"></i>
                            <h6 class="fw-bold">Total Campaigns</h6>
                            <h1 class="fw-bolder">{{ $campaigns->count() }}</h1>
                        </div>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="col-6 col-md-3">
                    <div class="card text-center shadow-sm border-0 bg-success text-white">
                        <div class="card-body">
                            <i class="fa fa-dollar-sign fa-2x mb-2 text-dark"></i>
                            <h6 class="fw-bold">Total Amount</h6>
                            <h1 class="fw-bolder">{{ $client_wallet->total }}</h1>
                        </div>
                    </div>
                </div>

                <!-- Total Paid -->
                <div class="col-6 col-md-3">
                    <div class="card text-center shadow-sm border-0 bg-info text-white">
                        <div class="card-body">
                            <i class="fa fa-money-bill-wave fa-2x mb-2 text-dark"></i>
                            <h6 class="fw-bold">Total Paid</h6>
                            <h1 class="fw-bolder">{{ $client_wallet->paid }}</h1>
                        </div>
                    </div>
                </div>

                <!-- Total Due -->
                <div class="col-6 col-md-3">
                    <div class="card text-center shadow-sm border-0 bg-danger text-white">
                        <div class="card-body">
                            <i class="fa fa-exclamation-circle fa-2x mb-2 text-dark"></i>
                            <h6 class="fw-bold">Total Due</h6>
                            <h1 class="fw-bolder">{{ $client_wallet->due }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-5">
            @foreach (auth()->user()->page as $p)
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fab fa-facebook-square fa-4x text-primary me-3"></i>
                                <div>
                                    <h1 class="mb-0 text-muted">{{ $p->page_name }}</h1>
                                </div>
                            </div>
                            <a href="{{ route('page.details', $p->id) }}" class="btn btn-primary">
                                View Campaigns
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            @foreach ($user_subscriptions as $user_subscription)
                <div class="col-6 col-md-4">
                    <div style="background: rgb(147, 235, 255)" class="card shadow rounded-3">
                        <div class="card-body text-center">
                            <h4 class="card-title">{{ $user_subscription->package_name }}</h4>
                            <span
                                class="badge bg-secondary text-dark mb-2">{{ $user_subscription->domain_name ?? '-' }}</span>
                            <p class="card-text">৳ {{ $user_subscription->package_price }} / month</p>
                            <p class="mb-2">Billing Date: {{ $user_subscription->billing_date->format('d M, Y') }}</p>
                            <a href="{{ route('subscription.details', $user_subscription->id) }}"
                                class="btn btn-light btn-sm text-dark">Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    @if (auth()->user()->getRoleNames()->first() == 'Super Admin')
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <!--begin::Col-->
            <div class="col-xl-3">
                <!--begin::Card widget 3-->
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end"
                    style="background-color: #7239EA">
                    <!--begin::Header-->
                    <div class="card-header pt-5 mb-3">
                        <!--begin::Icon-->
                        <div class="d-flex flex-center rounded-circle h-80px w-80px"
                            style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color: #7239EA">
                            <i class="fa fa-users text-white fa-2x"></i>
                        </div>
                        <!--end::Icon-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Card body-->
                    <div class="card-body d-flex align-items-end mb-3">
                        <!--begin::Info-->
                        <div class="d-flex align-items-center">
                            <span class="fs-4hx text-white fw-bold me-6">
                                {{ $users->count() }}
                            </span>
                            <div class="fw-bold fs-6 text-white">
                                <span class="d-block">Total</span>
                                <span class="">Users</span>
                            </div>
                        </div>
                        <!--end::Info-->
                    </div>
                    <a href="{{ route('user.index') }}" class="btn btn-sm bg-warning">View</a>
                    <!--end::Card body-->
                </div>
                <!--end::Card widget 3-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-3">
                <!--begin::Card widget 3-->
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end"
                    style="background-color: #7239EA">
                    <!--begin::Header-->
                    <div class="card-header pt-5 mb-3">
                        <!--begin::Icon-->
                        <div class="d-flex flex-center rounded-circle h-80px w-80px"
                            style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color: #7239EA">
                            <i class='fas fa-user-tie text-white fa-2x'></i>
                        </div>
                        <!--end::Icon-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Card body-->
                    <div class="card-body d-flex align-items-end mb-3">
                        <!--begin::Info-->
                        <div class="d-flex align-items-center">
                            <span class="fs-4hx text-white fw-bold me-6">
                                {{ $active_clients->count() }}
                            </span>
                            <div class="fw-bold fs-6 text-white">
                                <span class="d-block">Total Active</span>
                                <span class="">Clients</span>
                            </div>
                        </div>
                        <!--end::Info-->
                    </div>
                    <a href="{{ route('active.clients') }}" class="btn btn-sm bg-warning">View</a>
                    <!--end::Card body-->
                </div>
                <!--end::Card widget 3-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-3">
                <!--begin::Card widget 3-->
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end"
                    style="background-color: #7239EA">
                    <!--begin::Header-->
                    <div class="card-header pt-5 mb-3">
                        <!--begin::Icon-->
                        <div class="d-flex flex-center rounded-circle h-80px w-80px"
                            style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color: #7239EA">
                            <i class='fas fa-calendar text-white fa-2x'></i>
                        </div>
                        <!--end::Icon-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Card body-->
                    <div class="card-body d-flex align-items-end mb-3">
                        <!--begin::Info-->
                        <div class="d-flex align-items-center">
                            <span class="fs-4hx text-white fw-bold me-6">
                                {{ $subscriptions->total() }}
                            </span>
                            <div class="fw-bold fs-6 text-white">
                                <span class="d-block">Total</span>
                                <span class="">Subscriptions</span>
                            </div>
                        </div>
                        <!--end::Info-->
                    </div>
                    <a href="{{ route('subscriptions') }}" class="btn btn-sm bg-warning">View</a>
                    <!--end::Card body-->
                </div>
                <!--end::Card widget 3-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-3">
                <!--begin::Card widget 3-->
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end"
                    style="background-color: #7239EA">
                    <!--begin::Header-->
                    <div class="card-header pt-5 mb-3">
                        <!--begin::Icon-->
                        <div class="d-flex flex-center rounded-circle h-80px w-80px"
                            style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color: #7239EA">
                            <i class='fa fa-bullhorn text-white fa-2x'></i>
                        </div>
                        <!--end::Icon-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Card body-->
                    <div class="card-body d-flex align-items-end mb-3">
                        <!--begin::Info-->
                        <div class="d-flex align-items-center">
                            <span class="fs-4hx text-white fw-bold me-6">
                                {{ $total_campaigns }}
                            </span>
                            <div class="fw-bold fs-6 text-white">
                                <span class="d-block">Total</span>
                                <span class="">Campaigns</span>
                            </div>
                        </div>
                        <!--end::Info-->
                    </div>
                    <a href="{{ route('campaign.index') }}" class="btn btn-sm bg-warning">View</a>
                    <!--end::Card body-->
                </div>
                <!--end::Card widget 3-->
            </div>
            <!--end::Col-->
        </div>
        <div class="row gy-5 g-xl-8">
            <!--begin::Col-->
            <div class="col-xl-4 d-none">
                <!--begin::List Widget 3-->
                <div class="card card-xl-stretch mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title fw-bolder text-dark">
                            Add Server
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-2">
                        <!--begin::Form-->
                        <form method="POST" action="{{ route('add.server') }}" enctype="multipart/form-data">
                            @csrf
                            <!--begin::Input group-->
                            <div class="input-group mb-5">
                                <span class="input-group-text" id="basic-addon3">
                                    <i class="fa fa-server"></i>
                                </span>
                                <input type="text" class="form-control" name="name" />
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="input-group mb-5">
                                <button type="submit" class="btn btn-success btn-sm">Add New Server</button>
                            </div>
                            <!--end::Input group-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end:List Widget 3-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-12">
                <!--begin::Tables Widget 9-->
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1"><i
                                    class="fa fa-exclamation-circle text-danger"></i> Restricted for payments</span>
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body py-3">
                        <!--begin::Table container-->
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <!--begin::Table head-->
                                <thead>
                                    <tr>
                                        <th>Page Name</th>
                                        <th>Owner Name</th>
                                        <th>Campaign</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                    @forelse ($restricted_for_payments as $userId => $userCampaigns)
                                        @if (empty($userCampaigns->first()->user->deleted_at))
                                            @if ($userCampaigns->first()->user->client_wallet->due >= 0)
                                                <tr>
                                                    <td>
                                                        @foreach ($userCampaigns->unique('page_id') as $pageinfo)
                                                            {{ $pageinfo->page->page_name }}
                                                            <br>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        {{ $userCampaigns->first()->user->name }}
                                                    </td>
                                                    <td>
                                                        {{ $userCampaigns->count() }}
                                                    </td>
                                                    <td>
                                                        {{ $userCampaigns->first()->user->client_wallet->total }}
                                                    </td>
                                                    <td>
                                                        {{ $userCampaigns->first()->user->client_wallet->paid }}
                                                    </td>
                                                    <td>
                                                        @if ($userCampaigns->first()->user->client_wallet->due < 0)
                                                            <span class="text-success">
                                                                {{ $userCampaigns->first()->user->client_wallet->due }}
                                                            </span>
                                                        @else
                                                            <span class="text-danger">
                                                                {{ $userCampaigns->first()->user->client_wallet->due }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a target="_blank"
                                                                href="{{ route('campaign.show', $userId) }}"
                                                                class="btn btn-sm btn-success">Details</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="50" class="text-danger">Nothing to show here</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Table container-->
                    </div>
                    <!--begin::Body-->
                </div>
                <!--end::Tables Widget 9-->
            </div>
            <!--end::Col-->
        </div>
    @endif
@endsection
@section('footer_scripts')
    <script>
        let table = new DataTable('#worker_work_taken_table', {
            pageLength: 5,
        });
        document.querySelector('#mySearch').addEventListener('keyup', function() {
            table.search(this.value).draw();
        });
    </script>
@endsection

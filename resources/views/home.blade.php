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
    @if (auth()->user()->getRoleNames()->first() == 'Client')
        <div class="row mb-xl-10">
            <div class="col-xl-12 text-center">
                <div class="alert alert-info">
                    <a href="https://adifyiq.com/sohag-online" target="_blank">
                        <h4 class="mt-3">See your ad result</h4>
                    </a>
                </div>
            </div>
            <div class="row g-4">
                <!-- Total Campaigns -->
                <div class="col-12 col-md-3">
                    <div class="card text-center shadow-sm border-0 bg-primary text-white">
                        <div class="card-body">
                            <i class="fa fa-bullhorn fa-2x mb-2 text-dark"></i>
                            <h6 class="fw-bold">Total Campaigns</h6>
                            <h1 class="fw-bolder">{{ $campaigns->count() }}</h1>
                        </div>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="col-12 col-md-3">
                    <div class="card text-center shadow-sm border-0 bg-success text-white">
                        <div class="card-body">
                            <i class="fa fa-dollar-sign fa-2x mb-2 text-dark"></i>
                            <h6 class="fw-bold">Total Amount</h6>
                            <h1 class="fw-bolder">{{ $client_wallet->total }}</h1>
                        </div>
                    </div>
                </div>

                <!-- Total Paid -->
                <div class="col-12 col-md-3">
                    <div class="card text-center shadow-sm border-0 bg-info text-white">
                        <div class="card-body">
                            <i class="fa fa-money-bill-wave fa-2x mb-2 text-dark"></i>
                            <h6 class="fw-bold">Total Paid</h6>
                            <h1 class="fw-bolder">{{ $client_wallet->paid }}</h1>
                        </div>
                    </div>
                </div>

                <!-- Total Due -->
                <div class="col-12 col-md-3">
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
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
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
                            <i class='fab fa-facebook-f text-white fa-2x'></i>
                        </div>
                        <!--end::Icon-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Card body-->
                    <div class="card-body d-flex align-items-end mb-3">
                        <!--begin::Info-->
                        <div class="d-flex align-items-center">
                            <span class="fs-4hx text-white fw-bold me-6">
                                {{ $pages->count() }}
                            </span>
                            <div class="fw-bold fs-6 text-white">
                                <span class="d-block">Total</span>
                                <span class="">Pages</span>
                            </div>
                        </div>
                        <!--end::Info-->
                    </div>
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
                            <i class='fas fa-box text-white fa-2x'></i>
                        </div>
                        <!--end::Icon-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Card body-->
                    <div class="card-body d-flex align-items-end mb-3">
                        <!--begin::Info-->
                        <div class="d-flex align-items-center">
                            <span class="fs-4hx text-white fw-bold me-6">
                                {{ $packages->count() }}
                            </span>
                            <div class="fw-bold fs-6 text-white">
                                <span class="d-block">Total</span>
                                <span class="">Packages</span>
                            </div>
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card widget 3-->
            </div>
            <!--end::Col-->
        </div>
        <div class="row gy-5 g-xl-8">
            <!--begin::Col-->
            <div class="col-xl-4">
                <!--begin::List Widget 3-->
                <div class="card card-xl-stretch mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title fw-bolder text-dark">
                            Add Records
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-2">
                        <!--begin::Form-->
                        <form method="POST" action="{{ route('import') }}" enctype="multipart/form-data">
                            @csrf
                            <!--begin::Input group-->
                            <div class="input-group mb-5">
                                <span class="input-group-text" id="basic-addon3">
                                    <i class="las la-file-excel fs-1"></i>
                                </span>
                                <input type="file" class="form-control" name="import" />
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="input-group mb-5">
                                <button type="submit" class="btn btn-success btn-sm">Upload</button>
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
            <div class="col-xl-8">
                <!--begin::Tables Widget 9-->
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">User Statistics</span>
                            <span class="text-muted mt-1 fw-bold fs-7">Over {{ $users->count() }} members</span>
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
                                    <tr class="fw-bolder text-muted">
                                        <th class="w-25px">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    data-kt-check="true" data-kt-check-target=".widget-9-check">
                                            </div>
                                        </th>
                                        <th class="min-w-200px">Authors</th>
                                        <th class="min-w-150px">Company</th>
                                        <th class="min-w-150px">Progress</th>
                                        <th class="min-w-100px text-end">Actions</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
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

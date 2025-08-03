@extends('layouts.dashboard')

@section('campaign.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'campaign list' => 'campaign.index',
            'campaign details' => 'campaign.show',
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
                    <form action="{{ route('dollar.rate.insert') }}" class="form mb-15" method="post" id="kt_contact_form">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-9">
                            <h1 class="fw-bolder text-dark mb-0">
                                Campaign Details
                            </h1>
                            <a href="{{ route('campaign.index') }}" class="btn btn-primary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>

                        <div class="row mb-9">
                            <div class="col-3">
                                <div class="card text-center bg-secondary">
                                    <div class="card-body">
                                        <h5 class="card-title">Campaign Name</h5>
                                        <p class="card-text">{{ $campaign->name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card text-center bg-secondary">
                                    <div class="card-body">
                                        <h5 class="card-title">Total</h5>
                                        <p class="card-text">{{ $campaign->total }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card text-center bg-secondary">
                                    <div class="card-body">
                                        <h5 class="card-title">Paid</h5>
                                        <p class="card-text">{{ $campaign->paid }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card text-center bg-secondary">
                                    <div class="card-body">
                                        <h5 class="card-title">Due</h5>
                                        <p class="card-text">{{ $campaign->due }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endsession
                    <!--begin::Input group-->
                    <div class="row mb-5">
                        <!--begin::Col-->
                        <div class="col-md-4 fv-row">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold mb-2 required">Date</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="date" class="form-control form-control-solid" placeholder="" name=""
                            value="{{ \Carbon\Carbon::now()->toDateString() }}" disabled />
                            <!--end::Input-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-3 fv-row">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold mb-2 required">Today's Rate</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="number"
                            class="form-control form-control-solid @error('rate') border-danger is-invalid @enderror"
                            name="rate" step="0.01" value="{{ old('rate') }}" />
                            <!--end::Input-->
                            @error('rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <label class="fs-5 fw-bold mb-2">Total</label>
                                <!--begin::Submit-->
                                <button type="submit" class="btn btn-secondary form-control">
                                    <!--begin::Indicator-->
                                    <span class="indicator-label"> X {{ $dollar_rate->rate }} = <span id="here">Here</span></span>
                                    <!--end::Indicator-->
                                </button>
                                <!--end::Submit-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-2 fv-row">
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
                    Doller Rate History
                </h1>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Date</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($doller_rates as $doller_rate)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $doller_rate->created_at }}</td>
                                    <td>{{ $doller_rate->rate }}</td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Body-->
    </div>
@endsection

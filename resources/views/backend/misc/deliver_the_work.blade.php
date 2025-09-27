@extends('layouts.dashboard')

@section('home')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'deliver the work' => 'deliver.the.work',
        ],
    ])
@endsection

@section('content')
    <div class="card @if ($work->status != 'running') d-none @endif">
        <!--begin::Body-->
        <div class="card-body p-lg-17">
            <!--begin::Row-->
            <div class="row mb-3">
                <!--begin::Col-->
                <div class="col-md-12 pe-lg-10">
                    <!--begin::Form-->
                    <form action="{{ route('deliver.the.work.post', $work->id) }}" class="form mb-15" method="post"
                        id="kt_contact_form" enctype="multipart/form-data">
                        @csrf
                        <h1 class="fw-bolder text-dark mb-9">Deliver The Work -
                            {{ $work->subscription->package_name ?? '-' }} -
                            {{ $work->subscription->domain_name ?? '-' }}</h1>
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
                                <label class="fs-5 fw-bold mb-2 required">Charge</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number"
                                    class="form-control form-control-solid @error('charge') border-danger is-invalid @enderror"
                                    placeholder="" name="charge" value="{{ old('charge') }}" />
                                <!--end::Input-->
                                @error('charge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4 fv-row">
                                <!--end::Label-->
                                <label class="fs-5 fw-bold mb-2 required">Trx ID</label>
                                <!--end::Label-->
                                <!--end::Input-->
                                <input type="text"
                                    class="form-control form-control-solid @error('trx_id') border-danger is-invalid @enderror"
                                    placeholder="" name="trx_id" value="{{ old('trx_id') }}" />
                                <!--end::Input-->
                                @error('trx_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4 fv-row">
                                <!--end::Label-->
                                <label class="fs-5 fw-bold mb-2">Screenshot</label>
                                <!--end::Label-->
                                <!--end::Input-->
                                <input type="file" class="form-control" name="screenshot" />
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Submit-->
                        <button type="submit" class="btn btn-primary" id="kt_contact_submit_button">
                            <!--begin::Indicator-->
                            <span class="indicator-label">Deliver</span>
                            <!--end::Indicator-->
                        </button>
                        <!--end::Submit-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Body-->
    </div>
@endsection

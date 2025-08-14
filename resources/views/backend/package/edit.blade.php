@extends('layouts.dashboard')

@section('package.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'users' => 'user.index',
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
                    <form action="{{ route('package.update', $package->id) }}" class="form mb-15" method="post"
                        id="kt_contact_form">
                        @csrf
                        @method('PUT')
                        <h1 class="fw-bolder text-dark mb-9">Update Package - {{ $package->name }}</h1>
                        @session('update_success')
                            <div class="alert alert-info" role="alert">
                                {{ session('update_success') }}
                            </div>
                        @endsession
                        <!--begin::Input group-->
                        <div class="row mb-5">
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="fs-5 fw-bold mb-2 required">Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text"
                                    class="form-control form-control-solid @error('name') border-danger is-invalid @enderror"
                                    placeholder="" name="name" value="{{ $package->name }}" />
                                <!--end::Input-->
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--end::Label-->
                                <label class="fs-5 fw-bold mb-2 required">Price</label>
                                <!--end::Label-->
                                <!--end::Input-->
                                <input type="number"
                                    class="form-control form-control-solid @error('price') border-danger is-invalid @enderror"
                                    placeholder="" name="price" value="{{ $package->price }}" />
                                <!--end::Input-->
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-10 fv-row">
                            <label class="fs-6 fw-bold mb-2">Description</label>
                            <textarea class="form-control form-control-solid" rows="2" name="description" placeholder="">{{ $package->description }}</textarea>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Submit-->
                        <button type="submit" class="btn btn-primary" id="kt_contact_submit_button">
                            <!--begin::Indicator-->
                            <span class="indicator-label">Update Package</span>
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

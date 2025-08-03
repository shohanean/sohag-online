@extends('layouts.dashboard')

@section('campaign.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'campaign list' => 'campaign.index',
            'create campaign' => 'campaign.create',
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
                    <form action="{{ route('campaign.store') }}" class="form mb-15" method="post" id="kt_contact_form">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-9">
                            <h1 class="fw-bolder text-dark mb-0">
                                Add Campaign
                            </h1>
                            <a href="{{ route('campaign.index') }}"
                            class="btn btn-primary">
                                Campaign List
                            </a>
                        </div>
                        @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endsession
                        <!--begin::Input group-->
                        <div class="row mb-5">
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--end::Label-->
                                <label class="fs-5 fw-bold mb-2 required">Page Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select" name="page_id">
                                    @foreach ($pages as $page)
                                        <optgroup label="{{ $page->first()->user->name }}">
                                            @foreach ($page as $p)
                                                <option value="{{ $p->id }}">{{ $p->page_name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="fs-5 fw-bold mb-2 required">Campaign Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text"
                                    class="form-control form-control-solid @error('name') border-danger is-invalid @enderror"
                                    placeholder="" name="name" value="{{ old('name') }}" />
                                <!--end::Input-->
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Submit-->
                        <button type="submit" class="btn btn-primary" id="kt_contact_submit_button">
                            <!--begin::Indicator-->
                            <span class="indicator-label">Add Campaign</span>
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

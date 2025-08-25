@extends('layouts.dashboard')

@section('package.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'Add Package' => 'package.index',
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
                    <form action="{{ route('package.store') }}" class="form mb-15" method="post" id="kt_contact_form">
                        @csrf
                        <h1 class="fw-bolder text-dark mb-9">Add Package</h1>
                        @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
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
                                    placeholder="" name="name" value="{{ old('name') }}" />
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
                                    placeholder="" name="price" value="{{ old('price') }}" />
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
                            <textarea class="form-control form-control-solid" rows="2" name="description" placeholder=""></textarea>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Submit-->
                        <button type="submit" class="btn btn-primary" id="kt_contact_submit_button">
                            <!--begin::Indicator-->
                            <span class="indicator-label">Add Package</span>
                            <!--end::Indicator-->
                        </button>
                        <!--end::Submit-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row g-5 mb-5 mb-lg-15">
                @session('destroy_success')
                    <div class="alert alert-danger" role="alert">
                        {{ session('destroy_success') }}
                    </div>
                @endsession
                @foreach ($packages as $package)
                    <!--begin::Col-->
                    <div class="col-sm-3 ps-lg-10">
                        <!--begin::Address-->
                        <div class="text-center bg-light card-rounded d-flex flex-column justify-content-center p-10 h-100">
                            @if ($package->id == 1)
                                <h5>
                                    <span class="badge bg-success rounded-pill">Default Package</span>
                                </h5>
                            @endif
                            <!--begin::Icon-->
                            <!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
                            <span class="svg-icon svg-icon-3tx svg-icon-primary">
                                <i class="fa fa-box fa-2x"></i>
                            </span>
                            <!--end::Svg Icon-->
                            <!--end::Icon-->
                            <!--begin::Subtitle-->
                            <h1 class="text-dark fw-bolder my-5">{{ $package->name }}</h1>
                            <!--end::Subtitle-->
                            <!--begin::Description-->
                            <div class="text-gray-700 fs-3 fw-bold">৳{{ $package->price }}</div>
                            <p>{{ $package->description }}</p>
                            <!--end::Description-->
                            <div class="d-flex m-auto">
                                <a href="{{ route('package.edit', $package->id) }}" class="btn btn-sm btn-dark me-2">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                @if ($package->id != 1)
                                    <form action="{{ route('package.destroy', $package->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <!--end::Address-->
                    </div>
                    <!--end::Col-->
                @endforeach
            </div>
            <!--end::Row-->
        </div>
        <!--end::Body-->
    </div>
@endsection

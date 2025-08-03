@extends('layouts.dashboard')

@section('doller.rate')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'doller rate' => 'doller.rate',
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
                    <form action="{{ route('doller.rate.insert') }}" class="form mb-15" method="post" id="kt_contact_form">
                        @csrf
                        <h1 class="fw-bolder text-dark mb-9">
                            Doller Rate
                            @if ($doller_rates->count() != 0)
                                - ৳{{ $doller_rates->first()->rate }}
                            @endif
                        </h1>
                        @if ($doller_rates->count() == 0)
                            <div class="alert alert-danger" role="alert">
                                No doller rate set yet
                            </div>
                        @endif
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
                            <div class="col-md-4 fv-row">
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
                            <div class="col-md-4 fv-row">
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
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">SL. No.</th>
                                <th scope="col">Date</th>
                                <th scope="col">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doller_rates as $doller_rate)
                                <tr class="">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $doller_rate->created_at }}</td>
                                    <td>{{ $doller_rate->rate }}</td>
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

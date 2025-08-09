@php
    error_reporting(0);
@endphp
@extends('layouts.dashboard')

@section('home')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'page details' => 'page.details',
        ],
    ])
@endsection

@section('content')
    <div class="card mb-5">
        <!--begin::Body-->
        <div class="card-body">
            <div class="row mb-3">
                <!--begin::Col-->
                <div class="col-md-12 pe-lg-10">
                    <!--begin::Form-->
                    <form action="" class="form mb-15" method="post" id="kt_contact_form">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-9">
                            <h1 class="fw-bolder text-dark mb-0">
                                Page Details - {{ $page->page_name }}
                            </h1>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                        {{-- @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endsession
                        <div class="row mb-5">
                            <!--begin::Col-->
                            <div class="col-md-12 fv-row">
                                <!--begin::Label-->
                                <label class="fs-5 fw-bold mb-2">Rev</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control" placeholder="" name="ad_id" value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--begin::Submit-->
                        <button type="submit" class="btn btn-primary" id="kt_contact_submit_button">
                            <!--begin::Indicator-->
                            <span class="indicator-label">Add Campaign</span>
                            <!--end::Indicator-->
                        </button>
                        <!--end::Submit--> --}}
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Col-->
            </div>
            <!--begin::Row-->
            <div class="row mb-3">
                <!--begin::Col-->
                <div class="col-md-12 pe-lg-10">
                    <div class="d-flex justify-content-between align-items-center mb-9">
                        <h1 class="fw-bolder text-dark mb-0">
                            Campaigns List
                        </h1>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <div class="row">
                <div class="table-responsive">
                    @session('update_success')
                        <div class="alert alert-warning">{{ session('update_success') }}</div>
                    @endsession
                    <table class="table table-bordered text-center">
                        <thead class="border fw-bold">
                            <tr>
                                <th>SL. No.</th>
                                <th>Campaign Name</th>
                                <th>Ad ID</th>
                                <th>Total</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody class="border">
                            @forelse ($campaigns as $campaign)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $campaign->name }}</td>
                                    <td>
                                        <span class="badge bg-secondary text-dark">{{ $campaign->ad_id }}</span>
                                    </td>
                                    <td>{{ $campaign->total }}</td>
                                    {{-- <td>
                                        <a class="btn btn-sm btn-success"
                                            href="{{ route('campaign.edit', $campaign->id) }}">Show</a>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr class="text-danger">
                                    <td colspan="50">Nothing to show here</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Body-->
    </div>
@endsection

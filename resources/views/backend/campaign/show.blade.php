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
    <div class="card mb-5">
        <!--begin::Body-->
        <div class="card-body">
            <div class="row mb-3">
                <!--begin::Col-->
                <div class="col-md-12 pe-lg-10">
                    <!--begin::Form-->
                    <form action="{{ route('campaign.store') }}" class="form mb-15" method="post" id="kt_contact_form">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-9">
                            <h1 class="fw-bolder text-dark mb-0">
                                Add Campaign for {{ $campaigns->first()->user->name }}
                            </h1>
                            <a href="{{ route('campaign.index') }}" class="btn btn-primary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                        @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endsession
                        <div class="row mb-5">
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--end::Label-->
                                <label class="fs-5 fw-bold mb-2 required">Page Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select @error('page_id') is-invalid @enderror" name="page_id">
                                    @foreach ($pages as $page)
                                        <option value="{{ $page->id }}">{{ $page->page_name }}</option>
                                    @endforeach
                                </select>
                                @error('page_id')
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
                                <input type="text" class="form-control @error('name') border-danger is-invalid @enderror"
                                    placeholder="" name="name" value="{{ old('name') }}" />
                                <!--end::Input-->
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-5">
                            <!--begin::Col-->
                            <div class="col-md-12 fv-row">
                                <!--begin::Label-->
                                <label class="fs-5 fw-bold mb-2">Ad ID</label>
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
                        <!--end::Submit-->
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
                            Campaign Details
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
                    <table class="table table-bordered">
                        <thead class="border fw-bold">
                            <tr>
                                <th>Page Owner</th>
                                <th>Page Name</th>
                                <th>Campaign Name</th>
                                <th>Ad ID</th>
                                <th>Total</th>
                                <th>Last Updated</th>
                                <th>Running Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            @foreach ($campaigns->sortByDesc('running_status') as $campaign)
                                <tr>
                                    <td>{{ $campaign->page->user->name }}</td>
                                    <td>{{ $campaign->page->page_name }}</td>
                                    <td>{{ $campaign->name }}</td>
                                    <td>
                                        <span class="badge bg-secondary text-dark">{{ $campaign->ad_id }}</span>
                                    </td>
                                    <td>{{ $campaign->total }}</td>
                                    <td>
                                        {{ $campaign->transection()->latest()->first()?->updated_at?->format('jS F, Y') }}
                                        <br>
                                        <div class="badge bg-secondary text-dark">
                                            {{ $campaign->transection()->latest()->first()?->updated_at?->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('change.running.status', $campaign->id) }}" method="POST">
                                            @csrf
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="runningStatus"
                                                    name="runningStatus" @if ($campaign->running_status) checked @endif
                                                    onchange="this.form.submit()">
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal_{{ $campaign->id }}">
                                            Edit
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal_{{ $campaign->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit -
                                                            {{ $campaign->name }}</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('campaign.update', $campaign->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label for="campaign_name" class="form-label">Campaign
                                                                    Name</label>
                                                                <input type="text" class="form-control"
                                                                    id="campaign_name" name="campaign_name"
                                                                    value="{{ $campaign->name }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="ad_id" class="form-label">Ad ID</label>
                                                                <input type="text" class="form-control" id="ad_id"
                                                                    name="ad_id" value="{{ $campaign->ad_id }}">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                Changes</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="btn btn-sm btn-success"
                                            href="{{ route('campaign.edit', $campaign->id) }}">Show</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Body-->
    </div>
@endsection

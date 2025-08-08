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
            <!--begin::Row-->
            <div class="row mb-3">
                <!--begin::Col-->
                <div class="col-md-12 pe-lg-10">
                    <div class="d-flex justify-content-between align-items-center mb-9">
                        <h1 class="fw-bolder text-dark mb-0">
                            Campaign Details
                        </h1>
                        <a href="{{ route('campaign.index') }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="border fw-bold">
                            <tr>
                                <th>Page Owner</th>
                                <th>Page Name</th>
                                <th>Campaign Name</th>
                                <th>Ad ID</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            @foreach ($campaigns as $campaign)
                            <tr>
                                <td>{{ $campaign->page->user->name }}</td>
                                <td>{{ $campaign->page->page_name }}</td>
                                <td>{{ $campaign->name }}</td>
                                <td>
                                    <span class="badge bg-secondary text-dark">{{ $campaign->ad_id }}</span>
                                </td>
                                <td>{{ $campaign->total }}</td>
                                <td>
                                    <a class="btn btn-sm btn-success" href="{{ route('campaign.edit', $campaign->id) }}">Show</a>
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


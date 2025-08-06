@extends('layouts.dashboard')

@section('campaign.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'campaign list' => 'package.index',
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-17">
            <!--begin::Row-->
            <div class="row g-5 mb-5 mb-lg-15">
                <div class="d-flex justify-content-between align-items-center mb-9">
                    <h1 class="fw-bolder text-dark mb-0">
                        Campaign List
                    </h1>
                    <a href="{{ route('campaign.create') }}" class="btn btn-primary">
                        Add Campaign
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="fw-bold">
                            <tr>
                                <th>SL. No.</th>
                                <th>Name</th>
                                <th>Campaign</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($campaigns as $userId => $userCampaigns)
                                <tr>
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        {{ $userCampaigns->first()->user->name }}
                                    </td>
                                    <td>
                                        {{ $userCampaigns->count() }}
                                    </td>
                                    <td>
                                        {{ $userCampaigns->sum('total') }}
                                    </td>
                                    <td>
                                        {{ $userCampaigns->sum('paid') }}
                                    </td>
                                    <td>
                                        {{ $userCampaigns->sum('due') }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success">Details</button>
                                        @foreach ($userCampaigns as $campaign)
                                            <a href="{{ route('campaign.show', $campaign->id) }}">Show</a>
                                        @endforeach
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="50" class="text-danger">Nothing to show here</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Body-->
    </div>
@endsection

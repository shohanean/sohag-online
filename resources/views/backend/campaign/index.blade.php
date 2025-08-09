@extends('layouts.dashboard')

@section('campaign.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'campaign list' => 'campaign.index',
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
                        Clients List
                    </h1>
                    <a href="{{ route('campaign.create') }}" class="btn btn-primary">
                        Add Campaign
                    </a>
                </div>

                <div class="table-responsive">
                    @session('delete_success')
                        <div class="alert alert-danger">{{ session('delete_success') }}</div>
                    @endsession
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
                                @if (empty($userCampaigns->first()->user->deleted_at))
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
                                            {{ $userCampaigns->first()->user->client_wallet->total }}
                                        </td>
                                        <td>
                                            {{ $userCampaigns->first()->user->client_wallet->paid }}
                                        </td>
                                        <td>
                                            {{ $userCampaigns->first()->user->client_wallet->due }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form
                                                    action="{{ route('user.destroy', $userCampaigns->first()->user->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        onclick="return confirm('Are you sure you want to delete this item?')"
                                                        class="btn btn-sm btn-danger" type="submit">Delete</button>
                                                </form>
                                                <a href="{{ route('payment.index', $userId) }}"
                                                    class="btn btn-sm btn-info">Payment</a>
                                                <a href="{{ route('campaign.show', $userId) }}"
                                                    class="btn btn-sm btn-success">Details</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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

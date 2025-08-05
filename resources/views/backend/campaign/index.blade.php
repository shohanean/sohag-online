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
                    <a href="{{ route('campaign.create') }}"
                    class="btn btn-primary">
                        Add Campaign
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="fw-bold">
                            <tr>
                                <th>SL. No.</th>
                                <th>Name</th>
                                <th>Page Name</th>
                                <th>Campaign Name</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($campaigns as $user_id => $camp)
                                <tr class="border border-info">
                                    <td colspan="50">{{ App\Models\User::find($user_id)->name }}</td>
                                </tr>
                                @foreach ($camp as $campaign)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td><i class="fa fa-user"></i> {{ $campaign->user->name }}</td>
                                        <td><i class="fab fa-facebook-square"></i> {{ $campaign->page->page_name }}</td>
                                        <td>{{ $campaign->name }}</td>
                                        <td>{{ $campaign->total }}</td>
                                        <td>{{ $campaign->paid }}</td>
                                        <td>{{ $campaign->due }}</td>
                                        <td>
                                            <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-sm bg-secondary">Details</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                            <tr class="text-center">
                                <td colspan="50" class="text-danger">Nothing to show here</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="fw-bold">
                            <tr>
                                <th colspan="4">Sub Total</th>
                                <th>{{ $campaigns->sum('total') }}</th>
                                <th>{{ $campaigns->sum('paid') }}</th>
                                <th>{{ $campaigns->sum('due') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Body-->
    </div>
@endsection

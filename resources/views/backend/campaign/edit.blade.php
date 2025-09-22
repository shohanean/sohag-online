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
                        <a href="{{ route('campaign.show', $campaign->user_id) }}" class="btn btn-primary">
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
                                <th>Campaign Creation Date</th>
                                <th>Page Owner</th>
                                <th>Page Name</th>
                                <th>Campaign Name</th>
                                <th>Ad ID</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <tr>
                                <td>{{ $campaign->created_at->format('d/m/Y') }}</td>
                                <td>{{ $campaign->page->user->name }}</td>
                                <td>{{ $campaign->page->page_name }}</td>
                                <td>{{ $campaign->name }}</td>
                                <td>
                                    <span id="copyText" class="badge bg-secondary text-dark">{{ $campaign->ad_id }}</span>
                                    <i id="copy_btn" class="fa fa-copy ms-2" style="cursor:pointer;"
                                        onclick="copyText()"></i>
                                    <script>
                                        function copyText() {
                                            const text = document.getElementById("copyText").innerText;
                                            navigator.clipboard.writeText(text).then(() => {
                                                document.getElementById("copy_btn").classList.add('text-success');
                                            }).catch(err => {
                                                console.error("Failed to copy: ", err);
                                            });
                                        }
                                    </script>
                                </td>
                                <td>{{ $campaign->total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Body-->
    </div>
    <div>
        <div class="row mt-9">
            <!--begin::Col-->
            <div class="col-xxl-12">
                <!--begin::Earnings-->
                <div class="card card-xxl-stretch mb-5 mb-xxl-10">
                    <!--begin::Header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h3>Expense</h3>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pb-0">
                        @session('success')
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endsession

                        <form action="{{ route('add.expense', $campaign->id) }}" method="POST">
                            @csrf
                            <div class="row pb-9">
                                <div class="col-md-3">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold mb-2 required">Date</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        placeholder="" name="date" value="{{ \Carbon\Carbon::now()->toDateString() }}" />
                                    <!--end::Input-->
                                    @error('date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold mb-2 required">Amount ($)</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            Last:
                                            <span
                                                id="old_amount">{{ $transections->where('transaction_type', 'expense')->first()?->current_amount ?? 0 }}</span>
                                        </span>
                                        <input id="payment_amount" type="number"
                                            class="form-control @error('amount') is-invalid @enderror" name="amount"
                                            value="{{ old('amount') }}"
                                            min="{{ $transections->where('transaction_type', 'expense')->first()?->current_amount ?? 0 }}"
                                            step="0.01" />
                                        <span class="input-group-text">x{{ $dollar_rate->rate }}=<span
                                                id="xxx">{{ $dollar_rate->rate }}</span></span>
                                        @error('amount')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <div class="col-md-3">
                                    <label class="fs-5 fw-bold mb-2"> &nbsp; </label>
                                    <!--begin::Submit-->
                                    <button type="submit" class="btn btn-info form-control">
                                        <!--begin::Indicator-->
                                        <span class="indicator-label">Expense</span>
                                        <!--end::Indicator-->
                                    </button>
                                    <!--end::Submit-->
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Earnings-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xxl-5 d-none">
                <!--begin::Earnings-->
                <div class="card card-xxl-stretch mb-5 mb-xxl-10">
                    <!--begin::Header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h3>Payment</h3>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pb-0">
                        @session('psuccess')
                            <div class="alert alert-success" role="alert">
                                {{ session('psuccess') }}
                            </div>
                        @endsession
                        <form action="{{ route('add.payment', $campaign->id) }}" method="POST">
                            @csrf
                            <div class="row pb-9">
                                <div class="col-md-4">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold mb-2 required">Date</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="date" class="form-control @error('pdate') is-invalid @enderror"
                                        placeholder="" name="pdate"
                                        value="{{ \Carbon\Carbon::now()->toDateString() }}" />
                                    <!--end::Input-->
                                    @error('pdate')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold mb-2 required">Amount</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control @error('pamount') is-invalid @enderror"
                                        placeholder="" name="pamount" value="{{ old('pamount') }}" />
                                    <!--end::Input-->
                                    @error('pamount')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="fs-5 fw-bold mb-2"> &nbsp; </label>
                                    <!--begin::Submit-->
                                    <button @if ($transections->count() == 0) disabled @endif type="submit"
                                        class="btn btn-success form-control">
                                        <!--begin::Indicator-->
                                        <span class="indicator-label">Payment</span>
                                        <!--end::Indicator-->
                                    </button>
                                    <!--end::Submit-->
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Earnings-->
            </div>
            <!--end::Col-->
        </div>
    </div>
    <div class="card mb-xl-8">
        <!--begin::Header-->
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">Campaign Payment Details</span>
            </h3>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body py-3">
            @session('delete_success')
                <div class="alert alert-danger" role="alert">
                    {{ session('delete_success') }}
                </div>
            @endsession
            <!--begin::Table container-->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="fw-bold">
                        <tr>
                            <th>SL. No.</th>
                            <th>Date</th>
                            <th>Old Amount</th>
                            <th>Current Amount</th>
                            <th>Spent Amount</th>
                            <th>Dollar Rate</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transections as $transection)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $transection->date }}</td>
                                <td>{{ $transection->old_amount }}</td>
                                <td>{{ $transection->current_amount }}</td>
                                <td>{{ $transection->spent_amount }}</td>
                                <td>{{ $transection->dollar_rate }}</td>
                                <td>{{ $transection->amount }}</td>
                                <td>
                                    <span
                                        class="badge @if ($transection->transaction_type == 'expense') bg-info
                                    @else
                                        bg-success @endif">{{ Str::title($transection->transaction_type) }}</span>
                                </td>
                                <td>
                                    {{ $transection->created_at->diffForHumans() }}
                                    @if ($loop->index == 0)
                                        <form action="{{ route('campaign.destroy', $transection->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this item?')"
                                                type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
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
            <!--end::Table container-->
        </div>
        <!--begin::Body-->
    </div>
@endsection

@section('footer_scripts')
    <script>
        $(document).ready(function() {
            $('#payment_amount').on('keyup', function() {
                let value = $(this).val();
                let old_amount = $('#old_amount').html();
                let dollar_rate = "{{ $dollar_rate->rate }}";
                $('#xxx').html(parseFloat((value - old_amount) * dollar_rate).toFixed(2));
            });
        });
    </script>
@endsection

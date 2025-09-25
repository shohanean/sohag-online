@extends('layouts.dashboard')

@section('work.index')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'work' => 'work.index',
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-17">
            <!--begin::Row-->
            <div class="row g-5 mb-5 mb-lg-15">
                <h1 class="fw-bolder text-dark mb-9">
                    Work List
                </h1>
                <div class="table-responsive">
                    @session('update_success')
                        <div class="alert alert-success">{{ session('update_success') }}</div>
                    @endsession
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Subscription Details</th>
                                <th>Charge</th>
                                <th>Taken By</th>
                                <th>Trx ID</th>
                                <th>Screenshot</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($works as $work)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        Package Name: {{ $work->subscription->package_name }}
                                        <br>
                                        Package Price: {{ $work->subscription->package_price }}
                                        <br>
                                        Domain Name: {{ $work->subscription->domain_name }}
                                    </td>
                                    <td>{{ $work->charge }}</td>
                                    <td>{{ $work->user->name ?? '-' }}</td>
                                    <td>{{ $work->trx_id ?? '-' }}</td>
                                    <td>{{ $work->screenshot ?? '-' }}</td>
                                    <td>
                                        @if ($work->status == 'running')
                                            <span class="badge bg-primary">{{ Str::title($work->status) }}</span>
                                        @elseif ($work->status == 'open')
                                            <span class="badge bg-info">{{ Str::title($work->status) }}</span>
                                        @else
                                            <span class="badge bg-success">{{ Str::title($work->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Button trigger modal -->
                                            @if ($work->status != 'delivered')
                                                <button type="button"
                                                    class="btn btn-sm @if ($work->status == 'running') btn-secondary @else btn-warning @endif"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal_{{ $work->id }}">
                                                    @if ($work->status == 'running')
                                                        Reassign
                                                    @else
                                                        Assign
                                                    @endif
                                                </button>
                                            @endif
                                            <!-- Modal -->
                                            <div class="modal
                                                    fade"
                                                id="exampleModal_{{ $work->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Assign
                                                                Worker
                                                            </h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('work.update', $work->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label class="form-label">Worker Name</label>
                                                                    <select class="form-select" name="user_id">
                                                                        <option value="">-Select One Worker Name-
                                                                        </option>
                                                                        @foreach ($workers as $worker)
                                                                            <option
                                                                                @if ($worker->id == $work->user_id) selected @endif
                                                                                value="{{ $worker->id }}">
                                                                                {{ $worker->name }} -
                                                                                {{ $worker->email }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Assign
                                                                    Worker</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="50" class="text-center text-danger">Nothing to show here</td>
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

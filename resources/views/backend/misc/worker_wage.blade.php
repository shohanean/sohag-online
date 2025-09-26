@extends('layouts.dashboard')

@section('worker.wage')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'worker wage' => 'worker.wage',
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
                    Worker Wage
                </h1>
                <div class="table-responsive">
                    @session('update_success')
                        <div class="alert alert-success">{{ session('update_success') }}</div>
                    @endsession
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Wage</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($workers as $worker)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $worker->name }}</td>
                                    <td>{{ $worker->email }}</td>
                                    <td>{{ $worker->worker_wage?->wage ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('worker.wage.post') }}" method="POST">
                                            @csrf
                                            <div class="d-flex gap-2">
                                                <input class="form-control" type="hidden" name="user_id"
                                                    value="{{ $worker->id }}">
                                                <input class="form-control" type="number" name="wage">
                                                <button class="btn btn-sm bg-success">Set Wage</button>
                                            </div>
                                        </form>
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

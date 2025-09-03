@extends('layouts.dashboard')

@section('active.clients')
    active
@endsection

@section('toolbar')
    @includeIf('parts.toolbar', [
        'links' => [
            'home' => 'home',
            'active clients' => 'active.clients',
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
                    Active Clients List
                    <input type="text" id="searchInput" placeholder="Search Here..." style="margin-left:10px;">
                </h1>
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endforeach
                @session('success')
                    <div class="alert alert-info">
                        {{ session('success') }}
                    </div>
                @endsession
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Page Name</th>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>New Password</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <form action="{{ route('change.client.info', $client->id) }}" method="POST">
                                    @csrf
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <span class="d-none">{{ $client->page?->first()?->page_name }}</span>
                                            <input hidden class="form-control" type="text" name="page_id"
                                                value="{{ $client->page?->first()?->id }}">
                                            <input class="form-control" type="text" name="new_page_name"
                                                value="{{ $client->page?->first()?->page_name }}">
                                        </td>
                                        <td>
                                            <span class="d-none">{{ $client->name }}</span>
                                            <input class="form-control" type="text" name="new_name"
                                                value="{{ $client->name }}">
                                        </td>
                                        <td>
                                            <span class="d-none">{{ $client->email }}</span>
                                            <input class="form-control" type="email" name="new_email"
                                                value="{{ $client->email }}">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" name="new_password">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary">Change</button>
                                        </td>
                                    </tr>
                                </form>
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

@section('footer_scripts')
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#myTable tbody tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
@endsection

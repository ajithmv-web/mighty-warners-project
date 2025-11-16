<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Import Results</h2>
    </x-slot>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Import Summary</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Successful Imports</h6>
                            <h2 class="display-4 mb-0">{{ $successCount }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Failed Imports</h6>
                            <h2 class="display-4 mb-0">{{ $failureCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($errors))
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Errors</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm table-striped">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Row</th>
                                <th>Field</th>
                                <th>Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($errors as $error)
                                <tr>
                                    <td>{{ $error['row'] ?? 'N/A' }}</td>
                                    <td>{{ $error['field'] ?? 'N/A' }}</td>
                                    <td>{{ $error['error'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.import') }}" class="btn btn-primary">
            <i class="bi bi-upload"></i> Import More Products
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-success">
            <i class="bi bi-list"></i> View Products
        </a>
    </div>
</x-app-layout>

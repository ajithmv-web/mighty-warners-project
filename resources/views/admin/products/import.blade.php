<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Import Products</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <div class="card bg-light mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Example Excel Format</strong>
                    <a href="{{ route('admin.products.export.sample') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-download"></i> Download Sample File
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.products.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="file" class="form-label">Upload Excel File <span class="text-danger">*</span></label>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv"
                        class="form-control @error('file') is-invalid @enderror" required>
                    <div class="form-text">Supported formats: XLSX, XLS, CSV (Max: 10MB)</div>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Import Products
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

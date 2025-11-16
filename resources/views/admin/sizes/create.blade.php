<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Create Size</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.sizes.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                        class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Create Size
                    </button>
                    <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

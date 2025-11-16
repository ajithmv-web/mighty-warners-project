<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Colors</h2>
            <a href="{{ route('admin.colors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Color
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Hex Code</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($colors as $color)
                            <tr>
                                <td>{{ $color->id }}</td>
                                <td>{{ $color->name }}</td>
                                <td>
                                    @if($color->hex_code)
                                        <div class="d-flex align-items-center">
                                            <div style="width: 30px; height: 30px; background-color: {{ $color->hex_code }}; border: 1px solid #ddd; border-radius: 4px;" class="me-2"></div>
                                            {{ $color->hex_code }}
                                        </div>
                                    @endif
                                </td>
                                <td><span class="badge bg-info">{{ $color->products_count }}</span></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.colors.edit', $color) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.colors.destroy', $color) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $colors->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
</x-app-layout>

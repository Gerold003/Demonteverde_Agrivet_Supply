@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Stock In Records</h1>
        <a href="{{ route('inventory.stock-in.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-2"></i>Record Stock In
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Recent Stock In Transactions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Recorded By</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockIns as $stockIn)
                            <tr>
                                <td>{{ $stockIn->created_at->format('M j, Y g:i A') }}</td>
                                <td>
                                    <strong>{{ $stockIn->product->name }}</strong>
                                    @if($stockIn->product->brand)
                                        <br><small class="text-muted">{{ $stockIn->product->brand }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($stockIn->supplier)
                                        {{ $stockIn->supplier->name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success fs-6">{{ $stockIn->quantity }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ strtoupper($stockIn->unit) }}</span>
                                </td>
                                <td>{{ $stockIn->inventoryStaff->name }}</td>
                                <td>
                                    @if($stockIn->notes)
                                        <span title="{{ $stockIn->notes }}">
                                            {{ Str::limit($stockIn->notes, 30) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-arrow-down-circle display-4"></i>
                                        <p class="mt-2">No stock in records found.</p>
                                        <a href="{{ route('inventory.stock-in.create') }}" class="btn btn-success">
                                            <i class="bi bi-plus-circle me-2"></i>Record Your First Stock In
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($stockIns instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-4">
                    {{ $stockIns->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

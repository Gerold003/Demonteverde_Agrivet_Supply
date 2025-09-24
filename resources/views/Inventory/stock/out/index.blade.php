@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Stock Out Records</h1>
        <a href="{{ route('inventory.stock-out.create') }}" class="btn btn-warning">
            <i class="bi bi-plus-circle me-2"></i>Record Stock Out
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
            <h5>Recent Stock Out Transactions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Reason</th>
                            <th>Recorded By</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockOuts as $stockOut)
                            <tr>
                                <td>{{ $stockOut->created_at->format('M j, Y g:i A') }}</td>
                                <td>
                                    <strong>{{ $stockOut->product->name }}</strong>
                                    @if($stockOut->product->brand)
                                        <br><small class="text-muted">{{ $stockOut->product->brand }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-danger fs-6">{{ $stockOut->quantity }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ strtoupper($stockOut->unit) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $stockOut->reason === 'damaged' ? 'danger' : ($stockOut->reason === 'expired' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($stockOut->reason) }}
                                    </span>
                                </td>
                                <td>{{ $stockOut->inventoryStaff->name }}</td>
                                <td>
                                    @if($stockOut->notes)
                                        <span title="{{ $stockOut->notes }}">
                                            {{ Str::limit($stockOut->notes, 30) }}
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
                                        <i class="bi bi-arrow-up-circle display-4"></i>
                                        <p class="mt-2">No stock out records found.</p>
                                        <a href="{{ route('inventory.stock-out.create') }}" class="btn btn-warning">
                                            <i class="bi bi-plus-circle me-2"></i>Record Your First Stock Out
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($stockOuts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-4">
                    {{ $stockOuts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

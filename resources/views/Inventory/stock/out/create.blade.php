@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Record Stock Out</h1>
        <a href="{{ route('inventory.stock-out.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Stock Out Records
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Stock Out Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('inventory.stock-out.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="product_id" class="form-label">Product *</label>
                                <select class="form-select @error('product_id') is-invalid @enderror"
                                        id="product_id" name="product_id" required>
                                    <option value="">Select a product...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                            @if($product->brand)
                                                ({{ $product->brand }})
                                            @endif
                                            - Stock: {{ $product->current_stock_sack }} sacks
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity *</label>
                                <input type="number" min="1" step="0.01"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="unit" class="form-label">Unit *</label>
                                <select class="form-select @error('unit') is-invalid @enderror"
                                        id="unit" name="unit" required>
                                    <option value="">Select unit...</option>
                                    <option value="sack" {{ old('unit') == 'sack' ? 'selected' : '' }}>Sack</option>
                                    <option value="kilo" {{ old('unit') == 'kilo' ? 'selected' : '' }}>Kilo</option>
                                    <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="reason" class="form-label">Reason *</label>
                                <select class="form-select @error('reason') is-invalid @enderror"
                                        id="reason" name="reason" required>
                                    <option value="">Select reason...</option>
                                    <option value="damaged" {{ old('reason') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                    <option value="expired" {{ old('reason') == 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="lost" {{ old('reason') == 'lost' ? 'selected' : '' }}>Lost</option>
                                    <option value="returned" {{ old('reason') == 'returned' ? 'selected' : '' }}>Returned to Supplier</option>
                                    <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Additional notes about this stock out...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This action will reduce the product stock. Make sure this stock out is necessary and properly documented.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('inventory.stock-out.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-dash-circle me-2"></i>Record Stock Out
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

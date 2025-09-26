@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Record Stock In</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventory.stock-in.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="product_id" class="form-label">Product <span class="text-danger"></span></label>
                                <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                            @if($product->brand)
                                                ({{ $product->brand }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="supplier_name" class="form-label">Supplier Name <span class="text-danger"></span></label>
                                <input type="text" name="supplier_name" id="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" required>
                                @error('supplier_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger"></span></label>
                                <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1" step="0.01" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="unit" class="form-label">Unit <span class="text-danger"></span></label>
                                <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                    <option value="">Select Unit</option>
                                    <option value="piece">Piece</option>
                                    <option value="sack">Sack</option>
                                    <option value="kilo">Kilo</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Optional notes about this stock in..."></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('inventory.stock-in.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Stock In Records
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Record Stock In
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

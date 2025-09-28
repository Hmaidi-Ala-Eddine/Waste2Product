@extends('layouts.back')

@section('title', 'Créer un Produit')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Create Product</h6>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Nom du produit</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                </div>
                                @error('name')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Catégorie</label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" name="category" value="{{ old('category') }}" required>
                                </div>
                                @error('category')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" required>{{ old('description') }}</textarea>
                                </div>
                                @error('description')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Condition</label>
                                    <select class="form-control @error('condition') is-invalid @enderror" name="condition" required>
                                        <option value="">Sélectionner une condition</option>
                                        <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>Neuf</option>
                                        <option value="refurbished" {{ old('condition') == 'refurbished' ? 'selected' : '' }}>Reconditionné</option>
                                        <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Occasion</option>
                                    </select>
                                </div>
                                @error('condition')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Prix (€)</label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}">
                                </div>
                                @error('price')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Statut</label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        <option value="">Sélectionner un statut</option>
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                                        <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Vendu</option>
                                        <option value="donated" {{ old('status') == 'donated' ? 'selected' : '' }}>Donné</option>
                                        <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Réservé</option>
                                    </select>
                                </div>
                                @error('status')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Propriétaire</label>
                                    <select class="form-control @error('user_id') is-invalid @enderror" name="user_id" required>
                                        <option value="">Sélectionner un propriétaire</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Demande de déchets (optionnel)</label>
                                    <select class="form-control @error('waste_request_id') is-invalid @enderror" name="waste_request_id">
                                        <option value="">Aucune demande</option>
                                        @foreach($wasteRequests as $wasteRequest)
                                            <option value="{{ $wasteRequest->id }}" {{ old('waste_request_id') == $wasteRequest->id ? 'selected' : '' }}>{{ $wasteRequest->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('waste_request_id')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Image du produit</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">
                                </div>
                                @error('image')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn bg-gradient-dark">Create Product</button>
                                <a href="{{ route('admin.products.index') }}" class="btn bg-gradient-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

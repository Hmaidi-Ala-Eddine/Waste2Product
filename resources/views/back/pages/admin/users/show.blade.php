@extends('layouts.back')

@section('title', 'Détails de l\'Utilisateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Détails de l'Utilisateur</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Informations personnelles</h5>
                                    <p><strong>Nom:</strong> {{ $user->name }}</p>
                                    <p><strong>Email:</strong> {{ $user->email }}</p>
                                    <p><strong>Date d'inscription:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                                    <p><strong>Dernière mise à jour:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Statut</h5>
                                    @if($user->is_banned)
                                        <p><strong>Statut:</strong> <span class="badge bg-gradient-danger">Banni</span></p>
                                        <p><strong>Date de bannissement:</strong> {{ $user->banned_at->format('d/m/Y H:i') }}</p>
                                        <p><strong>Raison:</strong> {{ $user->ban_reason }}</p>
                                        @if($user->bannedBy)
                                            <p><strong>Banni par:</strong> {{ $user->bannedBy->name }}</p>
                                        @endif
                                    @else
                                        <p><strong>Statut:</strong> <span class="badge bg-gradient-success">Actif</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn bg-gradient-warning">Modifier</a>
                                @if($user->is_banned)
                                    <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn bg-gradient-success">Débannir</button>
                                    </form>
                                @else
                                    <button type="button" class="btn bg-gradient-danger" data-toggle="modal" data-target="#banModal">
                                        Bannir
                                    </button>
                                @endif
                                <a href="{{ route('admin.users.index') }}" class="btn bg-gradient-secondary">Retour</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ban Modal -->
<div class="modal fade" id="banModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bannir l'utilisateur</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ban_reason">Raison du bannissement</label>
                        <textarea class="form-control" id="ban_reason" name="ban_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Bannir</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

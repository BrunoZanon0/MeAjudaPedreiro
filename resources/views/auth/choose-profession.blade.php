@extends('layouts.app')

@section('title', 'Escolha sua Profissão')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="bi bi-question-circle"></i> Complete seu cadastro</h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" class="rounded-circle" width="100" height="100">
                    @endif
                    <h3 class="mt-3">Olá, {{ Auth::user()->name }}!</h3>
                    <p>Por favor, escolha sua profissão para continuar:</p>
                </div>

                <form action="{{ route('update.profession') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Quem você é? *</label>
                        <div class="border rounded p-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" 
                                    name="profession" value="consultor" 
                                    id="consultor" required>
                                <label class="form-check-label" for="consultor">
                                    <i class="bi bi-chat-dots text-white"></i> <span  class="text-white">Consultor</span>
                                    <small class="text-white d-block">Busco por profissionais (pedreiros)</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" 
                                    name="profession" value="pedreiro" 
                                    id="pedreiro" required>
                                <label class="form-check-label" for="pedreiro">
                                    <i class="bi bi-tools text-white"></i> <span class="text-white">Pedreiro</span>
                                    <small class="text-white d-block">Ofereço meus serviços</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Continuar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
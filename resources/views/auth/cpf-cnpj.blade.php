@extends('layouts.app')

@section('title', 'Completar CPF/CNPJ')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card w-100" style="max-width: 420px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-white">Complete seus dados</h2>
                <p class="text-white">Informe CPF ou CNPJ para continuar.</p>
            </div>

            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cpf.cnpj.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="CPF" class="form-label">CPF</label>
                    <input type="text"
                           id="CPF"
                           name="CPF"
                           class="form-control @error('CPF') is-invalid @enderror"
                           value="{{ old('CPF', auth()->user()->CPF) }}"
                           placeholder="000.000.000-00">
                    @error('CPF')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="CNPJ" class="form-label">CNPJ</label>
                    <input type="text"
                           id="CNPJ"
                           name="CNPJ"
                           class="form-control @error('CNPJ') is-invalid @enderror"
                           value="{{ old('CNPJ', auth()->user()->CNPJ) }}"
                           placeholder="00.000.000/0000-00">
                    @error('CNPJ')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Salvar CPF/CNPJ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
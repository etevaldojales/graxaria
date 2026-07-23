@extends('mobile.layout')

@section('content')
<div class="card" style="margin-top: 20px; box-shadow: var(--shadow-lg);">
    <div style="text-align: center; margin-bottom: 24px;">
        <span style="font-size: 48px;">🔐</span>
        <h2 style="font-size: 22px; font-weight: 700; margin-top: 12px; color: var(--primary);">Entrar no Sistema</h2>
        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Escolha seu nome e digite a sua senha</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-error" style="margin-bottom: 20px;">
            <span>⚠️</span>
            <ul style="list-style: none; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mobile.login.post') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label class="form-label" for="user_id">Selecione seu Nome:</label>
            <select name="user_id" id="user_id" class="form-control" required style="height: 60px; font-size: 16px; font-weight: 600; cursor: pointer;">
                <option value="" disabled selected>Clique para escolher...</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('user_id') == $driver->id ? 'selected' : '' }}>
                        {{ $driver->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Digite sua Senha:</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Sua senha..." required style="height: 60px; font-size: 18px; letter-spacing: 2px;">
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 10px; height: 60px;">
            <span>🔑</span> Entrar agora
        </button>
    </form>
</div>
@endsection

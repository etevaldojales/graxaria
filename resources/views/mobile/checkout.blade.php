@extends('mobile.layout')

@section('content')
<div style="margin-bottom: 10px;">
    <h1 style="font-size: 22px; font-weight: 700; color: var(--danger);">Finalizar Viagem</h1>
    <p style="font-size: 14px; color: var(--text-muted);">Registre as informações de retorno do veículo</p>
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

<div class="card" style="margin-bottom: 16px; border: 1px solid #fee2e2; background-color: #fff8f8;">
    <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 8px;">Dados do Veículo Ativo</div>
    <div style="font-size: 20px; font-weight: 700; color: var(--text);">{{ $activeCheckin->vehicle->plate }}</div>
    <div style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">{{ $activeCheckin->vehicle->brand_model }}</div>
    <div style="font-size: 14px; color: var(--text-muted); margin-top: 8px;">
        🏁 Odômetro de Partida: <strong>{{ number_format($activeCheckin->odometer_start, 0, ',', '.') }} KM</strong>
    </div>
</div>

<form action="{{ route('mobile.checkout.post') }}" method="POST">
    @csrf

    <div class="card" style="margin-bottom: 16px; box-shadow: var(--shadow-sm);">
        <div class="card-title">🏁 Odômetro Final</div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" for="odometer_end">Digite o Odômetro Final (KM):</label>
            <input type="number" name="odometer_end" id="odometer_end" class="form-control" placeholder="KM atual do painel..." required style="height: 60px; font-size: 18px; font-weight: 700;" min="{{ $activeCheckin->odometer_start }}">
            <small style="color: var(--text-muted); display: block; margin-top: 6px;">
                Deve ser maior ou igual a <strong>{{ number_format($activeCheckin->odometer_start, 0, ',', '.') }} KM</strong>.
            </small>
        </div>
    </div>

    <div class="card" style="margin-bottom: 16px; box-shadow: var(--shadow-sm);">
        <div class="card-title">📝 Observações de Retorno</div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" for="obs">Relate alguma ocorrência na viagem (Opcional):</label>
            <textarea name="obs" id="obs" class="form-control" placeholder="Escreva aqui se o carro apresentou barulhos, furos de pneus, etc..."></textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-danger" style="height: 64px; font-size: 18px; margin-top: 10px; margin-bottom: 30px; box-shadow: var(--shadow-lg);">
        🛑 Registrar Retorno e Finalizar
    </button>
</form>
@endsection

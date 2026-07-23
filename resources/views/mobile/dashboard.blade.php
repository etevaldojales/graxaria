@extends('mobile.layout')

@section('content')
<!-- Driver Welcome Header -->
<div style="margin-bottom: 10px;">
    <h3 style="font-size: 16px; font-weight: 500; color: var(--text-muted);">Olá, motorista</h3>
    <h1 style="font-size: 24px; font-weight: 700; color: var(--primary-dark);">{{ Auth::user()->name }}</h1>
</div>

<!-- Active Trip Status Card -->
@if($activeCheckin)
    <div class="card" style="border: 2px solid var(--success); background-color: #f0fdf4; box-shadow: var(--shadow-md);">
        <div class="card-title" style="color: #15803d; margin-bottom: 16px;">
            <span style="font-size: 22px;">🚚</span> Viagem Ativa - Liberado
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; font-size: 14px;">
            <div style="background: white; padding: 12px; border-radius: var(--radius-sm); border: 1px solid #e2e8f0;">
                <span style="color: var(--text-muted); font-size: 11px; display: block; font-weight: 600; text-transform: uppercase;">Veículo</span>
                <strong style="font-size: 16px; color: var(--text);">{{ $activeCheckin->vehicle->plate }}</strong>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">{{ $activeCheckin->vehicle->brand_model }}</span>
            </div>
            
            <div style="background: white; padding: 12px; border-radius: var(--radius-sm); border: 1px solid #e2e8f0;">
                <span style="color: var(--text-muted); font-size: 11px; display: block; font-weight: 600; text-transform: uppercase;">Ajudante(s)</span>
                <strong style="font-size: 15px; color: var(--text);">
                    {{ $activeCheckin->helper ? explode(' ', $activeCheckin->helper->name)[0] : 'Nenhum' }}
                    @if($activeCheckin->helper2)
                        / {{ explode(' ', $activeCheckin->helper2->name)[0] }}
                    @endif
                </strong>
            </div>

            <div style="background: white; padding: 12px; border-radius: var(--radius-sm); border: 1px solid #e2e8f0;">
                <span style="color: var(--text-muted); font-size: 11px; display: block; font-weight: 600; text-transform: uppercase;">Odômetro Inicial</span>
                <strong style="font-size: 16px; color: var(--text);">{{ number_format($activeCheckin->odometer_start, 0, ',', '.') }} KM</strong>
            </div>

            <div style="background: white; padding: 12px; border-radius: var(--radius-sm); border: 1px solid #e2e8f0;">
                <span style="color: var(--text-muted); font-size: 11px; display: block; font-weight: 600; text-transform: uppercase;">Qtd. Bombonas</span>
                <strong style="font-size: 16px; color: var(--text);">{{ $activeCheckin->num_drums }}</strong>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a href="{{ route('mobile.collection') }}" class="btn btn-success" style="height: 64px; font-size: 18px;">
                <span style="font-size: 24px;">➕</span> Registrar Coleta
            </a>
            
            <a href="{{ route('mobile.checkout') }}" class="btn btn-danger" style="height: 56px; font-size: 15px; background-color: #b91c1c;">
                <span style="font-size: 18px;">🛑</span> Finalizar Viagem / Trocar Veículo
            </a>
        </div>
    </div>
@else
    <div class="card" style="border: 2px solid var(--warning); background-color: #fffbeb; box-shadow: var(--shadow-md);">
        <div class="card-title" style="color: #b45309; margin-bottom: 12px;">
            <span style="font-size: 22px;">⚠️</span> Nenhuma viagem iniciada
        </div>
        
        <p style="font-size: 14px; color: #78350f; margin-bottom: 20px; line-height: 1.5;">
            Antes de registrar qualquer coleta ou sair com o veículo, você deve realizar o check-in e verificar as condições de segurança.
        </p>

        <a href="{{ route('mobile.checkin') }}" class="btn btn-primary" style="height: 64px; font-size: 18px; background-color: #0369a1;">
            <span style="font-size: 24px;">🚛</span> Iniciar Viagem (Check-in)
        </a>
    </div>
@endif

<!-- Commissions Summary Card -->
<div class="card" style="box-shadow: var(--shadow-sm);">
    <div class="card-title">
        <span style="font-size: 20px;">💰</span> Minhas Comissões
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
        <div style="background-color: #f8fafc; padding: 16px; border-radius: var(--radius-sm); border: 1px solid #e2e8f0; text-align: center;">
            <span style="color: var(--text-muted); font-size: 12px; font-weight: 600; display: block; margin-bottom: 4px;">Comissão de Hoje</span>
            <strong style="font-size: 20px; color: var(--success);">R$ {{ number_format($commissions['today'], 2, ',', '.') }}</strong>
        </div>
        
        <div style="background-color: #f8fafc; padding: 16px; border-radius: var(--radius-sm); border: 1px solid #e2e8f0; text-align: center;">
            <span style="color: var(--text-muted); font-size: 12px; font-weight: 600; display: block; margin-bottom: 4px;">Este Mês</span>
            <strong style="font-size: 20px; color: var(--primary);">R$ {{ number_format($commissions['month'], 2, ',', '.') }}</strong>
        </div>
    </div>

    <a href="{{ route('mobile.commissions') }}" class="btn btn-outline" style="height: 50px; font-size: 14px; border-color: var(--primary); color: var(--primary);">
        🔍 Ver Extrato Detalhado
    </a>
</div>
@endsection

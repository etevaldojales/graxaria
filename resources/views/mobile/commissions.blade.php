@extends('mobile.layout')

@section('content')
<div style="margin-bottom: 10px;">
    <h1 style="font-size: 22px; font-weight: 700; color: var(--primary-dark);">Extrato de Comissões</h1>
    <p style="font-size: 14px; color: var(--text-muted);">Veja o detalhamento dos seus valores acumulados</p>
</div>

<!-- Header Card: Big Numbers -->
<div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border: none; box-shadow: var(--shadow-md);">
    <div style="text-align: center; padding: 10px 0;">
        <span style="font-size: 14px; opacity: 0.8; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Comissão Acumulada</span>
        <h1 style="font-size: 32px; font-weight: 800; margin-top: 5px;">R$ {{ number_format($commissions['total'], 2, ',', '.') }}</h1>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.2); padding-top: 15px;">
        <div style="text-align: center;">
            <span style="font-size: 11px; opacity: 0.8; font-weight: 600;">Hoje</span>
            <div style="font-size: 16px; font-weight: 700; margin-top: 3px;">R$ {{ number_format($commissions['today'], 2, ',', '.') }}</div>
        </div>
        <div style="text-align: center; border-left: 1px solid rgba(255, 255, 255, 0.2);">
            <span style="font-size: 11px; opacity: 0.8; font-weight: 600;">Mês Atual</span>
            <div style="font-size: 16px; font-weight: 700; margin-top: 3px;">R$ {{ number_format($commissions['month'], 2, ',', '.') }}</div>
        </div>
    </div>
</div>

<!-- Detailed Log -->
<div class="card" style="box-shadow: var(--shadow-sm); padding: 15px 10px;">
    <div class="card-title" style="padding-left: 10px; margin-bottom: 16px;">
        📋 Histórico de Coletas Feitas
    </div>

    @if(empty($commissions['details']))
        <div style="text-align: center; padding: 40px 20px; color: var(--text-muted); font-size: 14px;">
            ⚠️ Nenhuma comissão registrada até o momento.
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @foreach($commissions['details'] as $col)
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 14px; display: flex; flex-direction: column; gap: 8px;">
                    <!-- Date & Value Header -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                        <div>
                            <strong style="font-size: 15px; color: var(--text);">{{ $col['supplier'] }}</strong>
                            <span style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 2px;">{{ $col['date'] }}</span>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 15px; font-weight: 800; color: var(--success);">+ R$ {{ number_format($col['total_commission'], 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Items breakdown -->
                    <div style="display: flex; flex-direction: column; gap: 4px; padding-top: 2px;">
                        @foreach($col['items'] as $item)
                            <div style="display: flex; justify-content: space-between; font-size: 13px; color: var(--text-muted);">
                                <span>• {{ $item['residue'] }} ({{ number_format($item['weight'], 2, ',', '.') }} kg)</span>
                                <span style="font-weight: 500; color: var(--text);">R$ {{ number_format($item['total'], 2, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

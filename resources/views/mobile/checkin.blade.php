@extends('mobile.layout')

@section('content')
<div style="margin-bottom: 10px;">
    <h1 style="font-size: 22px; font-weight: 700; color: var(--primary-dark);">Check-in de Viagem</h1>
    <p style="font-size: 14px; color: var(--text-muted);">Realize o checklist de segurança antes de iniciar</p>
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

<form action="{{ route('mobile.checkin.post') }}" method="POST" id="checkin-form">
    @csrf

    <!-- Card 1: Veículo e Ajudantes -->
    <div class="card" style="margin-bottom: 16px; box-shadow: var(--shadow-sm);">
        <div class="card-title">🛡️ Viagem & Equipe</div>
        
        <div class="form-group">
            <label class="form-label" for="vehicle_id">Selecione o Veículo:</label>
            <select name="vehicle_id" id="vehicle_id" class="form-control" required style="height: 60px; font-size: 16px; font-weight: 600; cursor: pointer;">
                <option value="" disabled selected>Escolha o veículo...</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}">{{ $vehicle->plate }} - {{ $vehicle->brand_model }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" for="helper_id">Selecione o Ajudante 1 (Opcional):</label>
            <select name="helper_id" id="helper_id" class="form-control" style="height: 60px; font-size: 16px;">
                <option value="">Nenhum ajudante</option>
                @foreach($helpers as $helper)
                    <option value="{{ $helper->id }}">{{ $helper->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" for="helper_2_id">Selecione o Ajudante 2 (Opcional):</label>
            <select name="helper_2_id" id="helper_2_id" class="form-control" style="height: 60px; font-size: 16px;">
                <option value="">Nenhum ajudante</option>
                @foreach($helpers as $helper)
                    <option value="{{ $helper->id }}">{{ $helper->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Card 2: Carga e Odômetro -->
    <div class="card" style="margin-bottom: 16px; box-shadow: var(--shadow-sm);">
        <div class="card-title">📊 Odômetro & Bombonas</div>

        <div class="form-group">
            <label class="form-label" for="odometer_start">Odômetro Inicial (KM):</label>
            <input type="number" name="odometer_start" id="odometer_start" class="form-control" placeholder="Carregando..." required style="height: 60px; font-size: 18px; font-weight: 700;">
            <small style="color: var(--text-muted); display: block; margin-top: 4px;">Pode ser ajustado se necessário</small>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Quantidade de Bombonas (Tambores):</label>
            <div style="display: flex; align-items: center; justify-content: center; gap: 20px; padding: 10px 0;">
                <button type="button" onclick="changeDrums(-5)" style="width: 50px; height: 50px; border-radius: 50%; border: none; background: #cbd5e1; font-size: 18px; font-weight: 800; cursor: pointer;">-5</button>
                <button type="button" onclick="changeDrums(-1)" style="width: 50px; height: 50px; border-radius: 50%; border: none; background: #cbd5e1; font-size: 24px; font-weight: 800; cursor: pointer;">-</button>
                
                <input type="number" id="num_drums" name="num_drums" value="0" readonly style="width: 70px; height: 50px; text-align: center; font-size: 20px; font-weight: bold; border: 2px solid #cbd5e1; border-radius: 8px;">
                
                <button type="button" onclick="changeDrums(1)" style="width: 50px; height: 50px; border-radius: 50%; border: none; background: #cbd5e1; font-size: 24px; font-weight: 800; cursor: pointer;">+</button>
                <button type="button" onclick="changeDrums(5)" style="width: 50px; height: 50px; border-radius: 50%; border: none; background: #cbd5e1; font-size: 18px; font-weight: 800; cursor: pointer;">+5</button>
            </div>
        </div>
    </div>

    <!-- Card 3: Checklist de Segurança -->
    <div class="card" style="margin-bottom: 16px; box-shadow: var(--shadow-sm);">
        <div class="card-title">🔍 Checklist de Segurança</div>
        
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Clique no item se estiver com problema (verde significa OK)</p>

        <!-- Check 1 -->
        <label class="checkbox-container checked" id="lbl_check_tires">
            <span class="checkbox-label">Pneus em bom estado</span>
            <input type="checkbox" name="check_tires" id="check_tires" value="1" checked onchange="toggleCheck('tires')">
            <span class="checkbox-switch"></span>
        </label>

        <!-- Check 2 -->
        <label class="checkbox-container checked" id="lbl_check_brakes">
            <span class="checkbox-label">Freios funcionando</span>
            <input type="checkbox" name="check_brakes" id="check_brakes" value="1" checked onchange="toggleCheck('brakes')">
            <span class="checkbox-switch"></span>
        </label>

        <!-- Check 3 -->
        <label class="checkbox-container checked" id="lbl_check_lights">
            <span class="checkbox-label">Faróis e lanternas OK</span>
            <input type="checkbox" name="check_lights" id="check_lights" value="1" checked onchange="toggleCheck('lights')">
            <span class="checkbox-switch"></span>
        </label>

        <!-- Check 4 -->
        <label class="checkbox-container checked" id="lbl_check_oil">
            <span class="checkbox-label">Óleo e água OK</span>
            <input type="checkbox" name="check_oil" id="check_oil" value="1" checked onchange="toggleCheck('oil')">
            <span class="checkbox-switch"></span>
        </label>

        <!-- Check 5 -->
        <label class="checkbox-container checked" id="lbl_check_wipers">
            <span class="checkbox-label">Limpador de para-brisa OK</span>
            <input type="checkbox" name="check_wipers" id="check_wipers" value="1" checked onchange="toggleCheck('wipers')">
            <span class="checkbox-switch"></span>
        </label>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

        <!-- Blocker Toggle -->
        <label class="checkbox-container" id="lbl_is_impeditivo" style="background-color: #fff1f2; border: 1px solid #fecdd3;">
            <span class="checkbox-label" style="color: #9f1239;">⚠️ Problema Impeditivo?</span>
            <input type="checkbox" name="is_impeditivo" id="is_impeditivo" value="1" onchange="toggleImpeditivo()">
            <span class="checkbox-switch" style="background-color: #fca5a5;"></span>
        </label>
        <span style="font-size: 12px; color: #9f1239; display: block; margin-top: -6px; margin-bottom: 12px; font-weight: 500;">
            (Marque se o veículo estiver quebrado e não puder sair)
        </span>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" for="obs">Observações do veículo (Opcional):</label>
            <textarea name="obs" id="obs" class="form-control" placeholder="Escreva aqui se encontrar algum problema..."></textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-success" style="height: 64px; font-size: 18px; margin-top: 10px; margin-bottom: 30px; box-shadow: var(--shadow-lg);">
        💾 Gravar e Iniciar Viagem
    </button>
</form>
@endsection

@section('scripts')
<script>
    // Adjust drums count with safety bounds
    function changeDrums(amount) {
        var input = document.getElementById('num_drums');
        var val = parseInt(input.value) + amount;
        if (val < 0) val = 0;
        input.value = val;
    }

    // Toggle checklist visual states
    function toggleCheck(field) {
        var checkbox = document.getElementById('check_' + field);
        var container = document.getElementById('lbl_check_' + field);
        if(checkbox.checked) {
            container.classList.add('checked');
        } else {
            container.classList.remove('checked');
        }
    }

    function toggleImpeditivo() {
        var checkbox = document.getElementById('is_impeditivo');
        var container = document.getElementById('lbl_is_impeditivo');
        if(checkbox.checked) {
            container.classList.add('checked');
            container.style.borderColor = '#ef4444';
            container.style.backgroundColor = '#fef2f2';
        } else {
            container.classList.remove('checked');
            container.style.borderColor = '#fecdd3';
            container.style.backgroundColor = '#fff1f2';
        }
    }

    // Fetch odometer when vehicle selected
    document.getElementById('vehicle_id').addEventListener('change', function() {
        var vehicleId = this.value;
        var odoInput = document.getElementById('odometer_start');
        
        odoInput.value = '';
        odoInput.placeholder = 'Buscando odômetro...';
        
        fetch('/mobile/vehicle/' + vehicleId + '/odometer')
            .then(response => response.json())
            .then(data => {
                odoInput.value = data.odometer;
                odoInput.placeholder = '';
            })
            .catch(error => {
                odoInput.value = 0;
                odoInput.placeholder = '';
                console.error('Erro ao buscar odômetro:', error);
            });
    });
</script>
@endsection

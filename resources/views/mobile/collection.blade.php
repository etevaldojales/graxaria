@extends('mobile.layout')

@section('content')
<div style="margin-bottom: 10px;">
    <h1 style="font-size: 22px; font-weight: 700; color: var(--primary-dark);">Registrar Coleta</h1>
    <p style="font-size: 14px; color: var(--text-muted);">Lance o peso dos resíduos recolhidos no fornecedor</p>
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

<form action="{{ route('mobile.collection.post') }}" method="POST" id="collection-form">
    @csrf

    <!-- Card 1: Fornecedor -->
    <div class="card" style="margin-bottom: 16px; box-shadow: var(--shadow-sm);">
        <div class="card-title">🏢 Fornecedor (Origem)</div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" for="supplier_id">Selecione o Fornecedor:</label>
            <input type="text" id="supplier_search" class="form-control" placeholder="🔍 Digite para buscar..." style="margin-bottom: 8px; height: 50px; font-size: 15px;">
            <select name="supplier_id" id="supplier_id" class="form-control" required style="height: 60px; font-size: 15px; font-weight: 600; cursor: pointer;">
                <option value="" disabled selected>Escolha o fornecedor...</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Card 2: Lançamento de Itens -->
    <div class="card" style="margin-bottom: 16px; box-shadow: var(--shadow-sm);">
        <div class="card-title">⚖️ Adicionar Peso de Resíduo</div>
        
        <div class="form-group">
            <label class="form-label" for="temp_residue_id">Tipo de Resíduo:</label>
            <select id="temp_residue_id" class="form-control" style="height: 60px; font-size: 16px; font-weight: 600; cursor: pointer;">
                <option value="" disabled selected>Escolha o tipo...</option>
                @foreach($residues as $residue)
                    <option value="{{ $residue->id }}">{{ $residue->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" for="temp_weight">Peso em Quilos (KG):</label>
            <input type="number" id="temp_weight" class="form-control" placeholder="0.00" step="0.01" min="0.01" inputmode="decimal" style="height: 60px; font-size: 20px; font-weight: 700; text-align: center;">
        </div>

        <button type="button" onclick="addItem()" class="btn btn-secondary" style="height: 56px; font-size: 16px; background-color: var(--secondary);">
            ➕ Adicionar à Lista
        </button>
    </div>

    <!-- Card 3: Lista de Itens Coletados -->
    <div class="card" style="margin-bottom: 16px; box-shadow: var(--shadow-sm);">
        <div class="card-title">📋 Itens na Coleta Atual</div>
        
        <div id="no-items-warning" style="text-align: center; padding: 20px 0; color: var(--text-muted); font-size: 14px;">
            Nenhum resíduo adicionado ainda. Adicione acima.
        </div>

        <div id="items-list" style="display: flex; flex-direction: column; gap: 8px;">
            <!-- Dinamicamente inserido via JS -->
        </div>

        <div id="total-weight-container" style="display: none; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 15px; border-top: 2px solid #e2e8f0;">
            <strong style="font-size: 16px; color: var(--text);">Total Geral:</strong>
            <strong id="total-weight-label" style="font-size: 20px; color: var(--primary);">0.00 KG</strong>
        </div>
    </div>

    <!-- Hidden input templates array container -->
    <div id="hidden-inputs-container"></div>

    <button type="submit" class="btn btn-success" id="submit-btn" disabled style="height: 64px; font-size: 18px; margin-top: 10px; margin-bottom: 30px; opacity: 0.6; box-shadow: var(--shadow-lg);">
        💾 Salvar Coleta Completa
    </button>
</form>
@endsection

@section('scripts')
<script>
    // Master search filter for suppliers dropdown
    var originalSuppliers = [];
    window.addEventListener('DOMContentLoaded', (event) => {
        var select = document.getElementById('supplier_id');
        for (var i = 0; i < select.options.length; i++) {
            var opt = select.options[i];
            originalSuppliers.push({
                value: opt.value,
                text: opt.text,
                disabled: opt.disabled,
                selected: opt.selected
            });
        }
    });

    document.getElementById('supplier_search').addEventListener('input', function() {
        var query = this.value.toLowerCase().trim();
        var select = document.getElementById('supplier_id');
        
        // Rebuild select options
        select.innerHTML = '';
        originalSuppliers.forEach(function(opt) {
            // Keep disabled option (first placeholder) or options matching search
            if (opt.value === "" || opt.text.toLowerCase().includes(query)) {
                var newOpt = document.createElement('option');
                newOpt.value = opt.value;
                newOpt.text = opt.text;
                newOpt.disabled = opt.disabled;
                newOpt.selected = opt.selected;
                select.appendChild(newOpt);
            }
        });
    });

    // In-memory collections list
    var items = [];

    function addItem() {
        var residueSelect = document.getElementById('temp_residue_id');
        var weightInput = document.getElementById('temp_weight');

        var residueId = residueSelect.value;
        var residueName = residueSelect.options[residueSelect.selectedIndex]?.text;
        var weight = parseFloat(weightInput.value);

        if (!residueId) {
            alert('Por favor, selecione o tipo de resíduo.');
            return;
        }

        if (isNaN(weight) || weight <= 0) {
            alert('Por favor, digite um peso válido maior que zero.');
            return;
        }

        // Check if item already added
        var existing = items.find(i => i.residue_id === residueId);
        if (existing) {
            existing.weight += weight;
        } else {
            items.push({
                residue_id: residueId,
                residue_name: residueName,
                weight: weight
            });
        }

        // Clear values
        residueSelect.selectedIndex = 0;
        weightInput.value = '';

        renderItems();
    }

    function removeItem(index) {
        items.splice(index, 1);
        renderItems();
    }

    function renderItems() {
        var listContainer = document.getElementById('items-list');
        var noItemsWarning = document.getElementById('no-items-warning');
        var totalContainer = document.getElementById('total-weight-container');
        var totalLabel = document.getElementById('total-weight-label');
        var hiddenContainer = document.getElementById('hidden-inputs-container');
        var submitBtn = document.getElementById('submit-btn');

        // Reset
        listContainer.innerHTML = '';
        hiddenContainer.innerHTML = '';

        if (items.length === 0) {
            noItemsWarning.style.display = 'block';
            totalContainer.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
            return;
        }

        noItemsWarning.style.display = 'none';
        totalContainer.style.display = 'flex';
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';

        var totalWeight = 0;

        items.forEach((item, index) => {
            totalWeight += item.weight;

            // HTML visual row
            var row = document.createElement('div');
            row.style.display = 'flex';
            row.style.justify = 'space-between';
            row.style.alignItems = 'center';
            row.style.padding = '12px 16px';
            row.style.backgroundColor = '#f8fafc';
            row.style.border = '1px solid #e2e8f0';
            row.style.borderRadius = 'var(--radius-sm)';

            row.innerHTML = `
                <div>
                    <strong style="color: var(--primary-dark); font-size: 15px;">${item.residue_name}</strong>
                    <span style="font-size: 14px; color: var(--text-muted); display: block; margin-top: 2px;">Peso: <strong>${item.weight.toFixed(2)} KG</strong></span>
                </div>
                <button type="button" onclick="removeItem(${index})" style="background: none; border: none; color: var(--danger); font-size: 14px; font-weight: 700; cursor: pointer; padding: 6px 12px;">
                    ❌ Remover
                </button>
            `;
            listContainer.appendChild(row);

            // Hidden inputs for form submit
            var inputResidue = document.createElement('input');
            inputResidue.type = 'hidden';
            inputResidue.name = `items[${index}][residue_id]`;
            inputResidue.value = item.residue_id;
            hiddenContainer.appendChild(inputResidue);

            var inputWeight = document.createElement('input');
            inputWeight.type = 'hidden';
            inputWeight.name = `items[${index}][weight]`;
            inputWeight.value = item.weight.toFixed(2);
            hiddenContainer.appendChild(inputWeight);
        });

        totalLabel.innerText = totalWeight.toFixed(2) + ' KG';
    }
</script>
@endsection

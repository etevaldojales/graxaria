<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Análise - Farinha de Carne e Ossos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 5px 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; color: #666; }
        .section-title { font-weight: bold; text-transform: uppercase; background-color: #f2f2f2; padding: 5px; margin: 15px 0 8px 0; border: 1px solid #ccc; font-size: 11px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .info-table td { padding: 4px; vertical-align: top; }
        .footer { margin-top: 50px; }
        .signatures { width: 100%; border-collapse: collapse; margin-top: 30px; }
        .signatures td { width: 50%; text-align: center; vertical-align: top; padding: 10px; }
        .signature-line { border-top: 1px solid #000; width: 80%; margin: 0 auto 5px auto; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SisGraxaria</h1>
        <p>PROGRAMA DE AUTOCONTROLE (PAC) / S.I.F. (SERVIÇO DE INSPEÇÃO FEDERAL)</p>
        <p>CERTIFICADO DE ANÁLISE DE FARINHA DE CARNE E OSSOS</p>
    </div>

    <div class="section-title">Dados de Expedição e Controle</div>
    <table class="info-table">
        <tr>
            <td><strong>Cliente:</strong> {{ $cert->client->name }}</td>
            <td><strong>Nota Fiscal:</strong> {{ $cert->invoice_number }}</td>
        </tr>
        <tr>
            <td><strong>Data de Análise:</strong> {{ $cert->analysis_date ? $cert->analysis_date->format('d/m/Y') : '' }}</td>
            <td><strong>Revisão Nº:</strong> {{ $cert->revisao_number }}</td>
        </tr>
        <tr>
            <td><strong>Peso Expedido:</strong> {{ number_format($cert->weight, 2, ',', '.') }} KG</td>
            <td><strong>Número do Lacre:</strong> {{ $cert->seal_number }}</td>
        </tr>
    </table>

    <div class="section-title">Dados do Transporte</div>
    <table class="info-table">
        <tr>
            <td><strong>Placa do Veículo:</strong> {{ $cert->vehicle_plate }}</td>
            <td><strong>Motorista:</strong> {{ $cert->driver_name }}</td>
        </tr>
        <tr>
            <td><strong>CPF do Motorista:</strong> {{ $cert->driver_cpf }}</td>
            <td></td>
        </tr>
    </table>

    <div class="section-title">Controle de Não-Conformidades e Ações Corretivas</div>
    <table class="info-table">
        <tr>
            <td><strong>Descrição de Não-Conformidades:</strong></td>
        </tr>
        <tr>
            <td style="padding-left: 15px; padding-bottom: 10px;">{{ $cert->non_conformities ?: 'Nenhuma ocorrência registrada durante o carregamento.' }}</td>
        </tr>
        <tr>
            <td><strong>Ações Corretivas Aplicadas:</strong></td>
        </tr>
        <tr>
            <td style="padding-left: 15px; padding-bottom: 10px;">{{ $cert->corrective_actions ?: 'Não aplicável.' }}</td>
        </tr>
        <tr>
            <td><strong>Verificação / Eficácia:</strong></td>
        </tr>
        <tr>
            <td style="padding-left: 15px; padding-bottom: 10px;">{{ $cert->verification ?: 'Carga inspecionada e liberada sob conformidade higiênica.' }}</td>
        </tr>
    </table>

    <div class="footer">
        <table class="signatures">
            <tr>
                <td>
                    <div class="signature-line"></div>
                    <p><strong>Controle de Qualidade</strong></p>
                    <p>Responsável pelo Laboratório</p>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <p><strong>Garantia de Qualidade SIF</strong></p>
                    <p>Responsável Técnico</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Análise - Sebo Bovino</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 5px 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; color: #666; }
        .section-title { font-weight: bold; text-transform: uppercase; background-color: #f2f2f2; padding: 5px; margin: 15px 0 8px 0; border: 1px solid #ccc; font-size: 11px; }
        .info-table, .results-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .info-table td { padding: 4px; vertical-align: top; }
        .results-table th, .results-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .results-table th { background-color: #f9f9f9; font-weight: bold; }
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
        <p>CERTIFICADO DE ANÁLISE DE SEBO BOVINO INDUSTRIAL</p>
    </div>

    <div class="section-title">Identificação da Carga</div>
    <table class="info-table">
        <tr>
            <td><strong>Cliente:</strong> {{ $cert->client->name }}</td>
            <td><strong>Nota Fiscal:</strong> {{ $cert->invoice_number }}</td>
        </tr>
        <tr>
            <td><strong>Placa do Veículo:</strong> {{ $cert->vehicle_plate }}</td>
            <td><strong>Transportadora:</strong> {{ $cert->carrier_name }}</td>
        </tr>
        <tr>
            <td><strong>Data de Expedição:</strong> {{ $cert->shipping_date ? $cert->shipping_date->format('d/m/Y') : '' }}</td>
            <td><strong>Data de Análise:</strong> {{ $cert->analysis_date ? $cert->analysis_date->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
            <td><strong>Data de Produção:</strong> {{ $cert->production_date }}</td>
            <td><strong>Validade:</strong> {{ $cert->expiry_info }}</td>
        </tr>
        <tr>
            <td><strong>Número do Lacre:</strong> {{ $cert->seal_number }}</td>
            <td></td>
        </tr>
    </table>

    <div class="section-title">Resultados das Análises Físico-Químicas</div>
    <table class="results-table">
        <thead>
            <tr>
                <th>Parâmetro Analisado</th>
                <th>Especificação Requerida</th>
                <th>Resultado Obtido</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Aspecto</strong></td>
                <td>Límpido e Isento de Impurezas</td>
                <td>{{ $cert->result_aspect }}</td>
            </tr>
            <tr>
                <td><strong>Acidez Livre (A.G.L. em ácido oleico)</strong></td>
                <td>Máximo 2,0%</td>
                <td>{{ $cert->result_acidity }}</td>
            </tr>
            <tr>
                <td><strong>Impurezas Totais</strong></td>
                <td>Máximo 1,0%</td>
                <td>{{ $cert->result_impurities }}</td>
            </tr>
            <tr>
                <td><strong>Odor</strong></td>
                <td>Característico</td>
                <td>{{ $cert->result_odor }}</td>
            </tr>
            <tr>
                <td><strong>Umidade e Matéria Volátil</strong></td>
                <td>Máximo 0,5%</td>
                <td>{{ $cert->result_moisture }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Vistoria de Higiene do Meio de Transporte</div>
    <table class="info-table">
        <tr>
            <td><strong>Limpeza Externa do Caminhão:</strong> {{ $cert->inspected_clean_external ? 'CONFORME (Limpo)' : 'NÃO CONFORME' }}</td>
        </tr>
        <tr>
            <td><strong>Limpeza Interna do Tanque:</strong> {{ $cert->inspected_clean_internal ? 'CONFORME (Limpo/Isento de resíduos)' : 'NÃO CONFORME' }}</td>
        </tr>
        <tr>
            <td><strong>Secagem do Tanque:</strong> {{ $cert->inspected_dry_internal ? 'CONFORME (Seco)' : 'NÃO CONFORME' }}</td>
        </tr>
        <tr>
            <td><strong>Status de Liberação:</strong> <strong>{{ $cert->is_released ? 'CARGA LIBERADA PARA EMBARQUE' : 'REPROVADO' }}</strong></td>
        </tr>
    </table>

    <div class="footer">
        <table class="signatures">
            <tr>
                <td>
                    <div class="signature-line"></div>
                    <p><strong>{{ $cert->qa_responsible }}</strong></p>
                    <p>Responsável pelo Laboratório</p>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <p><strong>{{ $cert->technical_responsible }}</strong></p>
                    <p>Garantia da Qualidade SIF/PAC</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

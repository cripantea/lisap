<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Report Vendite {{ $nomeMese }} {{ $anno }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
        }
        .header .subtitle {
            color: #666;
            margin-top: 5px;
        }
        .info-box {
            background: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #2563eb;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            background: #dbeafe !important;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            background: #10b981;
            color: white;
            border-radius: 3px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORT VENDITE MENSILE</h1>
        <div class="subtitle">{{ $nomeMese }} {{ $anno }}</div>
    </div>

    <div class="info-box">
        <strong>Data Generazione:</strong> {{ $dataGenerazione->format('d/m/Y H:i') }}<br>
        <strong>Periodo:</strong> {{ $nomeMese }} {{ $anno }}<br>
        <strong>Agenti con vendite:</strong> {{ count($datiReport) }}
    </div>

    @if(count($datiReport) > 0)
        <table>
            <thead>
                <tr>
                    <th>Codice</th>
                    <th>Agente</th>
                    <th class="text-center">N. Ordini</th>
                    <th class="text-right">Importo Ordini</th>
                    <th class="text-center">% Prov.</th>
                    <th class="text-right">Provvigioni</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totaleOrdini = 0;
                    $totaleImporto = 0;
                    $totaleProvvigioni = 0;
                @endphp

                @foreach($datiReport as $riga)
                    @php
                        $totaleOrdini += $riga['num_ordini'];
                        $totaleImporto += $riga['importo_ordini'];
                        $totaleProvvigioni += $riga['importo_provvigioni'];
                    @endphp
                    <tr>
                        <td>{{ $riga['agente']->codice }}</td>
                        <td>
                            {{ $riga['agente']->nome_completo }}
                            <br><small style="color: #666;">{{ $riga['agente']->email }}</small>
                        </td>
                        <td class="text-center">{{ $riga['num_ordini'] }}</td>
                        <td class="text-right">€ {{ number_format($riga['importo_ordini'], 2, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($riga['agente']->percentuale_provvigione, 2) }}%</td>
                        <td class="text-right">€ {{ number_format($riga['importo_provvigioni'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach

                <tr class="totals">
                    <td colspan="2"><strong>TOTALI</strong></td>
                    <td class="text-center"><strong>{{ $totaleOrdini }}</strong></td>
                    <td class="text-right"><strong>€ {{ number_format($totaleImporto, 2, ',', '.') }}</strong></td>
                    <td class="text-center">-</td>
                    <td class="text-right"><strong>€ {{ number_format($totaleProvvigioni, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Dettaglio Top 5 -->
        <h3 style="color: #2563eb; margin-top: 30px;">Top 5 Agenti del Mese</h3>
        <table>
            <thead>
                <tr>
                    <th>Posizione</th>
                    <th>Agente</th>
                    <th class="text-right">Provvigioni Maturate</th>
                </tr>
            </thead>
            <tbody>
                @foreach(collect($datiReport)->sortByDesc('importo_provvigioni')->take(5) as $index => $riga)
                    <tr>
                        <td class="text-center">
                            @if($index == 0)
                                🥇
                            @elseif($index == 1)
                                🥈
                            @elseif($index == 2)
                                🥉
                            @else
                                {{ $index + 1 }}°
                            @endif
                        </td>
                        <td>
                            <strong>{{ $riga['agente']->nome_completo }}</strong>
                            <br><small>{{ $riga['num_ordini'] }} ordini</small>
                        </td>
                        <td class="text-right">€ {{ number_format($riga['importo_provvigioni'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <p style="font-size: 18px;">Nessuna vendita registrata per questo periodo</p>
        </div>
    @endif

    <div class="footer">
        <p>LISAP - Sistema Gestione Ordini Multi-Piattaforma</p>
        <p>Report generato automaticamente - {{ $dataGenerazione->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>


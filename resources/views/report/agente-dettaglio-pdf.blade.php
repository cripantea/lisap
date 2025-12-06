<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Report {{ $agente->nome_completo }} - {{ $anno }}{{ $mese ? '/'.$mese : '' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2563eb;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 22px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            padding: 5px 10px;
            background: #f3f4f6;
            font-weight: bold;
            width: 30%;
        }
        .info-value {
            display: table-cell;
            padding: 5px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .stats-box {
            background: #dbeafe;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .stats-box .stat {
            display: inline-block;
            margin: 0 20px;
        }
        .stats-box .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
        }
        .stats-box .stat-label {
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background: #2563eb;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
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
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-amazon { background: #fef3c7; color: #92400e; }
        .badge-ebay { background: #dbeafe; color: #1e3a8a; }
        .badge-shopify { background: #d1fae5; color: #065f46; }
        .badge-tiktok { background: #e9d5ff; color: #6b21a8; }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 9px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORT AGENTE</h1>
        <div>{{ $agente->nome_completo }}</div>
        <div style="color: #666; font-size: 10px;">
            Periodo: {{ $mese ? \Carbon\Carbon::create()->month($mese)->locale('it')->monthName : 'Anno completo' }} {{ $anno }}
        </div>
    </div>

    <!-- Info Agente -->
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Codice Agente</div>
            <div class="info-value">{{ $agente->codice }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $agente->email }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Telefono</div>
            <div class="info-value">{{ $agente->telefono ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">% Provvigione</div>
            <div class="info-value">{{ number_format($agente->percentuale_provvigione, 2) }}%</div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="stats-box">
        <div class="stat">
            <div class="stat-value">{{ $totaleOrdini }}</div>
            <div class="stat-label">ORDINI</div>
        </div>
        <div class="stat">
            <div class="stat-value">€ {{ number_format($importoTotale, 2, ',', '.') }}</div>
            <div class="stat-label">FATTURATO</div>
        </div>
        <div class="stat">
            <div class="stat-value">€ {{ number_format($totaleProvvigioni, 2, ',', '.') }}</div>
            <div class="stat-label">PROVVIGIONI</div>
        </div>
    </div>

    @if($ordini->count() > 0)
        <!-- Dettaglio Ordini -->
        <h3 style="color: #2563eb; margin-top: 25px; font-size: 14px;">Dettaglio Ordini ({{ $ordini->count() }})</h3>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>N. Ordine</th>
                    <th>Piattaforma</th>
                    <th>Cliente</th>
                    <th>CAP/Città</th>
                    <th class="text-right">Importo</th>
                    <th>Stato</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordini as $ordine)
                    <tr>
                        <td>{{ $ordine->data_ordine->format('d/m/Y') }}</td>
                        <td style="font-family: monospace; font-size: 9px;">{{ $ordine->numero_ordine }}</td>
                        <td>
                            <span class="badge badge-{{ $ordine->piattaforma }}">
                                {{ ucfirst($ordine->piattaforma) }}
                            </span>
                        </td>
                        <td>{{ $ordine->cliente_completo }}</td>
                        <td>{{ $ordine->cap }} - {{ $ordine->citta }}</td>
                        <td class="text-right">€ {{ number_format($ordine->importo_totale, 2, ',', '.') }}</td>
                        <td style="font-size: 9px;">{{ str_replace('_', ' ', ucfirst($ordine->stato)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Riepilogo per Piattaforma -->
        <h3 style="color: #2563eb; margin-top: 25px; font-size: 14px;">Riepilogo per Piattaforma</h3>
        <table>
            <thead>
                <tr>
                    <th>Piattaforma</th>
                    <th class="text-center">N. Ordini</th>
                    <th class="text-right">Importo Totale</th>
                    <th class="text-right">% sul Totale</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $perPiattaforma = $ordini->groupBy('piattaforma');
                @endphp
                @foreach($perPiattaforma as $piattaforma => $ordiniPiattaforma)
                    <tr>
                        <td>
                            <span class="badge badge-{{ $piattaforma }}">
                                {{ ucfirst($piattaforma) }}
                            </span>
                        </td>
                        <td class="text-center">{{ $ordiniPiattaforma->count() }}</td>
                        <td class="text-right">€ {{ number_format($ordiniPiattaforma->sum('importo_totale'), 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format(($ordiniPiattaforma->sum('importo_totale') / $importoTotale) * 100, 1) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 30px; color: #666;">
            <p style="font-size: 14px;">Nessun ordine nel periodo selezionato</p>
        </div>
    @endif

    <div class="footer">
        <p>LISAP - Sistema Gestione Ordini Multi-Piattaforma</p>
        <p>Report generato il {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>


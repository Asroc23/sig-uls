<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Graduados</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #1e40af;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 12px;
        }

        .filters {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .filters-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #1e40af;
        }

        .filter-item {
            margin-bottom: 5px;
        }

        .filter-label {
            font-weight: bold;
            color: #374151;
        }

        .summary {
            background: #dbeafe;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid #3b82f6;
        }

        .summary p {
            font-size: 14px;
            color: #1e40af;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background: #1e40af;
            color: white;
        }

        th {
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            border: 1px solid #e5e7eb;
        }

        td {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        tbody tr:hover {
            background: #f0f4f8;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 30px;
            font-size: 10px;
            color: #999;
        }

        .page-number::after {
            content: " " counter(page);
        }

        @page {
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📊 Reporte de Graduados</h1>
            <p>Sistema de Información de Graduados - SIG</p>
            <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Filters Applied -->
        <div class="filters">
            <div class="filters-title">Filtros Aplicados:</div>
            <div class="filter-item">
                <span class="filter-label">Año de Graduación:</span> {{ $filters['year'] }}
            </div>
            <div class="filter-item">
                <span class="filter-label">Carrera:</span> {{ $filters['career'] }}
            </div>
            <div class="filter-item">
                <span class="filter-label">Género:</span> {{ $filters['gender'] }}
            </div>
        </div>

        <!-- Summary -->
        <div class="summary">
            <p>Total de Graduados: {{ $count }}</p>
        </div>

        <!-- Table -->
        @if($graduates->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Nombre</th>
                        <th style="width: 25%;">Carrera</th>
                        <th style="width: 15%;">Año</th>
                        <th style="width: 15%;">Género</th>
                        <th style="width: 15%;">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($graduates as $index => $graduate)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $graduate->first_name }} {{ $graduate->last_name }}</td>
                            <td>{{ $graduate->career->name ?? 'N/A' }}</td>
                            <td>{{ $graduate->graduation_year }}</td>
                            <td>{{ $graduate->gender === 'male' ? 'Masculino' : 'Femenino' }}</td>
                            <td>{{ $graduate->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <p>No hay datos que coincidan con los filtros especificados.</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Este reporte fue generado automáticamente por el Sistema de Información de Graduados.</p>
            <p>Para más información, contacte al administrador del sistema.</p>
        </div>
    </div>
</body>
</html>

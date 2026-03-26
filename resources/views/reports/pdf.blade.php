<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Graduados</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #1f2937;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        .metadata {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .metadata-item {
            font-size: 12px;
            color: #6b7280;
        }

        .metadata-item strong {
            color: #1f2937;
        }

        .filters-section {
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }

        .filters-section h3 {
            color: #1f2937;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .filters-content {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            font-size: 13px;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
        }

        .filter-item span:first-child {
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .filter-item span:last-child {
            color: #1f2937;
            font-weight: 500;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .summary-card {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 15px;
        }

        .summary-card h4 {
            color: #1e40af;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .summary-card p {
            color: #1f2937;
            font-size: 18px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead {
            background-color: #f3f4f6;
            border-bottom: 2px solid #3b82f6;
        }

        th {
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            color: #1f2937;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
            color: #4b5563;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tbody tr:hover {
            background-color: #f3f4f6;
        }

        .footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 30px;
            font-size: 11px;
            color: #6b7280;
            text-align: center;
        }

        .footer p {
            margin: 3px 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                padding: 0;
            }

            table {
                page-break-inside: avoid;
            }

            tbody tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Reporte de Graduados</h1>
            <p>SIG-ULS - Sistema de Información de Graduados</p>
        </div>

        <!-- Metadata -->
        <div class="metadata">
            <div class="metadata-item">
                <strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}
            </div>
            <div class="metadata-item">
                <strong>Total de registros:</strong> {{ $count }}
            </div>
        </div>

        <!-- Filters Applied -->
        @if ($filters['year'] !== 'Todos' || $filters['career'] !== 'Todas' || $filters['gender'] !== 'Todos')
            <div class="filters-section">
                <h3>Filtros Aplicados</h3>
                <div class="filters-content">
                    <div class="filter-item">
                        <span>Año de Graduación:</span>
                        <span>{{ $filters['year'] }}</span>
                    </div>
                    <div class="filter-item">
                        <span>Carrera:</span>
                        <span>{{ $filters['career'] }}</span>
                    </div>
                    <div class="filter-item">
                        <span>Género:</span>
                        <span>{{ $filters['gender'] }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="summary">
            <div class="summary-card">
                <h4>Graduados Encontrados</h4>
                <p>{{ $count }}</p>
            </div>
            <div class="summary-card">
                <h4>Período</h4>
                <p>{{ $filters['year'] }}</p>
            </div>
        </div>

        <!-- Data Table -->
        @if ($graduates->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre Completo</th>
                        <th>Carrera</th>
                        <th>Año</th>
                        <th>Género</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($graduates as $index => $graduate)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $graduate->first_name }} {{ $graduate->last_name }}</td>
                            <td>{{ $graduate->career?->name ?? 'N/A' }}</td>
                            <td>{{ $graduate->graduation_year }}</td>
                            <td>{{ $graduate->gender === 'male' ? 'Masculino' : 'Femenino' }}</td>
                            <td>{{ $graduate->email }}</td>
                            <td>{{ $graduate->phone ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 40px; color: #6b7280;">
                <p>No hay datos disponibles con los filtros aplicados</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Este reporte fue generado automáticamente por el Sistema de Información de Graduados (SIG-ULS)</p>
            <p>Para más información, contacte al administrador del sistema</p>
        </div>
    </div>
</body>
</html>

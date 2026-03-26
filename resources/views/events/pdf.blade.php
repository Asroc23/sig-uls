<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 14px;
            color: #6b7280;
        }
        .event-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }
        .info-group h3 {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-group p {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
        }
        .description {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #2563eb;
        }
        .description h3 {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .description p {
            font-size: 14px;
            color: #374151;
            white-space: pre-wrap;
        }
        .participants-section h2 {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 15px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }
        .participants-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .participant-item {
            background-color: #f9fafb;
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid #10b981;
        }
        .participant-name {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }
        .participant-carnet {
            font-size: 12px;
            color: #6b7280;
            margin-top: 3px;
        }
        .no-participants {
            text-align: center;
            color: #6b7280;
            padding: 30px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        @media (max-width: 600px) {
            .event-info {
                grid-template-columns: 1fr;
            }
            .participants-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $event->name }}</h1>
            <p>Reporte del Evento</p>
        </div>

        <div class="event-info">
            <div class="info-group">
                <h3>Fecha y Hora</h3>
                <p>{{ $event->event_date->format('d de M, Y H:i') }}</p>
            </div>
            <div class="info-group">
                <h3>Carrera</h3>
                <p>{{ $event->career->name }}</p>
            </div>
        </div>

        @if ($event->description)
            <div class="description">
                <h3>Descripción</h3>
                <p>{{ $event->description }}</p>
            </div>
        @endif

        <div class="participants-section">
            <h2>Participantes Registrados ({{ $event->graduates->count() }})</h2>
            
            @if ($event->graduates->count() > 0)
                <div class="participants-list">
                    @foreach ($event->graduates as $graduate)
                        <div class="participant-item">
                            <div class="participant-name">{{ $graduate->full_name }}</div>
                            <div class="participant-carnet">{{ $graduate->carnet }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-participants">
                    <p>No hay participantes registrados para este evento.</p>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Reporte generado: {{ now()->format('d de M, Y H:i') }}</p>
        </div>
    </div>
</body>
</html>

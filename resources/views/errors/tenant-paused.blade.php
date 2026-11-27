<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name ?? 'Loja' }} — Temporariamente Indisponível</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #faf8f5;
            color: #1c1917;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }
        .card {
            max-width: 480px;
            width: 100%;
            background: #ffffff;
            border: 1px solid #eee5db;
            border-radius: 16px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(28, 25, 23, 0.06);
        }
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 9999px;
            background: #fdf2f2;
            color: #720403;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        h1 {
            font-size: 24px;
            margin: 0 0 12px;
            color: #1c1917;
        }
        p {
            font-size: 15px;
            line-height: 1.6;
            color: #57534e;
            margin: 0 0 12px;
        }
        .reason {
            margin-top: 16px;
            padding: 12px 16px;
            background: #faf8f5;
            border-radius: 10px;
            font-size: 13px;
            color: #78716c;
        }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge">Loja Pausada</span>
        <h1>{{ $tenant->name }} está temporariamente indisponível</h1>
        <p>
            Esta loja virtual foi pausada pela administração da plataforma e não está aceitando acessos no momento.
        </p>
        @if (! empty($tenant->pause_reason))
            <div class="reason">
                Motivo: {{ $tenant->pause_reason }}
            </div>
        @endif
    </div>
</body>
</html>

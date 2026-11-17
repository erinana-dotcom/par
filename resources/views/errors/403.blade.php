<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - PARKIRIN</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --asphalt: #EDE4D3;
            --card: #F5EEDD;
            --line-yellow: #8B5E34;
            --text-primary: #3E2E1F;
            --text-muted: #7A6852;
            --border: #C9B896;
            --danger: #9C3D2E;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: var(--asphalt);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .box {
            width: 100%;
            max-width: 420px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 12px 32px rgba(62, 46, 31, 0.18);
        }

        .brand {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 22px;
        }
        .brand span { color: var(--line-yellow); }

        .code {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700;
            font-size: 84px;
            line-height: 1;
            color: var(--danger);
        }

        h1 { font-size: 18px; font-weight: 600; margin: 12px 0 8px; }
        p  { font-size: 14px; color: var(--text-muted); line-height: 1.55; margin-bottom: 26px; }

        .btn {
            display: inline-block;
            background: var(--line-yellow);
            color: #FAF6EE;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 11px 22px;
            border-radius: 4px;
        }
        .btn:hover { background: #734d2a; }
    </style>
</head>
<body>
    <div class="box">
        <div class="brand">PARKIR<span>IN</span></div>

        <div class="code">403</div>
        <h1>Akses Ditolak</h1>
        <p>{{ $exception->getMessage() ?: 'Anda tidak punya akses ke halaman ini.' }}</p>

        @auth
            <a href="{{ url('/dashboard/' . auth()->user()->role) }}" class="btn">Kembali ke Dashboard</a>
        @else
            <a href="{{ url('/') }}" class="btn">Ke Halaman Masuk</a>
        @endauth
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - PARKIRIN</title>
    @vite('resources/css/app.css')
    <style>
        :root {
            --bg: #EDE4D3; --card: #F5EEDD; --accent: #8B5E34;
            --text: #3E2E1F; --muted: #7A6852; --border: #C9B896; --danger: #9C3D2E;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        html { background: var(--bg); }

        body {
            min-height: 100vh;
            background: transparent;
            font-family: 'Inter', Arial, sans-serif;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* Ilustrasi jalan + mobil di belakang kotak login */
        .scene {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .login-box {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 380px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 36px 32px;
            box-shadow: 0 12px 32px rgba(62, 46, 31, 0.28);
        }

        .brand { font-size: 26px; font-weight: 700; text-align: center; }
        .brand span { color: var(--accent); }

        .sub { text-align: center; font-size: 14px; color: var(--muted); margin: 6px 0 28px; }

        label { display: block; font-size: 13px; color: var(--muted); margin-bottom: 6px; }

        input[type="text"], input[type="password"] {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 11px 12px;
            font-size: 14.5px;
            color: var(--text);
            font-family: inherit;
            margin-bottom: 18px;
        }
        input:focus { outline: none; border-color: var(--accent); }

        .remember { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); margin-bottom: 22px; }
        .remember input { width: auto; margin: 0; }

        button {
            width: 100%;
            background: var(--accent);
            color: #FAF6EE;
            border: none;
            border-radius: 4px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover { background: #734d2a; }

        .error {
            background: rgba(156, 61, 46, 0.12);
            border: 1px solid rgba(156, 61, 46, 0.35);
            color: var(--danger);
            font-size: 13px;
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <svg class="scene" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
        <defs>
            <linearGradient id="langit" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#F6EBD6"/>
                <stop offset="1" stop-color="#D9C3A0"/>
            </linearGradient>

            <!-- Mobil tampak samping (warna badan mengikuti 'color') -->
            <g id="mobil">
                <path fill="currentColor" d="M0,-30 Q0,-52 30,-56 L70,-60 L100,-92 Q108,-100 125,-100 L205,-100 Q222,-100 232,-88 L258,-60 L280,-56 Q300,-52 300,-30 L300,-14 L0,-14 Z"/>
                <path fill="#F5EEDD" fill-opacity="0.85" d="M110,-90 L150,-90 L150,-64 L94,-64 Z"/>
                <path fill="#F5EEDD" fill-opacity="0.85" d="M160,-90 L205,-90 L236,-64 L160,-64 Z"/>
                <rect x="286" y="-46" width="14" height="9" rx="3" fill="#F6E3A8"/>
                <rect x="0" y="-46" width="10" height="9" rx="3" fill="#C0553F"/>
                <circle cx="65" cy="-14" r="24" fill="#2E2118"/>
                <circle cx="65" cy="-14" r="11" fill="#C9B896"/>
                <circle cx="235" cy="-14" r="24" fill="#2E2118"/>
                <circle cx="235" cy="-14" r="11" fill="#C9B896"/>
            </g>
        </defs>

        <!-- Langit dan matahari -->
        <rect width="1440" height="900" fill="url(#langit)"/>
        <circle cx="1150" cy="230" r="110" fill="#FBF3E2" fill-opacity="0.75"/>

        <!-- Siluet gedung -->
        <g fill="#C2A67E" fill-opacity="0.6">
            <rect x="0" y="560" width="120" height="150"/>
            <rect x="130" y="500" width="90" height="210"/>
            <rect x="230" y="590" width="110" height="120"/>
            <rect x="960" y="545" width="100" height="165"/>
            <rect x="1070" y="480" width="120" height="230"/>
            <rect x="1200" y="575" width="110" height="135"/>
            <rect x="1320" y="520" width="120" height="190"/>
        </g>

        <!-- Jalan -->
        <rect x="0" y="700" width="1440" height="200" fill="#6B4A2E"/>
        <rect x="0" y="690" width="1440" height="14" fill="#A9835A"/>
        <line x1="0" y1="855" x2="1440" y2="855" stroke="#F5EEDD" stroke-opacity="0.8"
              stroke-width="6" stroke-dasharray="60 40"/>

        <!-- Rambu parkir -->
        <rect x="435" y="540" width="10" height="160" fill="#4A3320"/>
        <rect x="395" y="460" width="90" height="90" rx="10" fill="#8B5E34"/>
        <text x="440" y="526" text-anchor="middle" font-family="Arial, sans-serif"
              font-size="64" font-weight="700" fill="#F5EEDD">P</text>

        <!-- Dua mobil -->
        <use href="#mobil" transform="translate(90,795)" style="color:#8B5E34"/>
        <use href="#mobil" transform="translate(1350,795) scale(-1,1)" style="color:#B88F5E"/>
    </svg>

    <div class="login-box">
        <div class="brand">PARKIR<span>IN</span></div>
        <p class="sub">Masuk dengan ID petugas Anda</p>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        @error('username') <div class="error">{{ $message }}</div> @enderror
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <form action="{{ route('login.proses') }}" method="POST" novalidate>
            @csrf

            <label for="username">ID Petugas / Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>

            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" required>

            <label class="remember">
                <input type="checkbox" name="remember"> Ingat saya
            </label>

            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>
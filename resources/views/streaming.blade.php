<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Feed — {{ $drone->name ?? 'SkyGuard' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #1e2228;
            --surface:   #252a32;
            --border:    rgba(255,255,255,0.07);
            --text:      #d4d8de;
            --muted:     #6b7280;
            --accent:    #4fc3b0;
            --green:     #22c55e;
            --red:       #f44336;
            --mono:      'Geist Mono', 'Courier New', monospace;
            --sans:      'Instrument Sans', sans-serif;
        }

        html, body {
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: var(--sans);
            font-size: 15px;
            line-height: 1.6;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image: repeating-linear-gradient(
                -45deg,
                rgba(255,255,255,0.018) 0px,
                rgba(255,255,255,0.018) 1px,
                transparent 1px,
                transparent 14px
            );
        }

        nav {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            height: 60px;
            border-bottom: 1px solid var(--border);
            font-family: var(--mono);
            font-size: 13px;
        }

        .nav-brand {
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 0.08em;
        }

        .nav-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--muted);
            text-decoration: none;
            letter-spacing: 0.05em;
            transition: color 0.2s;
        }

        .nav-back:hover { color: var(--text); }

        .page {
            position: relative;
            z-index: 1;
            max-width: 1060px;
            margin: 0 auto;
            padding: 48px 32px 80px;
        }

        .header { margin-bottom: 36px; }

        .header-label {
            font-family: var(--mono);
            font-size: 11px;
            letter-spacing: 0.18em;
            color: var(--muted);
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .header-title {
            font-family: var(--mono);
            font-size: clamp(24px, 4vw, 42px);
            font-weight: 600;
            letter-spacing: 0.04em;
            color: var(--text);
            line-height: 1.1;
        }

        .status-row {
            display: flex;
            gap: 24px;
            margin-top: 18px;
            flex-wrap: wrap;
        }

        .badge {
            font-family: var(--mono);
            font-size: 12px;
            letter-spacing: 0.1em;
            color: var(--muted);
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .badge.active { color: var(--text); }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 6px var(--green);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.35; }
        }

        .feed-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            overflow: hidden;
        }

        .feed-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
            font-family: var(--mono);
            font-size: 12px;
            letter-spacing: 0.1em;
            color: var(--muted);
        }

        .feed-card-header .live-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--green);
            font-weight: 600;
        }

        .stream-wrapper {
            width: 100%;
            background: #0d0f12;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 420px;
        }

        .stream-wrapper img {
            width: 100%;
            height: auto;
            display: block;
        }

        .feed-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 12px;
        }

        .source-info {
            font-family: var(--mono);
            font-size: 11px;
            color: var(--muted);
            letter-spacing: 0.06em;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            font-family: var(--mono);
            font-size: 11px;
            letter-spacing: 0.1em;
            font-weight: 500;
            padding: 7px 16px;
            border-radius: 3px;
            border: 1px solid var(--border);
            cursor: pointer;
            background: transparent;
            color: var(--muted);
            transition: color 0.18s, border-color 0.18s, background 0.18s;
            text-transform: uppercase;
        }

        .btn:hover {
            color: var(--text);
            border-color: rgba(255,255,255,0.22);
            background: rgba(255,255,255,0.04);
        }

        .btn-primary {
            background: rgba(79, 195, 176, 0.12);
            border-color: rgba(79, 195, 176, 0.35);
            color: var(--accent);
        }

        .btn-primary:hover {
            background: rgba(79, 195, 176, 0.22);
            border-color: var(--accent);
            color: var(--accent);
        }

        @media (max-width: 600px) {
            nav { padding: 0 20px; }
            .page { padding: 28px 16px 60px; }
            .stream-wrapper { min-height: 220px; }
        }
    </style>
</head>
<body>

<nav>
    <a href="{{ route('home') }}" class="nav-brand">SKYGUARD</a>
    <a href="{{ route('drone.dashboard', $drone->id) }}" class="nav-back">
        ← BACK TO DASHBOARD
    </a>
</nav>

<div class="page">
    
    <div class="header">
        <div class="header-label">// SURVEILLANCE FEED — {{ strtoupper($drone->name ?? 'DRONE') }}</div>
        <div class="header-title">&lt;LIVE STREAM&gt;</div>

        <div class="status-row">
            <span class="badge active">
                [FEED: ACTIVE] <span class="dot"></span>
            </span>
            <span class="badge active">
                [SERIAL: {{ strtoupper($drone->serial_number) }}]
            </span>
            <span class="badge" style="color: {{ $drone->status === 'online' ? 'var(--green)' : 'var(--red)' }}">
                [STATUS: {{ strtoupper($drone->status) }}]
            </span>
        </div>
    </div>

    <div class="feed-card">
        <div class="feed-card-header">
            <span class="live-tag">
                <span class="dot"></span>
                LIVE
            </span>
            <span>{{ strtoupper($drone->name ?? 'CAM-01') }} / {{ strtoupper($drone->model ?? 'ZEROCAM') }}</span>
        </div>

        <div class="stream-wrapper">
            <img src="{{ $streamUrl }}" alt="Live Raspberry Pi camera stream">
        </div>

        <div class="feed-card-footer">
            <div class="source-info">
                ENDPOINT: {{ $streamUrl }}
            </div>
            <div class="actions">
                <button class="btn btn-primary" onclick="reloadStream()">RELOAD FEED</button>
                <button class="btn" onclick="openRawStream()">OPEN RAW</button>
            </div>
        </div>
    </div>

</div>

<script>
    function reloadStream() {
        const img = document.querySelector('.stream-wrapper img');
        const base = @json($streamUrl);
        img.src = base + '?t=' + Date.now();
    }
    function openRawStream() {
        window.open(@json($streamUrl), '_blank');
    }
</script>

</body>
</html>
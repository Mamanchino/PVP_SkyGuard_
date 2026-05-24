<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Feed — {{ $drone->name ?? 'SkyGuard' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite([ 'resources/css/streaming.css'
        
    ])
    <style>
        
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
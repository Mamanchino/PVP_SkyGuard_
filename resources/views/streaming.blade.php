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
        @vite(['resources/css/streaming.css'])
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <div class="notif-panel">
        <div class="notif-header">
            <div class="notif-header-left">
                <span class="notif-title">// DETECTION LOG</span>
                <span class="notif-count" id="unread-count"></span>
            </div>
            <button class="notif-mark-read" onclick="markAllRead()">MARK ALL READ</button>
        </div>
        <div class="notif-list" id="notif-list">
            <div class="notif-empty" id="notif-empty">NO DETECTIONS YET</div>
        </div>
    </div>

</div>

<script>
    function reloadStream() {
        const img  = document.querySelector('.stream-wrapper img');
        const base = @json($streamUrl);
        img.src    = base + '?t=' + Date.now();
    }
    function openRawStream() {
        window.open(@json($streamUrl), '_blank');
    }

    const DRONE_ID    = @json($drone->id);
    const POLL_MS     = 4000;
    const MAX_ITEMS   = 50;

    let notifications = [];
    let lastFetchedAt = null;

    const list       = document.getElementById('notif-list');
    const emptyMsg   = document.getElementById('notif-empty');
    const countBadge = document.getElementById('unread-count');

    function iconFor(type) {
        if (type === 'person_detected') return { cls: 'person', glyph: '⬤' };
        return { cls: 'default', glyph: '⬤' };
    }

    function timeAgo(isoStr) {
        const diff = Math.floor((Date.now() - new Date(isoStr).getTime()) / 1000);
        if (diff < 60)  return `${diff}s ago`;
        if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
        return `${Math.floor(diff / 3600)}h ago`;
    }

    function renderItem(n) {
        const icon = iconFor(n.type);
        const conf = n.confidence != null
            ? `<span class="notif-conf">CONF <span>${(n.confidence * 100).toFixed(1)}%</span></span>`
            : '';
        return `
            <div class="notif-item ${n.is_read ? '' : 'unread'}" data-id="${n.id}">
                <div class="notif-icon ${icon.cls}">${icon.glyph}</div>
                <div class="notif-body">
                    <div class="notif-msg">${escHtml(n.message)}</div>
                    <div class="notif-meta">
                        <span class="notif-time">${timeAgo(n.created_at)}</span>
                        ${conf}
                    </div>
                </div>
                <div class="notif-unread-dot"></div>
            </div>`;
    }

    function rebuildList() {
        if (notifications.length === 0) {
            emptyMsg.style.display = '';
            list.innerHTML         = '';
            list.appendChild(emptyMsg);
        } else {
            emptyMsg.style.display = 'none';
            list.innerHTML = notifications.map(renderItem).join('');
        }

        const unread = notifications.filter(n => !n.is_read).length;
        if (unread > 0) {
            countBadge.textContent = unread;
            countBadge.classList.add('visible');
        } else {
            countBadge.classList.remove('visible');
        }
    }

    async function fetchNotifications() {
        const params = new URLSearchParams({ drone_id: DRONE_ID });
        if (lastFetchedAt) params.set('since', lastFetchedAt);

        try {
            const res  = await fetch(`/notifications?${params}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            if (!res.ok) return;

            const fresh = await res.json();
            if (fresh.length === 0) return;

            notifications = [...fresh, ...notifications].slice(0, MAX_ITEMS);
            lastFetchedAt = fresh[0].created_at;
            rebuildList();
        } catch (_) { }
    }

    async function markAllRead() {
        try {
            await fetch('/notifications/mark-read', {
                method:  'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'Accept':        'application/json',
                    'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ drone_id: DRONE_ID }),
            });
            notifications = notifications.map(n => ({ ...n, is_read: true }));
            rebuildList();
        } catch (_) {}
    }

    function escHtml(str) {
        return str.replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));
    }

    setInterval(() => { if (notifications.length) rebuildList(); }, 60_000);

    fetchNotifications();
    setInterval(fetchNotifications, POLL_MS);
</script>

</body>
</html>
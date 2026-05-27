<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SkyGuard') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/camera_feed.js',
        'resources/css/dashboard.css',
        'resources/js/telemetry_data.js',
        'resources/js/mark_read.js',
       
    ])
    @else
        <style>
        </style>
    @endif
</head>

<body  data-vehicle-name="{{ $drone->sim_vehicle_name }}"
    data-drone-id="{{ $drone->id }}"
    data-last-event-id="{{ $events->max('id') ?? 0 }}"
    data-drone-name="{{ $drone->name }}">
    <x-navbar :events="$events" :drone="$drone" />
    <hr class="line-spacing">
    </hr>

    <div class="view-container">
        <x-notification-pane :events="$events" :drone="$drone" />
        <div class="view-interface">
            <div class="status-interface">
                <div class="battery-info">
                    <div class="battery">
                        <div class="battery-fill"
                            style="width: {{ $drone->battery_level }}%; background-color: {{ $drone->battery_level < 30 ? '#f44336' : ($drone->battery_level < 60 ? '#ff9800' : '#4caf50') }}">
                        </div>
                    </div>
                    <p class="battery-percentage">{{ $drone->battery_level }}%</p>
                    <div class="drone-status">
                        <p class="drone-connection-status">{{ $drone->status }}</p>
                    </div>
                </div>
                <div class="drone-name">
                    <h1>{{$drone->name}}</h1>
                </div>
            </div>
            <div class="camera">
                @if ($frameUrl)
                    <img id="airsim-camera-feed" src="{{ $frameUrl }}"
                    alt="Live AirSim camera feed for {{ $drone->name }}"
                    data-base-src="{{ $frameUrl }}"
                    >
                @else
                    <div class="camera-placeholder">
                        @if ($drone->status !== 'online')
                            <p>The drone is offline.</p>
                        @else
                            <p>This drone is not linked to an AirSim vehicle yet...</p>
                            <p>Set the <code>sim_vehicle_name</code> attribute for this drone to view the camera feed</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    

    <script>
        document.addEventListener('click', function (e) {
            console.log('Clicked element:', e.target);
            console.log('Tag:', e.target.tagName);
            console.log('Classes:', e.target.className);
        }, true);
    </script>
</body>

</html>

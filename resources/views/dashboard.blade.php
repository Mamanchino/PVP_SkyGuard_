<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
        'resources/js/telemetry_data.js'
    ])
    @else
        <style>
        </style>
    @endif
</head>

<body data-vehicle-name="{{  $drone->sim_vehicle_name }}">
    <x-navbar />
    <hr class="line-spacing">
    </hr>

    <div class="view-container">
        <div class="notification-pane">
            <div class="notification-header">
                <div class="notification-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                        <path fill="#6082B6" 
                            d="M10.146 3.248a2 2 0 0 1 3.708 0A7.003 7.003 0 0 1 19
                             10v4.697l1.832 2.748A1 1 0 0 1 20 19h-4.535a3.501 3.501
                              0 0 1-6.93 0H4a1 1 0 0 1-.832-1.555L5 14.697V10c0-3.224
                               2.18-5.94 5.146-6.752zM10.586 19a1.5 1.5 0 0 0 2.829
                                0h-2.83zM12 5a5 5 0 0 0-5 5v5a1 1 0 0 1-.168.555L5.869
                                 17H18.13l-.963-1.445A1 1 0 0 1 17 15v-5a5 5 0 0 0-5-5z" />
                    </svg>
                    <strong style="font-size: 20px; color: #f0eded">Notifications</strong>
                </div>
            </div>
            @foreach ($events as $event)
                <div class="notification-container">
                    <div class="alert-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                            <path fill="currentColor"
                                d="M11.53 2.3A1.85 1.85 0 0 0 10 1.21A1.85 1.85 0 0 0
                                 8.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67
                                  0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z" />
                        </svg>
                    </div>
                    <div class="notification-message">
                        <strong style= " color:#f0eded">{{$drone->name}}</strong>
                        <p class="notification-type">{{ $event->event_type }}</p>
                        <strong class="notification-date">{{ date("Y-m-d H:i", strtotime($event->started_at)) }}</strong>
                    </div>
                </div>
            @endforeach
        </div>
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

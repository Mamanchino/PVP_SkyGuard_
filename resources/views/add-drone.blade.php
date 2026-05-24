<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'SkyGuard') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/add.css', 'resources/js/app.js', 'resources/js/add-device-modal.js'])
    @else
        <style>
        </style>
    @endif
</head>

<body>
    <x-navbar />
    <hr class="line-spacing"></hr>
    <div class="device-container">

        <div class="devices">
            @foreach ($assignedDevices as $device)
            <div class="device" onclick="window.location='{{ route('drone.dashboard', $device->id) }}'">
                <div class="device-status-container">
                    <div class="battery">
                        <div class="battery-fill"
                            style="width: {{ $device->battery_level }}%; background-color: {{ $device->battery_level < 30 ? '#f44336' : ($device->battery_level < 60 ? '#ff9800' : '#4caf50') }}">
                        </div>
                    </div>
                    <div class="status">
                        <div>
                            {{ $device->battery_level }}%
                        </div>
                        <div class="work-status" style="color: {{ $device->status === 'online' ? '#4caf50' : '#f44336' }}">
                            {{ $device->status }}
                        </div>
                    </div>
                </div>
                <div class="serial_number">
                    <h3>{{ $device->name }}</h3>
                </div>
                <div class="device-image">
                    <img src="{{ asset($model_img[$device->model] ?? 'images/default_drone.png') }}" alt="Drone Image">
                </div>
                <div class="device-actions" onclick="event.stopPropagation()">
                    <a href="{{ route('drone.stream', $device->id) }}"
                       class="stream-btn" title="View live stream">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('drone.remove', $device->id) }}"
                          class="remove-form"
                          onsubmit="return confirm('Remove {{ $device->name }} from your account?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-btn" title="Remove drone">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="add-container">
            <div class="add-button">
                @csrf
                <div class="add-button-text">
                    <h3>Add Drone</h3>
                </div>
                <div class="add-button-img">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="plus-img">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div id="addDroneModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <form id="addDroneForm" method="POST" action="/drones/add">
                            @csrf
                            <label for="activation_code">Activation Code:</label>
                            <input type="text" id="activation_code" name="activation_code"
                                placeholder="Enter activation code" required>
                            <button type="submit">Add Drone</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="line-spacing"></hr>

    <script>
        document.addEventListener('click', function (e) {
            console.log('Clicked element:', e.target);
            console.log('Tag:', e.target.tagName);
            console.log('Classes:', e.target.className);
        }, true);
    </script>
</body>

</html>
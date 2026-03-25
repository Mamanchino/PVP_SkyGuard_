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
        @vite(['resources/css/add.css', 'resources/js/app.js', 'resources/js/add-device-modal.js'])
    @else
        <style>
        </style>
    @endif
</head>

<body>
    <x-navbar />
    <hr class="line-spacing">
    </hr>
    <div class="device-container">

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
                            <label for="serial">Drone Serial Number:</label>
                            <input type="text" id="serial" name="serial" placeholder="Enter serial number" required>
                            <button type="submit">Add Drone</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="line-spacing">
    </hr>
    <x-footer />

    <script>
        document.addEventListener('click', function (e) {
            console.log('Clicked element:', e.target);
            console.log('Tag:', e.target.tagName);
            console.log('Classes:', e.target.className);
        }, true);
    </script>
</body>

</html>
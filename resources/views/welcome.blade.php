<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Geist+Mono&display=swap" rel="stylesheet">
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/footer.css', 'resources/js/blink.js'])
        @else
            <style>
            </style>
        @endif
    </head>
    <body>
        <x-navbar />
        <hr class="line-spacing"></hr>
        <div class="opening_card">
        
            <div class="phrase">
                <p class="phrase-1">&lt;Security without&gt;</p>
                <p class="phrase-2">LIMITS</p>
                <div class="paragprah-1">
        
                    <p>&lsqb;LIVE MONITORING: ACTIVE&rsqb; <span id="blink" class="record-symbol"><svg
                                xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-record-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path d="M11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            </svg></span></p>
                    <p>&lsqb;DRONE STATUS: ACTIVE&rsqb; <span id=blink-2 class="green-dot"></span></p>
                    <p style="font-weight: bold;">&lsqb;ZONE: SECURED&rsqb;</p>
                </div>
            </div>
            <div class="opening-card-info">
                <div>
                    <div class="video-1">
                        PLACE for video
                    </div>
                </div>
            </div>
        </div>
        <div class="description">
            <div class="description-background"> </div>
            <div class="desctiption-content">
                <p>Monitor your property in real-time with a fully autonomous drone — no cameras, no blind spots, no limits.</p>
                <br>
                <p class="descpiton-2">SkyGuard patrols, detects threats, and alerts you instantly.</p>
            </div>
        </div>
        <hr>
        <br>
        <div class="info-card">
            <div class="info-1-container">
                <div class="info-1_1">
                    Place for info
                </div>
                <div class="info-1_2">
        
                </div>
            </div>
            <div class="divider"></div>
            <div class="info-2-container">
                <div class="info-2_1">
                    Place for photo
                </div>
                <div class="info-2_2">
                    Place for info
                </div>
            </div>
            <div class="divider"></div>
            <div class="info-3-container">
                <div class="info-3_1">
                    Place for info
                </div>
                <div class="info-3_2">
        
                </div>
            </div>
        </div>
        <hr>

        <script>
        document.addEventListener('click', function(e) {
            console.log('Clicked element:', e.target);
            console.log('Tag:', e.target.tagName);
            console.log('Classes:', e.target.className);
        }, true);
        </script>
    </body>
    <x-footer/>
</html>

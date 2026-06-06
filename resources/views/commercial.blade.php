
<head>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/js/app.js', 'resources/css/commercial.css', 'resources/css/app.css'])
    @endif
</head>

<body>
    <x-navbar />
    <section class="commercial-page">
        <div class="commercial-hero">
            <p class="commercial-label">Commercial Security</p>

            <h1>
                Autonomous aerial protection for commercial properties.
            </h1>

            <p>
                Eliminate blind spots, reduce guard risk, and automate continuous
                perimeter surveillance across large commercial spaces.
            </p>
        </div>

        <section class="target-section">
            <p class="commercial-label">Target Environments</p>

            <div class="target-grid">
                <div>Shopping Centers & Retail Parks</div>
                <div>Office Campuses</div>
                <div>Private Estates</div>
                <div>Parking Lots & Open Facilities</div>
            </div>
        </section>

        <section class="commercial-section">
            <p class="commercial-label">The Use Case</p>

            <h2>
                Managing assets and preventing post-hour breaches.
            </h2>

            <div class="case-grid">
                <article class="case-panel">
                    <span>01 / The Commercial Challenge</span>

                    <p>
                        Commercial properties often cover large open areas with multiple
                        entrances, parking zones, storage areas, and public-facing spaces.
                        Stationary cameras can leave blind spots between buildings, behind
                        vehicles, or around perimeter edges.
                    </p>

                    <p>
                        Expanding coverage with traditional CCTV requires expensive wiring,
                        camera installation, and ongoing maintenance. Manual patrols can also
                        leave long gaps between checks, especially after business hours.
                    </p>
                </article>

                <article class="case-panel case-panel-accent">
                    <span>02 / The SkyGuard Solution</span>

                    <p>
                        SkyGuard gives commercial sites a mobile aerial security layer that can
                        patrol wide areas, inspect suspicious activity, and stream live evidence
                        directly to the web dashboard.
                    </p>
                </article>
            </div>
        </section>

    </section>

    <x-footer />
</body>

<head>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/js/app.js', 'resources/css/industrial.css', 'resources/css/app.css'])
    @endif
</head>

<body>
    <x-navbar />
    <section class="industrial-page">
        <div class="industrial-hero">
            <p class="industrial-label">Industrial Security</p>

            <h1>
                Autonomous aerial protection for high-risk industrial infrastructures.
            </h1>

            <p>
                Eliminate blind spots, reduce physical guard risks, and automate continuous
                perimeter surveillance across massive industrial grounds.
            </p>
        </div>

        <section class="target-section">
            <p class="industrial-label">Target Environments</p>

            <div class="target-grid">
                <div>Logistics & Container Hubs</div>
                <div>Manufacturing & Factory Facilities</div>
                <div>Active Construction & Excavation Sites</div>
                <div>High-Value Resource Storage Yards</div>
            </div>
        </section>

        <section class="industrial-section">
            <p class="industrial-label">The Use Case</p>

            <h2>
                Managing assets and preventing post-hour breaches.
            </h2>

            <div class="case-grid">
                <article class="case-panel">
                    <span>01 / The Industrial Challenge</span>

                    <p>
                        Industrial zones occupy massive acreage filled with complex physical
                        obstructions. Shipping containers, heavy machinery, raw materials, and
                        sprawling warehouse buildings create permanent blind spots that stationary
                        cameras simply cannot see through.
                    </p>

                    <p>
                        Wiring these vast areas with fiber and electrical grids requires an enormous
                        upfront investment. Manual foot patrols through dark, unlit, or hazardous
                        manufacturing sites at night expose security personnel to safety risks while
                        leaving long intervals where parts of the facility remain unmonitored.
                    </p>
                </article>

                <article class="case-panel case-panel-accent">
                    <span>02 / The SkyGuard Solution</span>

                    <p>
                        SkyGuard redefines industrial protection by shifting from fixed ground
                        constraints to a dynamic aerial security ecosystem.
                    </p>
                </article>
            </div>
        </section>

        
    </section>

    <x-footer />
</body>
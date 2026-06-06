<head>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/js/app.js', 'resources/css/our_services.css', 'resources/css/app.css'])
    @else
    
    @endif
</head>
<body>
    
    <x-navbar />
<section class="services-page">
    <div class="services-hero">
        <p class="services-label">SkyGuard Security Solutions</p>

        <h1>
            Automated aerial security for modern territory protection.
        </h1>

        <p>
            SkyGuard replaces fixed, high-cost surveillance infrastructure with mobile
            drone patrols, AI-powered detection, live monitoring, instant alerts, and
            realistic 3D security simulation.
        </p>
    </div>

    <div class="services-grid">
        <article class="service-card">
            <span>01</span>

            <h2>Aerial Perimeter Patrols & Surveillance</h2>

            <p>
                We replace inflexible stationary camera setups with dynamic, automated
                aerial surveillance. Our quadcopter drone solutions provide a wider field
                of view and reduce the blind spots common in traditional security systems.
            </p>

            <ul>
                <li>Real-time aerial tracking across your property lines.</li>
                <li>No complex rewiring, trenching, or large multi-camera setup.</li>
            </ul>
        </article>

        <article class="service-card">
            <span>02</span>

            <h2>AI-Driven Real-Time Threat Detection</h2>

            <p>
                Equipped with edge computing and advanced computer vision, SkyGuard does
                more than watch. It detects, tags, and tracks potential threats in real time.
            </p>

            <ul>
                <li>AI models detect intruders and suspicious objects.</li>
                <li>Infrared NoIR cameras support low-light and nighttime monitoring.</li>
            </ul>
        </article>

        <article class="service-card">
            <span>03</span>

            <h2>Unified Web Control & Instant Alerting</h2>

            <p>
                Manage your aerial security system from anywhere through a single web
                platform, accessible from desktop and smartphone devices.
            </p>

            <ul>
                <li>Live HD video streaming from drone patrols.</li>
                <li>Instant dashboard notifications for detected incidents.</li>
                <li>Historical event logs, telemetry, and archived evidence.</li>
                <li>Reliable 4G/5G video and telemetry transmission.</li>
            </ul>
        </article>

        <article class="service-card">
            <span>04</span>

            <h2>3D Digital Twin Simulation & Risk Modeling</h2>

            <p>
                Before deployment, we reduce operational risk by testing security behavior
                inside realistic virtual staging environments.
            </p>

            <ul>
                <li>Facility simulation using Unreal Engine, AirSim, and PX4.</li>
                <li>Autonomous path planning and scenario testing.</li>
                <li>Obstruction handling and sensor accuracy evaluation.</li>
            </ul>
        </article>
    </div>
</section>

<x-footer />
</body>
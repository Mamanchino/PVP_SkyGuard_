
<head>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/js/app.js', 'resources/css/faq.css', 'resources/css/app.css'])
    @endif
</head>

<body>
    <x-navbar />
    <section class="faq-page">
        <div class="faq-hero">
            <p class="faq-label">Frequently Asked Questions</p>

            <h1>
                Questions about SkyGuard.
            </h1>

            <p>
                Everything you need to know about automated aerial surveillance,
                AI detection, drone connectivity, and the SkyGuard web platform.
            </p>
        </div>

        <div class="faq-group">
            <p class="faq-group-label">01 / General Questions</p>

            <details class="faq-item">
                <summary>What is SkyGuard?</summary>
                <p>
                    SkyGuard is an advanced, automated perimeter and territory surveillance
                    system that combines specialized drone hardware, an AI-powered object
                    detection layer, and a universal web management dashboard.
                </p>
            </details>

            <details class="faq-item">
                <summary>Who is this system designed for?</summary>
                <p>
                    It is built for small, medium, and large-scale commercial and industrial
                    operations looking for versatile infrastructure protection, including
                    warehouses, construction sites, logistics hubs, and private estates.
                </p>
            </details>

            <details class="faq-item">
                <summary>Why choose automated aerial patrolling over traditional CCTV cameras?</summary>
                <p>
                    Stationary security cameras are expensive to wire, install, and position
                    across large areas. They also suffer from structural blind spots and fixed
                    viewing angles. An autonomous drone covers wider fields of view, moves
                    dynamically to investigate target coordinates, and requires zero stationary
                    property infrastructure.
                </p>
            </details>
        </div>

        <div class="faq-group">
            <p class="faq-group-label">02 / Technical & AI Capabilities</p>

            <details class="faq-item">
                <summary>How does the drone detect intruders automatically?</summary>
                <p>
                    The system streams high-definition video to our backend processing server,
                    where an artificial intelligence model flags, outlines, and archives humans
                    or unauthorized entities entering forbidden coordinate boundaries.
                </p>
            </details>

            <details class="faq-item">
                <summary>Can the system operate at night or in low-light conditions?</summary>
                <p>
                    Yes. SkyGuard drone units can use Infrared-sensitive NoIR cameras, allowing
                    visual monitoring and object detection during nighttime shifts, low-light
                    conditions, or thick fog.
                </p>
            </details>

            <details class="faq-item">
                <summary>What type of connectivity does SkyGuard use?</summary>
                <p>
                    The drone communicates through onboard 4G/5G mobile data networks, allowing
                    it to send video footage and receive navigation commands across long ranges
                    without relying on local Wi-Fi.
                </p>
            </details>
        </div>

        <div class="faq-group">
            <p class="faq-group-label">03 / Software, Control, & Staging</p>

            <details class="faq-item">
                <summary>Do I need an app installed to monitor flights?</summary>
                <p>
                    No. SkyGuard uses a responsive web application. You can view real-time maps,
                    inspect drone diagnostics, watch live footage, and review incident logs from
                    any desktop computer, tablet, or smartphone browser.
                </p>
            </details>

            <details class="faq-item">
                <summary>What is the purpose of your 3D simulation engine?</summary>
                <p>
                    Before deployment, we map custom terrain footprints inside a simulation stack
                    using Unreal Engine, AirSim, and PX4 Autopilot. This digital twin helps test
                    obstacle navigation, route logic, and hazard behavior with zero real-world
                    hardware risk.
                </p>
            </details>
        </div>
    </section>

    <x-footer />
</body>
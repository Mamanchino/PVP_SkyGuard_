<x-navbar />
<head>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/js/app.js', 'resources/css/about_us.css', 'resources/css/app.css'])
        @else
            
        @endif
    </head>
    <body>
        <section class="about-page">
    <div class="about-hero">
        <p class="about-label">Our Vision</p>

        <h1>
            Security without fixed limits.
        </h1>

        <p>
            Traditional surveillance systems depend on expensive installation, complex
            infrastructure, electrical wiring, and fixed camera angles. Securing large
            areas often means adding more cameras, more cables, and more maintenance.
        </p>

        <p>
            SkyGuard was created to change that. We believe the future of security is
            smart, flexible, mobile, and automated.
        </p>
    </div>

    <div class="about-section">
        <p class="about-label">What is SkyGuard?</p>

        <h2>
            A next-generation aerial territory surveillance system.
        </h2>

        <p>
            SkyGuard combines drone technology, artificial intelligence, 3D simulation,
            and a user-friendly web platform to monitor perimeters and territories in
            real time.
        </p>
    </div>

    

    <div class="about-section">
        <p class="about-label">Why Choose Us?</p>

        <h2>
            Built for modern industrial security.
        </h2>

        <p>
            SkyGuard is designed for small and medium commercial and industrial
            enterprises that need cost-effective, eco-friendly, and adaptable protection.
            Our goal is to turn security management from a complicated chore into an
            efficient, fully automated process.
        </p>
    </div>
</section>
<x-footer />
</body>
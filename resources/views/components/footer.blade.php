    <footer>
        <div class="footer-container">
            <div>
                <img src="{{ Vite::asset('resources/images/logo.png') }}" class="h-10">
            </div>
            <div class="footer-row">
                <div class="footer-column">
                    <h4>Team</h4>
                    <ul>
                        <li> <a href="{{ route('about-us') }}">About Us</a></li>
                        <li> <a href="{{ route('our-services') }}">Our Services</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4> Get Help</h4>
                    <ul>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4> Use Cases</h4>
                    <ul>
                        <li><a href="{{ route('industrial') }}">Industrial</a></li>
                        <li><a href="{{ route('commercial') }}">Commercial</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
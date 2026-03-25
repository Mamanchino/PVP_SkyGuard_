
<header class="navbar">
    
            @if (Route::has('login'))
                <div class="navbar-container">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" class="h-10">
                    @auth
                        <div class="profile-container">
                            <div class="dropdown-wrapper">
                                <button class="dropdown-trigger">
                                    <div class="profile-picture">
                                        <img src="{{ asset('images/profile_picture.png') }}" class="h-10">
                                    </div>
                                    <div class="svg">
                                        <div>
                                            <p>Your account</p>
                                        </div>
                                        <div class="svg-image">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </div>
                                    </div>
                                </button>
                                <div class="dropdown-menu">
                                    <div class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        <a href="{{ route('profile') }}">Profile</a>
                                    </div>
                                    <div class="dropdown-item logout">
                                        <form method="POST" action="{{ route('logout') }}" class="relative z-50">
                                        @csrf
                                        <button type="submit" class="logout">Log out</button>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth
                    @guest

                        <ul class="nav-links">
                            <li>
                                <form method="GET" action="{{ route('login') }}" class="link">
                                    @csrf
                                    <button type="submit" class="">Log in</button>
                                </form>
                            </li>
                            <li>

                                <form method="GET" action="{{ route('signup') }}" class="link">
                                    @csrf
                                    <button type="submit" class="">Sign up</button>
                                </form>

                            </li>
                        </ul>
                    @endguest
                </div>
            @endif
        </header>
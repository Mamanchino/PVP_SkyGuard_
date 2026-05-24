@props(['events', 'drone'])
<div class="notification-pane">
            <div class="notification-header">
                <div class="notification-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                        <path fill="#6082B6" 
                            d="M10.146 3.248a2 2 0 0 1 3.708 0A7.003 7.003 0 0 1 19
                             10v4.697l1.832 2.748A1 1 0 0 1 20 19h-4.535a3.501 3.501
                              0 0 1-6.93 0H4a1 1 0 0 1-.832-1.555L5 14.697V10c0-3.224
                               2.18-5.94 5.146-6.752zM10.586 19a1.5 1.5 0 0 0 2.829
                                0h-2.83zM12 5a5 5 0 0 0-5 5v5a1 1 0 0 1-.168.555L5.869
                                 17H18.13l-.963-1.445A1 1 0 0 1 17 15v-5a5 5 0 0 0-5-5z" />
                    </svg>
                    <strong style="font-size: 20px; color: #f0eded">Notifications</strong>
                </div>
            </div>
            @foreach ($events as $event)
                @if($event->severity == 'info')
                    <div class="notification-container">
                        <div class="alert-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                <path fill="currentColor"
                                    d="M11.53 2.3A1.85 1.85 0 0 0 10 1.21A1.85 1.85 0 0 0
                                    8.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67
                                    0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z" />
                            </svg>
                        </div>
                        <div class="notification-message">
                            <strong style= " color:#f0eded">{{$drone->name}}</strong>
                            <p class="notification-type">{{ $event->event_type }}</p>
                            <strong class="notification-date">{{ date("Y-m-d H:i", strtotime($event->started_at)) }}</strong>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
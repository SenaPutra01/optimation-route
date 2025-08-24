@php
use App\Models\Notification;

$notifications = [];
$unreadCount = 0;

if(Auth::check() && Auth::user()->role === 'kurir') {
$notifications = Notification::where('user_id', Auth::id())
->latest()
->take(5)
->get();

$unreadCount = Notification::where('user_id', Auth::id())
->where('is_read', false)
->count();
}
@endphp


<nav class="navbar p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard.index') }}"><img
                src="{{ Vite::asset('resources/assets/images') }}/logo-mini.svg" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">
            @if(Auth::check() && Auth::user()->role === 'kurir')
            <li class="nav-item dropdown border-left">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                    data-toggle="dropdown">
                    <i class="mdi mdi-bell"></i>
                    @if($unreadCount > 0)
                    <span class="count bg-danger">{{ $unreadCount }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                    aria-labelledby="notificationDropdown">
                    <h6 class="p-3 mb-0">Notifikasi</h6>
                    <div class="dropdown-divider"></div>

                    @forelse($notifications as $notif)
                    <a class="dropdown-item preview-item" href="{{ route('deliveries.show', $notif->delivery_id) }}">
                        {{-- <a class="dropdown-item preview-item"> --}}
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-truck-delivery text-info"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject mb-1">{!! $notif->title !!}</p>
                                <p class="text-muted ellipsis mb-0">{!! $notif->message !!}</p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        @empty
                        <div class="dropdown-item text-muted">Tidak ada notifikasi</div>
                        @endforelse

                        <p class="p-3 mb-0 text-center"><a href="#">Lihat semua notifikasi</a></p>
                </div>
            </li>
            @endif

            <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                    <div class="navbar-profile">
                        <img class="img-xs rounded-circle"
                            src="{{ Vite::asset('resources/assets/images/faces/face15.jpg') }}" alt="">
                        <p class="mb-0 d-none d-sm-block navbar-profile-name">{{ Auth::user()->name }}</p>
                        <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                    aria-labelledby="profileDropdown">
                    <h6 class="p-3 mb-0">Profile</h6>
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item preview-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-logout text-danger"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject mb-1">Log out</p>
                            </div>
                        </a>
                    </form>
                </div>
            </li>
        </ul>
        @if(session('popup_notification') && Auth::check() && Auth::user()->role === 'kurir')
        <div id="popup-notification" class="position-fixed top-0 end-0 m-3 alert alert-info shadow-lg fade show"
            style="z-index: 1055; max-width: 350px;">
            <div class="d-flex align-items-center">
                <div class="me-2">
                    <i class="mdi mdi-truck-delivery text-info fs-4"></i>
                </div>
                <div>
                    <strong>{{ session('popup_notification')['title'] }}</strong><br>
                    <small>{{ session('popup_notification')['message'] }}</small>
                </div>
            </div>
        </div>

        <script>
            setTimeout(() => {
            const popup = document.getElementById('popup-notification');
            if (popup) {
                popup.classList.remove('show');
                setTimeout(() => popup.remove(), 500); // Smooth fade-out
            }
        }, 5000);
        </script>
        @endif

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
        </button>
    </div>
</nav>
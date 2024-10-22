<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <!-- Navbar Brand -->
    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ url('/') }}">
            <img src="{{ url('assets/images/logo.svg') }}" alt="logo" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
            <img src="{{ url('assets/images/logo-mini.svg') }}" alt="logo" />
        </a>
    </div>
    <!-- Navbar Menu -->
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <!-- Toggle Button -->
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <!-- Navbar Right Menu -->
        <ul class="navbar-nav navbar-nav-right">
            {{-- <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                    data-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    <span class="count bg-success">{{ auth()->user()->unreadNotifications->count() }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                    aria-labelledby="notificationDropdown">
                    @forelse (auth()->user()->unreadNotifications as $notification)
                    <div class="dropdown-item preview-item position-relative notification-item" id="notification-{{ $notification->id }}">
                        <a href="{{ route('notifications.show', $notification->id) }}" class="text-decoration-none">
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-medium text-dark">
                                    {{ $notification->data['message'] }}
                                </h6>
                                <p class="font-weight-light small-text">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="dropdown-item">
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-medium text-dark">Tidak ada notifikasi baru</h6>
                            <p class="font-weight-light small-text">Anda sudah membaca semua notifikasi.</p>
                        </div>
                    </div>
                @endforelse
                </div>
            </li> --}}

            <!-- User Dropdown -->
            <li class="nav-item dropdown d-none d-xl-inline-block">
                <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown"
                    aria-expanded="false">
                    <span class="profile-text d-none d-md-inline-flex">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item mt-2">Kelola Akun</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-menu icon-menu"></span>
        </button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const closeButtons = document.querySelectorAll('.close-notification');
            closeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const notificationId = this.getAttribute('data-notification-id');
                    closeNotification(notificationId);
                });
            });

            function closeNotification(id) {
                fetch(`/notifications/${id}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const notificationElement = document.getElementById(`notification-${id}`);
                            if (notificationElement) {
                                notificationElement.remove();
                            }
                            updateNotificationCount();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menandai notifikasi sebagai telah dibaca.');
                    });
            }

            function updateNotificationCount() {
                const notificationCount = document.querySelectorAll('.notification-item').length;
                const countElement = document.getElementById('notification-count');
                if (countElement) {
                    countElement.textContent = notificationCount;
                }
                if (notificationCount === 0) {
                    showNoNotificationsMessage();
                }
            }

            function showNoNotificationsMessage() {
                const notificationContainer = document.querySelector('.dropdown-menu');
                if (notificationContainer) {
                    notificationContainer.innerHTML = `
                    <div class="dropdown-item">
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-medium text-dark">Tidak ada notifikasi baru</h6>
                            <p class="font-weight-light small-text">Anda sudah membaca semua notifikasi.</p>
                        </div>
                    </div>
                `;
                }
            }
        });
    </script>
</nav>

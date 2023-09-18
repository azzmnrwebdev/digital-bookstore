<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ruang Literasi</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}">

    <link href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css') }}"
        rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
        crossorigin="anonymous">

    <link rel="preconnect" href="{{ url('https://fonts.googleapis.com') }}">
    <link rel="preconnect" href="{{ url('https://fonts.gstatic.com') }}" crossorigin>
    <link
        href="{{ url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap') }}"
        rel="stylesheet">

    <link rel="stylesheet"
        href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1;
        }

        footer {
            padding: 20px;
            margin-top: auto;
            background-color: #f8f8f8;
            border-top: 3px solid #42B549
        }

        .footer-wrapper {
            margin-top: auto;
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('home.partials.navbar')
    @yield('header')
    @yield('main')
    @include('home.partials.footer')

    <script src="{{ url('https://code.jquery.com/jquery-3.6.3.min.js') }}"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <script src="{{ url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js') }}"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>

    <script>
        let dropdowns = document.querySelectorAll('.navbar-web-satu .navbar-nav .nav-item.dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('mouseover', () => {
                document.body.classList.add('dropdown-active');
            });

            dropdown.addEventListener('mouseout', () => {
                document.body.classList.remove('dropdown-active');
            });
        });

        $(document).ready(function() {
            let isLoggedIn = checkUserLoginStatus();

            if (isLoggedIn) {
                loadUnreadNotifications();
                loadCartCount();
            }

            function loadUnreadNotifications() {
                $.ajax({
                    url: "{{ route('notification.unread') }}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.count > 0) {
                            $('.unread-notification-count').text(response.count).show();
                        } else {
                            $('.unread-notification-count').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                })
            }

            function loadCartCount() {
                $.ajax({
                    url: "{{ route('cart.count') }}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.count > 0) {
                            $('.cart-count').text(response.count).show();
                        } else {
                            $('.cart-count').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                })
            }

            function checkUserLoginStatus() {
                let loggedIn = false;

                $.ajax({
                    url: "{{ route('check.login.status') }}",
                    method: 'GET',
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        loggedIn = response.loggedIn;
                    }
                });

                return loggedIn;
            }
        });
    </script>

    @stack('scripts')
</body>

</html>

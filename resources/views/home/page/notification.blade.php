@extends('home.index')

@prepend('styles')
    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css') }}">

    <style>
        main {
            padding: 4rem 0 !important;
        }

        .section-header {
            padding: 10vh 0;
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(90deg, #42B549, #3F51B5, #FF4081);
        }

        .section-header .container h2 {
            color: white;
            font-size: 24px;
            text-align: center;
            text-transform: uppercase;
        }

        .section-header .container nav ol li,
        .section-header .container nav ol li.active {
            color: white !important;
        }

        section .section-title {
            color: #444444;
            position: relative;
            margin-bottom: 3rem !important;
        }

        section .section-title::after {
            content: "";
            position: absolute;
            left: 0;
            width: 80px;
            height: 5px;
            bottom: -15px;
            border-radius: 5px;
            background-color: #42B549;
        }

        .unread {
            background-color: #ffdde8;
        }

        .unread:hover,
        .unread:focus,
        .unread:active {
            color: #ffffff;
            background-color: #FF4081;
        }

        .read {
            background-color: #d2d8ff
        }

        .read:hover,
        .read:focus,
        .read:active {
            color: #ffffff;
            background-color: #3F51B5;
        }

        .read:hover .text-date,
        .unread:hover .text-date {
            color: #ffffff;
        }

        .list-group-item:last-child {
            margin-bottom: 0;
        }

        @media (min-width: 576px) {}

        @media (min-width: 768px) {
            .section-header .container h2 {
                font-size: 28px;
            }
        }

        @media (min-width: 992px) {
            .section-header {
                padding: 15vh 0;
            }

            .section-header .container h2 {
                font-size: 32px;
            }

        }

        @media (min-width: 1200px) {}
    </style>
@endprepend

@section('header')
    <header class="section-header">
        <div class="container d-flex justify-content-center align-items-center flex-column">
            <h2>All Notification</span></h2>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Notification</li>
                </ol>
            </nav>
        </div>
    </header>
@endsection

@section('main')
    <main>
        <section>
            <div class="container">
                <h3 class="section-title">All Notification</h3>

                {{-- unread --}}
                <h5 class="font-weight-bolder" style="color: #FF4081; margin-bottom: 0.5rem; font-size: 18px;">{{ __('Unread Notification') }} ({{ $unreadNotifications->count() }})</h5>
                <div class="list-group mt-3 mb-4">
                    @if ($unreadNotifications->count() > 0)
                        @foreach ($unreadNotifications as $unread)
                            <a href="{{ route('notification.show', $unread->id) }}" class="mb-2 py-3 rounded list-group-item list-group-item-action d-flex flex-column flex-md-row justify-content-start justify-content-md-between align-items-lg-center unread">
                                {{ $unread->title }}
                                <p class="text-date mb-0"><small>{{ $unread->getTimeAgo($unread->created_at) }}</small></p>
                            </a>
                        @endforeach
                    @else
                        <li class="list-group-item py-3">{{ __('No Notification') }}</li>
                    @endif
                </div>

                {{-- read --}}
                <h5 class="font-weight-bolder" style="color: #3F51B5; margin-bottom: 0.5rem; font-size: 18px;">{{ __('Read Notification') }} ({{ $readNotifications->count() }})</h5>
                <div class="list-group mt-3">
                    @if ($readNotifications->count() > 0)
                        @foreach ($readNotifications as $read)
                            <a href="{{ route('notification.show', $read->id) }}" class="mb-2 py-3 rounded list-group-item list-group-item-action d-flex flex-column flex-md-row justify-content-start justify-content-md-between align-items-lg-center read">
                                {{ $read->title }}
                                <p class="text-date mb-0"><small>{{ $read->getTimeAgo($read->created_at) }}</small></p>
                            </a>
                        @endforeach
                    @else
                        <li class="list-group-item py-3">{{ __('No Notification') }}</li>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection

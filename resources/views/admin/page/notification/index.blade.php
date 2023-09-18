@extends('admin.index')
@section('title-head', 'Notification')
@section('title-content', 'Notification')

@prepend('styles')
    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css') }}" />

    <style>
        .unread {
            background-color: #ffdde8;
        }

        .unread:hover,
        .unread:focus {
            color: #ffffff;
            background-color: #FF4081;
        }

        .read {
            background-color: #d2d8ff
        }

        .read:hover,
        .read:focus {
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
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Notification') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-body">
                    @if (Session('success'))
                        <div id="alert-msg" class="alert alert-success alert-dismissible mb-3">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ Session('success') }}
                        </div>
                    @endif

                    {{-- unread --}}
                    <h5 class="card-title font-weight-bolder" style="color: #FF4081;">{{ __('Unread Notification') }} ({{ $unreadNotifications->count() }})</h5><br>
                    <div class="list-group mt-3 mb-4">
                        @if ($unreadNotifications->count() > 0)
                            @foreach ($unreadNotifications as $unread)
                                <a href="{{ route('admin.notification.show', $unread->id) }}"
                                    class="mb-2 rounded list-group-item list-group-item-action d-flex flex-column flex-md-row justify-content-start justify-content-md-between align-items-lg-center unread">
                                    {{ $unread->title }}
                                    <p class="card-text text-date"><small>{{ $unread->getTimeAgo($unread->created_at) }}</small></p>
                                </a>
                            @endforeach
                        @else
                            <li class="list-group-item">{{ __('No Notification') }}</li>
                        @endif
                    </div>

                    {{-- read --}}
                    <h5 class="card-title font-weight-bolder" style="color: #3F51B5;">{{ __('Read Notification') }} ({{ $readNotifications->count() }})</h5><br>
                    <div class="list-group mt-3">
                        @if ($readNotifications->count() > 0)
                            @foreach ($readNotifications as $read)
                                <a href="{{ route('admin.notification.show', $read->id) }}"
                                    class="mb-2 rounded list-group-item list-group-item-action d-flex flex-column flex-md-row justify-content-start justify-content-md-between align-items-lg-center read">
                                    {{ $read->title }}
                                    <p class="card-text text-date"><small>{{ $read->getTimeAgo($read->created_at) }}</small></p>
                                </a>
                            @endforeach
                        @else
                            <li class="list-group-item">{{ __('No Notification') }}</li>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "8000",
            "extendedTimeOut": "8000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
        }

        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif
    </script>
@endprepend

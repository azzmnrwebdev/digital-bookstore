@extends('home.index')

@prepend('styles')
    {{-- jquery --}}
    <script src="{{ url('https://code.jquery.com/jquery-3.6.3.min.js') }}" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css') }}">

    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css') }}" />

    {{-- rateyo css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css') }}">

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

        .name,
        .email {
            color: #151515;
            margin-bottom: 0;
            font-weight: 400;
        }

        .id-pesanan {
            font-weight: 500;
        }

        .approved,
        .approved:hover,
        .approved:focus {
            background-color: #acf5b2;
        }

        .process,
        .process:hover,
        .process:focus {
            background-color: #f1f5ac;
        }

        .rejected,
        .rejected:hover,
        .rejected:focus {
            background-color: #f5acac;
        }

        .table-custom {
            color: #444444;
            background-color: #9efda4;
        }

        .hoverable-row:hover {
            background-color: #e3ffe5;
        }

        .tr-padding td {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

        .card-title.subtotal {
            font-size: 16px;
            font-weight: 400;
            color: #151515;
        }

        .card-title.total {
            font-size: 16px;
            font-weight: 600;
            color: #151515;
        }

        .btn-close:focus {
            outline: 0;
            box-shadow: none;
        }

        .form-control:focus {
            outline: 0;
            box-shadow: none;
            border-color: #42B549;
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
            <h2>My Order</span></h2>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"
                            class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Order</li>
                </ol>
            </nav>
        </div>
    </header>
@endsection

@section('main')
    <main>
        <section>
            <div class="container">
                @if (Session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ Session('success') }}
                    </div>
                @endif

                <h3 class="section-title">My Order List</h3>

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{ Request::is('my-order') ? route('order.index') : route('order.process') }}"
                            class="nav-link @if (Request::is('my-order') || Request::is('my-order/process')) active @endif" role="tab">Process</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('order.approved') }}"
                            class="nav-link @if (Request::is('my-order/approved')) active @endif" role="tab">Approved</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('order.rejected') }}"
                            class="nav-link @if (Request::is('my-order/rejected')) active @endif" role="tab">Rejected</a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- process --}}
                    <div class="tab-pane fade @if (Request::is('my-order') || Request::is('my-order/process')) show active @endif" role="tabpanel">
                        @if (Request::is('my-order') || Request::is('my-order/process'))
                            @php
                                $total = 0;
                            @endphp
                            @if ($orderProcess->count() > 0)
                                <h5 class="mt-4">Total Process Orders ({{ $orderProcess->count() }})</h5>
                                <div class="list-group mt-3">
                                    @foreach ($orderProcess as $process)
                                        <div class="mb-3">
                                            <button type="button"
                                                class="list-group-item list-group-item-action rounded process"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseProcess{{ $process->id }}" aria-expanded="false"
                                                aria-controls="collapseProcess{{ $process->id }}">
                                                <p class="name">Name&nbsp;&nbsp;: {{ $process->name }}</p>
                                                <p class="email">Email&nbsp;&nbsp;&nbsp;: {{ $process->email }}</p>
                                                <p class="id-pesanan mb-2 mt-3">ID Pesanan: {{ $process->id_pesanan }}</p>
                                                <small
                                                    class="text-muted">{{ $process->getTimeAgo($process->created_at) }}</small>
                                            </button>

                                            <div class="collapse" id="collapseProcess{{ $process->id }}">
                                                <div class="card mt-3">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-0">Order Detail</h5>

                                                        <h5 class="card-text m-0 mt-3 mb-1"
                                                            style="font-size: 16px; font-weight: 400; color: #151515;">
                                                            Total Item&nbsp;&nbsp;: {{ $process->orderDetails->count() }}
                                                        </h5>

                                                        <h5 class="card-text m-0 mb-1"
                                                            style="font-size: 16px; font-weight: 400; color: #151515;">
                                                            Payment Method&nbsp;&nbsp;:
                                                            @if ($process->payment_method === 'm_banking')
                                                                Mobile Banking BRI
                                                            @elseif ($process->payment_method === 'e_wallet')
                                                                E-wallet Dana
                                                            @else
                                                                -
                                                            @endif
                                                        </h5>

                                                        <h5 class="card-text m-0 mb-1"
                                                            style="font-size: 16px; font-weight: 400; color: #151515;">
                                                            Payment Proof&nbsp;&nbsp;:
                                                            @if ($process->payment_proof)
                                                                <a href="{{ asset($process->payment_proof) }}"
                                                                    class="text-decoration-none" download>Download</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </h5>

                                                        <h5 class="card-text m-0 mb-1"
                                                            style="font-size: 16px; font-weight: 400; color: #151515;">
                                                            Payment Status&nbsp;&nbsp;: {{ $process->payment_status }}
                                                        </h5>

                                                        @foreach ($process->orderDetails as $item)
                                                            @php
                                                                $total += $item->ebook->price * $item->quantity;
                                                            @endphp
                                                        @endforeach

                                                        <h5 class="card-title m-0 subtotal mb-1">Subtotal&nbsp;&nbsp;:
                                                            {{ 'Rp ' . number_format($total, 0, ',', '.') }}</h5>
                                                        <h5 class="card-title m-0 total">Total&nbsp;&nbsp;:
                                                            {{ 'Rp ' . number_format($total, 0, ',', '.') }}</h5>

                                                        {{-- table --}}
                                                        <div class="table-responsive mt-4">
                                                            <table class="table table-borderless align-middle mb-0">
                                                                <thead class="table-custom">
                                                                    <tr>
                                                                        <th class="text-center">No</th>
                                                                        <th>Title</th>
                                                                        <th>Thumbnail</th>
                                                                        <th class="text-center">Status</th>
                                                                        <th class="text-center">Quantity</th>
                                                                        <th>Price</th>
                                                                        <th class="text-center">Password Ebook</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    @foreach ($process->orderDetails as $item)
                                                                        <tr class="hoverable-row tr-padding">
                                                                            <td class="text-center">{{ $loop->iteration }}
                                                                            </td>
                                                                            <td>
                                                                                <a href="{{ route('ebook.detail', $item->ebook->slug) }}"
                                                                                    class="text-reset text-decoration-none">{{ $item->ebook->title }}</a>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#" data-bs-toggle="modal"
                                                                                    data-bs-target="#thumbnailModal{{ $item->id }}"
                                                                                    class="text-decoration-none">{{ __('Thumbnail Preview') }}</a>

                                                                                <!-- Modal Thumbnail -->
                                                                                <div class="modal fade"
                                                                                    id="thumbnailModal{{ $item->id }}"
                                                                                    tabindex="-1"
                                                                                    aria-labelledby="thumbnailModalLabel{{ $item->id }}"
                                                                                    aria-hidden="true">
                                                                                    <div
                                                                                        class="modal-dialog modal-dialog-scrollable">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h1 class="modal-title fs-5"
                                                                                                    id="thumbnailModalLabel{{ $item->id }}">
                                                                                                    Thumbnail Preview</h1>
                                                                                                <button type="button"
                                                                                                    class="btn-close"
                                                                                                    data-bs-dismiss="modal"
                                                                                                    aria-label="Close"></button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <img src="{{ asset($item->ebook->thumbnail) }}"
                                                                                                    alt="Thumbnail Ebook"
                                                                                                    class="img-fluid">
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button"
                                                                                                    class="btn btn-sm btn-secondary"
                                                                                                    data-bs-dismiss="modal">Close</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                            <td class="text-center">
                                                                                @if ($item->ebook->status === 'free')
                                                                                    <span
                                                                                        class="badge text-bg-primary">{{ $item->ebook->status }}</span>
                                                                                @else
                                                                                    <span
                                                                                        class="badge text-bg-success">{{ $item->ebook->status }}</span>
                                                                                @endif
                                                                            </td>

                                                                            <td class="text-center">x{{ $item->quantity }}
                                                                            </td>

                                                                            <td>{{ $item->ebook->status === 'free' ? 'Free' : 'Rp ' . number_format($item->ebook->price, 0, ',', '.') }}
                                                                            </td>

                                                                            <td class="text-center">
                                                                                {{ $item->ebook->password ? $item->ebook->password : '-' }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-4 py-5 d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4" width="200px"
                                        alt="Empty">
                                    <h4>{{ __('No data found') }}</h4>
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- approved --}}
                    <div class="tab-pane fade @if (Request::is('my-order/approved')) show active @endif" role="tabpanel">
                        @if (Request::is('my-order/approved'))
                            @php
                                $total = 0;
                            @endphp
                            @if ($orderApproved->count() > 0)
                                <h5 class="mt-4">Total Approved Orders ({{ $orderApproved->count() }})</h5>
                                <div class="list-group mt-3">
                                    @foreach ($orderApproved as $approved)
                                        <div class="mb-3">
                                            <button type="button" id="button-{{ $approved->id }}"
                                                class="list-group-item list-group-item-action rounded approved"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseApproved{{ $approved->id }}"
                                                aria-expanded="false"
                                                aria-controls="collapseApproved{{ $approved->id }}">
                                                <p class="name">Name&nbsp;&nbsp;: {{ $approved->name }}</p>
                                                <p class="email">Email&nbsp;&nbsp;&nbsp;: {{ $approved->email }}</p>
                                                <p class="id-pesanan mb-2 mt-3">ID Pesanan: {{ $approved->id_pesanan }}
                                                </p>
                                                <small
                                                    class="text-muted">{{ $approved->getTimeAgo($approved->created_at) }}</small>
                                            </button>

                                            <div class="collapse collapse-approved"
                                                id="collapseApproved{{ $approved->id }}">
                                                <div class="card mt-3">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-0">Order Detail</h5>

                                                        <h5 class="card-text m-0 mt-3 mb-1"
                                                            style="font-size: 16px; font-weight: 400; color: #151515;">
                                                            Total Item&nbsp;&nbsp;: {{ $approved->orderDetails->count() }}
                                                        </h5>

                                                        <h5 class="card-text m-0 mb-1"
                                                            style="font-size: 16px; font-weight: 400; color: #151515;">
                                                            Payment Method&nbsp;&nbsp;:
                                                            @if ($approved->payment_method === 'm_banking')
                                                                Mobile Banking BRI
                                                            @elseif ($approved->payment_method === 'e_wallet')
                                                                E-wallet Dana
                                                            @else
                                                                -
                                                            @endif
                                                        </h5>

                                                        <h5 class="card-text m-0 mb-1"
                                                            style="font-size: 16px; font-weight: 400; color: #151515;">
                                                            Payment Proof&nbsp;&nbsp;:
                                                            @if ($approved->payment_proof)
                                                                <a href="{{ asset($approved->payment_proof) }}"
                                                                    class="text-decoration-none" download>Download</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </h5>

                                                        <h5 class="card-text m-0 mb-1"
                                                            style="font-size: 16px; font-weight: 400; color: #151515;">
                                                            Payment Status&nbsp;&nbsp;: {{ $approved->payment_status }}
                                                        </h5>

                                                        @foreach ($approved->orderDetails as $item)
                                                            @php
                                                                $total += $item->ebook->price * $item->quantity;
                                                            @endphp
                                                        @endforeach

                                                        <h5 class="card-title m-0 subtotal mb-1">Subtotal&nbsp;&nbsp;:
                                                            {{ 'Rp ' . number_format($total, 0, ',', '.') }}</h5>
                                                        <h5 class="card-title m-0 total">Total&nbsp;&nbsp;:
                                                            {{ 'Rp ' . number_format($total, 0, ',', '.') }}</h5>

                                                        {{-- table --}}
                                                        <div class="table-responsive mt-4">
                                                            <table
                                                                class="table table-borderless align-middle mb-0 text-nowrap">
                                                                <thead class="table-custom">
                                                                    <tr>
                                                                        <th class="text-center">No</th>
                                                                        <th>Title</th>
                                                                        <th>Thumbnail</th>
                                                                        <th class="text-center">Status</th>
                                                                        <th class="text-center">Quantity</th>
                                                                        <th>Price</th>
                                                                        <th class="text-center">Password Ebook</th>
                                                                        <th class="text-center">Rate Ebook</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    @foreach ($approved->orderDetails as $item)
                                                                        <tr class="hoverable-row tr-padding">
                                                                            <td class="text-center">{{ $loop->iteration }}
                                                                            </td>
                                                                            <td>
                                                                                <a href="{{ route('ebook.detail', $item->ebook->slug) }}"
                                                                                    class="text-reset text-decoration-none">{{ $item->ebook->title }}</a>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#" data-bs-toggle="modal"
                                                                                    data-bs-target="#thumbnailModal{{ $item->id }}"
                                                                                    class="text-decoration-none">{{ __('Thumbnail Preview') }}</a>

                                                                                <!-- Modal Thumbnail -->
                                                                                <div class="modal fade"
                                                                                    id="thumbnailModal{{ $item->id }}"
                                                                                    tabindex="-1"
                                                                                    aria-labelledby="thumbnailModalLabel{{ $item->id }}"
                                                                                    aria-hidden="true">
                                                                                    <div
                                                                                        class="modal-dialog modal-dialog-scrollable">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h1 class="modal-title fs-5"
                                                                                                    id="thumbnailModalLabel{{ $item->id }}">
                                                                                                    Thumbnail Preview</h1>
                                                                                                <button type="button"
                                                                                                    class="btn-close"
                                                                                                    data-bs-dismiss="modal"
                                                                                                    aria-label="Close"></button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <img src="{{ asset($item->ebook->thumbnail) }}"
                                                                                                    alt="Thumbnail Ebook"
                                                                                                    class="img-fluid">
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button"
                                                                                                    class="btn btn-sm btn-secondary"
                                                                                                    data-bs-dismiss="modal">Close</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                            <td class="text-center">
                                                                                @if ($item->ebook->status === 'free')
                                                                                    <span
                                                                                        class="badge text-bg-primary">{{ $item->ebook->status }}</span>
                                                                                @else
                                                                                    <span
                                                                                        class="badge text-bg-success">{{ $item->ebook->status }}</span>
                                                                                @endif
                                                                            </td>

                                                                            <td class="text-center">x{{ $item->quantity }}
                                                                            </td>

                                                                            <td>{{ $item->ebook->status === 'free' ? 'Free' : 'Rp ' . number_format($item->ebook->price, 0, ',', '.') }}
                                                                            </td>

                                                                            <td class="text-center">
                                                                                {{ $item->ebook->password ? $item->ebook->password : '-' }}
                                                                            </td>

                                                                            <td class="text-center">
                                                                                @if ($item->ebook->ratings)
                                                                                    <a href="#"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#myReviewModal{{ $item->id }}"
                                                                                        class="text-decoration-none">{{ __('My Review') }}</a>

                                                                                    <!-- modal show review -->
                                                                                    <div class="modal fade"
                                                                                        id="myReviewModal{{ $item->id }}"
                                                                                        tabindex="-1"
                                                                                        aria-labelledby="myReviewModalLabel{{ $item->id }}"
                                                                                        aria-hidden="true">
                                                                                        <div
                                                                                            class="modal-dialog modal-dialog-scrollable">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h1 class="modal-title fs-5"
                                                                                                        id="myReviewModalLabel{{ $item->id }}">
                                                                                                        My Review
                                                                                                    </h1>
                                                                                                    <button type="button"
                                                                                                        class="btn-close"
                                                                                                        data-bs-dismiss="modal"
                                                                                                        aria-label="Close"></button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    @php
                                                                                                        $rating = $item->ebook->ratings->rating;
                                                                                                        $fullStars = floor($rating);
                                                                                                        $halfStar = ceil($rating - $fullStars);
                                                                                                        $emptyStars = 5 - $fullStars - $halfStar;
                                                                                                    @endphp

                                                                                                    <p class="m-0"
                                                                                                        style="font-size: 32px;">
                                                                                                        @for ($i = 1; $i <= $fullStars; $i++)
                                                                                                            <i class="bi bi-star-fill"
                                                                                                                style="color: #42B549;"></i>
                                                                                                        @endfor

                                                                                                        @for ($i = 1; $i <= $halfStar; $i++)
                                                                                                            <i
                                                                                                                class="bi bi-star-half"></i>
                                                                                                        @endfor

                                                                                                        @for ($i = 1; $i <= $emptyStars; $i++)
                                                                                                            <i
                                                                                                                class="bi bi-star"></i>
                                                                                                        @endfor
                                                                                                    </p>

                                                                                                    <h5
                                                                                                        class="text-start mt-3 mb-0 mb-1">
                                                                                                        Message</h5>
                                                                                                    <p
                                                                                                        class="text-start mb-0">
                                                                                                        {{ $item->ebook->ratings->review }}
                                                                                                    </p>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button
                                                                                                        class="btn btn-sm btn-warning"
                                                                                                        data-bs-target="#updateDataModal{{ $item->ebook->ratings->id }}"
                                                                                                        data-bs-toggle="modal">Update</button>
                                                                                                    <button type="button"
                                                                                                        class="btn btn-sm btn-secondary"
                                                                                                        data-bs-dismiss="modal">Close</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    {{-- modal update --}}
                                                                                    <div class="modal fade"
                                                                                        id="updateDataModal{{ $item->ebook->ratings->id }}"
                                                                                        aria-hidden="true"
                                                                                        aria-labelledby="updateDataModalLabel{{ $item->ebook->ratings->id }}"
                                                                                        tabindex="-1">
                                                                                        <div
                                                                                            class="modal-dialog modal-dialog-scrollable">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h1 class="modal-title fs-5"
                                                                                                        id="updateDataModalLabel{{ $item->ebook->ratings->id }}">
                                                                                                        Update Review</h1>
                                                                                                    <button type="button"
                                                                                                        class="btn-close"
                                                                                                        data-bs-dismiss="modal"
                                                                                                        aria-label="Close"></button>
                                                                                                </div>
                                                                                                <div class="modal-body text-start"
                                                                                                    style="text-align: left !important;">
                                                                                                    <form action="{{ route('order.approved_update_review', $item->ebook->ratings->id) }}"
                                                                                                        method="post">
                                                                                                        @csrf
                                                                                                        @method('put')

                                                                                                        {{-- rating star --}}
                                                                                                        <div
                                                                                                            class="mb-3">
                                                                                                            <label
                                                                                                                for="rating"
                                                                                                                class="form-label">Rating
                                                                                                                Star <span class="text-danger">*</span></label>
                                                                                                            <div
                                                                                                                id="ratingStar{{ $item->ebook->ratings->id }}">
                                                                                                            </div>
                                                                                                            <input
                                                                                                                type="hidden"
                                                                                                                name="rating"
                                                                                                                id="rating{{ $item->ebook->ratings->id }}"
                                                                                                                value="{{ old('rating', $item->ebook->ratings->rating) }}">
                                                                                                        </div>

                                                                                                        {{-- review --}}
                                                                                                        <div
                                                                                                            class="mb-3">
                                                                                                            <label
                                                                                                                for="review"
                                                                                                                class="form-label">Review <span class="text-danger">*</span></label>

                                                                                                            <textarea name="review" id="review" class="form-control" rows="5">{{ old('review', $item->ebook->ratings->review) }}</textarea>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button"
                                                                                                        class="btn btn-sm btn-dark"
                                                                                                        data-bs-target="#myReviewModal{{ $item->id }}"
                                                                                                        data-bs-toggle="modal">Back
                                                                                                        to Review</button>
                                                                                                    <button type="submit"
                                                                                                        class="btn btn-sm btn-warning">Update</button>
                                                                                                </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <script>
                                                                                        $(function() {
                                                                                            $("#ratingStar{{ $item->ebook->ratings->id }}").rateYo({
                                                                                                rating: $("#rating{{ $item->ebook->ratings->id }}").val(),
                                                                                                fullStar: true,
                                                                                                ratedFill: "#42B549",
                                                                                                onSet: function(rating, rateYoInstance) {
                                                                                                    $("#rating{{ $item->ebook->ratings->id }}").val(rating);
                                                                                                }
                                                                                            });
                                                                                        });
                                                                                    </script>
                                                                                @else
                                                                                    <a href="#"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#createDataModal{{ $item->id }}"
                                                                                        class="text-decoration-none">{{ __('Beri Ulasan') }}</a>

                                                                                    {{-- modal create --}}
                                                                                    <div class="modal fade"
                                                                                        id="createDataModal{{ $item->id }}"
                                                                                        aria-hidden="true"
                                                                                        aria-labelledby="createDataModalLabel{{ $item->id }}"
                                                                                        tabindex="-1">
                                                                                        <div
                                                                                            class="modal-dialog modal-dialog-scrollable">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h1 class="modal-title fs-5"
                                                                                                        id="createDataModalLabel{{ $item->id }}">
                                                                                                        Insert Review</h1>
                                                                                                    <button type="button"
                                                                                                        class="btn-close"
                                                                                                        data-bs-dismiss="modal"
                                                                                                        aria-label="Close"></button>
                                                                                                </div>
                                                                                                <div class="modal-body text-start"
                                                                                                    style="text-align: left !important;">
                                                                                                    @if ($errors->any())
                                                                                                        <div class="alert alert-danger" role="alert">
                                                                                                            <ul class="m-0">
                                                                                                                @foreach ($errors->all() as $error)
                                                                                                                    <li>{{ $error }}</li>
                                                                                                                @endforeach
                                                                                                            </ul>
                                                                                                        </div>
                                                                                                    @endif

                                                                                                    <form action="{{ route('order.approved_insert_review') }}"
                                                                                                        method="post">
                                                                                                        @csrf
                                                                                                        <input type="hidden" name="ebooks_id" value="{{ $item->ebook->id }}">

                                                                                                        {{-- rating star --}}
                                                                                                        <div
                                                                                                            class="mb-3">
                                                                                                            <label
                                                                                                                for="rating"
                                                                                                                class="form-label">Rating
                                                                                                                Star <span class="text-danger">*</span></label>
                                                                                                            <div
                                                                                                                id="ratingStar">
                                                                                                            </div>
                                                                                                            <input
                                                                                                                type="hidden"
                                                                                                                name="rating"
                                                                                                                id="rating"
                                                                                                                value="{{ old('rating', 0) }}">
                                                                                                        </div>

                                                                                                        {{-- review --}}
                                                                                                        <div
                                                                                                            class="mb-3">
                                                                                                            <label
                                                                                                                for="review"
                                                                                                                class="form-label">Review <span class="text-danger">*</span></label>

                                                                                                            <textarea name="review" id="review" class="form-control" rows="5" required>{{ old('review') }}</textarea>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button"
                                                                                                    class="btn btn-sm btn-secondary"
                                                                                                    data-bs-dismiss="modal">Close</button>
                                                                                                    <button type="submit"
                                                                                                        class="btn btn-sm btn-success">Insert</button>
                                                                                                </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <script>
                                                                                        $(function() {
                                                                                            $("#ratingStar").rateYo({
                                                                                                rating: $("#rating").val(),
                                                                                                fullStar: true,
                                                                                                ratedFill: "#42B549",
                                                                                                onSet: function(rating, rateYoInstance) {
                                                                                                    $("#rating").val(rating);
                                                                                                }
                                                                                            });
                                                                                        });
                                                                                    </script>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-4 py-5 d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4" width="200px"
                                        alt="Empty">
                                    <h4>{{ __('No data found') }}</h4>
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- rejected --}}
                    <div class="tab-pane fade @if (Request::is('my-order/rejected')) show active @endif" role="tabpanel">
                        @if (Request::is('my-order/rejected'))
                            @php
                                $total = 0;
                            @endphp
                            @if ($orderRejected->count() > 0)
                                <div class="list-group mt-4">
                                    @foreach ($orderRejected as $rejected)
                                        <div class="mb-3">
                                            <button type="button" id="button-{{ $rejected->id }}"
                                                class="list-group-item list-group-item-action rounded rejected"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseRejected{{ $rejected->id }}"
                                                aria-expanded="false"
                                                aria-controls="collapseRejected{{ $rejected->id }}">
                                                <p class="name">Name&nbsp;&nbsp;: {{ $rejected->name }}</p>
                                                <p class="email">Email&nbsp;&nbsp;&nbsp;: {{ $rejected->email }}</p>
                                                <p class="id-pesanan mb-2 mt-3">ID Pesanan: {{ $rejected->id_pesanan }}
                                                </p>
                                                <small
                                                    class="text-muted">{{ $rejected->getTimeAgo($rejected->created_at) }}</small>
                                            </button>

                                            <div class="collapse collapse-approved"
                                                id="collapseRejected{{ $rejected->id }}">
                                                <div class="card card-body mt-3">
                                                    <h5 class="card-title mb-0">Order Detail</h5>

                                                    <h5 class="card-text m-0 mt-3 mb-1"
                                                        style="font-size: 16px; font-weight: 400; color: #151515;">
                                                        Total Item&nbsp;&nbsp;: {{ $rejected->orderDetails->count() }}
                                                    </h5>

                                                    <h5 class="card-text m-0 mb-1"
                                                        style="font-size: 16px; font-weight: 400; color: #151515;">
                                                        Payment Method&nbsp;&nbsp;:
                                                        @if ($rejected->payment_method === 'm_banking')
                                                            Mobile Banking BRI
                                                        @elseif ($rejected->payment_method === 'e_wallet')
                                                            E-wallet Dana
                                                        @else
                                                            -
                                                        @endif
                                                    </h5>

                                                    <h5 class="card-text m-0 mb-1"
                                                        style="font-size: 16px; font-weight: 400; color: #151515;">
                                                        Payment Proof&nbsp;&nbsp;:
                                                        @if ($rejected->payment_proof)
                                                            <a href="{{ asset($rejected->payment_proof) }}"
                                                                class="text-decoration-none" download>Download</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </h5>

                                                    <h5 class="card-text m-0 mb-1"
                                                        style="font-size: 16px; font-weight: 400; color: #151515;">
                                                        Payment Status&nbsp;&nbsp;: {{ $rejected->payment_status }}
                                                    </h5>

                                                    @foreach ($rejected->orderDetails as $item)
                                                        @php
                                                            $total += $item->ebook->price * $item->quantity;
                                                        @endphp
                                                    @endforeach

                                                    <h5 class="card-title m-0 subtotal mb-1">Subtotal&nbsp;&nbsp;:
                                                        {{ 'Rp ' . number_format($total, 0, ',', '.') }}</h5>
                                                    <h5 class="card-title m-0 total">Total&nbsp;&nbsp;:
                                                        {{ 'Rp ' . number_format($total, 0, ',', '.') }}</h5>

                                                    {{-- table --}}
                                                    <div class="table-responsive mt-4">
                                                        <table class="table table-borderless align-middle mb-0">
                                                            <thead class="table-custom">
                                                                <tr>
                                                                    <th class="text-center">No</th>
                                                                    <th>Title</th>
                                                                    <th>Thumbnail</th>
                                                                    <th class="text-center">Status</th>
                                                                    <th class="text-center">Quantity</th>
                                                                    <th>Price</th>
                                                                    <th class="text-center">Password Ebook</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @foreach ($rejected->orderDetails as $item)
                                                                    <tr class="hoverable-row tr-padding">
                                                                        <td class="text-center">{{ $loop->iteration }}
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('ebook.detail', $item->ebook->slug) }}"
                                                                                class="text-reset text-decoration-none">{{ $item->ebook->title }}</a>
                                                                        </td>
                                                                        <td>
                                                                            <a href="#" data-bs-toggle="modal"
                                                                                data-bs-target="#thumbnailModal{{ $item->id }}"
                                                                                class="text-decoration-none">{{ __('Thumbnail Preview') }}</a>

                                                                            <!-- Modal Thumbnail -->
                                                                            <div class="modal fade"
                                                                                id="thumbnailModal{{ $item->id }}"
                                                                                tabindex="-1"
                                                                                aria-labelledby="thumbnailModalLabel{{ $item->id }}"
                                                                                aria-hidden="true">
                                                                                <div
                                                                                    class="modal-dialog modal-dialog-scrollable">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h1 class="modal-title fs-5"
                                                                                                id="thumbnailModalLabel{{ $item->id }}">
                                                                                                Thumbnail Preview</h1>
                                                                                            <button type="button"
                                                                                                class="btn-close"
                                                                                                data-bs-dismiss="modal"
                                                                                                aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <img src="{{ asset($item->ebook->thumbnail) }}"
                                                                                                alt="Thumbnail Ebook"
                                                                                                class="img-fluid">
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-bs-dismiss="modal">Close</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>

                                                                        <td class="text-center">
                                                                            @if ($item->ebook->status === 'free')
                                                                                <span
                                                                                    class="badge text-bg-primary">{{ $item->ebook->status }}</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge text-bg-success">{{ $item->ebook->status }}</span>
                                                                            @endif
                                                                        </td>

                                                                        <td class="text-center">x{{ $item->quantity }}
                                                                        </td>

                                                                        <td>{{ $item->ebook->status === 'free' ? 'Free' : 'Rp ' . number_format($item->ebook->price, 0, ',', '.') }}
                                                                        </td>

                                                                        <td class="text-center">
                                                                            {{ $item->ebook->password ? $item->ebook->password : '-' }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-4 py-5 d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4" width="200px"
                                        alt="Empty">
                                    <h4>{{ __('No data found') }}</h4>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@prepend('scripts')
    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js') }}"></script>

    {{-- rateyo js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js') }}"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "8000",
            "extendedTimeOut": "8000",
        }

        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif
    </script>
@endprepend

@extends('home.index')

@prepend('styles')
    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css') }}">

    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css') }}" />

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

        .btn-close:focus {
            outline: 0;
            box-shadow: none;
        }

        .thumbnail {
            height: 250px;
            overflow: hidden;
        }

        .thumbnail img {
            padding: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: left;
        }

        .card-title.isbn,
        .penulis {
            font-size: 16px;
            color: #6D6D6D;
            font-weight: 400;
        }

        .penulis a {
            color: #6D6D6D;
        }

        .penulis a:hover {
            text-decoration: underline !important;
        }

        .card-title.title {
            color: #151515;
            font-size: 24px;
            font-weight: 400;
        }

        .card-title.harga {
            color: #42B549;
            margin-top: 12px;
        }

        .card-text span {
            display: block;
            font-size: 16px;
            font-weight: 500;
        }

        .card-text {
            font-size: 14px;
            color: #151515;
            line-height: 24px;
        }

        @media (min-width: 576px) {}

        @media (min-width: 768px) {
            .section-header .container h2 {
                font-size: 28px;
            }

            .thumbnail {
                height: auto;
                padding: 20px;
                overflow: visible;
                border-radius: 18px;
                background-color: white;
                box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
            }

            .thumbnail img {
                object-fit: cover;
            }

            .card {
                margin-top: 20px !important;
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
            <h2>Detail Ebook</span></h2>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"
                            class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('ebooks') }}"
                            class="text-white text-decoration-none">Ebook</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Ebook</li>
                </ol>
            </nav>
        </div>
    </header>
@endsection

@section('main')
    <main>
        <section>
            <div class="container">
                @if (Session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ Session('error') }}
                    </div>
                @endif

                <h3 class="section-title">Detail Ebook</h3>

                <div class="col-lg-10 col-xl-8">
                    <div class="row">
                        <div class="col-md-5 col-xl-4 thumbnail">
                            <img src="{{ asset($ebook->thumbnail) }}" class="w-100" alt="Thumbnail Ebook">
                        </div>

                        <div class="col-md-7 col-xl-8">
                            <div class="card ms-md-3 border-0 bg-transparent mt-4 mt-md-0">
                                <div class="card-body p-0">
                                    {{-- isbn --}}
                                    @if ($ebook->isbn)
                                        <h5 class="card-title isbn mb-0">ISBN {{ $ebook->isbn }}</h5>
                                    @endif

                                    {{-- penulis --}}
                                    <div class="penulis">
                                        Author: @foreach ($ebook->authors as $key => $author)
                                            <a href="{{ route('author.profile', $author->user->username) }}"
                                                class="text-decoration-none">{{ $author->user->fullname }}</a>@if ($key < count($ebook->authors) - 1),&nbsp;@endif
                                        @endforeach
                                    </div>

                                    {{-- avg rating --}}
                                    <span class="badge text-bg-dark mt-3"><i class="bi bi-star-fill text-warning"></i>&nbsp;&nbsp;{{ $averageRating }}</span>

                                    {{--  title --}}
                                    <h5 class="card-title title mt-2">{{ $ebook->title }}</h5>

                                    {{-- harga --}}
                                    <h5 class="card-title harga mb-0 mb-1">
                                        {{ $ebook->status === 'free' ? 'Free' : 'Rp ' . number_format($ebook->price, 0, ',', '.') }}
                                    </h5>

                                    {{-- jumlah terjual or jumlah terdownload --}}
                                    <p class="card-text">
                                        @if ($ebookCount >= 1000)
                                            @if ($ebook->status === 'free')
                                                {{ number_format($ebookCount / 1000, 0, ',', '.') }}RB+&nbsp;terdownload
                                            @else
                                                {{ number_format($ebookCount / 1000, 0, ',', '.') }}RB+&nbsp;terjual
                                            @endif
                                        @else
                                            @if ($ebook->status === 'free')
                                                {{ $ebookCount }}&nbsp;terdownload
                                            @else
                                                {{ $ebookCount }}&nbsp;terjual
                                            @endif
                                        @endif
                                    </p>

                                    {{-- description --}}
                                    <p class="card-text"><span>Deskripsi Ebook</span>{{ $ebook->description }}</p>

                                    {{-- kategori --}}
                                    <div class="d-block mb-4">
                                        @foreach ($ebook->categories as $category)
                                            <a href="{{ route('category.get_ebook', $category->slug) }}"
                                                class="badge rounded-pill text-bg-dark text-decoration-none">{{ $category->name }}</a>
                                        @endforeach
                                    </div>

                                    @if ($ebook->status === 'free')
                                        <form action="{{ route('ebook.download_free') }}" method="post">
                                            @csrf

                                            <input type="hidden" name="ebooks_id" value="{{ $ebook->id }}">
                                            <button type="submit" class="btn btn-sm btn-warning">Download PDF</button>
                                        </form>
                                    @else
                                        <form action="{{ route('cart.store') }}" method="post">
                                            @csrf

                                            <input type="hidden" name="ebooks_id" value="{{ $ebook->id }}">
                                            <button type="submit" class="btn btn-sm btn-warning">Add to Cart</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-5">
            <div class="container">
                <h3 class="section-title">Review Ebook</h3>

                @if ($ebook->ratings->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            @foreach ($ebook->ratings as $key => $item)
                                <h5 class="card-title mb-3" style="font-size: 16px; color: #151515;">{{ $item->user->fullname }}</h5>

                                @php
                                    $rating = $item->rating;
                                    $fullStars = floor($rating);
                                    $halfStar = ceil($rating - $fullStars);
                                    $emptyStars = 5 - $fullStars - $halfStar;
                                @endphp

                                <h5 class="card-title mb-0 mb-1">
                                    @for ($i = 1; $i <= $fullStars; $i++)
                                        <i class="bi bi-star-fill" style="color: #42B549;"></i>
                                    @endfor

                                    @for ($i = 1; $i <= $halfStar; $i++)
                                        <i class="bi bi-star-half"></i>
                                    @endfor

                                    @for ($i = 1; $i <= $emptyStars; $i++)
                                        <i class="bi bi-star"></i>
                                    @endfor
                                </h5>

                                <p class="card-text mb-0">{{ $item->review }}</p>@if ($key < count($ebook->ratings) - 1)<hr>@endif
                            @endforeach
                        </div>
                    </div>
                @else
                    Belum mempunyai rating
                @endif
            </div>
        </section>
    </main>
@endsection

@prepend('scripts')
    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js') }}"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "8000",
            "extendedTimeOut": "8000",
        }

        @if (Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
    </script>
@endprepend

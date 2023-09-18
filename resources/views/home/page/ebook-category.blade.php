@extends('home.index')

@prepend('styles')
    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css') }}">

    {{-- slick css --}}
    <link rel="stylesheet" type="text/css"
        href="{{ url('https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ url('https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css') }}" />

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

        .card {
            cursor: pointer;
            border-radius: 0.625rem;
        }

        .card:hover {
            box-shadow: 6px 0px 14px rgba(42, 243, 55, 0.15), -6px 0px 14px rgba(11, 250, 27, 0.15);
        }

        .card .card-img-top {
            height: 250px;
            object-fit: contain;
        }

        .card .ribbon-wrapper {
            height: 70px;
            overflow: hidden;
            position: absolute;
            right: -2px;
            top: -2px;
            width: 70px;
            z-index: 10;
        }

        .card .ribbon-wrapper .ribbon {
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
            font-size: 0.8rem;
            line-height: 100%;
            padding: 0.375rem 0;
            position: relative;
            right: -2px;
            text-align: center;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.4);
            text-transform: uppercase;
            top: 10px;
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
            width: 90px;
        }

        .card .ribbon-wrapper .ribbon::before,
        .ribbon-wrapper .ribbon::after {
            border-left: 3px solid transparent;
            border-right: 3px solid transparent;
            border-top: 3px solid #9e9e9e;
            bottom: -3px;
            content: "";
            position: absolute;
        }

        .card .ribbon-wrapper .ribbon::before {
            left: 0;
        }

        .card .ribbon-wrapper .ribbon::after {
            right: 0;
        }

        .card .card-body .penulis {
            font-size: 12px;
            color: #6D6D6D;
            font-weight: 400;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
        }

        .card .card-body .penulis a {
            color: #94a3b8;
            text-decoration: none;
        }

        .card .card-body .penulis a:hover {
            text-decoration: underline;
        }

        .card .card-body .title {
            font-size: 14px;
            font-weight: 400;
            color: #151515;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
        }

        .card .card-body .card-text.harga {
            color: #42B549;
            font-weight: 500;
        }

        @media (max-width: 425px) {
            .row-cols-2>* {
                flex: 0 0 auto;
                width: 100%;
            }
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

            .card {
                border: 1px solid transparent;
            }

            .card:hover {
                border: 1px solid #42B549;
            }
        }

        @media (min-width: 1200px) {}
    </style>
@endprepend

@section('header')
    <header class="section-header">
        <div class="container d-flex justify-content-center align-items-center flex-column">
            <h2>Ebook Category</span></h2>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"
                            class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('categories') }}"
                            class="text-white text-decoration-none">Categories</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Ebook Category</li>
                </ol>
            </nav>
        </div>
    </header>
@endsection

@section('main')
    <main>
        <section>
            <div class="container">
                <h3 class="section-title">List of Ebooks by Category</h3>

                <div class="col-md-8 col-lg-6">
                    <h5>{{ $category->name }}</h5>
                    <p>{{ $category->description }}</p>
                </div>

                @if ($category->ebooks->count() > 0)
                    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 mt-4">
                        @foreach ($category->ebooks as $ebook)
                            <div class="col">
                                <div class="card h-100">
                                    <img src="{{ asset($ebook->thumbnail) }}" class="card-img-top" alt="Thumbnail Ebook">

                                    @if ($ebook->status === 'free')
                                        <div class="ribbon-wrapper">
                                            <div class="ribbon bg-info text-dark">
                                                Free
                                            </div>
                                        </div>
                                    @endif

                                    <div class="card-body">
                                        {{-- penulis --}}
                                        <div class="penulis">
                                            @foreach ($ebook->authors as $key => $author)
                                                <a href="{{ route('author.profile', $author->user->username) }}">{{ $author->user->fullname }}</a>@if ($key < count($ebook->authors) - 1),&nbsp;@endif
                                            @endforeach
                                        </div>


                                        {{-- title --}}
                                        <a href="{{ route('ebook.detail', $ebook->slug) }}" class="text-decoration-none">
                                            <h6 class="card-title title mt-2 mb-0 mb-1">{{ $ebook->title }}</h6>
                                        </a>

                                        <div class="d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between align-items-lg-center">
                                            {{-- harga --}}
                                            <p class="card-text harga mb-0">
                                                {{ $ebook->status === 'free' ? 'Free' : 'Rp ' . number_format($ebook->price, 0, ',', '.') }}
                                            </p>

                                            {{-- jumlah terjual or terdownload --}}
                                            <p class="card-text mb-0"
                                                style="font-size: 12px; color: #151515; font-weight: 500;">
                                                @if ($ebookCounts[$ebook->id] >= 1000)
                                                    @if ($ebook->status === 'free')
                                                        {{ number_format($ebookCounts[$ebook->id] / 1000, 0, ',', '.') }}RB+&nbsp;terdownload
                                                    @else
                                                        {{ number_format($ebookCounts[$ebook->id] / 1000, 0, ',', '.') }}RB+&nbsp;terjual
                                                    @endif
                                                @else
                                                    @if ($ebook->status === 'free')
                                                        {{ $ebookCounts[$ebook->id] }}&nbsp;terdownload
                                                    @else
                                                        {{ $ebookCounts[$ebook->id] }}&nbsp;terjual
                                                    @endif
                                                @endif
                                            </p>
                                        </div>

                                        {{-- avg rating --}}
                                        <span class="badge text-bg-dark mt-2"><i class="bi bi-star-fill text-warning"></i>&nbsp;&nbsp;{{ $averageRatings[$ebook->id] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="pt-5">
                        <center><img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4" width="200px" alt="Empty"></center>
                        <h5 class="text-center">{{ __('No ebooks found') }}</h5>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection

@prepend('scripts')
@endprepend

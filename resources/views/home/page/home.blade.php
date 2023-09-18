@extends('home.index')

@prepend('styles')
    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css') }}">

    {{-- slick css --}}
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />

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

        .section-header .container p {
            margin: 0;
            color: white;
            font-size: 15px;
            text-align: center;
        }

        /* .section-about, */
        .section-unggul,
        .section-rekomendasi,
        .section-populer {
            margin-bottom: 3rem;
        }

        /* .section-about p {
                                                                                                                                                        color: #444444;
                                                                                                                                                    } */

        /* .section-about .section-title, */
        .section-unggul .section-title,
        .section-rekomendasi .section-title,
        .section-populer .section-title,
        .section-testimoni .section-title {
            color: #444444;
            position: relative;
            margin-bottom: 3rem !important;
        }

        /* .section-about .section-title::after, */
        .section-unggul .section-title::after,
        .section-rekomendasi .section-title::after,
        .section-populer .section-title::after,
        .section-testimoni .section-title::after {
            content: "";
            position: absolute;
            left: 0;
            width: 80px;
            height: 5px;
            bottom: -15px;
            border-radius: 5px;
            background-color: #42B549;
        }

        .section-unggul .card .card-body i {
            font-size: 32px;
            font-weight: 500;
            color: #42B549;
        }

        .section-unggul .card .card-body .card-title {
            font-size: 16px;
            color: #1e293b;
        }

        .section-unggul .card .card-body .card-text {
            margin-top: 0.5rem;
            line-height: 18px;
        }

        .section-rekomendasi .section-title::after,
        .section-populer .section-title::after,
        .section-testimoni .section-title::after {
            left: 10px;
        }

        .section-rekomendasi .section-title,
        .section-populer .section-title,
        .section-testimoni .section-title {
            padding: 0 10px;
        }

        .ebook-slider .card {
            cursor: pointer;
            border-radius: 0.625rem;
        }

        .ebook-slider .card:hover {
            box-shadow: 6px 0px 14px rgba(42, 243, 55, 0.15), -6px 0px 14px rgba(11, 250, 27, 0.15);
        }

        .ebook-slider .card .card-img-top {
            height: 250px;
            object-fit: contain;
        }

        .ebook-slider .card .ribbon-wrapper {
            height: 70px;
            overflow: hidden;
            position: absolute;
            right: -2px;
            top: -2px;
            width: 70px;
            z-index: 10;
        }

        .ebook-slider .card .ribbon-wrapper .ribbon {
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

        .ebook-slider .card .ribbon-wrapper .ribbon::before,
        .ebook-slider .ribbon-wrapper .ribbon::after {
            border-left: 3px solid transparent;
            border-right: 3px solid transparent;
            border-top: 3px solid #9e9e9e;
            bottom: -3px;
            content: "";
            position: absolute;
        }

        .ebook-slider .card .ribbon-wrapper .ribbon::before {
            left: 0;
        }

        .ebook-slider .card .ribbon-wrapper .ribbon::after {
            right: 0;
        }

        .ebook-slider .card .card-body .penulis {
            font-size: 12px;
            color: #6D6D6D;
            font-weight: 400;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
        }

        .ebook-slider .card .card-body .penulis a {
            color: #94a3b8;
            text-decoration: none;
        }

        .ebook-slider .card .card-body .penulis a:hover {
            text-decoration: underline;
        }

        .ebook-slider .card .card-body .title {
            font-size: 14px;
            font-weight: 400;
            color: #151515;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
        }

        .ebook-slider .card .card-body .card-text.harga {
            color: #42B549;
            font-weight: 500;
        }

        .slick-slide {
            margin: 0 10px;
        }

        .slick-prev,
        .slick-next {
            display: none !important;
        }

        .slick-prev:before,
        .slick-next:before {
            color: #222;
        }

        .slick-dots {
            bottom: -35px;
        }

        .slick-dots li button:before {
            color: #42B549;
        }

        .slick-dots li.slick-active button:before {
            color: #42B549;
        }

        .section-subscribe {
            background: #000;
            background: linear-gradient(rgba(0, 0, 0, 0.3),
                    rgba(0, 0, 0, 0.8)), url('/assets/image/bg-parallax.jpg');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* .section-subscribe form .form-control {
                            border: none;
                            outline: none;
                            box-shadow: none;
                            padding: .5rem 1rem;
                        }

                        .section-subscribe form .form-control::placeholder {
                            color: #999;
                            font-style: italic;
                        } */

        .section-testimoni .testi_slider .card .card-body {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .section-testimoni .testi_slider .card .card-body .avatar-container {
            width: 100px;
            height: 100px;
            overflow: hidden;
        }

        .section-testimoni .testi_slider .card .card-body .avatar-container .avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .section-testimoni .testi_slider .card .card-body .card-title {
            margin-top: 1.5rem;
            font-size: 18px;
        }

        .section-testimoni .testi_slider .card .card-body .card-text {
            font-size: 14px;
        }

        .section-testimoni .testi_slider .card .card-body .card-title,
        .section-testimoni .testi_slider .card .card-body .card-text {
            color: #444444;
        }

        @media (min-width: 576px) {}

        @media (min-width: 768px) {
            .section-header .container h2 {
                font-size: 28px;
            }

            .section-header .container h2 span {
                display: inline !important;
            }

            .slick-prev,
            .slick-next {
                display: block !important;
            }

            .section-testimoni .testi_slider .card .card-body .avatar-container {
                width: 120px;
                height: 120px;
            }

            .section-testimoni .testi_slider .card .card-body .card-title {
                font-size: 18px;
            }

            .section-testimoni .testi_slider .card .card-body .card-text {
                font-size: 16px;
            }
        }

        @media (min-width: 992px) {
            .section-header {
                padding: 15vh 0;
            }

            .section-header .container h2 {
                font-size: 32px;
            }

            .section-unggul .card .card-body i {
                font-size: 38px;
            }

            .section-unggul .card .card-body .card-title {
                font-size: 18px;
            }

            .ebook-slider .card {
                border: 1px solid transparent;
            }

            .ebook-slider .card:hover {
                border: 1px solid #42B549;
            }

            .section-testimoni .testi_slider .card {
                height: 400px;
            }

            .section-testimoni .testi_slider .card .card-body {
                height: 100%;
                display: flex;
                padding: 0 4rem;
                align-items: center;
                flex-direction: column;
                justify-content: center;
            }

            .section-testimoni .testi_slider .card .card-body .card-title {
                font-size: 20px;
            }

            .section-testimoni .testi_slider .card .card-body .avatar-container {
                width: 140px;
                height: 140px;
            }
        }

        @media (min-width: 1200px) {
            .section-testimoni .testi_slider .card .card-body {
                padding-left: 5rem;
                padding-right: 5rem;
            }
        }
    </style>
@endprepend

@section('header')
    <header class="section-header">
        <div class="container">
            <h2>Selamat datang di <span style="display: block;">platform eBook kami!</span></h2>
            <p>Kami dengan bangga mempersembahkan kepada Anda
                website ebook yang lengkap dan mudah diakses</p>
        </div>
    </header>
@endsection

@section('main')
    <main>
        {{-- about us --}}
        {{-- <section class="section-about">
            <div class="container">
                <h3 class="section-title">Tentang Kami</h3>

                <div class="row flex-column-reverse flex-lg-row">
                    <div class="col-lg-6">
                        <p>Selamat datang di website eBook Pendidikan kami!
                            Kami memahami pentingnya kemudahan akses dan fleksibilitas dalam proses belajar. Oleh karena
                            itu, kami menyediakan eBook yang dapat diakses dengan mudah melalui perangkat digital apa pun.
                            Dengan tampilan yang menarik, fitur interaktif, dan konten berkualitas tinggi, kami berusaha
                            menciptakan pengalaman pembelajaran yang menarik dan efektif.
                            Bergabunglah dengan kami dalam menjelajahi dunia pengetahuan, menginspirasi pertumbuhan
                            intelektual, dan meraih potensi penuh Anda melalui pembelajaran yang menyenangkan dan bermakna.
                        </p>
                    </div>

                    <div class="mb-3 col-md-8 col-lg-6 mb-lg-0">
                        <img src="{{ asset('assets/image/cod.jpg') }}" alt="Image" class="img-fluid">
                    </div>
                </div>
            </div>
        </section> --}}

        {{-- Keunggulan --}}
        <section class="section-unggul">
            <div class="container">
                <h3 class="section-title">Keunggulan Kami</h3>

                <div
                    class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 justify-content-center g-3">
                    <div class="col">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="bi bi-patch-check"></i>
                                <h5 class="card-title mb-0">Buku Terverifikasi</h5>
                                <p class="card-text"><small>semua buku yang tersedia telah diverifikasi keasliannya</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="bi bi-stars"></i>
                                <h5 class="card-title mb-0">Buku Terpopuler</h5>
                                <p class="card-text"><small>buku-buku yang paling populer</small></p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="bi bi-hand-thumbs-up"></i>
                                <h5 class="card-title mb-0">Berbagai Genre Favorit</h5>
                                <p class="card-text"><small>buku-buku berdasarkan berbagai genre atau kategori
                                        favorit</small></p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="bi bi-shield-check"></i>
                                <h5 class="card-title mb-0">Pembayaran Terjamin</h5>
                                <p class="card-text"><small>menjamin keamanan dan kepercayaan dalam proses pembayaran.
                                    </small></p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="bi bi-book"></i>
                                <h5 class="card-title mb-0">Buku Gratis</h5>
                                <p class="card-text"><small>menyediakan koleksi buku-buku gratis</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- pembatas 1 --}}
        <div class="container">
            <hr class="m-0 mb-5" />
        </div>

        {{-- rekomendasi --}}
        <section class="section-rekomendasi">
            <div class="container">
                <h3 class="section-title">Gems of Literature: 10 Five-Star Rated Ebooks</h3>

                {{-- content --}}
                <div class="ebook-slider">
                    @if ($rekomendasiEbook->count() > 0)
                        @foreach ($rekomendasiEbook as $rekomendasi)
                            <div class="card h-100">
                                <img src="{{ asset($rekomendasi->thumbnail) }}" class="card-img-top" alt="Thumbnail Ebook">

                                @if ($rekomendasi->status === 'free')
                                    <div class="ribbon-wrapper">
                                        <div class="ribbon bg-info text-dark">
                                            Free
                                        </div>
                                    </div>
                                @endif

                                <div class="card-body">
                                    {{-- penulis --}}
                                    <div class="penulis">
                                        @foreach ($rekomendasi->authors as $key => $author)
                                            <a href="{{ route('author.profile', $author->user->username) }}">{{ $author->user->fullname }}</a>@if ($key < count($rekomendasi->authors) - 1),&nbsp;@endif
                                        @endforeach
                                    </div>

                                    {{-- title --}}
                                    <a href="{{ route('ebook.detail', $rekomendasi->slug) }}" class="text-decoration-none">
                                        <h6 class="card-title title mt-2 mb-0 mb-1">{{ $rekomendasi->title }}</h6>
                                    </a>

                                    <div
                                        class="d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between align-items-lg-center">
                                        {{-- harga --}}
                                        <p class="card-text harga mb-0">
                                            {{ $rekomendasi->status === 'free' ? 'Free' : 'Rp ' . number_format($rekomendasi->price, 0, ',', '.') }}
                                        </p>

                                        {{-- jumlah terjual or terdownload --}}
                                        <p class="card-text mb-0"
                                            style="font-size: 12px; color: #151515; font-weight: 500;">
                                            @if ($rekomendasiEbookCounts[$rekomendasi->id] >= 1000)
                                                @if ($rekomendasi->status === 'free')
                                                    {{ number_format($rekomendasiEbookCounts[$rekomendasi->id] / 1000, 0, ',', '.') }}RB+&nbsp;terdownload
                                                @else
                                                    {{ number_format($rekomendasiEbookCounts[$rekomendasi->id] / 1000, 0, ',', '.') }}RB+&nbsp;terjual
                                                @endif
                                            @else
                                                @if ($rekomendasi->status === 'free')
                                                    {{ $rekomendasiEbookCounts[$rekomendasi->id] }}&nbsp;terdownload
                                                @else
                                                    {{ $rekomendasiEbookCounts[$rekomendasi->id] }}&nbsp;terjual
                                                @endif
                                            @endif
                                        </p>
                                    </div>

                                    {{-- avg rating --}}
                                    <span class="badge text-bg-dark mt-2">
                                        <i
                                            class="bi bi-star-fill text-warning"></i>&nbsp;&nbsp;{{ $rekomendasiEbookAvgRatings[$rekomendasi->id] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        -
                    @endif
                </div>
            </div>
        </section>

        {{-- pembatas 2 --}}
        <div class="container">
            <hr class="m-0 mb-5" />
        </div>

        {{-- terpopuler --}}
        <section class="section-populer mb-5">
            <div class="container">
                <h3 class="section-title">Popular Picks: 10 Bestselling Ebook</h3>

                {{-- content --}}
                <div class="ebook-slider">
                    @if ($bestSeller->count() > 0)
                        @foreach ($bestSeller as $item)
                            <div class="card h-100">
                                <img src="{{ asset($item->thumbnail) }}" class="card-img-top" alt="Thumbnail Ebook">

                                @if ($item->status === 'free')
                                    <div class="ribbon-wrapper">
                                        <div class="ribbon bg-info text-dark">
                                            Free
                                        </div>
                                    </div>
                                @endif

                                <div class="card-body">
                                    {{-- penulis --}}
                                    <div class="penulis">
                                        @foreach ($item->authors as $key => $author)
                                            <a href="{{ route('author.profile', $author->user->username) }}">{{ $author->user->fullname }}</a>@if ($key < count($item->authors) - 1),&nbsp;@endif
                                        @endforeach
                                    </div>

                                    {{-- title --}}
                                    <a href="{{ route('ebook.detail', $item->slug) }}" class="text-decoration-none">
                                        <h6 class="card-title title mt-2 mb-0 mb-1">{{ $item->title }}</h6>
                                    </a>

                                    <div class="d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between align-items-lg-center">
                                        {{-- harga --}}
                                        <p class="card-text harga mb-0">
                                            {{ $item->status === 'free' ? 'Free' : 'Rp ' . number_format($item->price, 0, ',', '.') }}
                                        </p>

                                        {{-- jumlah terjual or terdownload --}}
                                        <p class="card-text mb-0"
                                            style="font-size: 12px; color: #151515; font-weight: 500;">
                                            @if ($bestSellerCounts[$item->id] >= 1000)
                                                @if ($item->status === 'free')
                                                    {{ number_format($bestSellerCounts[$item->id] / 1000, 0, ',', '.') }}RB+&nbsp;terdownload
                                                @else
                                                    {{ number_format($bestSellerCounts[$item->id] / 1000, 0, ',', '.') }}RB+&nbsp;terjual
                                                @endif
                                            @else
                                                @if ($item->status === 'free')
                                                    {{ $bestSellerCounts[$item->id] }}&nbsp;terdownload
                                                @else
                                                    {{ $bestSellerCounts[$item->id] }}&nbsp;terjual
                                                @endif
                                            @endif
                                        </p>
                                    </div>

                                    {{-- avg rating --}}
                                    <span class="badge text-bg-dark mt-2">
                                        <i class="bi bi-star-fill text-warning"></i>&nbsp;&nbsp;{{ $bestSellerAvgRatings[$item->id] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        -
                    @endif
                </div>
            </div>
        </section>

        {{-- subscribe --}}
        <section class="section-subscribe">
            <div class="container" style="padding-top: 150px; padding-bottom: 150px;">
                {{-- <div class="row justify-content-center justify-content-lg-end">
                    <div class="col-md-10 col-lg-5 col-xl-4">
                        <p class="text-center mb-0 mb-3" style="color: #f1f5f9; font-size: 20px;">Subscribe For Latest
                            Updates</p>
                        <form action="" method="POST">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">@</span>
                                <input type="email" id="email_subscribe" name="email_subscribe" class="form-control"
                                    placeholder="example@gmail.com" autocomplete="email"
                                    aria-describedby="addon-wrapping">
                            </div>

                            <center><button type="submit" class="btn btn-success mt-3">Subscribe</button></center>
                        </form>
                    </div>
                </div> --}}
            </div>
        </section>

        {{-- testimoni --}}
        <section class="section-testimoni mt-5">
            <div class="container">
                <h3 class="section-title">Apa Kata Mereka?</h3>

                <div class="testi_slider">
                    @foreach ($testimonials as $testimoni)
                        <div class="card text-center" style="border-radius: 16px;">
                            <div class="card-body">
                                @if ($testimoni->user->role === 'penulis')
                                    {{-- avatar --}}
                                    <center>
                                        <div class="avatar-container">
                                            <img src="{{ $testimoni->user->author->avatar ? asset($testimoni->user->author->avatar) : asset('assets/image/empty.jpg') }}"
                                                class="avatar" alt="Avatar Image">
                                        </div>
                                    </center>
                                @else
                                    {{-- avatar --}}
                                    <center>
                                        <div class="avatar-container">
                                            <img src="{{ $testimoni->user->profile->avatar ? asset($testimoni->user->profile->avatar) : asset('assets/image/empty.jpg') }}"
                                                class="avatar" alt="Avatar Image">
                                        </div>
                                    </center>
                                @endif

                                {{-- nama pengguna --}}
                                <h5 class="card-title">{{ $testimoni->user->fullname }} |
                                    {{ Str::ucfirst($testimoni->user->role) }}
                                </h5>

                                {{-- description --}}
                                <p class="card-text" title="{{ $testimoni->review }}">
                                    {{ $testimoni->review }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </main>
@endsection

@prepend('scripts')
    {{-- slick js --}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <script>
        $('.ebook-slider').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 2,
            responsive: [{
                    breakpoint: 1400,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 5
                    }
                },
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 2,
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 770,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        dots: true
                    }
                },
                {
                    breakpoint: 420,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: true
                    }
                }
            ]
        });

        $('.testi_slider').slick({
            speed: 300,
            dots: false,
            arrows: true,
            infinite: true,
            slidesToShow: 1,
            centerMode: true,
            slidesToScroll: 1,
            responsive: [{
                breakpoint: 992,
                settings: {
                    arrows: false,
                    centerMode: false,
                    centerPadding: '0',
                    adaptiveHeight: true,
                    dots: true
                }
            }]
        });
    </script>
@endprepend

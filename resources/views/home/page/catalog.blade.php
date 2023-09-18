@extends('home.index')

@prepend('styles')
    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css') }}">

    {{-- select2 css --}}
    <link href="{{ url('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css') }}" rel="stylesheet" />

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

        .form-control,
        .form-check-input,
        .form-select,
        .select2-container--default .select2-selection--multiple {
            border-color: #42B549;
        }

        .form-control:focus,
        .form-check-input:focus,
        .form-select:focus {
            outline: 0;
            box-shadow: none;
            border-color: #42B549;
        }

        .form-check-input:checked {
            background-color: #42B549;
        }

        .form-check-input:checked ~ label {
            font-weight: 500;
            color: #42B549;
        }

        @media (max-width: 425px) {
            .row-cols-2 > * {
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
            <h2>Catalog</span></h2>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"
                            class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Catalog</li>
                </ol>
            </nav>
        </div>
    </header>
@endsection

@section('main')
    <main>
        <section>
            <div class="container">
                <h3 class="section-title">Filter All Ebook Data</h3>

                {{-- filter search --}}
                <label for="search" class="form-label">Search</label>
                <input type="search" name="search" id="search" class="form-control"
                    placeholder="Search isbn or title ebook" value="{{ $search }}" autocomplete="title">

                <div class="row">
                    {{-- filter status --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label d-block mt-3">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="" {{ $status === '' ? 'selected' : '' }}>{{ __('Default') }}
                            </option>
                            <option value="free" {{ $status == 'free' ? 'selected' : '' }}>{{ __('Free') }}
                            </option>
                            <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}
                            </option>
                        </select>
                    </div>

                    {{-- filter select dari termurah ke termahal dan termahal ke termurah --}}
                    <div class="col-md-4">
                        <label for="sort" class="form-label d-block mt-3">Sort</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="" {{ $sort === '' ? 'selected' : '' }}>Default</option>
                            <option value="termurah-ke-termahal" {{ $sort == 'termurah-ke-termahal' ? 'selected' : '' }}>Termurah ke Termahal</option>
                            <option value="termahal-ke-termurah" {{ $sort == 'termahal-ke-termurah' ? 'selected' : '' }}>Termahal ke Termurah</option>
                        </select>
                    </div>

                    {{-- filter avg rating --}}
                    <div class="col-md-4">
                        <label for="rating" class="form-label d-block mt-3">Rating</label>
                        <select name="rating" id="rating" class="form-select">
                            <option value="" {{ $rating === '' ? 'selected' : '' }}>{{ __('Default') }}
                            </option>
                            <option value="5" {{ $rating === '5' ? 'selected' : '' }}>
                                5
                            </option>
                            <option value="4" {{ $rating === '4' ? 'selected' : '' }}>
                                4
                            </option>
                            <option value="3" {{ $rating === '3' ? 'selected' : '' }}>
                                3
                            </option>
                            <option value="2" {{ $rating === '2' ? 'selected' : '' }}>
                                2
                            </option>
                            <option value="1" {{ $rating === '1' ? 'selected' : '' }}>
                                1
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-lg-3">
                        {{-- filter multiple category --}}
                        <label for="category" class="form-label d-block">Category</label>
                        @foreach ($categories as $category)
                            @php
                                $categoryIdsArray = $categoryIds ? explode('-', $categoryIds) : [];
                            @endphp
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="category[]"
                                    id="category{{ $category->id }}" value="{{ $category->id }}"
                                    {{ in_array($category->id, $categoryIdsArray) ? 'checked' : '' }}>
                                <label class="form-check-label"
                                    for="category{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @endforeach

                        {{-- filter multiple penulis --}}
                        {{-- <label for="author" class="form-label d-block mt-3">Author</label>
                        <select name="author[]" id="author" class="form-select multiple-author" multiple="multiple"
                            style="width: 100%;">
                            @foreach ($authors as $author)
                                @php
                                    $authorIdsArray = $authorIds ? explode('-', $authorIds) : [];
                                @endphp
                                <option value="{{ $author->user->username }}"
                                    {{ in_array($author->user->username, $authorIdsArray) ? 'selected' : '' }}>
                                    {{ $author->user->fullname }}</option>
                            @endforeach
                        </select> --}}
                    </div>

                    <div class="col-lg-9 mt-5 mt-lg-0">
                        @if ($ebooks->count() > 0)
                            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
                                @foreach ($ebooks as $ebook)
                                    <div class="col">
                                        <div class="card h-100">
                                            <img src="{{ asset($ebook->thumbnail) }}" class="card-img-top"
                                                alt="Thumbnail Ebook">

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
                                                <a href="{{ route('ebook.detail', $ebook->slug) }}"
                                                    class="text-decoration-none">
                                                    <h6 class="card-title title mt-2 mb-0 mb-1">{{ $ebook->title }}</h6>
                                                </a>

                                                <div class="d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between align-items-lg-center">
                                                    {{-- harga --}}
                                                    <p class="card-text harga mb-0">
                                                        {{ $ebook->status === 'free' ? 'Free' : 'Rp ' . number_format($ebook->price, 0, ',', '.') }}
                                                    </p>

                                                    {{-- jumlah terjual or terdownload --}}
                                                    <p class="card-text mb-0" style="font-size: 12px; color: #151515; font-weight: 500;">
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
                            <div class="py-5 d-flex flex-column justify-content-center align-items-center">
                                <img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4" width="200px" alt="Empty">
                                <h4>{{ __('No ebook found') }}</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@prepend('scripts')
    {{-- select2 js --}}
    <script src="{{ url('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.multiple-author').select2();

            $('#search').on('input', function(event) {
                filter();
            });

            $('#status').on('change', function() {
                filter();
            });

            $('#sort').on('change', function() {
                filter();
            });

            $('#rating').on('change', function() {
                filter();
            });

            $('input[name="category[]"]').on('change', function() {
                filter();
            });

            // $('#author').on('change', function() {
            //     filter();
            // });

            function filter() {
                const searchValue = $('#search').val();
                const statusValue = $('#status').val();
                const sortValue = $('#sort').val();
                const ratingValue = $('#rating').val();
                const categoryValues = $('input[name="category[]"]:checked').map(function() {
                    return $(this).val();
                }).get().join('-');
                // const authorValues = $('#author').val().join('-');

                const params = {};
                const url = '{{ route('catalog') }}';

                if (searchValue.trim() !== '') {
                    params.q = encodeURIComponent(searchValue.trim());
                }

                if (statusValue !== '') {
                    params.status = statusValue;
                }

                if (sortValue !== '') {
                    params.sort = sortValue;
                }

                if (ratingValue !== '') {
                    params.rating = ratingValue;
                }

                if (categoryValues && categoryValues.length > 0) {
                    params.category = categoryValues;
                }

                // if (authorValues && authorValues.length > 0) {
                //     params.author = authorValues;
                // }

                const queryString = Object.keys(params)
                    .map(key => key + '=' + params[key])
                    .join('&');

                const finalUrl = url + '?' + queryString;
                window.location.href = finalUrl;
            }
        });
    </script>
@endprepend

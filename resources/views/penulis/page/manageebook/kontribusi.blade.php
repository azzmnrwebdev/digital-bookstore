@extends('penulis.index')
@section('title-head', 'Kontribusi Ebook')
@section('title-content', 'Kontribusi Ebook')

@prepend('styles')
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/select2/css/select2.min.css') }}">

    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css') }}" />

    <style>
        .form-control:focus {
            border-color: #42B549 !important;
        }

        .select2-container--default .select2-selection--multiple {
            border-color: #CED4DA;
        }

        .text-ellipsis {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
        }

        .card-ebook .card-title {
            overflow-wrap: break-word;
            word-wrap: break-word;
            hyphens: auto;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
        }

        .card-ebook {
            transition: border-radius 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-ebook:hover {
            border-radius: 16px !important;
            border-color: #b9f9bd !important;
            box-shadow: 0px 2px 20px rgba(0, 0, 0, 0.2) !important;
        }

        .card-ebook:hover .card-body .card-title {
            color: #42B549 !important;
        }

        .card-ebook:hover .card-body .card-title a {
            text-decoration: underline;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penulis.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Kontribusi Ebook') }}</li>
@endsection

@section('content')
    <div class="card" style="border-top: 4px solid #42B549;">
        <div class="card-body">
            @if (Session('success'))
                <div id="alert-msg" class="alert alert-success alert-dismissible mb-3">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ Session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-lg-4 col-xl-3">
                    <div class="card shadow-none">
                        <div class="card-header" style="background-color: #0f172a; color: #f1f5f9; font-weight: 500;">
                            <h3 class="card-title">{{ __('Filter Ebook') }}</h3>
                        </div>

                        <div class="card-body px-0 pb-0">
                            <div class="mb-3">
                                <label for="search">{{ __('Search Ebook') }}</label>
                                <input type="search" id="search" value="{{ $search }}" class="form-control"
                                    placeholder="Search by isbn or title" autocomplete="title">
                            </div>

                            <div class="mb-3">
                                <label for="category">{{ __('Category') }}</label>
                                <div class="select2-green">
                                    <select id="category" class="select2" multiple="multiple"
                                        data-placeholder="Select a Category" data-dropdown-css-class="select2-green"
                                        style="width: 100%;">
                                        @foreach ($categories as $category)
                                            @php
                                                $categoryIdsArray = $categoryIds ? explode('-', $categoryIds) : [];
                                            @endphp
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, $categoryIdsArray) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="status">{{ __('Status') }}</label>
                                <select id="status" class="form-control">
                                    <option value="" {{ $status === '' ? 'selected' : '' }}>{{ __('All Status') }}
                                    </option>
                                    <option value="free" {{ $status == 'free' ? 'selected' : '' }}>{{ __('Free') }}
                                    </option>
                                    <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="rating">{{ __('Rating') }}</label>
                                <select id="rating" class="form-control">
                                    <option value="" {{ $rating === '' ? 'selected' : '' }}>{{ __('All Rating') }}
                                    </option>
                                    <option value="5" {{ $rating == '5' ? 'selected' : '' }}>{{ __('5') }}
                                    </option>
                                    <option value="4" {{ $rating == '4' ? 'selected' : '' }}>{{ __('4') }}
                                    </option>
                                    <option value="3" {{ $rating == '3' ? 'selected' : '' }}>{{ __('3') }}
                                    </option>
                                    <option value="2" {{ $rating == '2' ? 'selected' : '' }}>{{ __('2') }}
                                    </option>
                                    <option value="1" {{ $rating == '1' ? 'selected' : '' }}>{{ __('1') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-xl-9">
                    @if ($ebooks->count() > 0)
                        <h5 class="mb-3">{{ __('Total Ebook') }} ({{ $ebooks->count() }})</h5>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-2 row-cols-xl-3">
                            @foreach ($ebooks as $ebook)
                                <div class="col mb-4">
                                    <div class="card card-ebook shadow-sm h-100" style="border: 1px solid #CED4DA;">
                                        <img src="{{ asset($ebook->thumbnail) }}"
                                            style="height: 250px; object-fit: contain;" class="card-img-top"
                                            alt="Thumbnail Ebook">

                                        <div class="card-body">
                                            {{-- title --}}
                                            <h5 class="card-title font-weight-bold" style="color: #444444;">
                                                <a href="{{ route('penulis.ebook.show', $ebook->slug) }}"
                                                    class="text-reset" title="{{ $ebook->title }}">{{ $ebook->title }}</a>
                                            </h5>

                                            {{-- deskripsi --}}
                                            <p class="card-text text-ellipsis mb-2"
                                                style="font-weight: 400; color: #64748b;">{{ $ebook->description }}</p>

                                            <div class="d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between align-items-lg-center mb-3">
                                                {{-- avg rating --}}
                                                <div>
                                                    <span class="badge badge-dark mb-1 mb-md-0"><i class="fas fa-star text-warning"></i>&nbsp;&nbsp;{{ $averageRatings[$ebook->id] }}</span>
                                                </div>

                                                {{-- jumlah terjual or terdownload --}}
                                                <div>
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
                                            </div>

                                            {{-- harga --}}
                                            <p class="card-text font-weight-bolder position-absolute" style="bottom: 20px;">
                                                {{ $ebook->status === 'free' ? 'Free' : 'Rp ' . number_format($ebook->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="my-4">
                            <center><img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4 w-25" alt="Empty">
                            </center>
                            <h4 class="text-center">{{ __('No ebooks found') }}</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- select2 --}}
    <script src="{{ asset('AdminLTE/plugins/select2/js/select2.full.min.js') }}"></script>

    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>

    <script>
        $('.select2').select2();

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

        $('#search').on('input', function() {
            filter();
        });

        $('#category').on('change', function() {
            filter();
        });

        $('#status').on('change', function() {
            filter();
        });

        $('#rating').on('change', function() {
            filter();
        });

        function filter() {
            const searchValue = $('#search').val();
            const categoryValues = $('#category').val().join('-');
            const statusValue = $('#status').val();
            const ratingValue = $('#rating').val();

            const params = {};
            const url = '{{ route('penulis.ebook.kontribusi') }}';

            if (searchValue.trim() !== '') {
                params.q = encodeURIComponent(searchValue.trim());
            }

            if (categoryValues && categoryValues.length > 0) {
                params.category = categoryValues;
            }

            if (statusValue !== '') {
                params.status = statusValue;
            }

            if (ratingValue !== '') {
                params.rating = ratingValue;
            }

            const queryString = Object.keys(params)
                .map(key => key + '=' + params[key])
                .join('&');

            const finalUrl = url + '?' + queryString;
            window.location.href = finalUrl;
        }
    </script>
@endprepend

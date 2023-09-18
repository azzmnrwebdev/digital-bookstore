@extends('home.index')

@prepend('styles')
    <style>
        main {
            padding: 4rem 0 !important;
        }

        .background {
            width: 100%;
            height: 160px;
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .avatar-container {
            width: 125px;
            height: 125px;
            overflow: hidden;
        }

        .avatar-container .avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile .card-title.author,
        .profile .card-title.bio,
        .profile .card-title.ebook {
            font-size: 20px;
            font-weight: 600;
            color: #151515;
        }

        .profile .card-text.isi-bio {
            font-size: 15px;
            color: #151515;
            line-height: 24px;
        }

        .table-bordered {
            border-color: #42B549;
        }

        .thead-custom {
            color: #444444;
            background-color: #9efda4;
        }

        .hoverable-row:hover {
            background-color: #e3ffe5;
        }

        .card-ebook {
            cursor: pointer;
            border-radius: 0.625rem;
        }

        .card-ebook:hover {
            box-shadow: 6px 0px 14px rgba(42, 243, 55, 0.15), -6px 0px 14px rgba(11, 250, 27, 0.15);
        }

        .card-ebook .card-img-top {
            height: 250px;
            object-fit: contain;
        }

        .card-ebook .ribbon-wrapper {
            height: 70px;
            overflow: hidden;
            position: absolute;
            right: -2px;
            top: -2px;
            width: 70px;
            z-index: 10;
        }

        .card-ebook .ribbon-wrapper .ribbon {
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

        .card-ebook .ribbon-wrapper .ribbon::before,
        .ribbon-wrapper .ribbon::after {
            border-left: 3px solid transparent;
            border-right: 3px solid transparent;
            border-top: 3px solid #9e9e9e;
            bottom: -3px;
            content: "";
            position: absolute;
        }

        .card-ebook .ribbon-wrapper .ribbon::before {
            left: 0;
        }

        .card-ebook .ribbon-wrapper .ribbon::after {
            right: 0;
        }

        .card-ebook .card-body .penulis {
            font-size: 12px;
            color: #6D6D6D;
            font-weight: 400;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
        }

        .card-ebook .card-body .penulis a {
            color: #94a3b8;
            text-decoration: none;
        }

        .card-ebook .card-body .penulis a:hover {
            text-decoration: underline;
        }

        .card-ebook .card-body .title {
            font-size: 14px;
            font-weight: 400;
            color: #151515;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
        }

        .card-ebook .card-body .card-text.harga {
            color: #42B549;
            font-weight: 500;
        }

        @media (min-width: 576px) {}

        @media (min-width: 768px) {
            .background {
                height: 200px;
            }

            .avatar-container {
                width: 140px;
                height: 140px;
            }
        }

        @media (min-width: 992px) {
            .background {
                height: 250px;
            }

            .avatar-container {
                width: 150px;
                height: 150px;
            }

            .card-ebook:hover {
                border: 1px solid #42B549;
            }
        }

        @media (min-width: 1200px) {
            .background {
                height: 300px;
            }
        }
    </style>
@endprepend

@section('main')
    <main>
        <section>
            <div class="container">
                {{-- background --}}
                @if ($author->background)
                    <img src="{{ asset($author->background) }}" class="background rounded mb-3" alt="Background Image">
                @else
                    <img src="{{ asset('assets/image/no-image.png') }}" class="background rounded mb-3" alt="Background Image">
                @endif

                <div class="card">
                    <div class="card-body px-md-5 py-5">
                        {{-- avatar --}}
                        <center>
                            <div class="avatar-container">
                                @if ($author->avatar)
                                    <img src="{{ asset($author->avatar) }}" class="avatar" alt="Avatar Image">
                                @else
                                    <img src="{{ asset('assets/image/empty.jpg') }}" class="avatar" alt="Avatar Image">
                                @endif
                            </div>
                        </center>

                        {{-- profil --}}
                        <div class="profile mt-4">
                            <h5 class="card-title author">Author</h5>

                            <div class="table-responsive">
                                <table class="table text-nowrap table-bordered align-middle">
                                    <thead class="thead-custom">
                                        <tr>
                                            <th class="text-start" style="font-weight: 500 !important;">Fullname</th>
                                            <th class="text-start" style="font-weight: 500 !important;">Username</th>
                                            <th class="text-start" style="font-weight: 500 !important;">Email</th>
                                            <th class="text-center" style="font-weight: 500 !important;">Status</th>
                                            <th class="text-center" style="font-weight: 500 !important;">Join Date</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr class="hoverable-row">
                                            <td class="text-start">{{ $author->user->fullname }}</td>
                                            <td class="text-start">{{ $author->user->username }}</td>
                                            <td class="text-start">{{ $author->user->email }}</td>
                                            <td class="text-center">
                                                @if ($author->user->is_active === 1)
                                                    <span class="badge text-bg-success">Active</span>
                                                @else
                                                    <span class="badge text-bg-danger">InActive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($author->user->created_at)->format('l, d F Y') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- bio --}}
                            <h5 class="card-title bio mt-4 mb-0 mb-1">Biografi</h5>
                            <p class="card-text isi-bio mb-0">
                                @if ($author->bio)
                                    {{ $author->bio }}
                                @else
                                    -
                                @endif
                            </p>

                            {{-- ebook --}}
                            <h5 class="card-title ebook mt-4 mb-0 mb-3">Ebook ({{ $ebooks->count() }})</h5>

                            @if ($ebooks->count() > 0)
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                                    @foreach ($ebooks as $ebook)
                                        <div class="col">
                                            <div class="card card-ebook h-100">
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
                                                            <a href="{{ route('author.profile', $author->user->username) }}">{{ $author->user->fullname }}</a>@if ($key < count($ebook->authors) - 1), @endif
                                                        @endforeach
                                                    </div>

                                                    {{-- title --}}
                                                    <a href="{{ route('ebook.detail', $ebook->slug) }}"
                                                        class="text-decoration-none">
                                                        <h6 class="card-title title mt-2 mb-0 mb-1">{{ $ebook->title }}
                                                        </h6>
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
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@prepend('scripts')
@endprepend

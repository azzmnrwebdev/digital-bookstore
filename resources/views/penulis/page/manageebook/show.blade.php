@extends('penulis.index')
@section('title-head', 'Detail Ebook')
@section('title-content', 'Detail Ebook')

@prepend('styles')
    <style>
        img {
            height: 300px;
        }

        .card-title {
            font-size: 24px !important;
            font-family: 'Times New Roman', Times, serif !important;
            font-weight: bold !important;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penulis.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('penulis.ebook.index') }}">{{ __('Manage Ebook') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Detail') }}</li>
@endsection

@section('content')
    <div class="card" style="border-top: 4px solid #42B549;">
        <div class="card-body">
            <div class="card">
                <div class="row no-gutters">
                    <div class="col-md-2">
                        <img src="{{ asset($ebook->thumbnail) }}" alt="Thumbnail">
                    </div>
                    <div class="col-md-10 ">
                        <div class="card-body ml-4">
                            {{-- isbn --}}
                            @if ($ebook->isbn)
                                <p>ISBN {{ $ebook->isbn }}</p>
                            @endif

                            {{-- kategori --}}
                            @foreach ($ebook->categories as $category)
                                <span class="badge badge-dark">{{ $category->name }}</span>
                            @endforeach

                            {{-- penulis --}}
                            <p class="penulis mt-2">
                                Author: @foreach ($ebook->authors as $key => $author)
                                    {{ $author->user->fullname }}@if (!$loop->last), @endif
                                @endforeach
                            </p>

                            {{-- title --}}
                            <h5 class="card-title">{{ $ebook->title }}</h5>

                            {{-- description --}}
                            <p class="card-text">{{ $ebook->description }}</p>

                            {{-- harga --}}
                            <p>{{ $ebook->status === 'free' ? 'Free' : 'Rp ' . number_format($ebook->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <hr class="mb-0" />
            </div>

            <div class="col-12" style="margin-top: 2.5rem;">
                <div class="card shadow-none">
                    <div class="card-header" style="background-color: #0f172a; color: #f1f5f9; font-weight: 500;">
                        <h3 class="card-title">{{ __('PDF Ebook') }}</h3>
                    </div>

                    <div class="card-body p-0">
                        <p class="card-text mt-3" style="font-size: 18px;"><span
                                style="font-weight: 600;">Katasandi&nbsp;&nbsp;:</span>
                            {{ $ebook->password ? $ebook->password : '-' }}</p>
                        <embed src="{{ asset($ebook->pdf) }}" type="application/pdf" width="100%" height="500px" />
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

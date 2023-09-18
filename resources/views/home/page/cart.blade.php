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

        .hoverable-row:hover {
            background-color: #e3ffe5;
        }

        .table-bordered {
            border-color: #42B549;
        }

        .table-custom {
            color: #444444;
            background-color: #9efda4;
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
            <h2>My Cart</span></h2>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"
                            class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Cart</li>
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

                @if (Session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session('error') }}
                    </div>
                @endif

                <h3 class="section-title">Ebook List in My Cart</h3>

                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-custom">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Ebook Title</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @if ($carts->count() > 0)
                                @foreach ($carts as $cart)
                                    <tr class="hoverable-row">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('ebook.detail', $cart->ebook->slug) }}" class="text-rest text-decoration-none">{{ $cart->ebook->title }}</a>
                                        </td>
                                        <td class="text-center">{{ $cart->quantity }}</td>
                                        <td class="text-center">
                                            {{ $cart->ebook->status === 'free' ? 'Free' : 'Rp ' . number_format($cart->ebook->price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.delete', $cart->id) }}" method="POST">
                                                @csrf
                                                @method('delete')

                                                <button type="submit" class="btn btn-sm btn-danger bg-gradient"
                                                    onclick="return confirm('Are you sure you want to delete this data?')">
                                                    <i class="fas fa-trash mr-3"></i>{{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    @php
                                        $total += $cart->ebook->price * $cart->quantity;
                                    @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4" width="250px;"
                                            alt="Empty">
                                        <h4>{{ __('No data found') }}</h4>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="col-md-6 col-lg-4 mt-4">
                    <div class="card">
                        <div class="card-header" style="background-color: #9efda4;">
                            Card Totals
                        </div>

                        <div class="card-body">
                            <h5 class="card-title m-0 subtotal mb-2">Subtotal&nbsp;:
                                {{ 'Rp ' . number_format($total, 0, ',', '.') }}</h5>
                            <h5 class="card-title m-0 total">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                {{ 'Rp ' . number_format($total, 0, ',', '.') }}</h5>

                            @if ($carts->count() > 0)
                                <a href="{{ route('checkout') }}" class="btn btn-sm btn-success mt-3">Procced to
                                    Checkout</a>
                            @endif
                        </div>
                    </div>
                </div>
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

        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if (Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
    </script>
@endprepend

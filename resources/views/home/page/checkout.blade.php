@extends('home.index')

@prepend('styles')
    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css') }}">

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

        .form-control:focus,
        .form-select:focus {
            outline: 0;
            box-shadow: none;
            border-color: #42B549;
        }

        .form-control.is-invalid:focus,
        .form-select.is-invalid:focus {
            outline: 0;
            box-shadow: none;
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
            <h2>Checkout</span></h2>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}"
                            class="text-white text-decoration-none">My Cart</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Checout</li>
                </ol>
            </nav>
        </div>
    </header>
@endsection

@section('main')
    <main>
        <section>
            <div class="container">
                <h3 class="section-title">Checkout Ebook</h3>

                {{-- payment information --}}
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading">Payment Information</h4>
                    <p>Untuk melakukan pembayaran, kami menyediakan metode-metode berikut:</p>
                    <ol>
                        <li>Transfer Bank BRI:<br>
                            Transfer ke rekening berikut: xxxxxxx a.n RuangLiterasi
                        </li>
                        <li>E-wallet Dana:<br>
                            Untuk melakukan pembayaran melaui e-wallet Dana, silahkan transfer ke nomor Dana berikut:
                            xxxxxxx a.n RuangLiterasi
                        </li>
                    </ol>

                    <hr>

                    <p>Mohon diperhatikan:<br>
                        <ul>
                            <li>Jumlah yang harus dibayar sebesar <strong>Rp {{ number_format($jumlahTagihan, 0, ',', '.') }}</strong></li>
                            <li>Pastikan Anda membayar tidak lebih dari nomimal yang seharusnya. Jika terjadi maka kami anggap sebagai kelalaian pengguna!</li>
                            <li>Pastikan Anda membayar tidak kurang dari nomimal yang seharusnya. Jika terjadi maka pesanan akan di tolak dan saldo Anda akan dikembalikan.</li>
                            {{-- <li>Jika saldo Anda tidak mencukupi, pesanan Anda akan ditolak, dan saldo Anda akan dikembalikan.
                                Silahkan konfirmasi ke nomor <a href="https://wa.me/6283836903996" class="text-decoration-none">WhatsApp admin kami.</a></li>
                            <li>Jika saldo Anda melebihi nomimal pembayaran dan kurang dari kurang Rp 20.000, kami tidak akan mengembalikan sisa saldo.</li>
                            <li>Jika saldo Anda melebihi Rp 20.000, kami akan mengembalikan saldo dengan potongan 10%.</li> --}}
                        </ul>
                    </p>
                </div>

                {{-- form --}}
                <form action="{{ route('order.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    {{-- name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" value="{{ old('name', auth()->user()->fullname) }}" readonly aria-describedby="nameHelp">

                        <div id="nameHelp" class="form-text">Field nama tidak bisa dicustom.</div>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            id="email" value="{{ old('email', auth()->user()->email) }}" aria-describedby="emailHelp">

                        <div id="emailHelp" class="form-text">Field email bisa dicustom dan masukan email valid untuk proses kirim ebook via email.</div>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- payment method --}}
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method <span
                                class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method"
                            class="form-select @error('payment_method') is-invalid @enderror">
                            <option value="" {{ old('payment_method') === null ? 'selected' : '' }}>
                                {{ __('-- Select Payment Method --') }}
                            </option>

                            <option value="m_banking" {{ old('payment_method') === 'm_banking' ? 'selected' : '' }}>
                                {{ __('Mobile Banking BRI') }}
                            </option>

                            <option value="e_wallet" {{ old('payment_method') === 'e_wallet' ? 'selected' : '' }}>
                                {{ __('E Wallet Dana') }}
                            </option>
                        </select>

                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- payment proof --}}
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Payment Proof <span
                                class="text-danger">*</span></label>
                        <input type="file" name="payment_proof" id="payment_proof"
                            class="form-control @error('payment_proof') is-invalid @enderror">

                        @error('payment_proof')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">Checkout</button>
                </form>
            </div>
        </section>
    </main>
@endsection

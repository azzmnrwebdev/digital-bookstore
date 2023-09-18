@extends('penulis.index')
@section('title-head', 'Sales Report')
@section('title-content', 'Sales Report')

@prepend('styles')
    <style>
        .thead-custom {
            color: #ffffff;
            background-color: #00ACC1;
        }

        .hoverable-row:hover {
            background-color: #f2feff;
        }

        .align-middle {
            vertical-align: middle !important
        }

        .link-ebook {
            color: #42B549;
            font-weight: 500;
        }

        .hoverable-row:hover .link-ebook {
            color: #2E912F;
            text-decoration: underline;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penulis.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Sales Report') }}</li>
@endsection

@section('content')
    <div class="card" style="border-top: 4px solid #42B549;">
        <div class="card-body d-flex flex-column">
            <p class="card-text font-italic">Catatan: saldo perbulan adalah pendapatan dari ebook yang anda upload dan
                bisa di cairkan setiap tanggal 25.</p>

            @if ($is25thDayOfMonth)
                @if ($saldoPerBulan < '50000')
                    {{-- information --}}
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Ooops! Penarikan Gagal</h4>
                        <p class="mb-0">Maaf, saldo anda kurang! Minimal saldo penarikan Rp 50.000</p>
                    </div>
                @else
                    {{-- information --}}
                    <div class="alert alert-info" role="alert">
                        <h4 class="alert-heading">Selamat! Saatnya Gajian</h4>
                        <p class="mb-0">Saldo sebesar Rp {{ number_format($saldoPerBulan, 0, ',', '.') }} akan di transfer ke rekening anda, bukti dikirim melalui email. Silahkan tunggu 1x24 jam, apabila mengalami kendala silahkan hubungi admin kami.</p>
                    </div>
                @endif
            @endif

            <h5 class="card-title mb-1">Total Keseluruhan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp
                {{ number_format($totalKeseluruhan, 0, ',', '.') }} (tidak termasuk kontribusi)</h5>
            <h5 class="card-title font-weight-bolder">Saldo Anda Perbulan&nbsp;: Rp
                {{ number_format($saldoPerBulan, 0, ',', '.') }}</h5>

            {{-- table penjualan --}}
            <h4 style="font-weight: 500; color: #020617" class="mt-4 mb-2">Laporan Penjualan Ebook</h4>
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead class="thead-custom">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-left">Title</th>
                            <th class="text-center">Status</th>
                            <th class="text-left">Price</th>
                            <th class="text-center">Purchased Amount</th>
                            <th class="text-center">Potongan</th>
                            <th class="text-left">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($ebooks as $ebook)
                            <tr class="hoverable-row">
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td class="text-left align-middle">
                                    <a href="{{ route('penulis.ebook.show', $ebook->slug) }}"
                                        class="link-ebook">{{ $ebook->title }}</a>
                                </td>
                                <td class="text-center align-middle">
                                    @if ($ebook->status === 'free')
                                        <span class="badge badge-primary">Free</span>
                                    @else
                                        <span class="badge badge-success">Paid</span>
                                    @endif
                                </td>
                                <td class="text-left align-middle">
                                    {{ $ebook->status === 'free' ? 'Free' : 'Rp ' . number_format($ebook->price, 0, ',', '.') }}
                                </td>
                                <td class="text-center align-middle">
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
                                </td>
                                <td class="text-center align-middle">10%</td>
                                <td class="text-left align-middle">
                                    @if ($ebook->status === 'free')
                                        Rp 0
                                    @else
                                        @php
                                            $total = $ebook->price * $ebookCounts[$ebook->id];
                                            $potongan = $total * 0.1;
                                            $totalSetelahPotongan = $total - $potongan;
                                        @endphp

                                        Rp {{ number_format($totalSetelahPotongan, 0, ',', '.') }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- table pelanggan --}}
            <h4 style="font-weight: 500; color: #020617;" class="mt-4 mb-2">Laporan Pelanggan Ebook</h4>
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead class="thead-custom">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-left">Name</th>
                            <th class="text-left">Ebook Title</th>
                            <th class="text-center">Payment Status</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center">Purchase Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($salesData as $sale)
                            <tr class="hoverable-row">
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td class="text-left align-middle">{{ $sale['user'] }}</td>
                                <td class="text-left align-middle">{{ $sale['ebook'] }}</td>
                                <td class="text-center align-middle">
                                    @if ($sale['payment_status'] === 'Process')
                                        <span class="badge badge-warning">Process</span>
                                    @elseif ($sale['payment_status'] === 'Approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif ($sale['payment_status'] === 'Rejected')
                                        <span class="badge badge-success">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @php
                                        $rating = floatval($sale['rating']);
                                        $fullStars = floor($rating);
                                        $halfStar = ceil($rating - $fullStars);
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                    @endphp

                                    @for ($i = 1; $i <= $fullStars; $i++)
                                        <i class="fas fa-star" style="color: #42B549;"></i>
                                    @endfor

                                    @for ($i = 1; $i <= $halfStar; $i++)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endfor

                                    @for ($i = 1; $i <= $emptyStars; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                </td>
                                <td class="text-center align-middle">
                                    {{ \Carbon\Carbon::parse($sale['purchase_date'])->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm:ss') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
@endprepend

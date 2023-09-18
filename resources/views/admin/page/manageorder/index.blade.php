@extends('admin.index')
@section('title-head', 'Manage Order')
@section('title-content', 'Manage Order')

@prepend('styles')
    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css') }}" />

    <style>
        .more-info-row {
            display: none;
        }

        .hoverable-row:hover,
        .hoverable-row.opened {
            background-color: #f2feff;
        }

        .align-middle {
            vertical-align: middle !important
        }

        .form-control:focus {
            border-color: #42B549;
        }

        .thead-custom {
            color: #ffffff;
            background-color: #00ACC1;
        }

        .hoverable-row:hover .link-fullname,
        .hoverable-row.opened .link-fullname {
            color: #2E912F;
            text-decoration: underline;
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
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Manage Order') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-body">
                    @if (Session('success'))
                        <div id="alert-msg" class="alert alert-success alert-dismissible mb-3">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ Session('success') }}
                        </div>
                    @endif

                    @if (Session('error'))
                        <div id="alert-msg" class="alert alert-danger alert-dismissible mb-3">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ Session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="form-group col-12">
                            <label for="search">{{ __('Search') }}</label>
                            <input type="search" class="form-control" name="search" id="search"
                                value="{{ $search }}" placeholder="Search by id pesanan, name or email">
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label for="payment_method">{{ __('Payment Method') }}</label>
                            <select name="payment_method" id="paymentMethod" class="form-control">
                                <option value="" {{ $paymentMethod === '' ? 'selected' : '' }}>Default</option>
                                <option value="m_banking" {{ $paymentMethod === 'm_banking' ? 'selected' : '' }}>
                                    Mobile Banking BRI</option>
                                <option value="e_wallet" {{ $paymentMethod === 'e_wallet' ? 'selected' : '' }}>
                                    E-wallet Dana</option>
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label for="payment_status">{{ __('Payment Status') }}</label>
                            <select name="payment_status" id="paymentStatus" class="form-control">
                                <option value="" {{ $paymentStatus === '' ? 'selected' : '' }}>Default</option>
                                <option value="Process" {{ $paymentStatus === 'Process' ? 'selected' : '' }}>Process
                                </option>
                                <option value="Approved" {{ $paymentStatus === 'Approved' ? 'selected' : '' }}>
                                    Approved</option>
                                <option value="Rejected" {{ $paymentStatus === 'Rejected' ? 'selected' : '' }}>
                                    Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table text-nowrap border-bottom">
                            <thead class="thead-custom">
                                <tr>
                                    <th class="text-center">{{ __('No') }}</th>
                                    <th class="text-left">{{ __('ID Pesanan') }}</th>
                                    <th class="text-left">{{ __('Name') }}</th>
                                    <th class="text-left">{{ __('Email') }}</th>
                                    <th class="text-center">{{ __('Payment Method') }}</th>
                                    <th class="text-center">{{ __('Payment Status') }}</th>
                                    <th class="text-center">{{ __('Details') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($orders->count() > 0)
                                    @php $number = ($orders->currentpage()-1) * $orders->perpage() + 1; @endphp
                                    @foreach ($orders as $row)
                                        <tr class="hoverable-row">
                                            <td class="text-center align-middle">{{ $number++ }}</td>
                                            <td class="align-middle">{{ $row->id_pesanan }}</td>
                                            <td class="align-middle">{{ $row->name }}</td>
                                            <td class="align-middle">{{ $row->email }}</td>

                                            <td class="align-middle text-center">
                                                @if ($row->payment_method === 'm_banking')
                                                    {{ __('Mobile Banking BRI') }}
                                                @elseif ($row->payment_method === 'e_wallet')
                                                    {{ __('E-wallet Dana') }}
                                                @else
                                                    {{ __('Empty') }}
                                                @endif
                                            </td>

                                            <td class="text-center align-middle">
                                                @if ($row->payment_status === 'Process')
                                                    <span class="badge badge-warning">{{ __('Process') }}</span>
                                                @elseif ($row->payment_status === 'Approved')
                                                    <span class="badge badge-success">{{ __('Approved') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-outline-secondary more-button">
                                                    <i class="fas fa-caret-down"></i></button>
                                            </td>

                                            <td class="align-middle text-center">
                                                <form action="{{ route('admin.manageorder.destroy', $row->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('delete')

                                                    <div class="btn-group">
                                                        <a class="btn btn-sm btn-warning bg-gradient"
                                                            href="{{ route('admin.manageorder.edit', $row->id_pesanan) }}"><i
                                                                class="fa fa-pencil-alt"></i></a>
                                                        <button type="submit" class="btn btn-sm btn-danger bg-gradient"
                                                            onclick="return confirm('Are you sure you want to delete this data?')"><i
                                                                class="fa fa-trash"></i></button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>

                                        <tr class="more-info-row">
                                            <td colspan="8">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <div class="card my-3">
                                                            <div class="card-body p-0">
                                                                <table class="table">
                                                                    <thead style="background-color: #f0fdf4">
                                                                        <tr>
                                                                            <th class="text-left">{{ __('Title') }}</th>
                                                                            <th class="text-center">{{ __('Thumbnail') }}
                                                                            </th>
                                                                            <th class="text-center">{{ __('Status') }}
                                                                            </th>
                                                                            <th class="text-center">{{ __('Quantity') }}
                                                                            </th>
                                                                            <th class="text-left">{{ __('Price') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                            $total = 0;
                                                                        @endphp
                                                                        @foreach ($row->orderDetails as $item)
                                                                            <tr>
                                                                                <td class="text-left">
                                                                                    {{ $item->ebook->title }}
                                                                                </td>

                                                                                <td class="text-center">
                                                                                    <a href="#" data-toggle="modal"
                                                                                        data-target="#thumbnailModal{{ $item->id }}">{{ __('Preview') }}</a>

                                                                                    <div class="modal fade"
                                                                                        id="thumbnailModal{{ $item->id }}"
                                                                                        tabindex="-1" role="dialog"
                                                                                        aria-labelledby="thumbnailModalLabel{{ $item->id }}"
                                                                                        aria-hidden="true">
                                                                                        <div class="modal-dialog modal-dialog-scrollable"
                                                                                            role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title"
                                                                                                        id="thumbnailModalLabel{{ $item->id }}">
                                                                                                        {{ __('Thumbnail Picture') }}
                                                                                                    </h5>
                                                                                                    <button type="button"
                                                                                                        class="close"
                                                                                                        data-dismiss="modal"
                                                                                                        aria-label="Close">
                                                                                                        <span
                                                                                                            aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <img src="{{ asset($item->ebook->thumbnail) }}"
                                                                                                        alt="Thumbnail Ebook"
                                                                                                        class="img-fluid"
                                                                                                        loading="lazy">
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button"
                                                                                                        class="btn btn-secondary"
                                                                                                        data-dismiss="modal">{{ __('Close') }}</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>

                                                                                <td class="text-center align-middle">
                                                                                    @if ($item->ebook->status === 'free')
                                                                                        <span class="badge badge-primary">
                                                                                            {{ __('Free') }}</span>
                                                                                    @elseif($item->ebook->status === 'paid')
                                                                                        <span
                                                                                            class="badge badge-success">{{ __('Paid') }}</span>
                                                                                    @endif
                                                                                </td>

                                                                                <td class="text-center">
                                                                                    x{{ $item->quantity }}
                                                                                </td>
                                                                                <td class="text-left">
                                                                                    {{ 'Rp ' . number_format($item->ebook->price, 0, ',', '.') }}
                                                                                </td>
                                                                            </tr>

                                                                            @php
                                                                                $total += $item->ebook->price * $item->quantity;
                                                                                $ebookPaid = $item->ebook->status === 'paid';
                                                                            @endphp
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="card my-3">
                                                            <div class="card-body d-flex flex-column">
                                                                <h5 class="card-title m-0 subtotal mb-2">Subtotal&nbsp;:
                                                                    {{ 'Rp ' . number_format($total, 0, ',', '.') }}</h5>
                                                                <h5 class="card-title m-0 total">
                                                                    Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                                    {{ 'Rp ' . number_format($total, 0, ',', '.') }}</h5>

                                                                @if ($ebookPaid)
                                                                    <a href="{{ asset($row->payment_proof) }}"
                                                                        class="btn btn-sm btn-dark mt-3" download>Download
                                                                        Bukti Pembayaran</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4 w-25"
                                                alt="Empty">
                                            <h4>{{ __('No orders found') }}</h4>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-6 mx-auto">
                <div class="d-flex align-content-center justify-content-center w-100">
                    {{ $orders->appends($_GET)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>

    <script>
        $(function() {
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

            @if (Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
            @endif

            $('.hoverable-row .more-button').on('click', function() {
                let row = $(this).closest('tr');
                let moreInfoRow = row.next('.more-info-row');
                let moreButton = $(this);

                if (row.hasClass('opened')) {
                    row.removeClass('opened');
                    moreInfoRow.hide();
                    moreButton.find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
                    moreButton.removeClass('btn-secondary').addClass('btn-outline-secondary');
                } else {
                    $('.hoverable-row.opened').not(row).each(function() {
                        let otherRow = $(this);
                        let otherMoreInfoRow = otherRow.next('.more-info-row');
                        let otherMoreButton = otherRow.find('.more-button');

                        otherRow.removeClass('opened');
                        otherMoreInfoRow.hide();
                        otherMoreButton.find('i').removeClass('fa-caret-up').addClass(
                            'fa-caret-down');
                        otherMoreButton.removeClass('btn-secondary').addClass(
                            'btn-outline-secondary');
                    });

                    row.addClass('opened');
                    moreInfoRow.show();
                    moreButton.find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
                    moreButton.removeClass('btn-outline-secondary').addClass('btn-secondary');
                }
            });

            $('#search').on('input', function() {
                filter();
            });

            $('#paymentMethod').on('change', function() {
                filter();
            });

            $('#paymentStatus').on('change', function() {
                filter();
            });

            function filter() {
                const searchValue = $('#search').val();
                const paymentMethodValue = $('#paymentMethod').val();
                const paymentStatusValue = $('#paymentStatus').val();

                const params = {};
                const url = '{{ route('admin.manageorder.index') }}';

                if (searchValue.trim() !== '') {
                    params.q = encodeURIComponent(searchValue.trim());
                }

                if (paymentMethodValue !== '') {
                    params.payment_method = paymentMethodValue;
                }

                if (paymentStatusValue !== '') {
                    params.payment_status = paymentStatusValue;
                }

                const queryString = Object.keys(params)
                    .map(key => key + '=' + params[key])
                    .join('&');

                const finalUrl = url + '?' + queryString;
                window.location.href = finalUrl;
            }
        });
    </script>
@endprepend

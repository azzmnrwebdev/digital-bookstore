@extends('admin.index')
@section('title-head', 'Manage Testimonial')
@section('title-content', 'Manage Testimonial')

@prepend('styles')
    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css') }}" />

    <style>
        #search:focus {
            border-color: #42B549;
        }

        .thead-custom {
            color: #ffffff;
            background-color: #00ACC1;
        }

        .hoverable-row:hover {
            background-color: #f2feff;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Manage Testimonial') }}</li>
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

                    <label for="search">{{ __('Search Testimonial') }}</label>
                    <input type="search" name="search" id="search" value="{{ $search }}" class="form-control"
                        placeholder="Search by name">

                    <div class="table-responsive mt-3">
                        <table class="table border-bottom text-nowrap">
                            <thead class="thead-custom">
                                <tr>
                                    <th class="text-center">{{ __('No') }}</th>
                                    <th class="text-left">{{ __('Name') }}</th>
                                    <th class="text-left">{{ __('Review') }}</th>
                                    <th class="text-center">{{ __('Created') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if ($testimonials->count() > 0)
                                    @php $number = ($testimonials->currentpage()-1) * $testimonials->perpage() + 1; @endphp
                                    @foreach ($testimonials as $row)
                                        <tr class="hoverable-row">
                                            <td class="text-center align-middle">{{ $number++ }}</td>
                                            <td class="text-left align-middle">{{ $row->user->fullname }}</td>

                                            <td class="text-left align-middle">
                                                @php
                                                    $text = $row->review;
                                                    if (strlen($row->review) > 80) {
                                                        $text = substr($row->review, 0, 80) . '...';
                                                    }
                                                @endphp

                                                {{ $text }}
                                            </td>

                                            <td class="text-center align-middle">{{ \Carbon\Carbon::parse($row->created_at)->format('l, d F Y H:m:s A') }}</td>

                                            <td class="text-center align-middle">
                                                <form action="{{ route('managetestimonial.destroy', $row->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('delete')

                                                    <button type="submit" class="btn btn-sm btn-danger bg-gradient"
                                                        onclick="return confirm('Are you sure you want to delete this data?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4 w-25"
                                                alt="Empty">
                                            <h4>{{ __('No testimonial found') }}</h4>
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
                    {{ $testimonials->appends($_GET)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>

    <script>
        $('#search').on('input', function() {
            filter();
        });

        function filter() {
            const searchValue = $('#search').val();
            const params = {};
            const url = '{{ route('managetestimonial.index') }}';

            if (searchValue.trim() !== '') {
                params.q = encodeURIComponent(searchValue.trim());
            }

            const queryString = Object.keys(params).map(key => key + '=' + params[key]);

            const finalUrl = url + '?' + queryString;
            window.location.href = finalUrl;
        }

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
    </script>
@endprepend

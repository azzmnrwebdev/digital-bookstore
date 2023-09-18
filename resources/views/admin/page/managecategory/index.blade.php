@extends('admin.index')
@section('title-head', 'Manage Category')
@section('title-content', 'Manage Category')

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

        .link-name {
            color: #42B549;
            font-weight: 500;
        }

        .hoverable-row:hover .link-name {
            color: #2E912F;
            text-decoration: underline;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Manage Category') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __('All Categories') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('managecategory.create') }}"
                                class="btn btn-sm btn-dark bg-gradient">{{ __('Create') }}</a>
                        </div>
                    </div>
                </div>

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

                    <label for="search">{{ __('Search Category') }}</label>
                    <input type="search" name="search" id="search" value="{{ $search }}" class="form-control"
                        placeholder="Search by name">

                    <div class="table-responsive mt-3">
                        <table class="table border-bottom text-nowrap">
                            <thead class="thead-custom">
                                <tr>
                                    <th class="text-center">{{ __('No') }}</th>
                                    <th class="text-left">{{ __('Category Name') }}</th>
                                    <th class="text-left">{{ __('Description') }}</th>
                                    <th class="text-center">{{ __('Created') }}</th>
                                    <th class="text-center">{{ __('Updated') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if ($categories->count() > 0)
                                    @php $number = ($categories->currentpage()-1) * $categories->perpage() + 1; @endphp
                                    @foreach ($categories as $row)
                                        <tr class="hoverable-row">
                                            <td class="text-center align-middle">{{ $number++ }}</td>
                                            <td class="text-left align-middle">
                                                <a href="{{ route('managecategory.show', $row->slug) }}"
                                                    class="link-name">{{ $row->name }}</a>
                                            </td>

                                            <td class="text-left align-middle">
                                                @php
                                                    $text = $row->description;
                                                    if (strlen($row->description) > 80) {
                                                        $text = substr($row->description, 0, 80) . '...';
                                                    }
                                                @endphp

                                                {{ $text }}
                                            </td>

                                            <td class="text-center align-middle">
                                                {{ $row->getTimeAgo($row->created_at) }}
                                            </td>
                                            <td class="text-center align-middle">
                                                {{ $row->getTimeAgo($row->updated_at) }}
                                            </td>

                                            <td class="text-center align-middle">
                                                <form action="{{ route('managecategory.destroy', $row->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <div class="btn-group">
                                                        <a href="{{ route('managecategory.edit', $row->slug) }}"
                                                            class="btn btn-sm btn-warning bg-gradient">
                                                            <i class="fa fa-pencil-alt"></i>
                                                        </a>

                                                        <button type="submit" class="btn btn-sm btn-danger bg-gradient"
                                                            onclick="return confirm('Are you sure you want to delete this data?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4 w-25"
                                                alt="Empty">
                                            <h4>{{ __('No categories found') }}</h4>
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
                    {{ $categories->appends($_GET)->links('pagination::bootstrap-4') }}
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
            const url = '{{ route('managecategory.index') }}';

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

        @if (Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
    </script>
@endprepend

@extends('admin.index')
@section('title-head', 'Manage Ebook Trash')
@section('title-content', 'Manage Ebook Trash')

@prepend('styles')
    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css') }}" />

    <style>
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
    <li class="breadcrumb-item active">{{ __('Manage Ebook Trash') }}</li>
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

                    <div class="table-responsive mt-3">
                        <table class="table border-bottom text-nowrap">
                            <thead class="thead-custom">
                                <tr>
                                    <th class="text-center">{{ __('No') }}</th>
                                    <th class="text-left">{{ __('Ebook Title') }}</th>
                                    <th class="text-center">{{ __('Author') }}</th>
                                    <th class="text-center">{{ __('Uploaded By') }}</th>
                                    <th class="text-center">{{ __('Date Removed') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if ($ebooksTrash->count() > 0)
                                    @php $number = ($ebooksTrash->currentpage()-1) * $ebooksTrash->perpage() + 1; @endphp
                                    @foreach ($ebooksTrash as $row)
                                        <tr class="hoverable-row">
                                            <td class="text-center align-middle">{{ $number++ }}</td>
                                            <td class="text-left align-middle">{{ $row->title }}</td>

                                            <td class="text-center align-middle">
                                                @foreach ($row->authors as $key => $author)
                                                    {{ $author->user->fullname }}@if ($key < count($row->authors) - 1),&nbsp;@endif
                                                @endforeach
                                            </td>

                                            <td class="text-center align-middle">
                                                {{ $getAuthorName[$row->id] }}
                                            </td>

                                            <td class="text-center align-middle">{{ \Carbon\Carbon::parse($row->deleted_at)->format('l, d F Y H:m:s A') }}</td>

                                            <td class="text-center align-middle">
                                                <div class="btn-group">
                                                    {{-- restore --}}
                                                    <form action="{{ route('manageebooktrash.restore', $row->id) }}" method="POST">
                                                        @csrf
                                                        @method('put')

                                                        <button type="submit" class="btn btn-sm btn-warning bg-gradient rounded-0 rounded-left" onclick="return confirm('Are you sure you want to restore this data?')">
                                                            <i class="fa fa-undo"></i> Restore
                                                        </button>
                                                    </form>

                                                    {{-- force delete --}}
                                                    <form action="{{ route('manageebooktrash.force_delete', $row->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="submit" class="btn btn-sm btn-danger bg-gradient rounded-0 rounded-right" onclick="return confirm('Are you sure you want to permanently delete this data?')">
                                                            <i class="fa fa-trash"></i> Force Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4 w-25"
                                                alt="Empty">
                                            <h4>{{ __('No ebooks found') }}</h4>
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
                    {{ $ebooksTrash->appends($_GET)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>

    <script>
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

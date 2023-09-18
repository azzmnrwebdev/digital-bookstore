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

        .form-control:focus {
            outline: 0;
            box-shadow: none;
            border-color: #42B549;
        }

        .btn-close:focus {
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
            <h2>My Testimonial</span></h2>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Testimonial</li>
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

                <h3 class="section-title">Your Testimonial About This Website</h3>

                @if (!$testimoni)
                    {{-- button create --}}
                    <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                        data-bs-target="#testimoniModal">Create</button>

                    <!-- Modal -->
                    <div class="modal fade" id="testimoniModal" tabindex="-1" aria-labelledby="testimoniModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="testimoniModalLabel">Rate App</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('testimonial.store') }}" method="post">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="review" class="form-label">Review <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="review" id="review" class="form-control" rows="5" required>{{ old('review') }}</textarea>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-sm btn-success">Submit Review</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- nampilin konten --}}
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="card-text">
                            @if ($testimoni)
                                {{ $testimoni->review }}
                            @else
                                You have not entered a testimonial.
                            @endif
                        </p>

                        @if ($testimoni)
                            <p class="card-text"><small class="text-body-secondary">Created
                                    {{ $testimoni->getTimeAgo($testimoni->created_at) }}</small></p>
                        @endif
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
    </script>
@endprepend

@extends('penulis.index')
@section('title-head', 'Edit Ebook')
@section('title-content', 'Edit Ebook')

@prepend('styles')
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .form-control:focus {
            border-color: #42B549 !important;
        }

        .select2-container--default .select2-selection--multiple {
            border-color: #CED4DA;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penulis.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('penulis.ebook.index') }}">Manage Ebook</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-body">

                    <div class="card mb-4 shadow-none">
                        <div class="card-header" style="background-color: #d8f8e2">
                            <h3 class="card-title">{{ __('Thumbnail Preview') }}</h3>
                        </div>

                        <div class="card-body px-0 pb-0 pt-2">
                            <img src="{{ asset($ebook->thumbnail) }}" id="thumbnailView" width="250px;" class="rounded"
                                alt="Thumbnail Image">
                        </div>
                    </div>

                    <form action="{{ route('penulis.ebook.update', $ebook->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div class="row">
                            {{-- isbn --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="isbn">{{ __('ISBN') }}</label>
                                <input type="text" name="isbn" id="isbn"
                                    class="form-control @error('isbn') is-invalid @enderror" placeholder="Cth:9786028519939"
                                    value="{{ old('isbn', $ebook->isbn) }}" pattern="[0-9]{10,13}" maxlength="13">

                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- title --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="title">{{ __('Title') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror" placeholder="Title"
                                    value="{{ old('title', $ebook->title) }}">

                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- status --}}
                            <div class="form-group col-12">
                                <label for="status">{{ __('Status') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="" selected>{{ __('Select a Status') }}</option>

                                    <option value="free"
                                        {{ old('status', $ebook->status) === 'free' ? 'selected' : '' }}>
                                        {{ __('Free') }}
                                    </option>
                                    <option value="paid"
                                        {{ old('status', $ebook->status) === 'paid' ? 'selected' : '' }}>
                                        {{ __('Paid') }}
                                    </option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- price --}}
                            <div class="form-group col-12 d-none" id="inputPrice">
                                <label for="price">{{ __('Price') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="text" name="price" id="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                    placeholder="Price, cth:100000" value="{{ old('price', $ebook->price) }}">

                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- category --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="category_ids">{{ __('Category') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <div class="select2-green">
                                    <select name="category_ids[]" id="category_ids"
                                        class="select2 select2-category @error('category_ids') is-invalid @enderror"
                                        multiple="multiple" data-placeholder="Select a Category"
                                        data-dropdown-css-class="select2-green" style="width: 100%;">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, old('category_ids', $ebook->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('category_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- author --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="author_ids">{{ __('Author') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <div class="select2-green">
                                    <select name="author_ids[]" id="author_ids"
                                        class="select2 select2-author @error('author_ids') is-invalid @enderror"
                                        multiple="multiple" data-placeholder="Select a Author"
                                        data-dropdown-css-class="select2-green" style="width: 100%;">
                                        @foreach ($authors as $author)
                                            <option value="{{ $author->id }}"
                                                {{ in_array($author->id, old('author_ids', $ebook->authors->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $author->user->fullname }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('author_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- description --}}
                            <div class="form-group col-12">
                                <label for="name">{{ __('Description') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                    rows="6" placeholder="Enter description">{{ old('description', $ebook->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- thumbnail --}}
                            <div class="form-group col-12">
                                <label for="thumbnail" class="form-label">{{ __('Thumbnail') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <div class="custom-file">
                                    <input type="file" name="thumbnail" id="thumbnail"
                                        class="custom-file-input @error('thumbnail') is-invalid @enderror"
                                        accept=".png, .jpg, .jpeg">
                                    <label class="custom-file-label"
                                        for="thumbnail">{{ $ebook->thumbnail ? 'Change thumbnail' : 'Choose thumbnail' }}</label>

                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- upload pdf --}}
                            <div class="form-group col-12">
                                <label for="pdf" class="form-label">{{ __('PDF Ebook') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <div class="custom-file">
                                    <input type="file" name="pdf" id="pdf"
                                        class="custom-file-input @error('pdf') is-invalid @enderror" accept=".pdf"
                                        aria-describedby="pdfHelp">
                                    <label class="custom-file-label"
                                        for="pdf">{{ $ebook->pdf ? 'Change ebook' : 'Choose ebook' }}</label>

                                    <small id="pdfHelp" class="form-text text-muted">Upload file ukuran ebook maksimal 10MB.</small>

                                    @error('pdf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- password ebook --}}
                            <div class="form-group col-12">
                                <label for="password">{{ __('Password Ebook') }}</label>
                                <input type="text" name="password" id="password" class="form-control"
                                    placeholder="Password ebook" value="{{ old('password', $ebook->password) }}"
                                    aria-describedby="passwordHelp">

                                <small id="passwordHelp" class="form-text text-muted">Jika ebook kamu memerlukan kata
                                    sandi, tuliskan kata sandi nya.</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sm btn-warning"><i
                                class="fas fa-pencil-alt mr-3"></i>{{ __('Update Ebook') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- select2 --}}
    <script src="{{ asset('AdminLTE/plugins/select2/js/select2.full.min.js') }}"></script>

    {{-- bs custom file input --}}
    <script src="{{ asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script>
        $(function() {
            bsCustomFileInput.init();

            $('.select2-category').select2();
            $('.select2-author').select2({
                minimumInputLength: 5,
                minimumResultsForSearch: Infinity,
            });

            const status = $("#status");
            const thumbnail = $('#thumbnail');
            const price = $("#inputPrice");

            status.on("change", function() {
                if (status.val() === "paid") {
                    price.removeClass('d-none').addClass('d-block');
                } else if (status.val() === "free") {
                    price.removeClass('d-block').addClass('d-none');
                }
            });

            if (status.val() === "paid") {
                price.removeClass('d-none').addClass('d-block');
            } else if (status.val() === "free") {
                price.removeClass('d-block').addClass('d-none');
            }

            thumbnail.on('change', function() {
                showThumbnail(this);
            });

            function showThumbnail(input) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    $('#thumbnailView').attr('src', e.target.result);
                    $('#thumbnailPreview').removeClass('d-none');
                }

                reader.readAsDataURL(file);
            }
        })
    </script>
@endprepend

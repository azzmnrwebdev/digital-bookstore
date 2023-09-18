@extends('admin.index')
@section('title-head', 'Create Category')
@section('title-content', 'Create Category')

@prepend('styles')
    <style>
        .form-control:focus {
            border-color: #42B549;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('managecategory.index') }}">{{ __('Manage Category') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-body">
                    <form action="{{ route('managecategory.store') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="name">{{ __('Category Name') }} <span
                                    class="text-danger">{{ __('*') }}</span></label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Enter category"
                                value="{{ old('name') }}">

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">{{ __('Description') }} <span
                                    class="text-danger">{{ __('*') }}</span></label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                rows="6" placeholder="Enter description">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm btn-success"><i
                                class="fas fa-check mr-3"></i>{{ __('Save Category') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
@endprepend

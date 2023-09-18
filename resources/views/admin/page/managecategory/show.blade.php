@extends('admin.index')
@section('title-head', 'Detail Category')
@section('title-content', 'Detail Category')

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
    <li class="breadcrumb-item active">{{ __('Detail') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">{{ __('Category Name') }}</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ $managecategory->name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="description">{{ __('Description') }}</label>
                        <textarea name="description" id="description" class="form-control" rows="6" readonly>{{ $managecategory->description }}</textarea>
                    </div>

                    <a href="{{ route('managecategory.index') }}" class="btn btn-sm btn-info"><i
                            class="fas fa-arrow-left mr-3"></i>{{ __('Previous') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
@endprepend

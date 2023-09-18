@extends('penulis.index')
@section('title-head', 'Detail Notification')
@section('title-content', 'Detail Notification')

@prepend('styles')
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penulis.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('penulis.notification.index') }}">{{ __('Notification') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Detail') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-body">
                    <h5 class="card-title font-weight-bolder mb-1">{{ $notification->title }}</h5>
                    <p class="card-text">{!! $notification->message !!}</p>

                    <div class="d-flex align-items-center justify-content-between mt-4">
                        <a href="{{ route('penulis.notification.index') }}"
                            class="btn btn-sm btn-info"><i class="fas fa-arrow-left mr-3"></i>{{ __('Previous') }}</a>

                        <form action="{{ route('penulis.notification.destroy', $notification->id) }}" method="POST">
                            @csrf
                            @method('delete')

                            <button type="submit" class="btn btn-sm btn-danger bg-gradient"
                                onclick="return confirm('Are you sure you want to delete this data?')">
                                <i class="fas fa-trash mr-3"></i>{{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
@endprepend

@extends('admin.index')
@section('title-head', 'Edit Order')
@section('title-content', 'Edit Order')

@prepend('styles')
    <style>
        .form-control:focus {
            border-color: #42B549;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.manageorder.index') }}">{{ __('Manage order') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
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

                    <form action="{{ route('admin.manageorder.update', $order->id) }}" method="post">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="payment_status">{{ __('Payment Status') }} <span
                                    class="text-danger">{{ __('*') }}</span></label>
                            <select name="payment_status" id="payment_status" class="form-control">
                                <option value="Process" @if ($order->payment_status === 'Process') selected @endif>
                                    {{ __('Process') }}
                                </option>

                                <option value="Approved" @if ($order->payment_status === 'Approved') selected @endif>
                                    {{ __('Approved') }}
                                </option>

                                <option value="Rejected" @if ($order->payment_status === 'Rejected') selected @endif>
                                    {{ __('Rejected') }}
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-sm btn-warning"><i
                                class="fas fa-pencil-alt mr-3"></i>{{ __('Update Order') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
@endprepend

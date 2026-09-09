@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Email Send') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('failed'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('failed') }}
                        </div>
                    @endif

                    <form action="{{ route('send-email') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="emailRecipient" class="col-md-4 col-form-label text-md-right">{{ __('To') }}</label>

                            <div class="col-md-6">
                                <input id="emailRecipient" type="email" class="form-control" name="emailRecipient" value="{{ old('emailRecipient') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emailCc" class="col-md-4 col-form-label text-md-right">{{ __('CC') }}</label>

                            <div class="col-md-6">
                                <input id="emailCc" type="email" class="form-control" name="emailCc" value="{{ old('emailCc') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emailBcc" class="col-md-4 col-form-label text-md-right">{{ __('BCC') }}</label>

                            <div class="col-md-6">
                                <input id="emailBcc" type="email" class="form-control" name="emailBcc" value="{{ old('emailBcc') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emailSubject" class="col-md-4 col-form-label text-md-right">{{ __('Subject') }}</label>

                            <div class="col-md-6">
                                <input id="emailSubject" type="text" class="form-control" name="emailSubject" value="{{ old('emailSubject') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emailBody" class="col-md-4 col-form-label text-md-right">{{ __('Body') }}</label>

                            <div class="col-md-6">
                                <textarea id="emailBody" class="form-control" name="emailBody">{{ old('emailBody') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emailAttachments" class="col-md-4 col-form-label text-md-right">{{ __('Attachment') }}</label>

                            <div class="col-md-6">
                                <input id="emailAttachments" type="file" class="form-control" name="emailAttachments[]" multiple>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Email') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
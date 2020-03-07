@extends('layouts.app')

@section('content')
<div class="container">
    <div class="columns is-centered">
        <div class="column is-half">
            <div class="card">
                <div class="card-content">
                    <p class="card-title">
                        {{ __('Verify Your Email Address') }}
                    </p>

                    @if (session('resent'))
                        <div class="notification is-info">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form id="form-verification-link" class="is-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <a class="has-text-primary" onclick="document.getElementById('form-verification-link').submit()">
                            {{ __('click here to request another') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

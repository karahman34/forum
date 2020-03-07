@extends('layouts.app')

@section('content')
<div class="container">
    <div class="columns is-centered">
        <div class="column is-half">
            <div class="card">
                <div class="card-content">
                    <p class="card-title">Forget Password</p>

                    @if (session('status'))
                        <div class="notification is-info">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        {{-- Email Field --}}
                        <div class="field">
                            <label for="email" class="label">E-Mail</label>
                            <div class="control has-icons-left">
                                <input type="email" name="email" id="email" class="input" placeholder="E-Mail" autofocus>
                                <span class="icon is-left">
                                    <i class="mdi mdi-email icon-in-form"></i>
                                </span>
                            </div>
                            @error('email')
                                <span class="has-text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="margin-bottom:13px;padding-top:10px;">
                            <a href="{{route('login') }}" class="has-text-primary">Back to login page</a>
                            <button type="submit" class="button is-primary is-pulled-right">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="columns is-centered">
        <div class="column is-8">
            <div class="card">
                <div class="card-content">
                    <p class="card-title">{{ __('Reset Password') }}</p>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- Email Field --}}
                        <div class="field">
                            <label for="email" class="label">E-Mail</label>
                            <div class="control has-icons-left">
                                <input type="email" name="email" id="email" class="input" placeholder="E-Mail" value="{{ old('email') }}" autofocus>
                                <span class="icon is-left">
                                    <i class="mdi mdi-email icon-in-form"></i>
                                </span>
                            </div>
                            @error('email')
                                <span class="has-text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password Input --}}
                        <div class="field">
                            <label for="password" class="label">Password</label>
                            <div class="control has-icons-left">
                                <input id="password" type="password" class="input" type="text" name="password" placeholder="Password">
                                <span class="icon is-left">
                                    <i class="mdi mdi-lock"></i>
                                </span>
                            </div>
                            @error('password')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password Input --}}
                        <div class="field" style="margin-bottom:25px;">
                            <label for="password_confirmation" class="label">Confirm Password</label>
                            <div class="control has-icons-left">
                                <input id="password_confirmation" type="password" class="input" type="text" name="password_confirmation" placeholder="Confirm Password">
                                <span class="icon is-left">
                                    <i class="mdi mdi-lock"></i>
                                </span>
                            </div>
                            @error('password_confirmation')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="button is-primary">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

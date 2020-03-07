@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 20px;">
    <div class="columns">
        <div class="card column is-half is-offset-one-quarter">
            <div class="card-content">
                {{-- Card Title --}}
                <p class="has-text-weight-semibold" style="margin-bottom: 20px;">
                    REGISTER
                </p>

                {{-- Form --}}
                <form id="form-login" action="{{ route('register') }}" method="POST">
                    @csrf

                    {{-- Username Input --}}
                    <div class="field">
                        <label for="username" class="label">Username</label>
                        <div class="control has-icons-left">
                            <input id="username" class="input" autofocus type="text" name="username" value="{{ old('username') }}" placeholder="Username">
                            <span class="icon is-left">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        @error('username')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email Input --}}
                    <div class="field">
                        <label for="email" class="label">E-Mail</label>
                        <div class="control has-icons-left">
                            <input id="email" class="input" autofocus type="text" name="email" value="{{ old('email') }}" placeholder="E-Mail">
                            <span class="icon is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                        @error('email')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Input --}}
                    <div class="field">
                        <label for="password" class="label">Password</label>
                        <div class="control has-icons-left">
                            <input id="password" type="password" class="input" type="text" name="password" placeholder="Password">
                            <span class="icon is-left">
                                <i class="fas fa-lock"></i>
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
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        @error('password_confirmation')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Register --}}
                    <a href="/register" class="has-text-primary">Already have an account ?</a>
                    {{-- Submit --}}
                    <button type="submit" class="button is-primary is-rounded is-pulled-right">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

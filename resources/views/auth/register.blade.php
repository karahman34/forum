@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 20px;">
    <div class="columns">
        <div class="card column is-half is-offset-one-quarter">
            <div class="card-content">
                {{-- Card Title --}}
                <p class="card-title">
                    REGISTER
                </p>

                {{-- Form --}}
                <form id="form-login" action="{{ route('register') }}" method="POST">
                    @csrf

                    {{-- Username Input --}}
                    <div class="field">
                        <label for="username" class="label">Username</label>
                        <div class="control has-icons-left">
                            <input id="username" class="input @error('username') is-danger @enderror" autofocus type="text" name="username" value="{{ old('username') }}" placeholder="Username">
                            <span class="icon is-left">
                                <i class="mdi mdi-account icon-in-form"></i>
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
                            <input id="email" class="input @error('email') is-danger @enderror" autofocus type="text" name="email" value="{{ old('email') }}" placeholder="E-Mail">
                            <span class="icon is-left">
                                <i class="mdi mdi-email icon-in-form"></i>
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
                            <input id="password" type="password" class="input @error('password') is-danger @enderror" type="text" name="password" placeholder="Password">
                            <span class="icon is-left">
                                <i class="mdi mdi-lock icon-in-form"></i>
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
                                <i class="mdi mdi-lock icon-in-form"></i>
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

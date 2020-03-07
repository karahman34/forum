@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="columns is-centered">
            <div class="card column is-half">
                <div class="card-content">
                    {{-- Card Title --}}
                    <p class="has-text-weight-semibold" style="margin-bottom: 20px;">
                        LOGIN
                    </p>

                    {{-- Form --}}
                    <form id="form-login" action="{{ route('login') }}" method="POST">
                        @csrf

                        {{-- Email / Username Input --}}
                        <div class="field">
                            <label for="email_or_username" class="label">Email / Username</label>
                            <div class="control has-icons-left">
                                <input id="email_or_username" class="input" autofocus type="text" name="email_or_username" value="{{ old('email_or_username') }}" placeholder="Email / Username">
                                <span class="icon is-left">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            @error('email_or_username')
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

                        {{-- Register --}}
                        <a href="/register" class="has-text-primary" style="margin-top:10px;">Don't have an account ?</a>
                        {{-- Submit --}}
                        <button type="submit" class="button is-primary is-rounded is-pulled-right">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
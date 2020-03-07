@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="columns is-centered">
            <div class="card column is-half">
                <div class="card-content">
                    {{-- Card Title --}}
                    <p class="card-title">
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
                                    <i class="mdi mdi-email icon-in-form"></i>
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
                                    <i class="mdi mdi-lock icon-in-form"></i>
                                </span>
                            </div>
                            @error('password')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="is-inline-block">
                            {{-- Register --}}
                            <a href="{{route('register')}}" class="is-block has-text-primary" style="margin-top:10px;">Don't have an account ?</a>
                            {{-- Forget Password --}}
                            <a href="{{route('password.request')}}" class="has-text-primary" style="margin-top:10px;">Forget Password ?</a>
                        </div>
                        {{-- Submit --}}
                        <button type="submit" class="button is-primary is-rounded is-pulled-right" style="margin-top:20px;">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
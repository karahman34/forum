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
              <input id="email_or_username" class="input @error('email_or_username') is-danger @enderror" type="text" name="email_or_username" value="{{ old('email_or_username') }}" placeholder="Email / Username" autofocus>
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
              <input id="password" type="password" class="input @error('password') is-danger @enderror" type="text" name="password" placeholder="Password">
              <span class="icon is-left">
                <i class="mdi mdi-lock icon-in-form"></i>
              </span>
            </div>
            @error('password')
              <p class="help is-danger">{{ $message }}</p>
            @enderror
          </div>

          {{-- Remember Me --}}
          <label class="checkbox is-block">
            <input name="remember" type="checkbox">
            Remember me
          </label>

          <div class="is-inline-block" style="margin-top:15px;">
            {{-- Register --}}
            <a href="{{route('register')}}" class="is-block has-text-primary">Don't have an account ?</a>
            {{-- Forget Password --}}
            <a href="{{route('password.request')}}" class="has-text-primary">Forget Password ?</a>
          </div>
          
          {{-- Submit --}}
          <button type="submit" class="button is-primary is-rounded is-pulled-right" style="margin-top:20px;">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
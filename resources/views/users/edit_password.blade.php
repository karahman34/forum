@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-half">
        <div class="card">
          <div class="card-content">
            <p class="card-title">
              Edit Password
            </p>

            {{-- Set Notif Type --}}
            @php
              $notifType = null;
              if (session('success')) {
                $notifType = 'is-success';
              }

              if (session('failed')) {
                $notifType = 'is-danger';
              }
            @endphp

            {{-- Notif --}}
            @if ($notifType !== null)
              <div class="notification custom {{ $notifType }}">
                @if (session('success'))
                  {{ session('success') }}
                @elseif (session('failed'))
                  {{ session('failed') }}  
                @endif
              </div>
            @endif

            <form action="{{ route('user.update_password', ['username' => $username]) }}" method="POST">
              @csrf
              @method('PUT')

              {{-- Old Password --}}
              <div class="field">
                <label for="old_password" class="label">Old Password</label>
                <div class="control">
                  <input id="old_password" type="password" name="old_password" class="input @error('old_password') is-danger @enderror" placeholder="Old Password" autofocus>
                </div>
                @error('old_password')
                  <p class="help is-danger">{{ $message }}</p>
                @enderror
              </div>

              {{-- New Password --}}
              <div class="field">
                <label for="password" class="label">New Password</label>
                <div class="control">
                  <input id="password" type="password" name="password" class="input @error('password') is-danger @enderror" placeholder="New Password">
                </div>
                @error('password')
                  <p class="help is-danger">{{ $message }}</p>
                @enderror
              </div>

              {{-- Password Confirmation --}}
              <div class="field">
                <label for="password_confirmation" class="label">Password Confirmation</label>
                <div class="control">
                  <input id="password_confirmation" type="password" name="password_confirmation" class="input @error('password_confirmation') is-danger @enderror" placeholder="Password Confirmation">
                </div>
                @error('password_confirmation')
                  <p class="help is-danger">{{ $message }}</p>
                @enderror
              </div>

              {{-- Footer --}}
              <div class="level" style="margin-top:15px;">
                <div class="level-left"></div>
                <div class="level-right">
                  <div class="level-item">
                    {{-- Update Button --}}
                    <button type="submit" class="button is-rounded is-primary">
                      Update
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
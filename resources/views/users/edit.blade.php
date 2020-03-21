@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-half">
        <div class="card">
          <div class="card-content">
            <p class="card-title">Edit {{ $user->username }}</p>

            {{-- Notif --}}
            @if(session('success'))
              <div class="notification custom is-success">
                {{ session('success') }}
              </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('user.update', ['id' => $user->id])}}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              {{-- Username Input --}}
              <div class="field">
                  <label for="username" class="label">Username</label>
                  <div class="control has-icons-left">
                    <input id="username" class="input @error('username') is-danger @enderror" autofocus type="text" name="username" value="{{ old('username') ?? $user->username }}" placeholder="Username">
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
                    <input id="email" class="input @error('email') is-danger @enderror" autofocus type="text" name="email" value="{{ old('email') ?? $user->email }}" placeholder="E-Mail">
                    <span class="icon is-left">
                        <i class="mdi mdi-email icon-in-form"></i>
                    </span>
                  </div>
                  @error('email')
                    <p class="help is-danger">{{ $message }}</p>
                  @enderror
              </div>

              {{-- Avatar Preview --}}
              <figure class="image is-96x96 image-preview @if ($user->avatar === null) is-hidden @endif">
                <img src="{{ $user->getAvatar() }}" alt="{{ $user->getAvatar() }}">
              </figure>

              <!-- Avatar Field -->
              <div class="file has-name" style="margin-top:20px;">
                <label class="file-label">
                  <input
                    id="avatar-field"
                    type="file"
                    name="avatar"
                    class="file-input"
                    accept="image/*"
                    onchange="avatarOnChange(this.files)"
                  />
                  <span class="file-cta">
                    <span class="file-icon">
                      <i class="mdi mdi-upload mdi-24px"></i>
                    </span>
                    <span class="file-label">Choose a avatar..</span>
                  </span>
                  <span class="file-name">
                    No File Selected.
                  </span>
                </label>
              </div>
              {{-- Avatar Error --}}
              @error('avatar')
                <p class="help is-danger">{{ $message }}</p>
              @enderror

              <div class="is-flex" style="justify-content: flex-end;margin-top:12px;">
                {{-- Submit --}}
                <button type="submit" class="button is-primary is-rounded">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    function setImagePreview(src) {
      const imagePreview = document.querySelector('.image-preview');
      if (src === null) {
        imagePreview.classList.add('is-hidden');
      } else {
        imagePreview.classList.remove('is-hidden');

        const img = imagePreview.getElementsByTagName('img')[0];
        img.setAttribute('src', src);
      }
    }

    function avatarOnChange(files) {
      const fileName = document.querySelector('.file-name');
      if (files.length === 0) {
        fileName.innerHTML = 'No File Selected.';

        // Remove current preview img
        setImagePreview(null);
      } else {
        const file = files[0];
        fileName.innerHTML = file.name;

        // Set Preview Image
        const src = URL.createObjectURL(file);
        setImagePreview(src);
      }
    }
  </script>
@endpush

@push('css')
  <style>
    .image-preview img {
      box-shadow: 0px 6px 8px rgba(128, 128, 128, 0.75);
    }

    .file-name {
      max-width: none;
      width: auto !important;
    }
  </style>
@endpush
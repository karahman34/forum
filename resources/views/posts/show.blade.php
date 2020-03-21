@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="columns">
      {{-- Left bar --}}
      <div class="column is-3-tablet is-2-desktop hidden-mobile">
        @include('partials.navigations.left')
      </div>

      {{-- Content --}}
      <div id="main-content" class="column is-10-desktop is-9-widescreen is-8-fullhd">
        {{-- Post Component --}}
        @component('components.posts.show', ['post' => $post])
        @endcomponent
        
        @php
          $auth = null;
          if (Auth::check()) {
            $auth = Auth::user();
            $auth['avatar'] = $auth->getAvatar();
          }
        @endphp

        {{-- Comments Section --}}
        <comment-section
          @auth 
            :auth="{{ $auth }}"
            post-author="{{ $post->user_id === Auth::id() ? 'y' : 'n' }}" 
          @endauth
          post-id="{{ $post->id }}"
        ></comment-section>

        @guest
          <div class="has-text-centered title is-5" style="margin-top:30px; margin-bottom: 20px;">
            Please
            <a href="{{ route('login') }}" class="has-text-primary">Sign in</a>
            or
            <a href="{{ route('register') }}" class="has-text-primary">Sign up</a>
            participate in this conversation.
            </div>
        @endguest
      </div>
    </div>
  </div>
@endsection

@push('css')
  <style>
    @media screen and (min-width: 1216px) {
      #main-content {
        padding-left: 100px;
      }
    }
  </style>
@endpush

@push('js')
  <script>
    function incrementPostSeen() {
      const actionUrl = "{{ route('post.seen', ['id' => $post->id]) }}";
      
      axios.post(actionUrl)
        .catch(err => {
          throw Error(err);
        });
    }

    window.addEventListener('load', () => {
      incrementPostSeen();
    });
  </script>
@endpush
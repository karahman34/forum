@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="columns">
      {{-- Left bar --}}
      <div class="column is-3-tablet is-2-desktop">
        @include('partials.navigations.left')
      </div>

      {{-- Content --}}
      <div id="main-content" class="column is-10-desktop is-9-widescreen is-8-fullhd">
        {{-- Post Component --}}
        @component('components.posts.show', ['post' => $post])
        @endcomponent

        @auth
            {{-- Comment Create --}}
          <comment-create 
            avatar="{{  Auth::user()->getAvatar() }}"
            url="{{ route('post.comments', ['id' => $post->id]) }}" 
          ></comment-create>
        @endauth

        {{-- Comments Section --}}
        <comment-section
          @auth 
            :auth="{{ Auth::user()->toJson() }}"
            post-author="{{ $post->user_id === Auth::id() ? 'y' : 'n' }}" 
          @endauth
          url="{{ route('post.comments', ['id' => $post->id]) }}"
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
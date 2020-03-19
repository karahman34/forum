@extends('layouts.app')

@section('content')
  <div id="div-root" class="container">
    <div class="columns">
      {{-- Profile --}}
      <div class="column is-3-tablet is-2-widescreen">
        @component('components.users.leftNav', ['user' => $user])
        @endcomponent
      </div>

      {{-- No Posts --}}
      <div id="no-posts" class="column is-12 @if (count($posts) > 0) is-hidden @endif">
        <p class="title is-3 has-text-weight-medium has-text-centered has-text-grey-dark">
          No Posts.
        </p>
      </div>

      @if (count($posts) > 0)
        {{-- User Posts --}}
        <div class="column is-9-tablet is-10-widescreen">
          <div class="columns is-multiline" style="margin-bottom:20px;">
            {{-- Post Card --}}
            @foreach ($posts as $post)
              <div id="post-{{ $post->id }}" class="column is-half user-posts">
                @component('components.posts.postCard', ['post' => $post])
                @endcomponent
              </div>
            @endforeach
          </div>

          {{-- Pagination --}}
          {{ $posts->onEachSide(4)->links('components.pagination') }}
        </div>
      @endif
    </div>
  </div>
@endsection

@push('js')
  <script>
    window.addEventListener('post-delete', ({ postId }) => {
      // Remove post card
      const postCard = document.getElementById(`post-${postId}`);
      postCard.parentNode.removeChild(postCard);

      const posts = document.getElementsByClassName('user-posts');
      if (!posts.length) {
        document.getElementById('no-posts').classList.remove('is-hidden');
      }
    });
  </script>
@endpush

@push('css')
  <style>
    #div-root {
      margin-bottom: 50px;
    }
  </style>
@endpush


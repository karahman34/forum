@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="columns">
      <div class="column is-2">
        @component('partials.navigations.left')
        @endcomponent
      </div>

      <div class="column is-10">
        <div class="columns is-multiline">

          <div id="no-saved-posts" class="column is-12 @if(count($posts) > 0) is-hidden @endif">
            <h3 class="has-text-centered has-text-grey title is-3 has-text-weight-medium" style="margin-top:30px;">
              No Saved Posts.
            </h3>
          </div>
          
          @if (count($posts) > 0)
            @foreach ($posts as $post)
              <div id="post-{{ $post->id }}" class="column is-half">
                @component('components.posts.postCard', ['post' => $post])
                  <x-slot name="header">
                    <div class="saved-time">
                      Saved {{ $post->pivot->created_at->diffForHumans() }}.
                    </div>
                  </x-slot>
                @endcomponent 
              </div>
            @endforeach
          @endif

          <div class="column is-12-mobile has-text-centered">
            {{-- Pagination --}}
            {{ $posts->onEachSide(4)->links('components.pagination') }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('css')
  <style>
    .saved-time {
      color: #4d4848;
      padding-bottom: 3px;
      margin-bottom: 8px;
      border-bottom:1px solid rgba(128, 128, 128, 0.5);
    }
  </style>
@endpush

@push('js')
  <script>
    window.addEventListener('post-unsave', ({ postId }) => {
      // Remove post card
      const postCard = document.getElementById(`post-${postId}`);
      postCard.parentNode.removeChild(postCard);

      // Check count postcard
      const postCards = document.getElementsByClassName('post-card');
      if (!postCards.length) {
        document.getElementById('no-saved-posts').classList.remove('is-hidden');
      }
    });
  </script>
@endpush
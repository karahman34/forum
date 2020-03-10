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

        {{-- Comments Section --}}
        <comment-list 
          url="{{ route('post.comments', ['id' => $post->id]) }}"
        ></comment-list>
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
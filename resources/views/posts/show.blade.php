@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="columns">
      {{-- Left bar --}}
      <div class="column is-2">
        @include('partials.navigations.left')
      </div>

      {{-- Content --}}
      <div id="main-content" class="column is-7">
        {{-- Post Component --}}
        @component('components.posts.show', ['post' => $post])
        @endcomponent
      </div>
    </div>
  </div>
@endsection

@push('css')
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <style>
    #main-content {
      padding-left: 100px;
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
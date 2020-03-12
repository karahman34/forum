@extends('layouts.app')

@section('content')
  <div id="div-root" class="container">
    <div class="columns">
      {{-- Profile --}}
      <div class="column is-3-tablet is-2-widescreen">
        @component('components.users.leftNav', ['user' => $user])
        @endcomponent
      </div>

      {{-- User Posts --}}
      <div class="column is-9-tablet is-10-widescreen">
        <div class="columns is-multiline" style="margin-bottom:20px;">
          {{-- Post Card --}}
          @foreach ($posts as $post)
            <div class="column is-half">
              @component('components.posts.postCard', ['post' => $post])
              @endcomponent
            </div>
          @endforeach
        </div>

        {{-- Paginate --}}
        {{ $posts->onEachSide(3)->links('components.pagination') }}
      </div>
    </div>
  </div>
@endsection

@push('css')
  <style>
    #div-root {
      margin-bottom: 50px;
    }
  </style>
@endpush


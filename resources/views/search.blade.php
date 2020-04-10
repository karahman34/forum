@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="columns">
      <div class="column is-2 is-hidden-mobile">
        @component('partials.navigations.left')
        @endcomponent
      </div>

      <div class="column is-10">
        {{-- Users Result --}}
        <search-users-result></search-users-result>

        {{-- Posts Result --}}
        <search-posts-result id="posts-result" @auth :auth="{{ auth()->user() }}" @endauth></search-posts-result>
      </div>
    </div>
  </div>
@endsection

@push('css')
  <style>
    #posts-result {
      margin-top: 30px;
    }
  </style>
@endpush
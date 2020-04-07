@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="columns is-mobile">
      <div class="column is-2 is-hidden-mobile">
        @include('partials.navigations.left')
      </div>

      <div class="column is-12-mobile is-9-desktop">
        <notifications></notifications>
      </div>
    </div>
  </div>
@endsection
@extends('layouts.app')

@section('content')
  {{-- Post Form --}}
  <post-form 
    csrf="{{ csrf_token() }}"
    action="{{ $action }}"
    method="{{ $method }}"
    @isset($post) :post="{{ $post->toJson() }}" @endisset
  ></post-form>
@endsection
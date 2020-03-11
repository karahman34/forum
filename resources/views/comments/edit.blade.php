@extends('layouts.app')

@section('content')
  <comment-edit 
    method="PUT" 
    csrf="{{ csrf_token() }}" 
    :comment="{{ $comment->toJson() }}"
    action="{{ route('comment.update', ['id' => $comment->id] ) }}" 
  ></comment-edit>
@endsection
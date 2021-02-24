@extends('themes.'.env('THEME', 'default').'.layouts.main')
@section('local_css')
@endsection
 
@section('content')
<div class="tt-empty">
  <i class="icon-lock-circled" style="font-size: 120px"></i>
  <div class="tt-page__name text-center">
    <h1>403 - Unauthorized Action</h1>
    <p>We are sorry, but you don't have the necessary permissions to access this resource.</p>
  </div>
  <div class="tt-empty__btn">
    <a class="btn" href="{{ URL::previous() }}">Go Back</a>
  </div>
</div>
@endsection
 
@section('local_script')
@endsection
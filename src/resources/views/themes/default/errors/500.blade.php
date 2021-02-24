@extends('themes.'.env('THEME', 'default').'.layouts.main')
@section('local_css')
@endsection
@section('content')
<div class="tt-empty">
  <i class="tt-empty__icon"><img src="/images/empty-500.svg" alt="Image name"></i>
  <div class="tt-page__name text-center">
    <h1>500 - Nothing Found</h1>
    <p>We are sorry, but the page you're looking for cannot be found.</p>
  </div>
  <div class="tt-empty__btn">
      <a class="btn" href="{{ URL::previous() }}">Go Back</a>
  </div>
</div>
@endsection
@section('local_script')
@endsection
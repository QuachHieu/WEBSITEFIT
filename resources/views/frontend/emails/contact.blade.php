@extends('frontend.layouts.email')

@section('content')
  <h1>@lang('You received a new appointment from the system')</h1>

  <p>@lang('Content appointment'): </p>

  <p>
    <strong>@lang('Fullname')</strong>: {{ $contact->name }}
  </p>
  <p>
    <strong>@lang('Email')</strong>: {{ $contact->email }}
  </p>
  <p>
    <strong>@lang('Phone')</strong>: {{ $contact->phone }}
  </p>
  <p>
    <strong>@lang('Content note')</strong>: {{ $contact->content }}
  </p>
@endsection

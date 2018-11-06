@extends('layouts.api-doc')

@section('content')
    <redoc spec-url="{{ route('api-spec') }}"></redoc>
@endsection

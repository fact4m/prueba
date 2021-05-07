@extends('tenant.layouts.app')

@section('content')

    <tenant-documents-index :admin="{{auth()->user()->admin}}"></tenant-documents-index>

@endsection
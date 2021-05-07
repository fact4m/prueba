@extends('tenant.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 col-md-12 pt-2 pt-md-0">
            <tenant-companies-form></tenant-companies-form>
        </div>
        <div class="col-lg-6 col-md-12">
            <tenant-establishments-form></tenant-establishments-form>
        </div>
    </div>
    @if (auth()->user()->admin)
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <tenant-certificates-index></tenant-certificates-index>
            </div>
            <div class="col-lg-6 col-md-12">
                <tenant-series-form></tenant-series-form>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <tenant-users-form></tenant-users-form>
        </div>
        <div class="col-lg-6 col-md-12">
            <tenant-options-form></tenant-options-form>
        </div>
    </div>
    @if (auth()->user()->admin)
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <tenant-bank_accounts-index></tenant-bank_accounts-index>
            </div>
            <div class="col-lg-6 col-md-12">
                <tenant-units-index></tenant-units-index>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <tenant-banks-index></tenant-banks-index>
            </div>
        </div>
    @endif
@endsection
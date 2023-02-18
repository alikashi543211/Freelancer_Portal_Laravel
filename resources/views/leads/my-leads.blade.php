@extends('layout')

@section('title','Leads')

@section('head')
@parent
<link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .project-type-icon {
        position: absolute;
        left: 10px;
        top: 10px;
    }

    .main-search-header {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding: 21px 15px;
    }

    .main-search-header .in-left {}

    .main-search-header .in-left .form-search {
        display: flex;
        align-items: center;
    }

    .main-search-header .in-left .form-search {
        max-width: 600px;
        width: 100%;
        position: relative;
        padding: 0;
        margin: 0;
    }

    .main-search-header .in-left .form-search input {
        width: 100%;
        height: 40px;
        border: 1px solid #ddd;
        padding-left: 34px;
    }

    .main-search-header .in-left .form-search i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 0;
        padding: 0 10px;
    }

    .main-search-header .in-left .form-search .search-result {}

    .main-search-header .in-left .form-search .search-result h5 {
        white-space: nowrap;
        margin: 0;
        margin-left: 11px;
    }

    .main-search-header .in-right {}

    .main-search-header .in-right h5 {}

</style>
@endsection
@section('content')
<form>
    <div class="row">
        <div class="col-md-12">
            {!! $leads !!}
        </div>
    </div>
</form>
@endsection

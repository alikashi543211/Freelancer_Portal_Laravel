<!-- Font Awesome -->
<link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .wrapper .content-wrapper {
        min-height: calc(100vh - calc(3.5rem + 1px)) !important;
    }

</style>
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

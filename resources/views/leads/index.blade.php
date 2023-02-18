@extends('layout')

@section('title','Leads')

@section('head')
@parent
<link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@endsection
@section('content')
<form>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <form id="filters-form">
                        <h6>Project Type</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="project_types[]" value="fixed" id="fixed-projects">
                            <label for="fixed-projects" class="form-check-label"> Fixed Projects</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="project_types[]" value="hourly" id="hourly-projects">
                            <label for="hourly-projects" class="form-check-label"> Hourly Projects</label>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Skills</label>
                            <select class="select2" name="jobs[]" multiple="multiple" data-placeholder="Select Skills" style="width: 100%;">
                                @foreach ($allJobs as $job)
                                <option value="{{ $job->id }}">{{ $job->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="listing-types">Listing Types</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="project_upgrades[]" value="featured" id="featured">
                                <label for="featured" class="form-check-label"> Featured</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="project_upgrades[]" value="sealed" id="sealed">
                                <label for="sealed" class="form-check-label"> Sealed</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="project_upgrades[]" value="NDA" id="NDA">
                                <label for="NDA" class="form-check-label"> NDA</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="project_upgrades[]" value="urgent" id="urgent">
                                <label for="urgent" class="form-check-label"> Urgent</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="project_upgrades[]" value="fulltime" id="fulltime">
                                <label for="fulltime" class="form-check-label"> Fulltime</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="main-search-header">
                            <div class="in-left">
                                <div class="form-search">
                                    <input type="text" placeholder="Search for project..." name="query" class="form-control">
                                    <i class="fa fa-search"></i>
                                    <div class="search-result">
                                        <h5><span id="total-results">{{ $total_count  }}</span> Results</h5>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="in-right d-flex justify-content-center align-items-center">
                                <div class="form-group">
                                    <select name="time_updated" id=""></select>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div id="leads">
                {!! $leads !!}
            </div>
            <div class="spinner-border text-primary mx-auto mt-5" style="display: none"></div>
            @endsection
        </div>
    </div>
</form>
@section('foot')
@parent
<script src="{{ url('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap4',
            multiple: true
        });
        bsCustomFileInput.init();
    });
    $('form').on('submit', function (e) {
        e.preventDefault();
    });
    $(document).on('change', 'form', function () {
        $('#leads').html('');
        $('.spinner-border').css('display', 'block');
        $.ajax({
            url: "{{ url('leads/get-leads') }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $('form').serialize(),
            success: function (res) {
                $('.spinner-border').css('display', 'none');
                $('#leads').html(res.leads);
                $('#total-results').html(res.total);
            }
        })
    });

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        $('#leads').html('');
        $('.spinner-border').css('display', 'block');
        $.ajax({
            url: "{{ url('leads/get-leads') }}?page=" + $(this).html(),
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $('form').serialize(),
            success: function (res) {
                $('.spinner-border').css('display', 'none');
                $('#leads').html(res.leads);
                $('#total-results').html(res.total);
            }
        })
    });

</script>
@endsection

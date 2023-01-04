@extends('crudbooster::admin_template')
@section('content')

<div id='box_main' class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Upload a File</h3>
        <div class="box-tools"></div>
    </div>

    @if ($message = Session::get('success_import'))
    <div class="alert alert-success" role="alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('success_import') }}
    </div>
    @endif 
    @if ($message = Session::get('error_import'))
    <div class="alert alert-danger" role="alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        Errors Found !
        <li>
            {!! Session::get('error_import') !!}
        </li>
        
    </div>
    @endif

    <form method='post' id="form" enctype="multipart/form-data" action="{{ $uploadRoute }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box-body">

            <div class='callout callout-success'>
                <h4>Welcome to Data Importer Tool</h4>
                Before uploading a file, please read below instructions : <br/>
                1. System will not accept wrong Item Code<br/>
                2. System will not accept blank<br/>
                3. File format should be : CSV file format<br/>
            </div>

            <label class='col-sm-2 control-label'>Import Template File: </label>
            <div class='col-sm-4'>
                <a href="{{ $uploadTemplate }}" class="btn btn-primary" role="button">Download Template</a>
            </div>
            <br/>
            <br/>

            <label for='import_file' class='col-sm-2 control-label'>File to Import: </label>
            <div class='col-sm-4'>
                <input type='file' name='import_file' class='form-control' required accept=".csv"/>
                <div class='help-block'>File type supported only : CSV</div>
            </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
            
            <a href="{{ route('getUploadModule') }}" class='btn btn-default pull-left'>Cancel</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> Upload</button>
        </div><!-- /.box-footer-->
    </form>
</div><!-- /.box -->

@endsection

@push('bottom')
<script type="text/javascript">
$(document).ready(function() {
    $("#btnSubmit").click(function() {
        $(this).prop("disabled", true);
        $("#form").submit();
    });
});
</script>
@endpush
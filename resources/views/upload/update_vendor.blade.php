@extends('crudbooster::admin_template')

@push('head')
<style type="text/css">
    .progress { 
        position:relative; 
        width:100%; 
    }
    .bar { 
        background-color: #008000; 
        width:0%; 
        height:20px; 
    }
    .percent { position:absolute; 
        display:inline-block; 
        left:50%; 
        color: #7F98B2; 
    }
</style>
@endpush

@section('content')

<div id='box_main' class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Upload a File</h3>
        <div class="box-tools"></div>
    </div>

    <form method='post' id="form" enctype="multipart/form-data" action="{{ route('update.vendor') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box-body">

            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                0%
                </div>
            </div>

            <div id="upload-message" class="alert" role="alert">
            </div>

            <div class='callout callout-success'>
                <h4>Welcome to Data Importer Tool</h4>
                Before uploading a file, please read below instructions : <br/>
                * File format should be : CSV file format<br/>
                * Do not upload the file with blank row in between records.<br/>
                * Do not upload with duplicate Vendor.<br/>
                * Please limit your upload to "<b>2,000</b>" lines.<br/>
                
            </div>
            
            <!--<div class="row">-->
            <!--    <label class='col-sm-2 control-label'>Import Template File: </label>-->
            <!--    <div class='col-sm-7'>-->
            <!--        <a href='{{ CRUDBooster::mainpath() }}/price-template' class="btn btn-primary" role="button">Download Template</a>-->
            <!--    </div>-->
            <!--</div>-->

            <br/>
            <br/>

            <label for='import_file' class='col-sm-2 control-label'>File to Import: </label>
            <div class='col-sm-4'>
                <input type='file' name='import_file' id='file_name' class='form-control' required accept=".csv"/>
                <div class='help-block'>File type supported only : CSV</div>
            </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
            <div class='pull-right'>
                <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
                <input type='submit' class='btn btn-primary' name='submit' value='Upload'/>
            </div>
        </div><!-- /.box-footer-->
    </form>
</div><!-- /.box -->

@endsection

@push('bottom')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    <script>

        // $('#form').submit(function () {
        //     if($('#file_name').get(0).files.length === 0) {
        //         return false;
        //     }
        // });
        
        // $('#form').ajaxForm({
        //     beforeSend:function(){
        //         $('#upload-message').empty();
        //     },
        //    uploadProgress:function(event, position, total, percentComplete) {
        //         $('.progress-bar').text(percentComplete + '%');
        //         $('.progress-bar').css('width', percentComplete + '%');
                
        //         $(':submit').attr('disabled', 'disabled'); //disable on any form submit
        //    },
        //     success:function(data) {
        //         if(data.errors) {
        //             $('.progress-bar').text('0%');
        //             $('.progress-bar').css('width', '0%');
        //             $('#upload-message').addClass("alert-danger");
        //             $('#upload-message').html('<span style="color:white;"><b>'+data.errors+'</b></span>');
        //         }
        //         if(data.success)  {
        //             $('.progress-bar').text('Updated successfully!');
        //             $('.progress-bar').css('width', '100%');
        //             $('#upload-message').addClass("alert-success");
        //             $('#upload-message').html('<span style="color:white;"><b>'+data.success+'</b></span>');
        //         }
        //     }
        // });
        
    </script>
@endpush
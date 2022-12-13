@extends('crudbooster::admin_template')
@section('content')

@push('head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<style type="text/css">

.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}

fieldset.field-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !immportant;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.field-border {
    font-size: 1.2em !important;
    font-weight: bold !important;
    text-align: left !important;
    width: inherit;
    padding: 0 10px; 
    border-bottom: none;
    color: red;
}
.select2-container--default .select2-selection--single {border-radius: 0px !important}
.select2-container .select2-selection--single {height: 35px}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #3c8dbc !important;
    border-color: #367fa9 !important;
    color: #fff !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff !important;
}

.required {
    padding-left: 2px;
    color: red;
}

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
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
<section class="content">

    <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
            <form action="{{ route('uploadSKULegend') }}" method="POST" id="sku-legend" role="form" enctype="multipart/form-data">
            
                <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
                
                <fieldset class="field-border">
                <legend class="text-primary">&nbsp;Upload Excel</legend>
                <div class="row">
                    <div class='callout callout-success col-md-12'>
                        <h4>Welcome to Data Importer Tool</h4>
                        Before uploading a file, please read below instructions : <br/>
                        * File format should be : CSV file format<br/>
                        * Invalid template will not accept.<br/>
                        * Tasteless Code should be unique.<br/>
                        * Invalid Tasteless Code will not accept.<br/>
                        * Blank SKU Legend will not accept.<br/>
                        * Blank row will not accept.<br/>
                        * Invalid or Inactive Segmentation will not accept.<br/>
                        * Invalid or Inactive SKU Legend will not accept.<br/>
                        * Please limit your items to "<b style="color:yellow;">2k</b>" records per upload.<br/>
                    </div>
                </div>
                <div class="row">
                    <div class="text-center col-md-4">
                        <a href="{{ route('getSKULegendTemplate') }}"  style="width: 100%;" class="btn btn-primary" role="button">Download SKU Legend Template</a>
                    </div>

                    <div class="col-md-8">
                    
                        <input type='file' name='import_file1' id="import_file1" class='form-control' accept=".csv"/>
                        <div class='help-block'>* File type supported only : CSV</div>
                    </div>
                </div>

                </fieldset>

                <div id="upload-message" class="alert" role="alert">
                </div>

                <div class="row">

                    <div class="col-md-12">

                        <div class="box-footer">
                            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-upload" ></i> Upload SKU Legend</button>
                        </div>

                    </div>

                </div>

            </form>
            
        </div>
    
    </div>

        <div class="modal fade" id="loading-screen" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header alert-info" style="text-center">
                     <h4 class="modal-title">Please wait your file is uploading . . .</h4>
                </div>
                    <div class="modal-body">
                            <div>
                                     <img src="{{asset("public/img/loading-blue.gif")}}"  class="center">
                            </div>
                </div>
            </div>
        </div>
        
</section>

@endsection

@push('bottom')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script type="text/javascript">
   
   $("#btnSubmit").on('click',function() {
        if($("#import_file1").val() == "" || $("#import_file1").val() == null){
            alert("Please choose a file!");
             return false;  
        }else{
            $('#loading-screen').modal('show'); 
            return true; 
         }
        //$('#loading-screen').modal('show'); 
   });
   

</script>
@endpush
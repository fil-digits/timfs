
@extends('crudbooster::admin_template')
@section('content')

  <div class='panel panel-default'>
      <div class='panel-body'>

        @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {!! implode('', $errors->all('<div>:message</div>')) !!}

        </div>

        @endif

          <form method='post' id='form' enctype='multipart/form-data' action='{{$uploadRoute}}'>
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="upload_action" value="{{$uploadAction}}">
              <div class="box-body">
                  <div class='callout callout-success'>
                      <h4>Welcome to Data Importer Tool</h4>
                      Before uploading a file, please read below instructions : <br />
                      * File format should be : CSV file format<br />

                  </div>

                  <table class="table table-striped">
                    <thead>
                        <tr>
                          <th scope="col">Import Template File</th>
                          <th scope="col">File to Import</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><a href='{{ $uploadTemplate }}' class='btn btn-primary' role='button'>Download Template</a></td>
                          <td><input type='file' name='import_file' id='file_name' class='form-control' required accept='.csv' />
                            <div class='help-block'>File type supported only : CSV</div></td>
                        </tr>

                  </table>


                </div>
                  </div>
                  <div class='panel-footer'>
                      <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
                      <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-upload" ></i> Upload</button>
                  </div>
          </form>
      </div>

@endsection
@push('bottom')
<script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"
      integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.js" integrity="sha256-CT21YfDe01wscF4AKCPn7mDQEHR2OC49jQZkt5wtl0g=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#btnSubmit').bind('keypress keydown keyup', function(e){
      if(e.keyCode == 13) { e.preventDefault(); }
    });

    $("#btnSubmit").click(function(event) {
      event.preventDefault();

      if($("#form").valid()){
        $(this).prop("disabled", true);
        $("#form").submit();
      }
    });
  });
</script>
@endpush

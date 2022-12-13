$(document).ready(function() {
    $("#segment_column_code,#segment_column_description").keyup(function(){
            this.value = this.value.toLocaleUpperCase();
            $("#segment_column_name").val("segmentation_"+$("#segment_column_code").val());
            var str =  $("#segment_column_name").val();
            var res = str.toLowerCase();
            $("#segment_column_name").val(res);
    });
});
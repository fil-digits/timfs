$(document).ready(function() {
    $("#menu_segment_column_code,#menu_segment_column_description").keyup(function(){
            this.value = this.value.toLocaleUpperCase();
            $("#menu_segment_column_name").val("segmentation_"+$("#menu_segment_column_code").val());
            var str =  $("#menu_segment_column_name").val();
            var res = str.toLowerCase();
            $("#menu_segment_column_name").val(res);
    });
});
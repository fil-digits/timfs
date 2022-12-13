$(document).ready(function() {
    $("#vendor_type_code, #vendor_type_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
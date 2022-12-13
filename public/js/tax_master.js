$(document).ready(function() {
    $("#tax_code, #tax_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
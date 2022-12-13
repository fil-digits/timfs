$(document).ready(function() {
    $("#packaging_code, #packaging_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
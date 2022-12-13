$(document).ready(function() {
    $("#country_code, #country_name").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
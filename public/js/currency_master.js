$(document).ready(function() {
    $("#currency_code, #currency_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
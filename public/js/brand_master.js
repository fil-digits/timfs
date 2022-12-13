$(document).ready(function() {
    $("#brand_code, #brand_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
$(document).ready(function() {
    $("#color_code, #color_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
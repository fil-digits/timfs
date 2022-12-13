$(document).ready(function() {
    $("#city_name").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
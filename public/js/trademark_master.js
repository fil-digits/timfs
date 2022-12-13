$(document).ready(function() {
    $("#trademark").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
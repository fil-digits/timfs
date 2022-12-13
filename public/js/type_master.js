$(document).ready(function() {
    $("#type_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
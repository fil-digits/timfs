$(document).ready(function() {
    $("#state_name").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
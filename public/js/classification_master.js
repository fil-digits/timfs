$(document).ready(function() {
    $("#classification_name").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
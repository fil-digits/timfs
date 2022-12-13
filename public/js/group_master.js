$(document).ready(function() {
    $("#group_code, #group_description, #group_short_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
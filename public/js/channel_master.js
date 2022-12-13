$(document).ready(function() {
    $("#channel_code, #channel_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
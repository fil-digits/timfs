$(document).ready(function() {
    $("#category_code, #category_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
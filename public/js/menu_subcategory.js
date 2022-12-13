$(document).ready(function() {
    $("#subcategory_code, #subcategory_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
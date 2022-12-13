$(document).ready(function() {
    $("#inventory_type_code, #inventory_type_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
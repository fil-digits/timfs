$(document).ready(function() {
    $("#uom_code, #uom_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
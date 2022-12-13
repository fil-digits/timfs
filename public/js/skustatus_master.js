$(document).ready(function() {
    $("#sku_status_code, #sku_status_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
$(document).ready(function() {
    $("#concept_group_name").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
$( document ).ready(function() {
    // $('#first_name').attr('autocomplete','off');
    // $('#last_name').attr('autocomplete','off');

    $("#last_name, #first_name").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });

    $("#card_id").keypress(function (evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 45  && charCode > 32 && (charCode < 48 || charCode > 57)) return evt.preventDefault();

        return true;
    }).on('paste', function () {
        var $this = $(this);
        setTimeout(function () {
            $this.val($this.val().replace(/[^0-9]/g, ''));
        }, 5);
    });
});
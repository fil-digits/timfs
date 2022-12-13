$(document).ready(function() {
    
    //------added by cris 20200804----
        let x = $(location).attr('pathname').split('/');
        let edit_action = x.includes("edit");
        //automatic set to ACTIVE
        $('#card_status option').eq(1).prop('selected', true);
    
        if(edit_action)
        {
             $('#card_status option').eq(1).prop('selected', true);
        }

        $('#last_name,#first_name1,#middle_name1,#last_name1,#bill_from2,#bill_from3,#bill_from4,#bill_from5,#ship_from1,#ship_from2,#ship_from3,#ship_from4,#ship_from5,#job_title').keyup(function(){
            this.value = this.value.toLocaleUpperCase();
        });

        $('#last_name').on('change click keyup', function() {
            document.getElementById('company').value=document.getElementById('last_name').value;
            document.getElementById('bill_from1').value=document.getElementById('last_name').value;
        });
       
    //---------------------------------
    
    $("#last_name, #first_name, #address1_line1, #address1_line2, #contact_name, #salutation, #payment_memo").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });

    $("#tax_id_no, #phone_number1, #phone_number2, #phone_number3, #fax_number").keypress(function (evt) {
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
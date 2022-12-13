$(document).ready(function() {
    // hided by cris
    // $("#last_name, #first_name, #address1_line1, #address1_line2, #address2_line1, #address2_line2, #receipt_memo").keyup(function() {
    //     this.value = this.value.toLocaleUpperCase();
    // });
    
    //--------edited by cris 20200901---------------
    $("#customer,#company,#last_name, #first_name, #address1_line1, #address1_line2, #address2_line1, #address2_line2, #receipt_memo").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
    //---------------------------------------------
    
    //------added by cris 20200804----
     let x = $(location).attr('pathname').split('/');
     let edit_action = x.includes("edit");
     //automatic set to ACTIVE
     $('#card_status option').eq(1).prop('selected', true);
 
     if(edit_action)
     {
          $('#card_status option').eq(1).prop('selected', true);
          if($('#tax_item').val() !== "OUTPUT VAT")
          {
             $('#tax_item option').eq(2).prop('selected', true); 
          }
          
     }

     $('#company').on('change click keyup', function() {
        var customer = document.getElementById('customer').value;
        var company = document.getElementById('company').value;
        document.getElementById('bill_from1').value=customer + " "+company;
     });

     $('#credit_limit').focusout(function(){

        var val = parseInt($(this).val());
        $(this).val(val.toFixed(2));

    });
     //---------------------------------

    $("#tax_id_no, #post_code, #post_code2").keypress(function (evt) {
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
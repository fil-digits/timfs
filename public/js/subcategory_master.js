$(document).ready(function() {
    var count=0;
    let x = $(location).attr('pathname').split('/');
    let add_action = x.includes("add");

    $("#subcategory_code, #subcategory_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });

    $('#categories_id').on('change', function() {
        var id_category = this.value;

        $.ajax({
            type: 'GET',
            url: 'getCategoryCode/'+id_category,
            data: '',
            success: function(data) {
                $('#subcategory_code').val(data.category_code+'-');
                $('#subcategory_description').val(data.category_code+'-');

                $('#subcategory_code').focus();
            }
        });
    });

    $('#subcategory_code').on('keyup', function() {
        count = document.getElementById('subcategory_code').value.length;
        document.getElementById('id_subcategory_code').innerHTML = 'Character Count: '+ count;
        if(count >= 15) {
            document.getElementById('id_subcategory_code').style.color = 'red';
            $('#subcategory_code').css('border-color', 'red');
            $('#subcategory_code').focus();
        }
        else if(count == 0) {
            document.getElementById('id_subcategory_code').innerHTML = '';
            $('#subcategory_code').css('border-color', 'gray');
        }
        else {
            document.getElementById('id_subcategory_code').style.color = 'black';
            $('#subcategory_code').css('border-color', 'gray');
        }
            
    });

    $('#subcategory_description').on('keyup keydown', function(e) {
        count = document.getElementById('subcategory_description').value.length;
        document.getElementById('id_subcategory_description').innerHTML = 'Character Count: '+ count;
        if(count > 35) {
            if( e.keyCode != 8) {
                swal('Warning !','**Please limit the Subcategory Description to 35 characters.');
            }
            
            document.getElementById('id_subcategory_description').style.color = 'red';
            $('#subcategory_description').css('border-color', 'red');
            $('#subcategory_description').focus();
            
        }
        else if(count == 0) {
            document.getElementById('id_subcategory_description').innerHTML = '';
            $('#subcategory_description').css('border-color', 'gray');
        }   
        else {
            document.getElementById('id_subcategory_description').style.color = 'black';
            $('#subcategory_description').css('border-color', 'gray');
        }
            
    });

    $('form').submit(function(event) {

        if(add_action) {
            count = document.getElementById('subcategory_description').value.length;
            if (count <= 35) {
                return;
            }
            else {
                swal('Warning !','**Please limit the Subcategory Description to 35 characters.');
            }
            event.preventDefault();
        }
    });

});
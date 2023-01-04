$(document).ready(function() {
    
    var description_count=false;
    var count_myob_description=0;
    var count_full_description=0;
    let x = $(location).attr('pathname').split('/');
    let add_action = x.includes("add");
    let edit_action = x.includes("edit");

    $('#quantity_on_hand').val('0.00');

    $("#supplier_item_code, #myob_item_description, #full_item_description, #actual_color, #flavor, #size, #dimension").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });

    $('#myob_item_description').on('change click keyup', function(e) {

        count_myob_description = $('#myob_item_description').val().length;
        $('#id_myob_item_description').text('Character Count: '+ count_myob_description);

        if(count_myob_description > 30)
        {
            if(e.keyCode != 8) //check if not backspace
            {
                if(!description_count){
                    swal('Warning !','**Please limit the MYOB Item Description to 30 characters.');
                    description_count = true;
                }
            }

            $('#id_myob_item_description').css('color', 'red');
            $('#myob_item_description').css('border-color', 'red');
            $('#myob_item_description').focus();


            
        }
        else if(count_myob_description == 0)
        {
            $('#id_myob_item_description').text('');
            $('#myob_item_description').css('border-color', 'gray');
        }
        else
        {
            $('#id_myob_item_description').css('color', 'black');
            $('#myob_item_description').css('border-color', 'gray');
        }

    });

    $('#full_item_description').on('change click keyup', function() {

        count_full_description = $('#full_item_description').val().length;
        $('#id_full_item_description').text('Character Count: '+ count_full_description);
        $('#purchase_description').val($('#full_item_description').val());

    });
    
    if(edit_action)
    { 
        if($('#tax_codes_id').eq(1).prop('selected', true))
        {
            $('#tax_status').val("TAX");
        }else if($('#tax_codes_id').eq(2).prop('selected', true)){
            $('#tax_status').val("NON");
        }
    }
    
    $('#tax_codes_id').on('change click keyup', function() {
        if($('#tax_codes_id').val() == 1)
        {
            $('#tax_status').val("TAX");
        }else if($('#tax_codes_id').val() == 2)
        {
            $('#tax_status').val("NON");
        }else{
            $('#tax_status').val("VAT INC");
        }
        
        $('#purchase_price').val("");
        $('#ttp').val("");
        $('#ttp_percentage').val("");
        $('#landed_cost').val("");
        $('#price').val("");
    });
    //-------------------------------------------

    $('#brands_id').on('change', function() {
        var id_brand = this.value;
        if(add_action) {
            $.ajax({
                type: 'GET',
                url: 'getBrandData/'+id_brand,
                data: '',
                success: function(data) {
                    if(data.brand_code != 'GNR'){
                        $('#myob_item_description').val(data.brand_code+' ');
                        count_myob_description = $('#myob_item_description').val().length;
                        $('#id_myob_item_description').text('Character Count: '+ count_myob_description);

                        $('#full_item_description').val(data.brand_description+' ');
                        count_full_description = $('#full_item_description').val().length;
                        $('#id_full_item_description').text('Character Count: '+ count_full_description);
                    }else{
                        $('#myob_item_description').val('');
                        count_myob_description = $('#myob_item_description').val().length;
                        $('#id_myob_item_description').text('Character Count: '+ count_myob_description);

                        $('#full_item_description').val('');
                        count_full_description = $('#full_item_description').val().length;
                        $('#id_full_item_description').text('Character Count: '+ count_full_description);
                    }
                },
                error: function(errors) {
                    console.log('Error! '+errors.status+' '+errors.statusText);
                }
            });
        }else if(edit_action) {
            $.get('getBrandData/'+id_brand, function(data) {
                console.log('Success!');
            });
        }
    });
    
    //-----------Round off supplier cost to 5 decimal-------------
    var supplier_cost = parseFloat($('#purchase_price').val());
    if(supplier_cost != ''){
        const noZeroes = parseFloat(supplier_cost.toFixed(5)); 
        $('#purchase_price').val(noZeroes);
    }
    
    $('#purchase_price').on('blur', function() {
        var supplier_cost = parseFloat($('#purchase_price').val());
        const noZeroes = parseFloat(supplier_cost.toFixed(5));  
        $('#purchase_price').val(noZeroes);
    });
    //-------------------------------------------------------------

    $('#ttp').on('blur', function() {
        var salesprice = $('#ttp').val();
        $('#ttp').val(parseFloat(salesprice).toFixed(2));
    });

    $("#ttp").on('change keyup click', function() {
        var salesprice = $('#ttp').val();
        var lc = $('#landed_cost').val();
        var commi_margin = (salesprice - lc)/salesprice;

        if(add_action){
            if(lc != ''){
                $('#ttp_percentage').val(parseFloat(commi_margin).toFixed(2));
            }
        }else if(edit_action){
            if(lc != ''){
                $('#ttp_percentage').val(parseFloat(commi_margin).toFixed(2));
            }
        }
    });
    
    $("#landed_cost").on('change keyup click', function() {
        var salesprice = $('#ttp').val();
        var lc = $('#landed_cost').val();
        var commi_margin = (salesprice - lc)/salesprice;

        if(add_action){
            if(lc != ''){
                $('#ttp_percentage').val(parseFloat(commi_margin).toFixed(2));
            }
        }else if(edit_action){
            if(lc != ''){
                $('#ttp_percentage').val(parseFloat(commi_margin).toFixed(2));
            }
        }
    });
    
    //------------------------Commi Margin-----------------------
    var margin = $('#ttp_percentage').val();  
    if(margin != ''){
        var salesprice = $('#ttp').val();
        var lc = $('#landed_cost').val();
        var commi_margin = (salesprice - lc)/salesprice;
        $('#ttp_percentage').val(parseFloat(commi_margin).toFixed(2));
    }
    //------------------------------------------------------------

    $(document).on("wheel", "input[type=number]", function (e) {
        $(this).blur();
    });
    
    //------added by cris 20200707----------------
    $("#tax_codes_id,#purchase_price,#ttp,#ttp_percentage").on('change keyup click', function() {
        var pp = $('#purchase_price').val();
        var tax_code = $('#tax_codes_id').val();
        var ttp_percentage = $('#ttp_percentage').val();
        var ttp = $('#ttp').val();
        var price;
        
        if(tax_code == 1)
        {
            price = (parseFloat(pp/1.12));
            var markup = (parseFloat(ttp_percentage)/100) + 1;
            price = (parseFloat(price*markup).toFixed(5));
             $('#price').val(price);
        }else{
            $('#price').val(ttp);
        }
    });
    //----------------------------------------------

    $('form').submit(function(event) {
        if(add_action) {
            count_myob_description = $('#myob_item_description').val().length;
            if (count_myob_description <= 30) {
                return true;
            }
            else {
                $('#myob_item_description').focus();
                swal('Warning !','**Please limit the MYOB Item Description to 30 characters.');
            }
            event.preventDefault();
        }
        else {
            return true;
        }
    });
});
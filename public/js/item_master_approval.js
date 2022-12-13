{/* <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> */}

$(document).ready(function() {
    var description_count=false;
    var count_myob_description=0;
    var count_full_description=0;
    let x = $(location).attr('pathname').split('/');
    let add_action = x.includes("add");
    let edit_action = x.includes("edit");

    //edit approval validation condition
    var tasteless_code = $('#tasteless_code').val();

    if(tasteless_code != null){
        var object_items = JSON.parse(items);
    }
    
    if(object_items != null){
        if(tasteless_code != null && edit_action && items != ""){
    
            switch(object_items.tasteless_code){
                case 0:
                    $('#form-group-tasteless_code').hide();
                break;

                default:
                    $('#form-group-tasteless_code').show();
                break;
            }

            switch(object_items.supplier){
                case 0:
                    $('#form-group-suppliers_id').hide();
                break;

                default:
                    $('#form-group-suppliers_id').show();
                break;
            }

            switch(object_items.trademark){
                case 0:
                    $('#form-group-trademarks_id').hide();
                break;

                default:
                    $('#form-group-trademarks_id').show();
                break;
            }

            switch(object_items.classification){
                case 0:
                    $('#form-group-classifications_id').hide();
                break;

                default:
                    $('#form-group-classifications_id').show();
                break;
            }

            switch(object_items.supplier_item_code){
                case 0:
                    $('#form-group-supplier_item_code').hide();
                break;

                default:
                    $('#form-group-supplier_item_code').show();
                break;
            }

            switch(object_items.myob_item_description){
                case 0:
                    $('#form-group-myob_item_description').hide();
                break;

                default:
                    $('#form-group-myob_item_description').show();
                break;
            }

            switch(object_items.full_item_description){
                case 0:
                    $('#form-group-full_item_description').hide();
                break;

                default:
                    $('#form-group-full_item_description').show();
                break;
            }

            switch(object_items.brand_code){
                case 0:
                    $('#form-group-brand_code').hide();
                break;

                default:
                    $('#form-group-brand_code').show();
                break;
            }

            switch(object_items.brand_description){
                case 0:
                    $('#form-group-brands_id').hide();
                break;

                default:
                    $('#form-group-brands_id').show();
                break;
            }

            switch(object_items.group){
                case 0:
                    $('#form-group-groups_id').hide();
                break;

                default:
                    $('#form-group-groups_id').show();
                break;
            }

            switch(object_items.category_code){
                case 0:
                    $('#form-group-category_code').hide();
                break;

                default:
                    $('#form-group-category_code').show();
                break;
            }

            switch(object_items.category_description){
                case 0:
                    $('#form-group-categories_id').hide();
                break;

                default:
                    $('#form-group-categories_id').show();
                break;
            }

            switch(object_items.subcategory){
                case 0:
                    $('#form-group-subcategories_id').hide();
                break;

                default:
                    $('#form-group-subcategories_id').show();
                break;
            }

            switch(object_items.type){
                case 0:
                    $('#form-group-types_id').hide();
                break;

                default:
                    $('#form-group-types_id').show();
                break;
            }

            switch(object_items.color_code){
                case 0:
                    $('#form-group-color_code').hide();
                break;

                default:
                    $('#form-group-color_code').show();
                break;
            }

            switch(object_items.color_description){
                case 0:
                    $('#form-group-colors_id').hide();
                break;

                default:
                    $('#form-group-colors_id').show();
                break;
            }

            switch(object_items.actual_color){
                case 0:
                    $('#form-group-actual_color').hide();
                break;

                default:
                    $('#form-group-actual_color').show();
                break;
            }

            switch(object_items.packaging_qty){
                case 0:
                    $('#form-group-packaging_qty').hide();
                break;

                default:
                    $('#form-group-packaging_qty').show();
                break;
            }

            switch(object_items.packaging_size){
                case 0:
                    $('#form-group-packaging_size').hide();
                break;

                default:
                    $('#form-group-packaging_size').show();
                break;
            }

            switch(object_items.packaging_size){
                case 0:
                    $('#form-group-packaging_size').hide();
                break;

                default:
                    $('#form-group-packaging_size').show();
                break;
            }

            switch(object_items.uom){
                case 0:
                    $('#form-group-uoms_id').hide();
                break;

                default:
                    $('#form-group-uoms_id').show();
                break;
            }

            switch(object_items.packaging){
                case 0:
                    $('#form-group-packagings_id').hide();
                break;

                default:
                    $('#form-group-packagings_id').show();
                break;
            }

            switch(object_items.vendor_type){
                case 0:
                    $('#form-group-vendor_types_id').hide();
                break;

                default:
                    $('#form-group-vendor_types_id').show();
                break;
            }

            switch(object_items.inventory_type){
                case 0:
                    $('#form-group-inventory_types_id').hide();
                break;

                default:
                    $('#form-group-inventory_types_id').show();
                break;
            }

            switch(object_items.sku_status){
                case 0:
                    $('#form-group-sku_statuses_id').hide();
                break;

                default:
                    $('#form-group-sku_statuses_id').show();
                break;
            }

            switch(object_items.purchase_price){
                case 0:
                    $('#form-group-purchase_price').hide();
                break;

                default:
                    $('#form-group-purchase_price').show();
                break;
            }

            switch(object_items.currency){
                case 0:
                    $('#form-group-currencies_id').hide();
                break;

                default:
                    $('#form-group-currencies_id').show();
                break;
            }

            switch(object_items.price_status){
                case 0:
                    $('#form-group-price_status').hide();
                break;

                default:
                    $('#form-group-price_status').show();
                break;
            }

            switch(object_items.price_status){
                case 0:
                    $('#form-group-price_status').hide();
                break;

                default:
                    $('#form-group-price_status').show();
                break;
            }

            switch(object_items.tax_code){
                case 0:
                    $('#form-group-tax_codes_id').hide();
                break;

                default:
                    $('#form-group-tax_codes_id').show();
                break;
            }

            switch(object_items.ttp){
                case 0:
                    $('#form-group-ttp').hide();
                break;

                default:
                    $('#form-group-ttp').show();
                break;
            }

            switch(object_items.ttp_percentage){
                case 0:
                    $('#form-group-ttp_percentage').hide();
                break;

                default:
                    $('#form-group-ttp_percentage').show();
                break;
            }

            switch(object_items.landed_cost){
                case 0:
                    $('#form-group-landed_cost').hide();
                break;

                default:
                    $('#form-group-landed_cost').show();
                break;
            }

            switch(object_items.moq_supplier){
                case 0:
                    $('#form-group-moq_supplier').hide();
                break;

                default:
                    $('#form-group-moq_supplier').show();
                break;
            }

            switch(object_items.moq_store){
                case 0:
                    $('#form-group-moq_store').hide();
                break;

                default:
                    $('#form-group-moq_store').show();
                break;
            }

            switch(object_items.segmentation){
                case 0:
                    $('#form-group-segmentation').hide();
                break;

                default:
                    $('#form-group-segmentation').show();
                break;
            }

            switch(object_items.chart_accounts_id){
                case 0:
                    $('#form-group-chart_accounts_id').hide();
                break;

                default:
                    $('#form-group-chart_accounts_id').show();
                break;
            }
        }
    }

    $("#supplier_item_code, #myob_item_description, #full_item_description, #actual_color, #flavor, #size, #dimension").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });

    $('#myob_item_description').on('change click keyup', function(e) {

        count_myob_description = document.getElementById('myob_item_description').value.length;
        document.getElementById('id_myob_item_description').innerHTML = 'Character Count: '+ count_myob_description;

        if(count_myob_description > 30)
        {
            if(e.keyCode != 8) //check if not backspace
            {
                if(!description_count){
                    swal('Warning !','**Please limit the MYOB Item Description to 30 characters.');
                    description_count = true;
                }
            }

            document.getElementById('id_myob_item_description').style.color = 'red';
            $('#myob_item_description').css('border-color', 'red');
            $('#myob_item_description').focus();


            
        }
        else if(count_myob_description == 0)
        {
            document.getElementById('id_myob_item_description').innerHTML = '';
            $('#myob_item_description').css('border-color', 'gray');
        }
        else
        {
            document.getElementById('id_myob_item_description').style.color = 'black';
            $('#myob_item_description').css('border-color', 'gray');
        }

    });

    $('#full_item_description').on('change click keyup', function() {

        count_full_description = document.getElementById('full_item_description').value.length;
        document.getElementById('id_full_item_description').innerHTML = 'Character Count: '+ count_full_description;

    });

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
                        count_myob_description = document.getElementById('myob_item_description').value.length;
                        document.getElementById('id_myob_item_description').innerHTML = 'Character Count: '+ count_myob_description;

                        $('#full_item_description').val(data.brand_description+' ');
                        count_full_description = document.getElementById('full_item_description').value.length;
                        document.getElementById('id_full_item_description').innerHTML = 'Character Count: '+ count_full_description;
                    }
                    else{
                        $('#myob_item_description').val('');
                        count_myob_description = document.getElementById('myob_item_description').value.length;
                        document.getElementById('id_myob_item_description').innerHTML = 'Character Count: '+ count_myob_description;

                        $('#full_item_description').val('');
                        count_full_description = document.getElementById('full_item_description').value.length;
                        document.getElementById('id_full_item_description').innerHTML = 'Character Count: '+ count_full_description;
                    }

                    
                    
                },
                error: function(errors) {
                    console.log('Error! '+errors.status+' '+errors.statusText);
                }
            });
        } //end if

        else if(edit_action) {
            $.get('getBrandData/'+id_brand, function(data) {
                console.log('Success!');
            });
        } //end else
    });

    $('#ttp').on('change keyup click', function() {
        var salesprice = $('#ttp').val();
        var lc = parseFloat(document.getElementById('landed_cost').value);
        var commi_margin = (salesprice - lc)/salesprice;
	    $('#ttp_percentage').val(parseFloat(commi_margin).toFixed(2));
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

    $('#landed_cost').on('change keyup click', function() {
        var salesprice = $('#ttp').val();
        var lc = parseFloat(document.getElementById('landed_cost').value);
        var commi_margin = (salesprice - lc)/salesprice;   
	    $('#ttp_percentage').val(parseFloat(commi_margin).toFixed(2));
    });
    
    //------------------------Commi Margin-----------------------
    // var margin = $('#ttp_percentage').val();  
    // if(margin != ''){
    //     var salesprice = $('#ttp').val();
    //     var lc = parseFloat(document.getElementById("landed_cost").value);
    //     var commi_margin = (salesprice - lc)/salesprice;
    //     $('#ttp_percentage').val(parseFloat(commi_margin).toFixed(2));
    // }
    //------------------------------------------------------------

    $(document).on("wheel", "input[type=number]", function (e) {
        $(this).blur();
    });

    $('form').submit(function(event) {

        if(add_action) {
            count_myob_description = document.getElementById('myob_item_description').value.length;
            if (count_myob_description <= 30) {
                return true;
            }
            else {
                document.getElementById('myob_item_description').focus();
                swal('Warning !','**Please limit the MYOB Item Description to 30 characters.');
            }
            event.preventDefault();
        }
        else {
            return true;
        }
    });
});
@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<style type="text/css">
    .ingredient-entry {
        margin-bottom: 10px;
        padding: 15px;
        border: 1px solid #ddd;
        position: relative;
    }

    .ingredient-entry > * {
        display:inline-block;
    }

    .ingredient-inputs > * {
        margin-right: 10px;
    }

    .required-star {
        color: red;
        font-size: 15px;
    }

    #add-row {
        margin-bottom: 10px;
    }

    .display-ingredient {
        min-width: 27vw;
    }

    .uom {
        width: 199.6px;
    }

    .swal2-popup, .swal2-modal, .swal2-icon-warning .swal2-show {
        font-size: 1.6rem !important;
    }

    .ingredient {
        display: none;
    }

    .item-list {
        position: absolute;
    }

    label {
        margin-bottom: 10px;
    }

    .menu-item-label {
        display: block;
    }

    .list-item a {
        color: #555 !important;
    }

    .list-item a:hover {
        background: #1E90FF !important;
        color: #eee !important;
    }

    .recipe-text {
        font-weight: 700;
        letter-spacing: 5px;
        text-align: center;
        margin: 20px 0;
        color: #367fa9;
    }

    .no-ingredient-warning {
        color: grey;
        font-style: italic;
        text-align: center;
        margin-bottom: 20px;
    }

    .section-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .label-total {
        display: inline-table;
        position: relative;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }

    input[type=number] {
        -moz-appearance:textfield; /* Firefox */
    }
    
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')

{{-- 
    A COPY OF INGREDIENT ENTRY!!! FOR CLONING!!
    THIS IS HIDDEN FROM THE DOM!!! --> {display: none}
--}}

<div class="ingredient-entry" style="display: none;">
    <div class="ingredient-inputs">
        <label>
            <span class="required-star">*</span> Ingredient
            <div>
                <input value="" type="text" name="ingredient[]" class="ingredient form-control" required/>
                <input value="" type="text" class="form-control display-ingredient span-2" placeholder="Search Item" required/>
                <div class="item-list">
                </div>
            </div>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Quantity
            <input value="" name="quantity[]" class="form-control quantity" type="number" min="0" step="any" readonly required/>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient UOM
            <div>
                <input type="text" class="form-control uom" name="uom[]" value="" style="display: none;"/>
                <input type="text" class="form-control display-uom" value="" readonly>
            </div>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Cost
            <input value="" name="cost[]" class="form-control cost" type="text" readonly required>
        </label>
    </div>
    <div class="actions">
        <button class="btn btn-info move-up" type="button"> <i class="fa fa-arrow-up" ></i></button>
        <button class="btn btn-info move-down" type="button"> <i class="fa fa-arrow-down" ></i></button>
        <button class="btn btn-danger delete" type="button"> <i class="fa fa-trash" ></i></button>
    </div>
</div>
{{-- 
    END OF COPY
 --}}
 <a title="Return" href="{{ CRUDBooster::mainpath() }}">
    <i class="fa fa-chevron-circle-left "></i>
    Back To List Data Menu Item Masterfile
</a>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-pencil"></i><strong> Edit Menu Item</strong>
    </div>
    <div class="panel-body">
        <form class='form-horizontal' action="{{ route('edit_menu_item') }}" id="form" method="POST" autocomplete="off">
            @csrf
            <input type="text" name="menu_items_id" value="{{$item->id}}" style="display: none;">
            <label class="menu-item-label">
                Menu Item Code
                <input class="form-control" type="text" value="{{$item->tasteless_menu_code}}" disabled>
            </label>
            <label class="menu-item-label">
                Menu Item Description
                <input class="form-control" type="text" value="{{$item->menu_item_description}}" disabled>
            </label>
            <label class="menu-item-label">
                Menu Item SRP
                <input class="form-control menu-item-srp" type="text" value="₱ {{$item->menu_price_dine}}" disabled>
            </label>
            <h4 class="recipe-text""><i class="fa fa-spoon"></i> RECIPE <i class="fa fa-spoon"></i></h4>
            <h5 class="no-ingredient-warning" style="display: none;">No ingredients currently saved.</h5>
            <section class="ingredient-section">
                {{-- IF THE MENU ITEM DOES HAVE SOME SAVED INGREDIENTS //LOOP// --}}
                @foreach($current_ingredients as $current_ingredient)
                    <div class="ingredient-entry">
                        <div class="ingredient-inputs">
                            <label>
                                <span class="required-star">*</span> Ingredient
                                <div>
                                    <input value="{{$current_ingredient->item_masters_id}}" ttp="{{$current_ingredient->ttp}}" type="text" name="ingredient[]" class="ingredient form-control" required/>
                                    <input value="{{$current_ingredient->full_item_description}}" type="text" class="form-control display-ingredient span-2" placeholder="Search Item" required/>
                                    <div class="item-list">
                                    </div>
                                </div>
                            </label>
                            <label>
                                <span class="required-star">*</span> Ingredient Quantity
                                <input value="{{$current_ingredient->qty}}" name="quantity[]" class="form-control quantity" type="number" min="0" step="any" required />
                            </label>
                            <label>
                                <span class="required-star">*</span> Ingredient UOM
                                <div>
                                    <input type="text" class="form-control uom" value="{{$current_ingredient->id}}" name="uom[]" style="display: none;"/>
                                    <input type="text" class="form-control display_uom" value="{{$current_ingredient->uom_description}}" readonly>
                                </div>
                            </label>
                            <label>
                                <span class="required-star">*</span> Ingredient Cost
                                <input value="{{$current_ingredient->cost}}" name="cost[]" class="form-control cost" type="text" readonly required />
                            </label>
                        </div>
                        <div class="actions">
                            <button class="btn btn-info move-up" type="button"> <i class="fa fa-arrow-up" ></i></button>
                            <button class="btn btn-info move-down" type="button"> <i class="fa fa-arrow-down" ></i></button>
                            <button class="btn btn-danger delete" type="button"> <i class="fa fa-trash" ></i></button>
                        </div>
                    </div>
                @endforeach
            </section>
            <section class="section-footer">
                <button class="btn btn-success" id="add-row" name="button" type="button" value="add_ingredient"> <i class="fa fa-plus" ></i> Add ingredient</button>
                <label class="label-total">
                    Total Ingredients Cost (<span class="percentage"></span>)
                    <input class="form-control total-cost" type="text" readonly>
                </label>
            </section>
            <div class="panel-footer">
                <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
                <button class="btn btn-primary pull-right" type="button" id="save-edit"> <i class="fa fa-save" ></i> Save</button>
            </div>
        </form>
    </div>
</div>
  

@endsection
@push('bottom')

<script>

    $(document).ready(function() {

        $.fn.reload = function() {
            if($('.ingredient-entry').length == 1) {
                $('.no-ingredient-warning').css('display', '')
            }

            $('.display-ingredient').keyup(function() {
                const entry = $(this).parents('.ingredient-entry');
                const query = ($(this).val());
                const current_ingredients = $(".ingredient").serializeArray();
                const arrayOfIngredients = [];
                const index = $('.display-ingredient').index(this);
                current_ingredients.forEach((item, item_index) => {
                    // TO STILL SHOW THE CURRENT INGREDIENT OF THE SELECTED INPUT
                    // BUT HIDE THE INGREDIENTS OF OTHER INPUTS
                    if (item_index != index) arrayOfIngredients.push(item.value);
                });

                if (query == '') {
                    $('.item-list').html = '';
                }
                const _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('type_search') }}",
                    method:"POST",
                    data: {
                        query: query,
                        _token: _token,
                        entry_id: index,
                        current_ingredients: arrayOfIngredients.join(','),
                    },
                    success:function(response) { 
                        $('.item-list').html('');
                        entry.find('.item-list').fadeIn(); 
                        entry.find('.item-list').html(response);                        
                    }
                });
            });

            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                event.preventDefault();
                return false;
                }
            });

            $('#form input, #form select').keyup(function() {
                $('#form input:valid, #form select:valid').css('outline', 'none');
            });

            $('.quantity').keyup(function() {
                const entry = $(this).parents('.ingredient-entry');
                const ingredientCost = entry.find('.ingredient').attr('ttp');
                entry.find('.cost').val($(this).val() * ingredientCost);
                $.fn.sumCost();
            });
        }

        $.fn.sumCost = function() {
            let sum = 0;
            const menuItemSRP = Number($('.menu-item-srp').val().split(' ')[1]);
            $('.cost').each(function() {
                sum += Number($(this).val().replace(/[^0-9.]/g, ''));
            });
            $('.total-cost').val(sum);
            const percentage = (Math.round(sum / menuItemSRP * 10000)) / 100;
            const percentageText = $('.percentage');
            $(percentageText).text(`${percentage}% of SRP`);
            if (percentage > 30) {
                $(percentageText).css('color', 'red');
                $('.total-cost').css({'color': 'red', 'outline': '2px solid red',});
            } else {
                $(percentageText).css('color', '');
                $('.total-cost').css({'color': '', 'outline': '',});    
            }
            $.fn.formatNumbers();
        }

        $.fn.formatNumbers = function() {
            const costs = jQuery.makeArray($('.cost, .total-cost'));
            costs.forEach(cost => {
                const val = Number($(cost).val().replace(/[^0-9.]/g, '')).toLocaleString();
                $(cost).val(`₱ ${val}`);
            })
        }

        $(document).on('click', '#save-edit', function(event) {
            const formValues = $('#form input, #form select');
            const isValid = jQuery.makeArray(formValues).every(e => !!$(e).val());
            if (isValid) {
                Swal.fire({
                    title: 'Do you want to save the changes?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Save'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).prop("disabled", true);
                        $('form').submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please enter appropriate data!',
                }).then(() => {
                    $('#form input:invalid, #form select:invalid').css('outline', '2px solid red');
                    $('#form .ingredient:invalid').parents('.ingredient-entry').find('.display-ingredient').css('outline', '2px solid red');
                });
            }
        }); 

        $(document).on('click', '.list-item', function(event) { 
            const entry = $(this).parents('.ingredient-entry');
            const ingredient = entry.find('.ingredient');
            ingredient.val($(this).attr('item_id'));
            ingredient.attr('ttp', $(this).attr('ttp'));
            ingredient.attr('uom', $(this).attr('uom'));
            entry.find('.display-ingredient').val($(this).text());
            entry.find('.uom').val($(this).attr('uom'));
            entry.find('.display-uom').val($(this).attr('uom_desc'));
            entry.find('.cost').val($(this).attr('ttp'));
            entry.find('.quantity').val('1');
            entry.find('.quantity').attr('readonly', false);
            $('#form input:valid, #form select:valid').css('outline', 'none');
            $('.item-list').html('');  
            $('.item-list').fadeOut();
            $.fn.sumCost();
        });

        $(document).on('click', '.move-up', function() {
            const entry = $(this).parents('.ingredient-entry');
            const sibling = entry.prev()[0];
            if (!sibling) return;
            $(sibling).animate(
                {
                    top: `+=${$(sibling).outerHeight()}`,
                },
                {
                    duration: 200,
                    queue: false,
                    done: function() {
                        $(sibling).css('top', '0');
                    }
                }
            );

            entry.animate(
                {
                    top: `-=${entry.outerHeight()}`
                },
                {
                    duration: 200,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                        entry.insertBefore($(entry).prev());
                    }
                }
            );
        });

        $(document).on('click', '.move-down', function() {
            const entry = $(this).parents('.ingredient-entry');
            const sibling = entry.next()[0];
            if (!sibling) return;

            $(sibling).animate(
                {
                    top: `-=${$(sibling).outerHeight()}`,
                },
                {
                    duration: 200,
                    queue: false,
                    done: function() {
                        $(sibling).css('top', '0');
                    }
                }
            );

            entry.animate(
                {
                    top: `+=${entry.outerHeight()}`
                },
                {
                    duration: 200,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                        entry.insertAfter($(entry).next());
                    }
                }
            );
            
        });

        $(document).on('click', '.delete', function(event) {
            const entry = $(this).parents('.ingredient-entry');
            const itemMastersId = entry.find('.ingredient').val();
            const menuItemsId = {{$item->id}};
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    handleDelete(); 
                    Swal.fire(
                        'Deleted!',
                        'Ingredient Deleted!',
                        'success'
                    );
                }
            });

            function handleDelete() {
                const _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('delete_ingredient') }}",
                    method: 'POST',
                    data: {
                        _token: _token,
                        item_masters_id: itemMastersId,
                        menu_items_id: menuItemsId,
                    },
                    success: function(response) {
                        if (response || !itemMastersId) {
                            entry.remove();
                            if($('.ingredient-entry').length == 1) {
                                $('.no-ingredient-warning').css('display', '');
                            }
                            $('.item-list').html('');  
                            $('.item-list').fadeOut();
                            $.fn.sumCost();
                        }
                    }
                });
            }
        }); 
        
        $.fn.reload();
        $.fn.sumCost();
    });

</script>


<script>
    const addButton = document.querySelector('#add-row');
    addButton.onclick = function(event) {
        const section = $($('.ingredient-entry').eq(0).prop('outerHTML'));
        section.find('input').val('');
        section.find('.ingredient').val('');
        section.find('.display-ingredient').val('');
        section.find('.ingredient').val('');
        section.find('.quantity').val('');
        section.find('.uom').val('');
        section.find('.cost').val('');
        section.css('display', '');
        $('.ingredient-section').append(section);
        $('.item-list').fadeOut();
        $('.no-ingredient-warning').hide();
        $.fn.reload();
    }

</script>
@endpush

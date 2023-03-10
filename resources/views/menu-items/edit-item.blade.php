@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/aee358fec0.js" crossorigin="anonymous"></script>
<style type="text/css">
    .info-div {
        position: relative;
    }

    .version-btn {
        position: absolute;
        right: 0;
        top: 0;
    }

    .info-label {
        position: relative;
    }

    .brand-search {
        position: absolute;
        left: 20px;
    }

    .label-secondary {
        background: #7e57c2;
    }

    .dropdown-menu {
        overflow-y: scroll;
        max-height: 255px;
        max-width: 700px;
    }

    .loading-label {
        text-align: center;
        font-style: italic;
        color: grey;
    }

    .ingredient-section {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ingredient-wrapper, .new-ingredient-wrapper {
        position: relative;
        margin-bottom: 10px;
        border: 2px solid grey;
        border-radius: 5px;
        padding: 20px;
    }

    .ingredient-entry {
        padding: 15px;
        position: relative;
    }

    .ingredient-entry > *, .substitute > *, .new-substitute {
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

    .display-ingredient, .ingredient_name {
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

    input, button {
        font-weight: normal;
        margin-top: 3px;
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
    
    .substitute, .new-substitute {
        border: 1px dashed grey;
        border-radius: 5px;
        display: flex;
        align-items: center;
        padding: 10px;
        margin-left: 50px;
        margin-bottom: 10px;
    }
    .substitute .actions > * {
        margin: 1px;
    }

    .add-sub-btn {
        background-color: #367fa9;
        font-size: 14;
        height: 30px;
        width: 30px;
        text-align: center;
        border-radius: 50%;
        color: white;
        position: absolute;
        bottom: -15px;
        left: 10px;
        cursor: pointer;
        rotate: -90deg;
        transition: 200ms;
        display: grid;
        place-items: center;
    }

    .new-add-sub-btn {
        background-color: #008d4c;
        font-size: 14;
        height: 30px;
        width: 30px;
        text-align: center;
        border-radius: 50%;
        color: white;
        position: absolute;
        bottom: -15px;
        left: 50px;
        cursor: pointer;
        rotate: -90deg;
        transition: 200ms;
        display: grid;
        place-items: center;
    }

    .add-sub-btn:hover,  .new-add-sub-btn:hover {
        transform: scale(1.2);
        rotate: 90deg;
        transition: 200ms;
    }

    .label-total {
        font-size: 18px !important;
    }

    .swal2-html-container {
        line-height: 5rem;
    }

</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')

{{-- 
    A COPY OF INGREDIENT ENTRY!!! FOR CLONING!!
    THIS IS HIDDEN FROM THE DOM!!! --> {display: none}
--}}
<div class="ingredient-wrapper" style="display: none;">
    <div class="ingredient-entry" isExisting="true">
        <div class="ingredient-inputs">
            <label>
                <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
                <div>
                    <input value="" type="text" name="ingredient[]" class="ingredient form-control" required/>
                    <input value="" type="text" class="form-control display-ingredient span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
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
            <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
            <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
            <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
        </div>
    </div>
    <div class="add-sub-btn" title="Add Existing Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
    <div class="new-add-sub-btn" title="Add New Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
</div>

 <div class="new-ingredient-wrapper" style="display: none;">
    <div class="ingredient-entry" isExisting="false">
        <div class="ingredient-inputs">
            <label>
                <span class="required-star">*</span> Ingredient <span class="item-from label label-secondary">USER</span>
                <div>
                    <input value="" type="text" name="ingredient_name[]" class="ingredient_name form-control" required/>
                    <div class="item-list">
                    </div>
                </div>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient Quantity
                <input value="" name="quantity[]" class="form-control quantity" type="number" min="0" step="any" required/>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient UOM
                <div>
                    <input type="text" class="form-control uom_name" name="uom_name[]" value="" required>
                </div>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient Cost
                <input value="" name="cost[]" class="form-control cost" type="text" required>
            </label>
        </div>
        <div class="actions">
            <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
            <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
            <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
        </div>
    </div>
    <div class="add-sub-btn" title="Add Existing Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
    <div class="new-add-sub-btn" title="Add New Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
</div>

 <div class="substitute" style="display: none;" isExisting="true">
    <div class="ingredient-inputs">
        <label>
            <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
            <div>
                <input value="" type="text" name="ingredient[]" class="ingredient form-control" required/>
                <input value="" type="text" class="form-control display-ingredient span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
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
        <button class="btn btn-info set-primary" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
        <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
    </div>
 </div> 

 <div class="new-substitute" style="display: none;" isExisting="false">
    <div class="ingredient-inputs">
        <label>
            <span class="required-star">*</span> Ingredient <span class="item-from label label-secondary">USER</span>
            <div>
                <input value="" type="text" name="ingredient_name[]" class="ingredient_name form-control" required/>
                <div class="item-list">
                </div>
            </div>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Quantity
            <input value="" name="quantity[]" class="form-control quantity" type="number" min="0" step="any" required/>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient UOM
            <div>
                <input type="text" class="form-control uom_name" name="uom_name[]" value="" required>
            </div>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Cost
            <input value="" name="cost[]" class="form-control cost" type="text" required>
        </label>
    </div>
    <div class="actions">
        <button class="btn btn-info set-primary" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
        <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
    </div>
 </div> 

{{-- 
    END OF COPY
 --}}

 {{-- DOM STARTS HERE !!!! --}}

 <a title="Return" href="{{ CRUDBooster::mainpath() }}">
    <i class="fa fa-chevron-circle-left "></i>
    Back To List Data Menu Item Masterfile
</a>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-pencil"></i><strong> Edit Menu Item</strong>
    </div>
    <div class="panel-body">
        <form class='form-horizontal' id="form" method="POST" autocomplete="off">
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
            <div class="info-div">
                <h4 class="recipe-text""><i class="fa fa-cheese"></i> RECIPE <i class="fa fa-utensils"></i></h4>
                <h5 class="no-ingredient-warning" style="display: none;"><i class="fa fa-spoon"></i> No ingredients currently saved.</h5>
                <p class="loading-label">Loading...</p>
                <button class="btn btn-primary version-btn" type="button">
                    See Versions
                </button>
            </div>
            <section class="ingredient-section">
            </section>
            <section class="section-footer">
                <div class="add-buttons">
                    <button class="btn btn-primary" id="add-existing" name="button" type="button" value="add_ingredient"> <i class="fa fa-plus" ></i> Add existing ingredient</button>
                    <button class="btn btn-success" id="add-new" name="button" type="button" value="add_ingredient"> <i class="fa fa-plus" ></i> Add new ingredient</button>
                </div>
                <label class="label-total">
                    Food Cost (<span class="percentage"></span>)
                    <input class="form-control total-cost" name="total_cost" type="text" readonly>
                </label>
            </section>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
        <button class="btn btn-primary pull-right" type="button" id="save-edit"> <i class="fa fa-save" ></i> Save</button>
    </div>
</div>
  

@endsection
@push('bottom')

<script>
        const ingredientVersions = {!! json_encode($versions) !!};
        const currentIngredients = {!! json_encode($current_ingredients) !!};
        let savedIngredients = currentIngredients;
        const menuItem = {!! json_encode($item) !!};
        const privilege = {!! json_encode($privilege) !!};
        const canBeEdited = (menuItem.accounting_approval_status == 'APPROVED' && menuItem.marketing_approval_status == 'APPROVED') ||
            [menuItem.accounting_approval_status, menuItem.marketing_approval_status].some(status => status == 'REJECTED') ||
            [menuItem.accounting_approval_status, menuItem.marketing_approval_status].every(status => !status) ||
            [menuItem.accounting_approval_status, menuItem.marketing_approval_status].every(status => status == 'PENDING');

    $(document).ready(function() {
        $('body').addClass('sidebar-collapse');

        const debounce = (func, wait, immediate)=> {
            let timeout;

            return function executedFunction() {
                const context = this;
                const args = arguments;    
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };

                const callNow = immediate && !timeout;
                
                clearTimeout(timeout);

                timeout = setTimeout(later, wait);
                
                if (callNow) func.apply(context, args);
            }
        }

        $.fn.firstLoad = function() {
            const entryCount = [...new Set([...savedIngredients.map(e => e.ingredient_group)])];
            const section = $('.ingredient-section');
            for (i of entryCount) {
                const groupedIngredients = savedIngredients.filter(e => e.ingredient_group == i);
                const wrapperTemplate = $(document.createElement('div'));
                wrapperTemplate.addClass('ingredient-wrapper');
                wrapperTemplate.append($('.add-sub-btn').eq(0).clone());
                wrapperTemplate.append($('.new-add-sub-btn').eq(0).clone());

                groupedIngredients.forEach(savedIngredient => {
                    if (savedIngredient.is_primary == 'TRUE') {
                        if (savedIngredient.is_existing == 'TRUE') {
                            //primary and existing
                            element = $('.ingredient-wrapper .ingredient-entry').eq(0).clone();
                            const ingredientInput = element.find('.ingredient');
                            ingredientInput.val(savedIngredient.item_masters_id || savedIngredient.menu_as_ingredient_id);
                            ingredientInput.attr({
                                cost: savedIngredient.ingredient_cost || savedIngredient.food_cost,
                                uom: savedIngredient.uom_id,
                                item_id: savedIngredient.item_masters_id,
                                menu_item_id: savedIngredient.menu_as_ingredient_id,
                            });
                            element.find('.display-ingredient').val(savedIngredient.full_item_description || savedIngredient.menu_item_description);
                            element.find('.quantity').val(savedIngredient.qty).attr('readonly', false);
                            element.find('.uom').val(savedIngredient.uom_id);
                            element.find('.display-uom').val(savedIngredient.packaging_description);
                            element.find('.cost').val(savedIngredient.cost);
                        } else {
                            //primary and new
                            element = $('.new-ingredient-wrapper .ingredient-entry').eq(0).clone();
                            element.find('.ingredient_name').val(savedIngredient.ingredient_name);
                            element.find('.quantity').val(savedIngredient.qty).attr('readonly', false);
                            element.find('.uom_name').val(savedIngredient.uom_name);
                            element.find('.cost').val(savedIngredient.cost);
                        }
                    } else {
                        if (savedIngredient.is_existing == 'TRUE') {
                            //substitute and existing
                            element = $('.substitute').eq(0).clone();
                            if (savedIngredient.is_selected == 'TRUE') element.attr('primary', true);
                            const ingredientInput = element.find('.ingredient');
                            ingredientInput.val(savedIngredient.item_masters_id || savedIngredient.menu_as_ingredient_id);
                            ingredientInput.attr({
                                cost: savedIngredient.ingredient_cost || savedIngredient.food_cost,
                                uom: savedIngredient.uom_id,
                                item_id: savedIngredient.item_masters_id,
                                menu_item_id: savedIngredient.menu_as_ingredient_id,
                            });
                            element.find('.display-ingredient').val(savedIngredient.full_item_description || savedIngredient.menu_item_description);
                            element.find('.quantity').val(savedIngredient.qty).attr('readonly', false);
                            element.find('.uom').val(savedIngredient.uom_id);
                            element.find('.display-uom').val(savedIngredient.packaging_description);
                            element.find('.cost').val(savedIngredient.cost);
                            element.css('display', '');
                        } else {
                            //substitute and new
                            element = $('.new-substitute').eq(0).clone();
                            if (savedIngredient.is_selected == 'TRUE') element.attr('primary', true);
                            element.find('.ingredient_name').val(savedIngredient.ingredient_name);
                            element.find('.quantity').val(savedIngredient.qty).attr('readonly', false);
                            element.find('.uom_name').val(savedIngredient.uom_name);
                            element.find('.cost').val(savedIngredient.cost);
                            element.css('display', '');
                        }
                    }
                    if (savedIngredient.menu_status == 'INACTIVE' || savedIngredient.item_status == 'INACTIVE') 
                        element.find('.label-danger').text('⚠️INACTIVE ITEM');

                    if (savedIngredient.item_masters_id && !savedIngredient.menu_as_ingredient_id)
                        element.find('.item-from').addClass('label label-info').text('IMFS');

                    if (savedIngredient.menu_as_ingredient_id && !savedIngredient.item_masters_id)
                        element.find('.item-from').addClass('label-warning').text('MIMF');

                    wrapperTemplate.append(element);
                });
                section.append(wrapperTemplate);
            }
        }

        $.fn.reload = function() {
            if($('.ingredient-wrapper').length == 1) {
                $('.no-ingredient-warning').css('display', '')
            }

            $('.display-ingredient').keyup(debounce(function() {
                const entry = $(this).parents('.ingredient-entry, .substitute');
                const query = $(this).val().trim().toLowerCase().split(' ').filter(e => !!e);
                const arrayOfIngredients = [];
                const index = $('.display-ingredient').index(this);
                const itemList = entry.find('.item-list');
                let searchResult  = [];

                if (!query.length) {
                    $('.item-list').html('');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: "{{route('search_ingredient')}}",
                    data: { content: JSON.stringify(query)},
                    success: function(response) {
                        searchResult = JSON.parse(response);
                        renderSearchResult();
                    },
                    error: function(response) { 
                        console.log(response); 
                    }  
                });

                function renderSearchResult() {
                    const current_ingredients = {item_id: [], menu_item_id: []};

                    $('#form .ingredient').each(function(ingredientIndex) {
                        const ingredient = $(this);
                        if (ingredientIndex != $('#form .ingredient').index(entry.find('.ingredient'))) {
                            if (ingredient.attr('item_id'))  current_ingredients.item_id.push(ingredient.attr('item_id'));
                            if (ingredient.attr('menu_item_id')) current_ingredients.menu_item_id.push(ingredient.attr('menu_item_id'));
                        }
                    });
    
                    const result = [...searchResult]
                        .filter(ingredient => !current_ingredients.item_id.includes(ingredient.item_masters_id?.toString()))
                        .sort((a, b) => (a.full_item_description || a.menu_item_description)
                        ?.localeCompare(b.full_item_description || b.menu_item_description));
    
                    if (!result.length) {
                        result.push({full_item_description: 'No Item Found'});
                    }
    
                    $('.item-list').html('');
                    
                   itemList.fadeIn('fast');
    
                    const ul = $(document.createElement('ul'));
                    ul.addClass('dropdown-menu');
                    ul.css({
                        display: 'block',
                        position: 'absolute',
                    });
                    result.forEach(e => {
                        const li = $(document.createElement('li'));
                        const a = $(document.createElement('a'));
                        if (!e.item_masters_id && !e.menu_item_id) {
                            a.css('color', 'red !important');
                        }
                        li.addClass('list-item dropdown-item');
                        li.attr({
                            item_id: e.item_masters_id,
                            cost: e.ingredient_cost,
                            uom: e.packagings_id || e.uoms_id,
                            uom_desc: e.packaging_description || e.uom_description,
                            menu_item_id: e.menu_item_id,
                            food_cost: e.food_cost,
                            item_desc: e.full_item_description || e.menu_item_description,
                        });
                        a.html(e.full_item_description && e.item_masters_id ? `<span class="label label-info">IMFS</span> ${e.full_item_description}`
                            : e.menu_item_description ? `<span class="label label-warning">MIMF</span> ${e.menu_item_description}` 
                            : 'No Item Found');
                        li.append(a);
                        ul.append(li);
                    });
                    itemList.append(ul);
                }
            }, 750));

            $(window).keydown(function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            $('#form input, #form select').keyup(function() {
                $('#form input:valid, #form select:valid').css('outline', 'none');
            });

            $('.quantity').keyup(function() {
                const entry = $(this).parents('.ingredient-entry, .substitute');
                const ingredientCost = entry.find('.ingredient').attr('cost') || entry.find('.ingredient').attr('food_cost');
                entry.find('.cost').val($(this).val() * ingredientCost);
                $.fn.sumCost();
            });

            $('.cost').keyup(function() {
                const entry = $(this).parents('.ingredient-entry, .substitute, .new-substitute');
                $.fn.sumCost();
            });
        }

        $.fn.sumCost = function() {
            const wrappers = jQuery.makeArray($('.ingredient-wrapper, .new-ingredient-wrapper'));
            let sum = 0;
            let setPercentage = Number(sessionStorage.setPercentage) || 30;
            wrappers.forEach(wrapper => {
                const primary = $(wrapper).find('.ingredient-entry');
                const substitute = jQuery.makeArray($(wrapper).find('.substitute, .new-substitute'));
                const markedSub = substitute.filter(e => $(e).attr('primary') == 'true');
                if (!!markedSub.length) {
                    sum += Number($(markedSub[0]).find('.cost').val().replace(/[^0-9.]/g, ''));
                } else {
                    sum += Number(primary.find('.cost').val().replace(/[^0-9.]/g, ''));
                }
                
            });
            const menuItemSRP = Number(menuItem.menu_price_dine.replace(/[^0-9.]/g, ''));
            $('.total-cost').val(sum);
            const percentage = menuItemSRP != 0 ? Number(((sum / menuItemSRP * 100).toFixed(2))) : 0;
            const percentageText = $('.percentage');
            $(percentageText).text(`${percentage}%`);
            if (percentage > 30) {
                $(percentageText).css('color', 'red');
                $('.total-cost').css({'color': 'red', 'outline': '2px solid red', 'font-weight': 'bold',});
            } else {
                $(percentageText).css('color', '');
                $('.total-cost').css({'color': '', 'outline': '', 'font-weight': 'normal'});    
            }
            $.fn.formatNumbers();
        }

        $.fn.formatNumbers = function() {
            const costs = jQuery.makeArray($('.cost, .total-cost'));
            costs.forEach(cost => {
                const ingredientEntry = $(cost).parents('.substitute, .new-substitute, .ingredient-entry');
                if (ingredientEntry.attr('isExisting') == 'false') {
                    $(cost).val(`₱ ${$(cost).val().replace(/[^0-9.]/g, '')}`);
                    return;
                }
                const val = Number($(cost).val().replace(/[^0-9.]/g, '')).toLocaleString(undefined, {maximumFractionDigits: 4});
                $(cost).val(`₱ ${val}`);
            })
        }

        $.fn.formatSelected = function() {
            const substitutes = jQuery.makeArray($('.substitute, .new-substitute'));
            substitutes.forEach(sub => {
                if ($(sub).attr('primary') == 'true') {
                    $(sub).css('background', '#F4D35E');
                    $(sub).find('.set-primary').css('color', 'black');
                } else {
                    $(sub).css('background', '');
                    $(sub).find('.set-primary').css('color', '');
                }
            });
        }

        $.fn.restrictionFormat = function() {
            if (!canBeEdited || privilege != 'Chef' && privilege != 'Super Administrator') {
                $('button').remove();
                $('.add-sub-btn').remove();
                $('.new-add-sub-btn').remove();
                $('input').attr('disabled', true);
            }
            
            if (privilege == 'Ingredient Approver (Accounting)' && 
                menuItem.accounting_approval_status == 'PENDING') {
                const approveButton = $(document.createElement('button'))
                    .addClass('btn btn-success pull-right approve-btn accounting')
                    .attr('action', 'APPROVED')
                    .append($(document.createElement('i')).addClass('fa fa-thumbs-up'))
                    .append(' Approve');
                
                const rejectButton = $(document.createElement('button'))
                    .addClass('btn btn-danger pull-right reject-btn accounting')
                    .attr('action', 'REJECTED')
                    .css('margin-right', '10px')
                    .append($(document.createElement('i')).addClass('fa fa-circle'))
                    .append(' Reject');
                $('.panel-footer').append(approveButton, rejectButton);
            }

            if (privilege == 'Ingredient Approver (Marketing)' && 
                menuItem.marketing_approval_status == 'PENDING') {
                const approveButton = $(document.createElement('button'))
                    .addClass('btn btn-success pull-right approve-btn marketing')
                    .attr('action', 'APPROVED')
                    .append($(document.createElement('i')).addClass('fa fa-thumbs-up'))
                    .append(' Approve');
                
                const rejectButton = $(document.createElement('button'))
                    .addClass('btn btn-danger pull-right reject-btn marketing')
                    .attr('action', 'REJECTED')
                    .css('margin-right', '10px')
                    .append($(document.createElement('i')).addClass('fa fa-circle'))
                    .append(' Reject');
                $('.panel-footer').append(approveButton, rejectButton);
            }

        }

        $(document).on('click', '#save-edit', function(event) {
            const formValues = $('#form input, #form select');
            const isValid = jQuery.makeArray(formValues).every(e => !!$(e).val()) &&
                jQuery.makeArray($('#form .cost')).every(e => !!$(e).val().replace(/[^0-9.]/g, ''));
            if (isValid) {
                Swal.fire({
                    title: 'Do you want to save the changes?',
                    html: 'Doing so will turn the status of this item to <span class="label label-warning">PENDING</span>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Save'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const ingredientsArray = [];
                        const ingredientGroups = jQuery.makeArray($('#form .ingredient-wrapper, #form .new-ingredient-wrapper'));
                        ingredientGroups.forEach((ingredientGroup, groupIndex) => {
                            const group = $(ingredientGroup);
                            const ingredientArray = [];
                            const ingredients = jQuery.makeArray(group.find('.ingredient-entry, .substitute, .new-substitute'));
                            ingredients.forEach((ingredient, memberIndex) => {
                                const ingredientMember = $(ingredient);
                                const ingredientObject = {};
                                ingredientObject.is_existing = (ingredientMember.attr('isExisting') == 'true').toString().toUpperCase();
                                ingredientObject.is_primary = (ingredientMember.hasClass('ingredient-entry')).toString().toUpperCase();
                                ingredientObject.is_selected = (ingredientMember.attr('primary') == 'true').toString().toUpperCase();
                                ingredientObject.row_id = memberIndex;
                                ingredientObject.ingredient_group = groupIndex;
                                ingredientObject.menu_items_id = menuItem.id;
                                ingredientObject.item_masters_id = ingredientMember.find('.ingredient').attr('item_id');
                                ingredientObject.menu_as_ingredient_id = ingredientMember.find('.ingredient').attr('menu_item_id');
                                ingredientObject.ingredient_name = ingredientMember.find('.ingredient_name').val()?.trim().toUpperCase();
                                ingredientObject.qty = ingredientMember.find('.quantity').val();
                                ingredientObject.uom_id = ingredientMember.find('.uom').val();
                                ingredientObject.uom_name = ingredientMember.find('.uom_name').val()?.trim().toUpperCase();
                                ingredientObject.cost = ingredientMember.find('.cost').val().replace(/[^0-9.]/g, '');
                                ingredientObject.total_cost = $('.total-cost').val().replace(/[^0-9.]/g, '');

                                ingredientArray.push(ingredientObject);
                            });
                            if (ingredientArray.length) {
                                ingredientsArray.push(ingredientArray);
                            }
                        });
                        const result = JSON.stringify(ingredientsArray);
                        const form = $(document.createElement('form'))
                            .attr('method', 'POST')
                            .attr('action', "{{ route('edit_menu_item') }}")
                            .css('display', 'none');

                        const csrf = $(document.createElement('input'))
                            .attr({
                                type: 'hidden',
                                name: '_token',
                            }).val("{{ csrf_token() }}");

                        const ingredientsData = $(document.createElement('input'))
                            .attr('name', 'ingredients')
                            .val(result);

                        const menuItemData = $(document.createElement('input'))
                            .attr('name', 'menu_items_id')
                            .val("{{ $item->id }}");
                        
                        const totalCostData = $(document.createElement('input'))
                            .attr('name', 'food_cost')
                            .val($('.total-cost').val().replace(/[^0-9.]/g, ''));

                        const percentageData = $(document.createElement('input'))
                            .attr('name', 'food_cost_percentage')
                            .val($('.percentage').text().replace(/[^0-9.]/g, ''));


                        form.append(csrf, ingredientsData, menuItemData, totalCostData, percentageData);
                        $('.panel-body').append(form);
                        form.submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill out all fields!',
                }).then(() => {
                    $('#form input:invalid, #form select:invalid').css('outline', '2px solid red');
                    $('#form .ingredient:invalid').parents('.ingredient-entry').find('.display-ingredient').css('outline', '2px solid red');
                });
            }
        }); 

        $(document).on('click', '.list-item', function(event) { 
            const entry = $(this).parents('.substitute, .ingredient-entry');
            const ingredient = entry.find('.ingredient');

            if ($(this).attr('item_id') && !$(this).attr('menu_item_id'))
                entry.find('.item-from')
                    .removeClass('label-info label-warning label-success label-secondary label-primary')
                    .addClass('label-info')
                    .text('IMFS');
            else
                entry.find('.item-from')
                    .removeClass('label-info label-warning label-success label-secondary label-primary')
                    .addClass('label-warning').text('MIMF')
            
            entry.find('.label-danger').text('');
            if (!$(this).attr('item_id') && !$(this).attr('menu_item_id')) return;
            ingredient.val($(this).attr('item_id') || $(this).attr('menu_item_id'));
            ingredient.attr({
                cost: $(this).attr('cost'),
                food_cost: $(this).attr('food_cost'),
                uom: $(this).attr('uom'),
                item_id: $(this).attr('item_id'),
                menu_item_id: $(this).attr('menu_item_id'),
            });
            entry.find('.display-ingredient').val($(this).attr('item_desc'));
            entry.find('.ingredient-menu').val($(this).attr('menu_item_id'));
            entry.find('.uom').val($(this).attr('uom'));
            entry.find('.display-uom').val($(this).attr('uom_desc'));
            entry.find('.cost').val($(this).attr('cost') || $(this).attr('food_cost'));
            entry.find('.quantity').val('1');
            entry.find('.quantity').attr('readonly', false);
            $('#form input:valid, #form select:valid').css('outline', 'none');
            $('.item-list').html('');  
            $('.item-list').fadeOut();
            $.fn.sumCost();
        });

        $(document).on('click', '.move-up', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            const sibling = entry.prev()[0];
            if (!sibling) return;
            $(sibling).animate(
                {
                    top: `+=${$(entry).outerHeight()}`,
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        $(sibling).css('top', '0');
                    }
                }
            );

            entry.animate(
                {
                    top: `-=${$(sibling).outerHeight()}`
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                        entry.insertBefore($(entry).prev());
                    }
                }
            );
        });

        $(document).on('click', '#add-existing', function() {
            const section = $($('.ingredient-wrapper').eq(0).clone());
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
            $('.no-ingredient-warning').remove();
            $.fn.reload();
        });

        $(document).on('click', '#add-new', function() {
            const section = $($('.new-ingredient-wrapper').eq(0).clone());
            section.find('input').val('').attr('readonly', false);
            section.find('.ingredient').val('');
            section.find('.display-ingredient').val('');
            section.find('.ingredient').val('');
            section.find('.quantity').val('');
            section.find('.uom').val('');
            section.find('.cost').val('');
            section.css('display', '');
            $('.ingredient-section').append(section);
            $('.item-list').fadeOut();
            $('.no-ingredient-warning').remove();
            $.fn.reload();
        });

        $(document).on('click', '.move-down', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            const sibling = entry.next()[0];
            if (!sibling) return;

            $(sibling).animate(
                {
                    top: `-=${$(entry).outerHeight()}`,
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        $(sibling).css('top', '0');
                    }
                }
            );

            entry.animate(
                {
                    top: `+=${$(sibling).outerHeight()}`
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                        entry.insertAfter($(entry).next());
                    }
                }
            );
            
        });

        $(document).on('click', '.delete', function(event) {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            entry.hide(300, function() {
                $(this).remove();
                $.fn.sumCost();
            });
        }); 

        $(document).on('click', '.add-sub-btn', function(event) {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            const substitute = $('.substitute').eq(0).clone();
            substitute.css('display', '');
            entry.append($(substitute));
            $.fn.reload();
        });

        $(document).on('click', '.new-add-sub-btn', function(event) {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            const substitute = $('.new-substitute').eq(0).clone();
            substitute.css('display', '');
            entry.append($(substitute));
            $.fn.reload();
        });

        $(document).on('click', '.set-primary', function(event) {
            const sub = $(this).parents('.substitute, .new-substitute');
            const ingredientWrapper = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            const isPrimary = sub.attr('primary') == 'true';
            ingredientWrapper.find('.substitute, .new-substitute').attr('primary', false);
            if (!isPrimary) {
                sub.attr('primary', true);
                sub.css('background', '#F4D35E');
            }
            $.fn.formatSelected();
            $.fn.sumCost();

        });

        $(document).on('click', '.delete-sub', function(event) {
            const subEntry = $(this).parents('.substitute, .new-substitute');
            subEntry.hide('fast', function() {
                $(this).remove();
                $.fn.sumCost();
            });
        });

        $(document).on('click', '.approve-btn, .reject-btn', function(event) {
            const button = $(this);
            const action = button.attr('action');
            Swal.fire({
                    title: `Do you want to ${action == 'APPROVED' ? 'approve' : 'reject'} this menu item?`,
                    html: `Doing so will turn the status of this item to <span class="label label-${action == 'APPROVED' ? 'success' : 'danger'}">${action}</span>`,
                    showCancelButton: true,
                    confirmButtonText: action == 'APPROVED' ? 'Approve' : 'Reject',
                }).then((result) => {
                if (result.isConfirmed) {
                    const hasOneApproval = [menuItem.accounting_approval_status, menuItem.marketing_approval_status].some(status => status == 'APPROVED');
                    const form = $(document.createElement('form'))
                        .attr('method', 'POST')
                        .attr('action', "{!! route('approve_or_reject') !!}")
                        .css('display', 'none');
                    const csrf = $(document.createElement('input'))
                        .attr({
                            type: 'hidden',
                            name: '_token',
                        })
                        .val("{{ csrf_token() }}");
                    const actionInput = $(document.createElement('input'))
                        .attr('name', 'action')
                        .val(action);
                    const idInput = $(document.createElement('input'))
                        .attr('name', 'menu_items_id')
                        .val(menuItem.id);
                    const approverInput = $(document.createElement('input'))
                        .attr('name', 'approver')
                        .val(privilege.toLowerCase().includes('accounting') ? 'accounting' : 'marketing');
                    const hasOneApprovalInput = $(document.createElement('input'))
                        .attr('name', 'has_one_approval')
                        .val(hasOneApproval);
                    const foodCost = $(document.createElement('input'))
                        .attr('name', 'food_cost')
                        .val($('.total-cost').val().replace(/[^0-9.]/g, ''));
                    const foodCostPercentage = $(document.createElement('input'))
                        .attr('name', 'food_cost_percentage')
                        .val($('.percentage').text().replace(/[^0-9.]/g, ''));
                    const versionId = $(document.createElement('input'))
                        .attr('name', 'version_id')
                        .val(new Date().toLocaleString());
                    
                    form.append(
                        csrf,
                        actionInput,
                        idInput, 
                        approverInput, 
                        hasOneApprovalInput, 
                        foodCost, 
                        foodCostPercentage,
                        versionId
                    );
                    $('.panel-body').append(form);
                    form.submit();
                    $('button').attr('disabled', true);
                }
                return
            });
        });

        $(document).on('click', '.version-btn', async function(event) {
            const versions = {};
            ingredientVersions.forEach(version => {
                versions[version.created_at] = version.created_at
            })
            const { value: version } = await Swal.fire({
                title: 'Select Versions',
                input: 'select',
                inputOptions: {
                    ...versions,
                },
                inputPlaceholder: 'Select ingredient version',
                showCancelButton: true,
            });

            if (version) {
                const selectedVersion = ingredientVersions.filter(e => e.created_at == version)[0];
                const versionIngredients = JSON.parse(selectedVersion.ingredients_json);
                savedIngredients = versionIngredients;
                const wrappers = $('#form .ingredient-wrapper, #form .new-ingredient-wrapper')
                wrappers.remove();
                $.fn.firstLoad();
                $.fn.sumCost();
                $('#form button').not('.version-btn').remove();
                $('#form .add-sub-btn').remove();
                $('#form .new-add-sub-btn').remove();
                $('input').attr('disabled', true);
            }
        });

        $('.loading-label').remove();
        $.fn.firstLoad();
        $.fn.reload();
        $.fn.formatSelected();
        $.fn.sumCost();
        $.fn.restrictionFormat();
    });

</script>
@endpush

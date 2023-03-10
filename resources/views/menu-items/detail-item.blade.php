@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/aee358fec0.js" crossorigin="anonymous"></script>
<style>
    th, td {
        text-align: center;
    }

    .total-cost-label, .percentage-label {
        text-align: right;
        font-weight: bold;
    }

    .total-cost {
        font-weight: bold
    }

    .note {
        color: blue;
        font-weight: bold;
    }

    .label-secondary {
        background: #7e57c2;
    }
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')

<a title="Return" href="{{ CRUDBooster::mainpath() }}">
    <i class="fa fa-chevron-circle-left "></i>
    Back To List Data Menu Item Masterfile
</a>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-eye"></i><strong> Detail Menu Item</strong>
    </div>
    <div class="panel-body">
        <h4 style="font-weight: 600; text-align: center;">Menu Information</h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">Menu Item Code</th>
                    <th scope="col">Menu Item Description</th>
                    <th scope="col">Menu Item SRP</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$item->tasteless_menu_code}}</td>
                    <td>{{$item->menu_item_description}}</td>
                    <td class="peso">{{$item->menu_price_dine}}</td>
                </tr>
            </tbody>
        </table>
            <h4 class="no-ingredient-warning" style="color: gray; text-align: center; font-style: italic; display: none"> <i class="fa fa-spoon"></i> No ingredients to show...</h4>
        <div class="with-ingredient" style="display: none;">
            <h4 style="font-weight: 600; text-align: center;">Ingredients List</h4>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"> </th>
                            <th scope="col">Status</th>
                            <th scope="col">From</th>
                            <th scope="col">Tasteless Code</th>
                            <th scope="col">Ingredient</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">UOM</th>
                            <th scope="col">Cost</th>
                        </tr>
                    </thead>
                    <tbody class="ingredient-tbody">
                    </tbody>
                </table>
            </div>
        </div>
        <p class="note">** Highlighted ingredient names are primary ingredients.</p>
    </div>
    <div class="panel-footer">
        <a class="btn btn-primary" href="{{ CRUDBooster::mainpath() }}" type="button" id="export"> <i class="fa fa-arrow-left" ></i> Back </a>
    </div>
</div>
@endsection

@push('bottom')
<script>
    $(document).ready(function() {
        const ingredients = {!! json_encode($ingredients) !!};
        const item = {!! json_encode($item) !!};
        const tbody = $('.ingredient-tbody');
        const groupCount = [...new Set([...ingredients.map(e => e.ingredient_group)])];
        for (i of groupCount) {
            const groupedIngredients = ingredients.filter(e => e.ingredient_group == i);
            const isSelected = groupedIngredients.find(e => e.is_selected == 'TRUE');
            let primary;
            if (isSelected) isSelected.checked = true;
            else groupedIngredients.find(e => e.is_primary == 'TRUE').checked = true;
            groupedIngredients.forEach(groupedIngredient => {
                const tr = $(document.createElement('tr'));
                const check = $(document.createElement('td'))
                    .text(groupedIngredient.checked ? '✓' : '')
                    .css('font-weight', '700');
                const status = $(document.createElement('td'));
                const from = $(document.createElement('td'))
                const tastelessCode = $(document.createElement('td'))
                    .text(
                        groupedIngredient.tasteless_code ||
                        groupedIngredient.tasteless_menu_code ||
                        'No Item Code'
                    ).css('font-style', !groupedIngredient.tastelessCode)
                const ingredient = $(document.createElement('td'));
                const ingredientSpan = $(document.createElement('span'))
                    .text(
                        groupedIngredient.full_item_description ||
                        groupedIngredient.menu_item_description ||
                        groupedIngredient.ingredient_name
                    ).css('background', groupedIngredient.checked ? 'yellow' : '');
                ingredient.html(ingredientSpan);
                const quantity = $(document.createElement('td'))
                    .text(groupedIngredient.qty);
                const uom = $(document.createElement('td'))
                    .text(groupedIngredient.uom_description || groupedIngredient.uom_name);
                const cost = $(document.createElement('td'));
                const costSpan = $(document.createElement('span'))
                    .text(groupedIngredient.cost)
                    .css('font-weight', groupedIngredient.checked ? 'bold' : '')
                    .addClass(groupedIngredient.checked ? 'peso cost' : 'peso');
                cost.html(costSpan);

                if (groupedIngredient.full_item_description || groupedIngredient.item_masters_id)
                    from.html('<span class="label label-info">IMFS</span>')
                else if (groupedIngredient.menu_item_description)
                    from.html('<span class="label label-warning">MIMF</span>')
                else
                    from.html('<span class="label label-secondary">USER</span>')

                if (groupedIngredient.menu_item_status == 'INACTIVE' || groupedIngredient.item_status == 'INACTIVE')
                    status.html('<span class="label label-danger">INACTIVE</span>')
                else if (groupedIngredient.menu_item_status == 'ACTIVE' || groupedIngredient.item_status == 'ACTIVE')
                    status.html('<span class="label label-success">ACTIVE</span>')
                else if (groupedIngredient.menu_item_status == 'ALTERNATIVE' || groupedIngredient.item_status == 'ALTERNATIVE')
                    status.html('<span class="label label-primary">ALTERNATIVE</span>')
                tr.append(check, status, from, tastelessCode, ingredient, quantity, uom, cost);
                $('.ingredient-tbody').append(tr);
            });
        }

        const totalCostTR = $(document.createElement('tr'));
        const totalCostLabelTD = $(document.createElement('td'));
        const totalCostValueTD = $(document.createElement('td'));
        totalCostLabelTD.attr('colspan', '7');
        totalCostLabelTD.addClass('total-cost-label');
        totalCostLabelTD.text('Food Cost');
        totalCostValueTD.addClass('total-cost peso');
        totalCostTR.append(totalCostLabelTD, totalCostValueTD);        

        $('.ingredient-tbody').append(totalCostTR);

        if (!ingredients.length) {
            $('.no-ingredient-warning').css('display', '');
        } else {
            $('.with-ingredient').css('display', '');
        }

        const costsElems = jQuery.makeArray($('.cost'))
        const totalCostElem = $('.total-cost');
        const totalCost = costsElems.reduce((total, cost) => total + Number($(cost).text().replace(/[^0-9.]/g, '')), 0);
        const percentage = (totalCost / item.menu_price_dine * 100).toFixed(2);
        $(totalCostElem).text(totalCost);
        percentageSpan = $(document.createElement('span')).text(`(${item.menu_price_dine == 0 ? '0' : percentage}%)`).addClass('percentage-text');
        percentageSpan.css('color', item.menu_price_dine != 0 && percentage > (Number(sessionStorage.setPercentage) || 30) ? 'red' : '');
        totalCostLabelTD.text(`Total Cost `).append(percentageSpan);
        function formatNumbers() {
            const elems = jQuery.makeArray($('.peso'));
            elems.forEach(elem => $(elem).text('₱ ' + Number($(elem).text()).toLocaleString(undefined, {maximumFractionDigits: 4})))
        }

        formatNumbers();
        $('table th, table td').css('border', '1px solid #aaaaaa');
        $('table thead').css('background', '#deeaee');
    });
    
</script>
@endpush
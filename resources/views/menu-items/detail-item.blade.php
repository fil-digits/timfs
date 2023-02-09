@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/aee358fec0.js" crossorigin="anonymous"></script>
<style>
    th, td {
        text-align: center;
    }

    .total-cost-label {
        text-align: right;
        font-weight: bold;
    }

    .total-cost {
        font-weight: bold
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
                            <th scoped="col">Tasteless Code</th>
                            <th scoped="col">Ingredient</th>
                            <th scoped="col">Quantity</th>
                            <th scoped="col">UOM</th>
                            <th scoped="col">Cost</th>
                        </tr>
                    </thead>
                    <tbody class="ingredient-tbody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('bottom')
<script>
     $(document).ready(function() {
        const ingredients = {!! json_encode($ingredients) !!};
        const tbody = $('.ingredient-tbody');
        const entryCount = [...new Set([...ingredients.map(e => e.ingredient_group)])];
        for (i of entryCount) {
            const groupedIngredients = ingredients.filter(e => e.ingredient_group == i);
            const isSelected = groupedIngredients.filter(e => e.is_selected == 'TRUE');
            let primary;
            if (isSelected.length) {
                primary = isSelected[0];
            } else {
                primary = groupedIngredients.filter(e => e.is_primary == 'TRUE')[0];
            }
            const tr = $(document.createElement('tr'));
            for (let i=0; i<5; i++) {
                const td = $(document.createElement('td'));
                if (i == 0) td.text(primary.tasteless_code);
                if (i == 1) td.text(primary.full_item_description);
                if (i == 2) td.text(primary.qty);
                if (i == 3) td.text(primary.uom_description);
                if (i == 4) {
                    td.text(primary.cost);
                    td.addClass('cost peso');
                }

                tr.append(td);
                console.log(td.html());
            }
            $('.ingredient-tbody').append(tr);
        }

        const totalCostTR = $(document.createElement('tr'));
        const totalCostLabelTD = $(document.createElement('td'));
        const totalCostValueTD = $(document.createElement('td'));
        totalCostLabelTD.attr('colspan', '4');
        totalCostLabelTD.addClass('total-cost-label');
        totalCostLabelTD.text('Total Ingredient Cost');
        totalCostValueTD.addClass('total-cost peso');
        totalCostTR.append(totalCostLabelTD);
        totalCostTR.append(totalCostValueTD);
        $('.ingredient-tbody').append(totalCostTR);

        if (!ingredients.length) {
            $('.no-ingredient-warning').css('display', '');
        } else {
            $('.with-ingredient').css('display', '');
        }

        console.log(ingredients);
        const costsElems = jQuery.makeArray($('.cost'))
        const totalCostElem = $('.total-cost');
        const totalCost = costsElems.reduce((total, cost) => total + Number($(cost).text().replace(/[^0-9.]/g, '')), 0);
        $(totalCostElem).text(totalCost);

        function formatNumbers() {
            const elems = jQuery.makeArray($('.peso'));
            elems.forEach(elem => $(elem).text('₱ ' + Number($(elem).text()).toLocaleString(undefined, {maximumFractionDigits: 4})))
        }

        formatNumbers();
    });
    
</script>
@endpush
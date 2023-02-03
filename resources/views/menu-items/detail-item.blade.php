@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
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

        @if(!count($ingredients))
            <h4 style="color: gray; text-align: center; font-style: italic;"> <i class="fa fa-spoon"></i> No ingredients to show...</h4>
        @else
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
        
                    <tbody>
                        @foreach ($ingredients as $ingredient)
                            <tr>
                                <td>{{$ingredient->tasteless_code}}</td>
                                <td>{{$ingredient->full_item_description}}</td>
                                <td>{{$ingredient->qty}}</td>
                                <td>{{$ingredient->uom_description}}</td>   
                                <td class="cost peso">{{$ingredient->cost}}</td>
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="4" class="total-cost-label">Total Ingredients Cost</td>
                                <td class="total-cost peso"></td>
                            </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('bottom')
<script>
     $(document).ready(function() {
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
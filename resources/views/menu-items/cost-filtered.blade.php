@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/aee358fec0.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<style>
    table, th, td {
        border: 1px solid rgb(215, 214, 214) !important;
        text-align: center;
    }

    .action {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
</style>
@endpush


@extends('crudbooster::admin_template')
@section('content')
<a title="Return" href="{{ CRUDBooster::mainpath() }}">
    <i class="fa fa-chevron-circle-left "></i>
    Back To List Food Cost
</a>

<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-dollar"></i><strong> Food Cost</strong>
    </div>

    <div class="panel-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="active">
                    <th scope="col">Menu Item Code</th>
                    <th scope="col">Menu Item Description</th>
                    <th scope="col">SRP</th>
                    <th scope="col">Food Cost</th>
                    <th scope="col">Percentage</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div class="panel-footer">
        <a class="btn btn-primary" href="{{ CRUDBooster::mainpath() }}" type="button" id="export"> <i class="fa fa-arrow-left" ></i> Back </a>
    </div>
</div>

@endsection

@push('bottom')
<script>
    $(document).ready(function() {
        let menuItems = {!! json_encode($filtered_items) !!};
        menuItems = menuItems.sort((a, b) => Number((a.food_cost / a.menu_price_dine * 100)) - Number((b.food_cost / b.menu_price_dine * 100)))
        const tbody = $('tbody');

        menuItems.forEach((item, index) => {
            const tr = $(document.createElement('tr'));
            const menuItemCode = $(document.createElement('td')).text(item.tasteless_menu_code);
            const menuItemDescription = $(document.createElement('td')).text(item.menu_item_description); 
            const srp = $(document.createElement('td')).text(`₱ ${item.menu_price_dine}`);
            const foodCost = $(document.createElement('td')).text(`₱ ${item.food_cost}`);
            const percentage = $(document.createElement('td')).text(`${(item.food_cost / item.menu_price_dine * 100).toFixed(2)}%`)
            const action = $(document.createElement('td')).addClass('action');
            const detail = $(document.createElement('a')).append($(document
                .createElement('i'))
                .addClass('fa fa-eye button'))
                .attr('href', "{{ CRUDBooster::adminPath('menu_items/detail') }}" + `/${item.id}`);
            const edit = $(document.createElement('a')).append($(document
                .createElement('i'))
                .addClass('fa fa-pencil button'))
                .attr('href', "{{ CRUDBooster::adminPath('menu_items/edit') }}" + `/${item.id}`);
            action.append(detail, edit);
            tr.append(menuItemCode, menuItemDescription, srp, foodCost, percentage, action);
            tbody.append(tr);
        });
    });


</script>
@endpush
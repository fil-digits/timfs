@push('head')
<script src="https://code.jquery.com/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

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

    .concept-name {
        text-align: center;
        letter-spacing: 3px;
        font-weight: 600;
    }

    .filter-name {
        text-align: center;
        font-size: 16px;
        text-transform: uppercase;
        font-style: italic;
        color: grey;
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
        <h3 class="concept-name">{{$concept[0]->menu_segment_column_description ? $concept[0]->menu_segment_column_description : 'ALL'}}</h3>
        <p class="filter-name">{{$filter != 'no-cost' ? $filter : 'no'}} Cost</p>
        <table id="tableData" class="table table-striped table-bordered">
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
<script type="text/javascript">
    $(document).ready(function() {
        let menuItems = {!! json_encode($filtered_items) !!};
        menuItems = menuItems.sort((a, b) => Number((a.food_cost / a.menu_price_dine * 100)) - Number((b.food_cost / b.menu_price_dine * 100)))
        const tbody = $('tbody');

        menuItems.forEach((item, index) => {
            const tr = $(document.createElement('tr'));
            const menuItemCode = $(document.createElement('td')).text(item.tasteless_menu_code);
            const menuItemDescription = $(document.createElement('td')).text(item.menu_item_description); 
            const srp = $(document.createElement('td')).text(`₱ ${item.menu_price_dine}`);
            const foodCost = $(document.createElement('td')).text(`₱ ${item.food_cost || 0}`);
            const percentage = $(document.createElement('td')).text(item.menu_price_dine == 0 ? '0.00%' : `${((item.food_cost || 0) / (item.menu_price_dine) * 100).toFixed(2)}%`)
            const action = $(document.createElement('td')).addClass('action');
            const detail = $(document.createElement('a')).append($(document
                    .createElement('i'))
                    .addClass('fa fa-eye button'))
                .attr('href', "{{ CRUDBooster::adminPath('menu_items/detail') }}" + `/${item.id}`)
                .attr('target', '_blank');
            const edit = $(document.createElement('a')).append($(document
                    .createElement('i'))
                    .addClass('fa fa-pencil button'))
                .attr('href', "{{ CRUDBooster::adminPath('menu_items/edit') }}" + `/${item.id}`)
                .attr('target', '_blank');
            action.append(detail, edit);
            tr.append(menuItemCode, menuItemDescription, srp, foodCost, percentage, action);
            tbody.append(tr);
        });

        $('table').DataTable({
            pagingType: 'full_numbers',
            pageLength: 50,
        });
    });


</script>
@endpush
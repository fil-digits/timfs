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
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Menu Item Code</th>
                    <th scope="col">Menu Item Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$item->tasteless_menu_code}}</td>
                    <td>{{$item->menu_item_description}}</td>
                </tr>
            </tbody>
        </table>
        <h4 style="font-weight: 600">Ingredients List</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scoped="col">Tasteless Code</th>
                    <th scoped="col">Ingredient</th>
                    <th scoped="col">Quantity</th>
                    <th scoped="col">UOM</th>
                    <th scoped="col">SRP</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($ingredients as $ingredient)
                <tr>
                    <td>{{$ingredient->tasteless_code}}</td>
                    <td>{{$ingredient->full_item_description}}</td>
                    <td>{{$ingredient->qty}}</td>
                    <td>{{$ingredient->uom_description}}</td>   
                    <td>{{$ingredient->srp}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection
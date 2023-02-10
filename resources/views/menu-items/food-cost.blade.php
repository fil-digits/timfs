@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/aee358fec0.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<style>
    table, th, td {
        border: 1px solid rgb(215, 214, 214) !important;
        text-align: center;
    }
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')

<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-dollar"></i><strong> Food Cost</strong>
    </div>

    <div class="panel-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="active">
                    <th scope="col">No.</th>
                    <th scope="col">Concept Name</th>
                    <th scope="col">Low Cost</th>
                    <th scope="col">High Cost</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div class="panel-footer">
        <button class="btn btn-primary" type="button" id="export"> <i class="fa fa-download" ></i> Export</button>
    </div>
</div>

@endsection

@push('bottom')
<script>
    $(document).ready(function() {
        const concepts = {!! json_encode($concepts) !!};
        const menuItems = {!! json_encode($menu_items) !!};
        concepts.forEach((concept, index) => {
            const tr = $(document.createElement('tr'));
            const groupedItems = [...menuItems].filter(menuItem => !!menuItem[concept.menu_segment_column_name]);
            const low = groupedItems.filter(item => item.food_cost && item.food_cost / item.menu_price_dine <= 0.30);
            const high = groupedItems.filter(item => item.food_cost && item.food_cost / item.menu_price_dine > 0.30);
            for (let i=0; i<4; i++) {
                const td = $(document.createElement('td'));
                if (i==0) td.text(index + 1);
                if (i==1) td.text(concept.menu_segment_column_description);
                if (i==2) td.text(low.length), td.addClass('low');
                if (i==3) td.text(high.length), td.addClass('high');
                tr.append(td)
            }

            $('tbody').append(tr);
        });

        const totalTR = $(document.createElement('tr'));
        const totalLabelTD = $(document.createElement('td'));
        totalTR.css('font-weight', 'bold');
        totalLabelTD.text('Total');
        totalLabelTD.attr('colspan', '2');
        totalTR.append(totalLabelTD);
        for (let i=0; i<2; i++) {
            const td = $(document.createElement('td'));
            let sum = 0;
            let values;
            if (i==0) values = jQuery.makeArray($('.low'));
            if (i==1) values = jQuery.makeArray($('.high'));
            values.forEach(value => {
                sum += Number($(value).text());
            });
            td.text(sum)
            totalTR.append(td);
        }

        $('tbody').append(totalTR);
        

        function exportToExcel(type, fn, dl) {
            var elt = document.querySelector('table');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
            XLSX.writeFile(wb, fn || (`Food_Cost_${new Date().toISOString().slice(0, 10)}` + (type || 'xlsx')));
        }

        $(document).on('click', '#export', function() {
            exportToExcel('.xlsx');
        });

    });

</script>
@endpush
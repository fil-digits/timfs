@push('head')
<script src="https://code.jquery.com/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<style>
    table, th, td {
        border: 1px solid rgb(215, 214, 214) !important;
        text-align: center;
    }

    .clickable {
        color: blue;
        cursor: pointer;
    }

    .clickable:hover{
        outline: 2px solid blue;
        background: rgb(220, 220, 220);
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
                    <th scope="col">Concept Name</th>
                    <th scope="col">Low Cost</th>
                    <th scope="col">High Cost</th>
                    <th scope="col">No Cost</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    {{-- <div class="panel-footer">
        <button class="btn btn-primary" type="button" id="export"> <i class="fa fa-download" ></i> Export</button>
    </div> --}}
</div>

@endsection

@push('bottom')
<script>
    $(document).ready(function() {

        // PER CONCEPT !!!
        const concepts = {!! json_encode($concepts) !!};
        const menuItems = {!! json_encode($menu_items) !!};
        concepts.forEach((concept, index) => {
            const tr = $(document.createElement('tr'));
            const groupedItems = [...menuItems].filter(menuItem => !!menuItem[concept.menu_segment_column_name]);
            const low = groupedItems.filter(item => item.food_cost && item.food_cost / item.menu_price_dine <= 0.30);
            const high = groupedItems.filter(item => item.food_cost && item.food_cost / item.menu_price_dine > 0.30);
            for (let i=0; i<4; i++) {
                const td = $(document.createElement('td'));
                td.attr('id', concept.id);
                if (i==0) {
                    td.text(concept.menu_segment_column_description);
                } else if (i==1) {
                    const items = low.map(item => item.id).join(',');
                    td.text(low.length);
                    td.attr('filter', 'low');
                    td.attr('items', items);
                    td.addClass('low clickable');
                } else if (i==2) {
                    const items = high.map(item => item.id).join(',');
                    td.text(high.length);
                    td.attr('filter', 'high');
                    td.attr('items', items);
                    td.addClass('high clickable');
                } else if (i==3) {
                    const items = groupedItems.filter(item => item.food_cost == 0 || !item.food_cost).map(item => item.id);
                    td.text(items.length);
                    td.attr('filter', 'no-cost');
                    td.attr('items', items.join(','));
                    td.addClass('clickable');
                }
                tr.append(td)
            }

            $('tbody').append(tr);
        });

        // TOTAL || ALL
        const totalTR = $(document.createElement('tr'));
        const totalLabelTD = $(document.createElement('td'));
        const allLow = [...menuItems].filter(item => Number(item.food_cost) > 0 && item.food_cost / item.menu_price_dine <= 0.30);
        const allHigh = [...menuItems].filter(item => Number(item.food_cost) > 0 && item.food_cost / item.menu_price_dine > 0.30);
        const allNoCost = [...menuItems].filter(item => item.food_cost == 0 || !item.food_cost);
        totalTR.css('font-weight', 'bold');
        totalLabelTD.text('All');
        // totalLabelTD.attr('colspan', '2');
        totalTR.append(totalLabelTD);
        for (let i=0; i<3; i++) {
            const td = $(document.createElement('td'));
            if (i==0) {
                const items = allLow.map(item => item.id).join(',');
                td.text(allLow.length);
                td.attr('filter', 'low');
                td.attr('items', items);
                td.addClass('low clickable');
            } else if (i==1) {
                const items = allHigh.map(item => item.id).join(',');
                td.text(allHigh.length);
                td.attr('filter', 'high');
                td.attr('items', items);
                td.addClass('high clickable');
            } else if (i==2) {
                const items = allNoCost.map(item => item.id).join(',');
                td.text(allNoCost.length);
                td.attr('filter', 'no-cost');
                td.attr('items', items);
                td.addClass('clickable');
            }
            td.attr('id', 'all');
            td.addClass('clickable');
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

        $(document).on('click', '.clickable', function() {
            const td = $(this);
            const id = td.attr('id');
            const filter = td.attr('filter');
            const items = td.attr('items');

            const form = $(document.createElement('form'))
                .attr('method', 'POST')
                .attr('action', "{{ route('filter_by_cost') }}")
                .css('display', 'none');
            const csrf = $(document.createElement('input'))
                .attr({
                    type: 'hidden',
                    name: '_token',
                })
                .val("{{ csrf_token() }}");
            const idInput = $(document.createElement('input'))
                .attr('name', 'id')
                .val(id);
            const itemInput = $(document.createElement('input'))
                .attr('name', 'items')
                .val(items);
            const filterInput = $(document.createElement('input'))
                .attr('name', 'filter')
                .val(filter);
            $('.panel-body').append(form);
            form.append(csrf, idInput, itemInput, filterInput);
            form.submit();
        });

        $(document).on('click', '#export', function() {
            exportToExcel('.xlsx');
        });

        $('table').DataTable({
            pagingType: 'full_numbers',
            pageLength: 100,
        });

    });

</script>
@endpush
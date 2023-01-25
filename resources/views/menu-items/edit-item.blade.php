@push('head')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<style type="text/css">

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

    
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')

{{-- 
    A COPY OF INGREDIENT ENTRY!!! FOR CLONING!!
--}}

<div class="ingredient-entry" style=" margin-bottom: 10px; padding: 15px; border: 1px solid #ddd; display: none">
    <div class="ingredient-inputs">
    <label style="margin-bottom: 10px;">
        <span class="required-star">*</span> Ingredient
        <div>
            <input value="" type="text" style="display: none;" name="ingredient[]" class="ingredient form-control" required/>
            <input value="" type="text" class="form-control display-ingredient span-2" placeholder="Search Item" required/>
            <div class="item-list" style="position: absolute;">
            </div>
        </div>
    </label>
    <label for="" style="margin-bottom: 10px;">
        <span class="required-star">*</span> Ingredient Quantity
        <input value="" name="quantity[]" class="form-control" type="number" min="0" required/>
    </label>
    <label for="" style="margin-bottom: 10px;">
        <span class="required-star">*</span> Ingredient UOM
        <?php $current_uom = $uoms->where('id', $current_ingredient->uom_id)->first(); ?>
        <select name="uom[]" class="select2 form-control uom" required>
            <option value="">Please select UOM</option>
            @foreach($uoms as $uom)
            <option value="{{$uom->id}}">{{$uom->uom_description}}</option>
            @endforeach
        </select>
    </label>
    <label for="" style="margin-bottom: 10px;">
        <span class="required-star">*</span> Ingredient SRP
        <input value="" name="srp[]" class="form-control" type="number" min="0" step="0.01" required>
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
    Back To List Data Menu Item Masterfile</a>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><p>Edit Menu Item</p></strong>
    </div>
    <div class="panel-body">
        <form class='form-horizontal' action="{{ route('edit_menu_item') }}" id="form" method="POST" autocomplete="off">
            @csrf
            <input type="text" name="menu_items_id" value="{{$item->id}}" style="display: none;">
            <label for="" style="display: block; margin-bottom: 10px;">
                Menu Item Code
                <input class="form-control" type="text" value="{{$item->tasteless_menu_code}}" disabled>
            </label>
            <label for="" style="display: block; margin-bottom: 10px;">
                Menu Item Description
                <input class="form-control" type="text" value="{{$item->menu_item_description}}" disabled>
            </label>


            <section class="ingredient-section">
                {{-- IF THE MENU ITEM DOES HAVE SOME SAVED INGREDIENTS //LOOP// --}}
                @foreach($current_ingredients as $current_ingredient)
                <div class="ingredient-entry" style=" margin-bottom: 10px; padding: 15px; border: 1px solid #ddd;">
                    <div class="ingredient-inputs">
                    <label style="margin-bottom: 10px;">
                        <span class="required-star">*</span> Ingredient
                        <div>
                            <input value="{{$current_ingredient->id}}" type="text" style="display: none;" name="ingredient[]" class="ingredient form-control" required/>
                            <input value="{{$current_ingredient->full_item_description}}" type="text" class="form-control display-ingredient span-2" placeholder="Search Item" required/>
                            <div class="item-list" style="position: absolute;">
                            </div>
                        </div>
                    </label>
                    <label for="" style="margin-bottom: 10px;">
                        <span class="required-star">*</span> Ingredient Quantity
                        <input value="{{$current_ingredient->qty}}" name="quantity[]" class="form-control quantity" type="number" min="0" required />
                    </label>
                    <label for="" style="margin-bottom: 10px;">
                        <span class="required-star">*</span> Ingredient UOM
                        <?php $current_uom = $uoms->where('id', $current_ingredient->uom_id)->first(); ?>
                        <select name="uom[]" class="select2 form-control uom" required>
                            <option value="">Please select UOM</option>
                            @if($current_uom)
                                <option value="{{$current_uom->id}}" selected>{{$current_uom->uom_description}}</option>
                            @endif
                            @foreach($uoms as $uom)
                                @if($current_uom->id != $uom->id)
                                    <option value="{{$uom->id}}">{{$uom->uom_description}}</option>
                                @endif
                            @endforeach
                        </select>
                    </label>
                    <label for="" style="margin-bottom: 10px;">
                        <span class="required-star">*</span> Ingredient SRP
                        <input value="{{$current_ingredient->srp}}" name="srp[]" class="form-control srp" type="number" min="0" step="0.01" required />
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
            <button class="btn btn-success" id="add-row" name="button" type="button" value="add_ingredient"> <i class="fa fa-plus" ></i> Add ingredient</button>
            <div class='panel-footer'>
                <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
                <button class="btn btn-primary pull-right" name="submit" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> Save</button>
            </div>
        </form>
    </div>
</div>
  

@endsection
@push('bottom')

<script>

    $(document).ready(function() {

        $.fn.reload = function() {
            $('.display-ingredient').keyup(function() {
                const entry = $(this).parents('.ingredient-entry');
                const query = ($(this).val());
                const current_ingredients = $(".ingredient").serializeArray();
                const arrayOfIngredients = [];
                const index = $('.display-ingredient').index(this);
                // console.log(current_ingredients);
                current_ingredients.forEach((item, item_index) => {
                    // TO STILL SHOW THE CURRENT INGREDIENT OF THE SELECTED INPUT
                    // BUT HIDE THE INGREDIENTS OF OTHER INPUTS
                    if (item_index != index) arrayOfIngredients.push(item.value);
                });
                console.log(arrayOfIngredients);

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
                        $('.item-list').eq(index).fadeIn(); 
                        $('.item-list').eq(index).html(response);                        
                    }
                });
            });
        }

        $(document).on('click', '.list-item', function(event) { 
            const entry = $(this).parents('.ingredient-entry');
            // console.log(this);
            entry.find('.ingredient').val($(this).attr('item_id'));
            entry.find('.display-ingredient').val($(this).text());

            $('.item-list').html('');  
            $('.item-list').fadeOut();
        });

        $(document).on('click', '.move-up', function() {
            const entry = $(this).parents('.ingredient-entry');
            // console.log(entry);
            entry.insertBefore($(entry).prev());
        });

        $(document).on('click', '.move-down', function() {
            const entry = $(this).parents('.ingredient-entry');
            // console.log(entry);
            entry.insertAfter($(entry).next());
        });

        $(document).on('click', '.delete', function(event) {
            const index = $('.delete').index(this);
            const itemMastersId = $('.ingredient').eq(index).val();
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
                            $('.ingredient-entry').eq(index).remove();
                            $('.item-list').html('');  
                            $('.item-list').fadeOut();
                        }
                    }
                });
            }
        }); 
        
        $.fn.reload();
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
        section.find('.srp').val('');
        section.css('display', '');
        $('.ingredient-section').append(section);
        $('.item-list').fadeOut();
        $.fn.reload();
    }

</script>
@endpush

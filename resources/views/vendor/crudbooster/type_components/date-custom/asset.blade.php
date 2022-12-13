
@push('bottom')

<script type="text/javascript">
    var lang = '{{App::getLocale()}}';
    $(function() {
        $('.input_date').daterangepicker({
            format: 'YYYY-MM-DD'
        });
        
        $('.open-datetimepicker').click(function() {
			  $(this).next('.input_date').daterangepicker('show');
		});
        
    });

</script>
@endpush
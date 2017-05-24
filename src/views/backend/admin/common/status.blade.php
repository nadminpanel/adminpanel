@if(session('status'))

<script type="text/javascript">
	
	$(document).ready(function(){

		var status="{{ session('status') }}";

		toastr.success(status, 'Status', {timeOut: 2000});

	});

</script>

@endIf
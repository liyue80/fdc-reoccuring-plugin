<style>
</style>
<div class="modal fade" id="HelloWorld2Dialog" tabindex="-1" role="dialog" style="width:95%; height:95%;" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog ask-dialog" style="max-width: 90%; height: 85%">
		<div class="modal-content" style="height:100%;overflow: visible;">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel">Hello World 2</h5>
				<button type="button" class="close push-xs-right form-control input-sm" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="text-align: center; overflow: visible;">
				<label id="user2Name" style="padding-top: 100px; font-size:50px;"></label>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script>
	//IMPORTANT - csrf_token
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
		},
		error: function(jqXHR, textStatus, errorThrown) {
			toastr.error('Lost Connection');
		}
	});


	var filePath = '';
	$(document).ready(function() {

		fillUser2Name();

		function fillUser2Name() {
			var success = false;
			var formData = new FormData();
			formData.append('accountId', 2);
			$.ajax({
				url: '{{ url("/helloWorld2/getUserNameById") }}',
				type: 'POST',
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				success: function(data) {
					console.log(data);
					$('#user2Name').text('Hello!!! ' + data.toUpperCase());
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
				},
			});
		};

	});
</script>
@endpush
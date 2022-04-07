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
			<div class="modal-body" style="overflow: visible;">
				<div class="container-md">
					<div class="row">
						<div class="col-md-8 offset-md-2">
							<form>
								<div class="form-group">
									<label for="inputTimeWindow">Time Window</label>
									<input type="number" class="form-control" id="inputTimeWindow" value="30">
								</div>
								<div class="form-group">
									<label for="inputCountThreshold">Count Threshold</label>
									<input type="number" class="form-control" id="inputCountThreshold" value="5">
								</div>
								<div class="form-group">
									<label>Object Types</label>
									<div id="objecttypes">
										<!-- <div class="form-check custom-control-inline">
                                <input type="checkbox" id="customRadioInline1" name="customRadioInline"
                                    class="form-check-input">
                                <label for="customRadioInline1">Car</label>
                            </div>

                            <div class="form-check custom-control-inline">
                                <input type="checkbox" id="customRadioInline2" name="customRadioInline"
                                    class="form-check-input">
                                <label for="customRadioInline2">Bus</label>
                            </div> -->

									</div>
								</div>
								<button type="submit" class="btn btn-primary">Submit</button>
								<button type="reset" class="btn btn-secondary">Reset</button>
							</form>
						</div>
					</div>
				</div>
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
		//fillUser2Name();
		createElementObjectTypes();

		function createElementObjectTypes() {
			const types = ["animal", "backpack", "bag", "bicycle", "bus", "car",
				"cell_phone", "cow", "dog", "fire", "forklift", "handgun",
				"head", "human", "motorbike", "object", "others", "person", "rifle",
				"smoke", "stroller", "transportation", "truck", "umbrella", "wheelchair"
			]
			types.forEach(function(element) {
				let elementId = 'id_' + element
				let div = $('<div class="form-check form-check-inline">')
				$('<input type="checkbox" class="form-check-input" style="margin-left: 0">').prop('id', elementId).prop('name', elementId).appendTo(div)
				$('<label class="form-check-label" style="text-transform: capitalize;"></label>').prop('for', elementId).text(element).appendTo(div)
				div.appendTo($('#objecttypes'))
			})
		}

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
<style>
	.ac-fixed-width {
		width: 90px;
		margin-left: 0.75rem;
	}
</style>
<div class="modal fade" id="ReoccuringConfigDialog" tabindex="-1" role="dialog" style="width:95%; height:95%;" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog ask-dialog" style="max-width: 90%; height: 85%">
		<div class="modal-content" style="height:100%;overflow: visible;">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel">Reoccuring Config Dialog</h5>
				<button type="button" class="close push-xs-right form-control input-sm" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="overflow: visible;">
				<div class="container-md" style="margin-top: 20px;">
					<div class="row">
						<div class="col-md-8 offset-md-2">
							<form id="formfdc" style="margin-bottom: 5px;">
								<div class="form-group">
									<label for="inputTimeWindow">Time Window(in minutes)</label>
									<input type="number" class="form-control" id="inputTimeWindow" min="1" max="43200" value="30">
								</div>
								<div class="form-group">
									<label for="inputCountThreshold">Count Threshold</label>
									<input type="number" class="form-control" id="inputCountThreshold" min="1" max="9999" value="1">
								</div>
								<div class="form-group">
									<label>Object Types</label>
									<div id="objecttypes"></div>
								</div>
								<button type="button" class="btn confirmBtn" id="submitfdcopt"><img ok="">Apply</button>
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

		addCheckboxes().then(() => {
			getAndUpdateOptions();
		})

		$("#submitfdcopt").on("click", () => {

			let timeWindowValue = $("#inputTimeWindow").val().trim();
			if (!/^\d+$/.test(timeWindowValue)) return false;
			let twv = parseInt(timeWindowValue);
			if (!twv) return false;

			let thresholdValue = $("#inputCountThreshold").val().trim();
			if (!/^\d+$/.test(thresholdValue)) return false;
			let ctv = parseInt(thresholdValue);
			if (!ctv) return false;

			let typelist = []
			$("#formfdc input[type=checkbox]:checked").each(function() {
				typelist.push($(this).prop('id').substr(3));
			});

			// console.log(typeof timeWindowValue, typeof thresholdValue)
			// console.log(timeWindowValue, thresholdValue)
			// console.log(typelist)

			$.ajax({
				url: '{{ url("/reoccuringConfig/setOptions") }}',
				type: 'PUT',
				dataType: 'json',
				cache: false,
				contentType: 'application/json',
				data: JSON.stringify({
					timewindow: twv * 60,
					countthreshold: ctv,
					targetobjects: typelist
				}),
				processData: false,
				success: function(data, textStatus, jQxhr) {
					console.log(data);
					showAlert('All right. The configuration was saved.', 'alert-success');
				},
				error: function(jqXhr, textStatus, errorThrown) {
					console.log(errorThrown);
					showAlert('Oops there was an error while saving configuration!.', 'alert-danger');
				}
			});
			// $("#ReoccuringConfigDialog").modal("hide");
		});

		function showAlert(message, color) {
			$('#alertfdc').remove(); // close previous alert if exists
			let alertDiv = $('<div id="alertfdc" class="alert" role="alert">').addClass(color);
			$('<span>').text(message).appendTo(alertDiv);
			let alertBtn = $('<button type="button" class="close" data-dismiss="alert" aria-label="Close">').appendTo(alertDiv);
			$('<span aria-hidden="true">').text('x').appendTo(alertBtn);
			alertDiv.insertAfter('#formfdc');
		}

		// query current options from the reoccuring service.
		// and update data to GUI
		function getAndUpdateOptions() {
			$.ajax({
				url: '{{ url("/reoccuringConfig/getOptions") }}',
				type: 'GET',
				cache: false,
				dataType: 'json',
				success: function(data) {
					$("#inputTimeWindow").val(Math.round(data.timewindow / 60));
					$("#inputCountThreshold").val(data.countthreshold);
					data.targetobjects.forEach((e) => {
						let selector = '#ot_' + e;
						$(selector).prop("checked", true);
					})
				},
				error: function(jqXHR) {
					console.log("getOptions error");
					showAlert('Oops there was an error while loading configuration.', 'alert-danger');
				},
			});
		}

		// GET all supported object types
		// and show their check boxes
		function addCheckboxes() {
			return new Promise((resolve, reject) => {
				$.ajax({
					url: '{{ url("/reoccuringConfig/getObjectTypes") }}',
					type: 'GET',
					cache: false,
					dataType: 'json',
					success: function(data) {
						// const data = ["animal", "backpack", "bag", "bicycle", "bus", "car",
						// 	"cell_phone", "cow", "dog", "fire", "forklift", "handgun",
						// 	"head", "human", "motorbike", "object", "others", "person", "rifle",
						// 	"smoke", "stroller", "transportation", "truck", "umbrella", "wheelchair"
						// ]
						const exclusions = ["animal", "human", "object", "others", "transportation"];
						data.forEach(function(element) {
							if (exclusions.indexOf(element) < 0) {
								let elementId = 'ot_' + element;
								let div = $('<div class="form-check form-check-inline ac-fixed-width">');
								$('<input type="checkbox" class="form-check-input" style="margin-left: 0">')
									.prop('id', elementId)
									.prop('name', elementId)
									.appendTo(div);
								$('<label class="form-check-label" style="text-transform: capitalize;"></label>')
									.prop('for', elementId)
									.text(element)
									.appendTo(div);
								div.appendTo($('#objecttypes'));
							}
						})
						resolve(data)
					},
					error: function(error) {
						console.log("getObjectTypes error")
						reject(error)
					},
				})
			})
		}

		function fillUser2Name() {
			var success = false;
			var formData = new FormData();
			formData.append('accountId', 2);
			$.ajax({
				url: '{{ url("/reoccuringConfig/getUserNameById") }}',
				type: 'POST',
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				success: function(data) {
					$('#user2Name').text('Hello!!! ' + data.toUpperCase());
				},
				error: function(jqXHR, textStatus, errorThrown) {},
			});
		};

	});
</script>
@endpush
<?php
	if (!isset($startdate)) $startdate = "2005-01-01";
	if (!isset($enddate)) $enddate = date("Y-m-d");
	$ROWSPERPAGE = 10;
?>

<form action="trainingsummary.php" method="post" class="form-horizontal">
	<div class="form-group">
		<?php if ((isset($_GET["admin"]) && $_GET["admin"] == 1) || (isset($admin) && $admin == 1)): ?>
			<input type="hidden" name="admin" value="1" />
		<?php endif ?>
		
		<div class="col-md-4 col-md-offset-1 text-left">
			<label>
				<b class="required">Training type</b>
				<select name="trainingid" id="trainingid" data-placeholder="Choose a training type..." class="chosen-select" style="width: 75%;">
					<option disabled selected value>Select an option</option>
					<?php foreach($traintypes as $traintype): ?>
						<option value="<?= $traintype["trainingid"] ?>" 
							<?php if(isset($trainingid) && $traintype["trainingid"] == $trainingid){ ?> selected="selected" <?php } ?>>
							<?= htmlspecialchars($traintype["type"]) ?>
						</option>
					<?php endforeach ?>
				</select>
			</label>
			<label>
				<b>Description (Optional)</b>
				<input class="form-control autocomplete" name="description" id="newTrainDesc2" type="text" maxlength="60" 
				placeholder="e.g. Scientific Update Med Chem Course" onfocus="setAutocompleteType('newTrainDesc', 'trainingid')" 
				<?= (isset($description) && strlen($description)>0)?"value=\"" . $description . "\" ":"" ?> />
			</label>
		</div>
		<div class="col-md-2 text-left">
			<label>
				<b class="required">Start Date</b>
				<input class="form-control" name="startdate" type="date" required="required" value="<?= $startdate ?>" 
					min="2005-01-01" max="<?= date("Y-m-d") ?>" />
			</label>
			<label>
				<b class="required">End Date</b>
				<input class="form-control" name="enddate" type="date" required="required" value="<?= $enddate ?>"
					min="2005-01-01" max="<?= date("Y-m-d") ?>" />
			</label>
		</div>
		<div class="col-md-3 text-left">
			<b>Departments</b><br />
			<?php foreach ($depts as $dept): ?>
				<label style="float: left;"><input type="checkbox" name="departments[]" value="<?= $dept["depmask"] ?>" 
					<?php if((isset($departments) && array_search($dept["depmask"], $departments) !== FALSE) || !isset($departments)): ?>
						checked="checked" 
					<?php endif ?>
					/><?= $dept["department"] ?>&nbsp;&nbsp;
				</label>
			<?php endforeach ?>
		</div>
		<div class="col-md-1 text-center">
			<br />
			<button class="btn btn-info" type="submit">Submit</button>
		</div>
	</div>
</form>
<?php if (isset($verified)): ?>
	<div class="form-group">
		<div class="col-md-3">
			<table class="conflist paginated" style="width: 100%;">
				<thead>
					<th class="left right" style="width: 6em;">
						Verified Users
					</th>
				</thead>
				<tbody>
					<?php foreach ($verified as $user): ?>
						<tr>
							<td>
								<?= htmlspecialchars($user["firstname"] . " " . $user["lastname"]) ?>
								<?php if ($user["count"] != 1) print(" (" . intval($user["count"]) . ")"); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-3">
			<form action="modifyrecord.php?type=verifyTrain" method="post">
				<input type="hidden" name="superuser" value="1" />
				<input type="hidden" name="trainingid" value="<?= $trainingid ?>" />
				<table class="conflist paginated" style="width: 100%;">
					<thead>
						<th class="left right">
							Unverified Users
						</th>
					</thead>
					<tbody>
						<?php foreach ($unverified as $user): ?>
							<tr>
								<td>
									<div class="button_check">
										<label for="<?= htmlspecialchars($user["recordid"]) ?>">
											<input type="checkbox" id="<?= htmlspecialchars($user["recordid"]) ?>" 
												value="<?= htmlspecialchars($user["recordid"]) ?>" name="verifyrecords[]" 
												<?php if ($user["count"] != 1) print("disabled "); ?>
											/>
											<span>
												<?= htmlspecialchars($user["firstname"] . " " . $user["lastname"]) ?>
												<?php 
													if ($user["count"] != 1) print(" (" . intval($user["count"]) . ")");
													if ($user["confirmed"] == 0) print(" (Unconfirmed)"); 
												?>
											</span>
										</label>
									</div>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				<button class="btn btn-success" type="submit">Verify Selected</button>
			</form>
		</div>
		<div class="col-md-3">
			<form id="addTrain" action="modifyrecord.php?type=newTrain" method="post">
				<input type="hidden" name="superuser" value="1" />
				<input type="hidden" name="startdate" value="<?= $startdate ?>" />
				<input type="hidden" name="enddate" value="<?= $enddate ?>" />
				<input type="hidden" name="depmask" value="<?= $depmask ?>" />
				<table class="conflist paginated" style="width: 100%;">
					<thead>
						<th class="left right">
							Unconfirmed Users
						</th>
					</thead>
					<tbody>
						<?php foreach ($unconfirmed as $user): ?>
							<tr>
								<td>
									<div class="button_check">
										<label for="<?= htmlspecialchars($user["userid"]) ?>">
											<input type="checkbox" id="<?= htmlspecialchars($user["userid"]) ?>" 
												value="<?= htmlspecialchars($user["userid"]) ?>" name="otherusers[]" />
											<span><?= htmlspecialchars($user["firstname"] . " " . $user["lastname"]) ?></span>
										</label>
									</div>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				<button type="button" class="btn btn-primary" onclick="show_modal('modalNewTrain', 0)">
					<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add and Verify
				</button>
				<!-- Modal for adding new training record -->
				<div class="modal fade" id="modalNewTrain" tabindex="-1" role="dialog" aria-labelledby="modalNewTrain">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="modalAddTrainLabel">Add New Training Record</h4>
								<p>To add an entry to the training history of the selected users, please confirm the following details:</p>
							</div>
							<div class="modal-body">
								<fieldset class="formfieldgroup">
									<legend>Training Record Information</legend>
									<div class="form-group clearfix">
										<div class="col-md-3 text-left">
											<label style="max-width:50%; float: left;">
												<b class="required">Month</b>
												<select class="form-control" name="month" required="required">
													<?php enumeratemonthoptions() ?>
												</select>
											</label>
											<label style="max-width:40%; float: left;">
												<b class="required">Year</b>
												<input class="form-control" name="year" type="number" min="1980" max="2500" 
													value="<?= date('Y') ?>" required="required" />
											</label>
											<p class="text-muted">Training start date</p>
										</div>
										<div class="col-md-5 text-left">
											<label>
												<b class="required">Training Type</b>
												<select name="trainingid" id="newTrainID" data-placeholder="Select training type..." class="chosen-select" 
													required="required">
													<option disabled selected value>Select an option</option>
													<?php foreach($traintypes as $traintype): ?>
														<option value="<?= $traintype["trainingid"] ?>" 
														<?php if(isset($trainingid) && $traintype["trainingid"] == $trainingid){ ?> selected="selected" <?php } ?>>
														<?= htmlspecialchars($traintype["type"]) ?>
														</option>
													<?php endforeach ?>
												</select>
											</label>
										</div>
									</div>
									<div class="form-group clearfix">
										<div class="col-md-7 text-left">
											<label>
												<b>Description (Optional)</b>
												<input class="form-control autocomplete" name="description" id="newTrainDesc" type="text" maxlength="60" 
													placeholder="e.g. Scientific Update Med Chem Course" onfocus="setAutocompleteType('newTrainDesc', 'newTrainID')" 
													<?= (isset($description) && strlen($description)>0)?"value=\"" . $description . "\" ":"" ?> />
											</label>
										</div>
										<div class="col-md-3 text-left">
											<label>
												<b class="required">Total Duration (days)</b>
												<input class="form-control" name="days" type="number" min="0" max="10" step="0.1" required="required" />
											</label>
										</div>
									</div>
									<div class="form-group clearfix">
										<div class="col-md-3 col-md-offset-1 text-left">
											<label>
												<b class="required">Location</b>
												<select name="internal_location" required="required">
													<option disabled selected value>Select option</option>
													<option value="1">Internal</option>
													<option value="0">External</option>
												</select>
											</label>
										</div>
										<div class="col-md-3 text-left">
											<label>
												<b class="required">Trainer</b>
												<select name="internal_trainer" required="required">
													<option disabled selected value>Select option</option>
													<option value="1">Internal</option>
													<option value="0">External</option>
													<option value="2">No Trainer</option>
												</select>
											</label>
										</div>
									</div>
								</fieldset>
							</div>
							<div class="modal-footer">
								<fieldset>
									<button class="btn btn-success" type="submit">Submit</button>
									<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetForm('addTrain')">Cancel</button>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-3">
			<div id="statusChart"></div>
		</div>
	</div>
<?php endif ?>

<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

	<?php if (isset($verified)): ?>
		// Load the Visualization API and the corechart package.
		google.charts.load('current', {'packages':['corechart']});

		// Set a callback to run when the Google Visualization API is loaded.
		google.charts.setOnLoadCallback(drawCharts);

		// Callback that creates and populates a data table,
		// instantiates the pie chart, passes in the data and
		// draws it.
		function drawCharts() {

			// Status Chart
			// Create the data table.
			var data1 = new google.visualization.DataTable();
			data1.addColumn('string', 'Status');
			data1.addColumn('number', 'Frequency');
			
			data1.addRows(	[
								['Verified', <?= count($verified) ?>], 
								['Unverified', <?= count($unverified) ?>], 
								['Unconfirmed', <?= count($unconfirmed) ?>]
							]);

			// Set chart options
			var options1 = {
				title:'Users Status',
				width:250,
				height:250,
				is3D:true,
				legend: {position: 'bottom'}
			};

			// Instantiate and draw our chart, passing in some options.
			var chart = new google.visualization.PieChart(document.getElementById('statusChart'));
			chart.draw(data1, options1);
			
		}
	<?php endif ?>

	// function to show modal with specified id, triggered by button in HTML
	function show_modal(id){
		$( "#"+id ).modal( "toggle" );
		$(".chosen-container").width("75%");
	}
	
	// reset form with specified id, used when cancel button is pressed
	function resetForm(id){
		$("#"+id).trigger("reset");
	}

	/* keep track of which input box has focus and return appropriate autocomplete results
	 * type is submitted in the query string and determines which query is used to obtain results
	 * filter specifies the id of the form element passed in the query string used to filter the
	 * results (0 for no filtering) */
	function setAutocompleteType(type, filter){
		var autocompleteType = type;
		
		var source = "getautocomplete.php?type=" + autocompleteType;
		if (filter != 0) source += "&filter=" + $("#" + filter).children(":selected").val();

		// set up autocomplete using appropriate type
		$( "input.autocomplete" ).autocomplete({
			source: source,
			minLength: 1
		});
	}

	$(document).ready(function(){

		<?php if ((isset($_GET["admin"]) && $_GET["admin"] ==1) || (isset($admin) && $admin == 1)): ?>
		// Make navAdmin navbar item selected
		$('#navAdmin').addClass("active");
		<?php else: ?>
		// Make navSummaries navbar item selected
		$('#navSummaries').addClass("active");
		<?php endif ?>

		// Initiate chosen select box
		$('.chosen-select').chosen();

		// Initialise each paginated table
		$('table.paginated').each(function() {
			var currentPage = 0;
			var numPerPage = <?= $ROWSPERPAGE ?>;
			// Current table
			var $table = $(this);

			// Add table member function to repaginate table
			$table.bind('repaginate', function() {
				// Show all rows
				$table.find('tbody tr').show();
				// Hide rows on pages before current page
				$table.find('tbody tr:lt(' + currentPage * numPerPage + ')').hide();
				// Hide rows on pages after current page
				$table.find('tbody tr:gt(' + ((currentPage + 1) * numPerPage - 1) + ')').hide();
			});

			// Prepare page navigation HTML to inject under table
			var numRows = $table.find('tbody tr').length;
			var numPages = Math.ceil(numRows / numPerPage);
			// Create div
			var $pager = $('<div class="pager"></div>');

			// Append pager title
			$('<span class="pager-title"> Page: </span>').appendTo($pager);

			// Append page numbers
			for (var page = 0; page < numPages; page++) {
			  $('<span class="page-number"> ' + (page + 1) + '</span>')
				// On click
				.bind('click', {'newPage': page}, function(event) {
					// Update currentPage
					currentPage = event.data['newPage'];
					// Repaginate
					$table.trigger('repaginate');
					// Reset active page number
					$(this).addClass('active').siblings().removeClass('active');
				}).appendTo($pager).addClass('clickable');
			}

			// Initially set first page to active
			$pager.find('span.page-number:first').addClass('active');

			// Insert pager div underneath table
			$pager.insertAfter($table);

			// Run initial pagination
			$table.trigger('repaginate');
		});
		
	});
</script>

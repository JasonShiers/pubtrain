<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="ConferenceHistory">
			<h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseConferenceHistory" 
					aria-expanded="true" aria-controls="collapseConferenceHistory">
					<b>My Conference History <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
						<span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></b>
				</a>
			</h4>
		</div>
		<div id="collapseConferenceHistory" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="ConferenceHistory">
			<div class="panel-body">
				<table class="conflist paginated" style="width: 100%">
					<thead>
						<tr>
							<th class="left" style="width: 6em;">
								Start Date
							</th>
							<th>
								Title
							</th>
							<th>
								Location
							</th>
							<th style="width: 3em;">
								Days
							</th>
							<th>
								Feedback
							</th>
							<th class="right">
								Options
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($confhistory as $h)
							{
								print("<tr>");
								print("<td>" . htmlspecialchars($h["confdate"]) . "</td>");
								print("<td>" . htmlspecialchars($h["title"]) . "</td>");
								print("<td>" . htmlspecialchars($h["location"]) . "</td>");
								print("<td>" . htmlspecialchars($h["days"]) . "</td>");
								if ($h["attended"]==1)
								{
									print("<td ><a style=\"font-weight: bold; color: forestgreen;\" href=\"feedbackreview.php?id=" 
										. htmlspecialchars($h["req_id"]) . "&userid=" . htmlspecialchars($_SESSION["userid"]) 
										. "\">Review</a></td>");
								}
								else
								{
									print("<td>N/A</td>");
								}
								print("	<td>
											<button type=\"button\" class=\"btn btn-info btn-xs\">
												<span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit
											</button>
											&nbsp;
											<button type=\"button\" class=\"btn btn-danger btn-xs\">
												<span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Delete
											</button>
										</td>");
								print("</tr>");
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan=6>
								<button type="button" class="btn btn-primary btn-xs" onclick="show_modalNewConf()">
									<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add
								</button>
								 conference not requested through ConfTracker.
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="TrainingHistory">
			<h4 class="panel-title">
				<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTrainingHistory"
					aria-expanded="false" aria-controls="collapseTrainingHistory">
					<b>My Training History <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
						<span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></b>
				</a>
			</h4>
		</div>
		<div id="collapseTrainingHistory" class="panel-collapse collapse" role="tabpanel" aria-labelledby="TrainingHistory">
			<div class="panel-body">
				<table class="conflist paginated" style="width: 100%">
					<thead>
						<tr>
							<th style="width: 6em;">
								Start Date
							</th>
							<th>
								Title and Description
							</th>
							<th>
								Location
							</th>
							<th>
								Trainer
							</th>
							<th style="width: 3em;">
								Days
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($trainhistory as $h)
							{
								print("<tr>");
								print("<td>" . htmlspecialchars($h["date"]) . "</td>");
								print("<td>" . htmlspecialchars($h["type"]));
								if ($h["description"] !== ""){
									print("<br />" . htmlspecialchars($h["description"]));
								}
								print("</td>");
								if ($h["internal_location"] == 0)
								{
									print("<td>External</td>");
								}
								else
								{
									print("<td>Internal</td>");
								}									
								if ($h["internal_trainer"] === 0)
								{
									print("<td>External</td>");
								}
								else if ($h["internal_trainer"] == 1)
								{
									print("<td>Internal</td>");
								}
								else
								{
									print("<td>N/A</td>");
								}
								print("<td>" . htmlspecialchars($h["total_days"]) . "</td>");
								print("</tr>");
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="PublicationHistory">
			<h4 class="panel-title">
				<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePublicationHistory"
					aria-expanded="false" aria-controls="collapsePublicationHistory">
					<b>My Publication History <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
						<span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></b>
				</a>
			</h4>
		</div>
		<div id="collapsePublicationHistory" class="panel-collapse collapse" role="tabpanel" aria-labelledby="PublicationHistory">
			<div class="panel-body">
				<table class="conflist paginated" style="width: 100%">
					<thead>
						<tr>
							<th style="width: 6em;">
								Start Date
							</th>
							<th>
								Title
							</th>
							<th>
								Location
							</th>
							<th style="width: 3em;">
								Days
							</th>
							<th>
								Feedback
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($confhistory as $h)
							{
								print("<tr>");
								print("<td>" . htmlspecialchars($h["confdate"]) . "</td>");
								print("<td>" . htmlspecialchars($h["title"]) . "</td>");
								print("<td>" . htmlspecialchars($h["location"]) . "</td>");
								print("<td>" . htmlspecialchars($h["days"]) . "</td>");
								if ($h["attended"]==1)
								{
									print("<td ><a style=\"font-weight: bold; color: forestgreen;\" href=\"feedbackreview.php?id="
										. htmlspecialchars($h["req_id"]) . "&userid=" . htmlspecialchars($_SESSION["userid"]) 
										. "\">Review</a></td>");
								}
								else
								{
									print("<td>N/A</td>");
								}
								print("</tr>");
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal for adding new conference -->
<div class="modal fade" id="modalNewConf" tabindex="-1" role="dialog" aria-labelledby="modalNewConf">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Add New Conference Attended</h4>
		<p>To add an entry to your conference history please enter the following details:</p>
	</div>
	<form id="addConf" action="addconf.php" method="post">
		<div class="modal-body">
			<fieldset class="formfieldgroup">
				<legend>Conference Information</legend>
				<div class="form-group clearfix">
					<div class="col-md-3 text-left">
						<label>
							<b class="required">Start Date (Month+Year)</b>
							<input class="form-control" name="confdate" type="month" required="required" />
						</label>
					</div>
					<div class="col-md-9 text-left">
						<label>
							<b class="required">Conference Name</b>
							<input class="form-control autocomplete" name="title" id="newConfName" required="required" 
								placeholder="Paste here, or type keyword for autocomplete" type="text" maxlength="90"
								onfocus="setAutocompleteType('newConfName')" />
						</label>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<fieldset>
				<button class="btn btn-success" type="submit">Submit</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetForm('addConf')">Cancel</button>
			</fieldset>
		</div>
	</form>
  </div>
</div>

<script>
	function show_modalNewConf(){
		$( "#modalNewConf" ).modal( "toggle" );
	}
	
	function resetForm(id){
		$("#"+id).trigger("reset");
	}

	function setAutocompleteType(type){
		var autocompleteType = type;
	
		$( "input.autocomplete" ).autocomplete({
			source: "getautocomplete.php?type=" + autocompleteType,
			minLength: 2
		});
	}

	$(document).ready(function(){

		$('table.paginated').each(function() {
			var currentPage = 0;
			var numPerPage = 5;
			var $table = $(this);

			$table.bind('repaginate', function() {
				// Show all rows
				$table.find('tbody tr').show();
				// Hide rows on pages before current page
				$table.find('tbody tr:lt(' + currentPage * numPerPage + ')').hide();
				// Hide rows on pages after current page
				$table.find('tbody tr:gt(' + ((currentPage + 1) * numPerPage - 1) + ')').hide();
			});

			var numRows = $table.find('tbody tr').length;
			var numPages = Math.ceil(numRows / numPerPage);

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

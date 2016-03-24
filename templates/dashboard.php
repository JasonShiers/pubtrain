<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="ConferenceHistory">
			<h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseConferenceHistory" aria-expanded="true" aria-controls="collapseConferenceHistory">
					<b>My Conference History</b>
				</a>
			</h4>
		</div>
		<div id="collapseConferenceHistory" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="ConferenceHistory">
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
									print("<td ><a style=\"font-weight: bold; color: forestgreen;\" href=\"feedbackreview.php?id=" . htmlspecialchars($h["req_id"]) 
										. "&userid=" . htmlspecialchars($_SESSION["userid"]) . "\">Review</a></td>");
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
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="TrainingHistory">
			<h4 class="panel-title">
				<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTrainingHistory" aria-expanded="false" aria-controls="collapseTrainingHistory">
					<b>My Training History</b>
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
				<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePublicationHistory" aria-expanded="false" aria-controls="collapsePublicationHistory">
					<b>My Publication History</b>
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
									print("<td ><a style=\"font-weight: bold; color: forestgreen;\" href=\"feedbackreview.php?id=" . htmlspecialchars($h["req_id"]) 
										. "&userid=" . htmlspecialchars($_SESSION["userid"]) . "\">Review</a></td>");
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
<script>
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

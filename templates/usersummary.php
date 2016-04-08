<?php 
$ROWSPERPAGE = 5;
?>

<form action="linereportsummary.php" method="post" class="form-horizontal">
	<div class="form-group">
		<?php if ((isset($_GET["admin"]) && $_GET["admin"] == 1) || (isset($admin) && $admin == 1)): ?>
			<input type="hidden" name="admin" value="1" />
		<?php endif ?>
		
		<div class="col-md-4 col-md-offset-3">
			<label>
				<b>User:</b>&nbsp;
				<select name="userid" data-placeholder="Choose a user to view..." class="chosen-select" style="width: 75%;">
					<?php
						print("<option disabled selected value>Select an option</option>");
						foreach($linegroup as $line)
						{
							print("<option value=\"" . $line["userid"] . "\" ");
							if(isset($userid) && $line["userid"] == $userid)
							{
								print("selected=\"selected\" ");
							}
							print(">" . htmlspecialchars($line["firstname"] . " " . $line["lastname"]) 
								. "</option>\n");
						}
					?>							
				</select>
			</label>
		</div>
		<div class="col-md-1 text-center">
			<button class="btn btn-success" type="submit">Submit</button>
		</div>
	</div>
</form>
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
							<th class="right">
								Options
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$rownumber = -1;
							if(isset($confhistory))
							{
								foreach ($confhistory as $h)
								{
									print("<tr ");
									$rownumber++;
									if ($h["confirmed"] === 0)
									{
										print("style=\"color: darkgray\";");
									}
									print(">");
									print("<td>" . htmlspecialchars($h["confdate"]) . "</td>");
									print("<td>" . htmlspecialchars($h["title"]) . "</td>");
									print("<td>" . htmlspecialchars($h["location"]) . "</td>");
									print("<td>" . htmlspecialchars($h["days"]) . "</td>");
									if ($h["attended"]==1)
									{
										print("	<td >
													<div class=\"imgdiv\"><span class=\"glyphicon glyphicon-thumbs-up\" title=\"Confirmed by ConferenceTracker\" 
														style=\"color: darkblue;\" aria-hidden=\"true\"></span></div>
													&nbsp;
													<a class=\"btn btn-info btn-xs\" 
													href=\"//intranet/confdb/feedbackreview.php?id=" . htmlspecialchars($h["req_id"]) 
													. "&userid=" . htmlspecialchars($_SESSION["userid"]) . "\" target=\"_blank\">
														&nbsp;<span class=\"glyphicon glyphicon-list-alt\" title=\"Review Feedback\" 
														aria-hidden=\"true\"></span>&nbsp;</a>
												</td>");
									}
									else if ($h["editable"]==0)
									{
										print("	<td >
													<div class=\"imgdiv\"><span class=\"glyphicon glyphicon-question-sign\" title=\"Not Confirmed\" 
														aria-hidden=\"true\"></span></div>
													&nbsp;
												</td>");
									}
									if ($h["editable"]==1)
									{
										if($h["confirmed"] !== 0)
										{
											print("	<td>
														<div class=\"imgdiv\">
															<span class=\"glyphicon glyphicon-thumbs-up\" ");
											if ($h["confirmed"] == 1)
											{
												print("			title=\"Confirmed by user\" ");
											}
											else
											{
												print("			title=\"Entered by user\" ");
											}
											print("				style=\"color: forestgreen;\" aria-hidden=\"true\"></span>
														</div>");
										}
										else
										{
											print("	<td>&nbsp;<span class=\"glyphicon glyphicon-question-sign\" title=\"Not Confirmed\" 
														aria-hidden=\"true\"></span>&nbsp;");
										}
										print("</td>");
									}
									print("</tr>");
								}
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
							<th>
								Options
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$rownumber = -1;
							if(isset($trainhistory))
							{
								foreach ($trainhistory as $h)
								{
									print("<tr ");
									$rownumber++;
									if ($h["confirmed"] === 0)
									{
										print("style=\"color: darkgray\";");
									}
									print(">");
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
									if($h["confirmed"] !== 0)
									{
										print("	<td>
													<div class=\"imgdiv\">
														<span class=\"glyphicon glyphicon-thumbs-up\" ");
										if ($h["confirmed"] === 1)
										{
											print("			title=\"Confirmed by user\" ");
										}
										else
										{
											print("			title=\"Entered by user\" ");
										}
										print("				style=\"color: forestgreen;\" aria-hidden=\"true\"></span>
													</div>");
									}
									else
									{
										print("	<td>&nbsp;
											<div class=\"imgdiv\">
												<span class=\"glyphicon glyphicon-question-sign\" title=\"Not Confirmed\" 
													aria-hidden=\"true\"></span>
											</div>");
									}
									print("	&nbsp;
										<div class=\"imgdiv\"" . (($h["verified"] === 1)?"":" style=\"visibility: hidden;\"") . ">
											<span class=\"glyphicon glyphicon-ok-circle\" title=\"Verified\" 
												style=\"color: green;\" aria-hidden=\"true\">");
									print("</td></tr>");
								}
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
							<th class="left" style="width: 4em;">
								Year
							</th>
							<th>
								Publication reference
							</th>
							<th>
								Source
							</th>
							<th class="right">
								Options
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$rownumber = -1;
							if(isset($pubhistory))
							{
								foreach ($pubhistory as $h)
								{
									print("<tr ");
									$rownumber++;
									if ($h["confirmed"] === 0)
									{
										print("style=\"color: darkgray\";");
									}
									print(">");
									print("<td>" . htmlspecialchars($h["year"]) . "</td>");
									if ($h["journal"] == 1)
									{
										print("<td><i>" . htmlspecialchars($h["title"]) . "</i>");
										if($h["volume"] !== "0")
										{
											print(", <b>" . htmlspecialchars($h["volume"]) . "</b>");
										}
										if($h["issue"] !== "0")
										{
											print("(" . htmlspecialchars($h["issue"]) . ")");
										}
										print(", " . htmlspecialchars($h["startpage"]));
										if($h["endpage"] !== "0")
										{
											print("-" . htmlspecialchars($h["endpage"]));
										}
									}
									else
									{
										print("<td>" . htmlspecialchars($h["title"]));								
									}
									print("</td>");
									print("<td>" . htmlspecialchars($h["source"]) . "</td>");
									if($h["confirmed"] !== 0)
									{
										print("	<td>
													<div class=\"imgdiv\">
														<span class=\"glyphicon glyphicon-thumbs-up\" ");
										if ($h["confirmed"] === "1")
										{
											print("			title=\"Confirmed by user\" ");
										}
										else
										{
											print("			title=\"Entered by user\" ");
										}
										print("				style=\"color: forestgreen;\" aria-hidden=\"true\"></span>
													</div>");
									}
									else
									{
										print("	<td>&nbsp;<span class=\"glyphicon glyphicon-question-sign\" title=\"Not Confirmed\" 
													aria-hidden=\"true\"></span>&nbsp;");
									}
									print("</td></tr>");
								}
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

		<?php if ((isset($_GET["admin"]) && $_GET["admin"] ==1) || (isset($admin) && $admin == 1)): ?>
		// Make navAdmin navbar item selected
		$('#navAdmin').addClass("active");
		<?php else: ?>
		// Make navSummaries navbar item selected
		$('#navSummaries').addClass("active");
		<?php endif ?>
		
		// Initiate Multi-select box
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

<?php 
$ROWSPERPAGE = 5;
?>

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
		<div id="collapseConferenceHistory" class="panel-collapse collapse" role="tabpanel" aria-labelledby="ConferenceHistory">
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
												<div class=\"imgdiv\"><span class=\"glyphicon glyphicon-thumbs-up\" title=\"Approved in ConferenceTracker\" 
													style=\"color: darkgray;\" aria-hidden=\"true\"></span></div>
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
										if ($h["confirmed"] === 1)
										{
											print("			title=\"Confirmed by you\" ");
										}
										else
										{
											print("			title=\"Entered by you\" ");
										}
										print("				style=\"color: forestgreen;\" aria-hidden=\"true\"></span>
													</div>");
									}
									else
									{
										print("	<td><a class=\"btn btn-info btn-xs\" 
													href=\"modifyrecord.php?type=confirmConf&id=" . $h["id"] 
													. "&page=" . intval($rownumber/$ROWSPERPAGE+1) . "\">
													&nbsp;<span class=\"glyphicon glyphicon-question-sign\" title=\"Confirm I Attended\" 
													aria-hidden=\"true\"></span>&nbsp;</a>");
									}
									print("		&nbsp;
												<button type=\"button\" class=\"btn btn-danger btn-xs\" 
												onclick=\"bindModalButton('modalDelConf', " 
													. "'delConf' , " . $h["id"] . ", " . intval($rownumber/$ROWSPERPAGE+1) . ", '')\">
													&nbsp;<span class=\"glyphicon glyphicon-trash\" 
													title=\"Delete\" aria-hidden=\"true\"></span>&nbsp;
												</button></td>");
								}
								print("</tr>");
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan=5>
								<button type="button" class="btn btn-primary btn-xs" onclick="show_modal('modalNewConf', 0)">
									<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add
								</button>
								 conference not requested through ConferenceTracker.
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
							<th>
								Options
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$rownumber = -1;
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
										print("			title=\"Confirmed by you\" ");
									}
									else
									{
										print("			title=\"Entered by you\" ");
									}
									print("				style=\"color: forestgreen;\" aria-hidden=\"true\"></span>
												</div>");
								}
								else
								{
									print("	<td><a class=\"btn btn-info btn-xs\" 
												href=\"modifyrecord.php?type=confirmTrain&id=" . $h["id"] 
													. "&page=" . intval($rownumber/$ROWSPERPAGE+1) . "\">
												&nbsp;<span class=\"glyphicon glyphicon-question-sign\" title=\"Confirm I Attended\" 
												aria-hidden=\"true\"></span>&nbsp;</a>");
								}
								print("		&nbsp;
											<button type=\"button\" class=\"btn btn-danger btn-xs\" onclick=\"bindModalButton('modalDelTrain', " 
												. "'delTrain' , " . $h["id"] . ", " . intval($rownumber/$ROWSPERPAGE+1) 
												. ", '#collapseTrainingHistory')\">
												&nbsp;<span class=\"glyphicon glyphicon-trash\" 
												title=\"Delete\" aria-hidden=\"true\"></span>&nbsp;
											</button>");
								print("	&nbsp;
										<div class=\"imgdiv\"" . (($h["verified"] === 1)?"":" style=\"visibility: hidden;\"") . ">
											<span class=\"glyphicon glyphicon-ok-circle\" title=\"Verified\" 
												style=\"color: green;\" aria-hidden=\"true\">");
								print("</td></tr>");
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan=6>
								<button type="button" class="btn btn-primary btn-xs" onclick="show_modal('modalNewTrain', 0)">
									<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add
								</button>
							</th>
						</tr>
					</tfoot>
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
									if($h["volume"] !== 0)
									{
										print(", <b>" . htmlspecialchars($h["volume"]) . "</b>");
									}
									if($h["issue"] !== 0)
									{
										print("(" . htmlspecialchars($h["issue"]) . ")");
									}
									print(", " . htmlspecialchars($h["startpage"]));
									if($h["endpage"] !== 0)
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
									if ($h["confirmed"] === 1)
									{
										print("			title=\"Confirmed by you\" ");
									}
									else
									{
										print("			title=\"Entered by you\" ");
									}
									print("				style=\"color: forestgreen;\" aria-hidden=\"true\"></span>
												</div>");
								}
								else
								{
									print("	<td><a class=\"btn btn-info btn-xs\" 
												href=\"modifyrecord.php?type=confirmPub&id=" . $h["id"] 
												. "&page=" . intval($rownumber/$ROWSPERPAGE+1) . "\">
												&nbsp;<span class=\"glyphicon glyphicon-question-sign\" title=\"Confirm\" 
												aria-hidden=\"true\"></span>&nbsp;</a>");
								}
								print("		&nbsp;
											<button type=\"button\" class=\"btn btn-danger btn-xs\" 
												onclick=\"bindModalButton('modalDelPub', " 
												. "'delPub' , " . $h["id"] . ", " . intval($rownumber/$ROWSPERPAGE+1) . ", '')\">
												&nbsp;<span class=\"glyphicon glyphicon-trash\" 
												title=\"Delete\" aria-hidden=\"true\"></span>&nbsp;
											</button></td>");
								print("</tr>");
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan=4>
								<button type="button" class="btn btn-primary btn-xs" onclick="show_modal('modalNewPub', 0)">
									<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add
								</button>
							</th>
						</tr>
					</tfoot>
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
				<h4 class="modal-title" id="modalAddConfLabel">Add New Conference Attended</h4>
				<p>To add an entry to your conference history please enter the following details:</p>
			</div>
			<form id="addConf" action="modifyrecord.php?type=newConf" method="post">
				<div class="modal-body">
					<fieldset class="formfieldgroup">
						<legend>Conference Information</legend>
						<div class="form-group clearfix">
							<div class="col-md-3 text-left">
								<label style="max-width:50%; float: left;">
									<b class="required">Month</b>
									<select class="form-control" name="month" required="required">
										<option value selected disabled>Month</option>
										<option value="01">Jan</option>
										<option value="02">Feb</option>
										<option value="03">Mar</option>
										<option value="04">Apr</option>
										<option value="05">May</option>
										<option value="06">Jun</option>
										<option value="07">Jul</option>
										<option value="08">Aug</option>
										<option value="09">Sep</option>
										<option value="10">Oct</option>
										<option value="11">Nov</option>
										<option value="12">Dec</option>
									</select>
								</label>
								<label style="max-width:40%; float: left;">
									<b class="required">Year</b>
									<?php print("<input class=\"form-control\" name=\"year\" type=\"number\" min=\"1980\" max=\"2500\" 
										value=\"" . date('Y') . "\" required=\"required\" />"); ?>
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
						<div class="form-group clearfix">
							<div class="col-md-7 text-left">
								<label>
									<b class="required">Location</b>
									<input class="form-control autocomplete" name="location" id="newConfLocation" type="text" maxlength="60" 
										required="required" placeholder="e.g. Cambridge, UK" onfocus="setAutocompleteType('newConfLocation')" />
								</label>
							</div>
							<div class="col-md-3 text-left">
								<label>
									<b class="required">Duration (days)</b>
									<input class="form-control" required="required" name="days" type="number" min="0.5" max="10" step="0.5"/>
								</label>
							</div>
						</div>
						<div class="form-group clearfix">
							<label>
								<b>Other attendees on this conference:</b>
								<select name="otherusers[]" id="otherusersconf" data-placeholder="Other attendees..." 
									class="chosen-select" multiple style="width: 75%;">
									<?php
										foreach ($users as $user)
										{
											if($user["userid"] !== $_SESSION["userid"])
											{
												print("<option style=\"text-align: left;\" value=\"" . htmlspecialchars($user["userid"]) . "\">");
												print(htmlspecialchars($user["firstname"] . " " . $user["lastname"]) . "</option>\n");
											}
										}
									?>
								</select>
							</label>
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
</div>	

<!-- Modal for adding new training record -->
<div class="modal fade" id="modalNewTrain" tabindex="-1" role="dialog" aria-labelledby="modalNewTrain">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modalAddTrainLabel">Add New Training Record</h4>
				<p>To add an entry to your training history please enter the following details:</p>
			</div>
			<form id="addTrain" action="modifyrecord.php?type=newTrain" method="post">
				<div class="modal-body">
					<fieldset class="formfieldgroup">
						<legend>Training Record Information</legend>
						<div class="form-group clearfix">
							<p class="bg-warning">Please use this form to enter training records. For conferences 
								<button type="button" class="btn btn-warning btn-xs" data-dismiss="modal" onclick="show_modal('modalNewConf', 0)">click here</button>
							</p>
							<div class="col-md-3 text-left">
								<label style="max-width:50%; float: left;">
									<b class="required">Month</b>
									<select class="form-control" name="month" required="required">
										<option value selected disabled>Month</option>
										<option value="01">Jan</option>
										<option value="02">Feb</option>
										<option value="03">Mar</option>
										<option value="04">Apr</option>
										<option value="05">May</option>
										<option value="06">Jun</option>
										<option value="07">Jul</option>
										<option value="08">Aug</option>
										<option value="09">Sep</option>
										<option value="10">Oct</option>
										<option value="11">Nov</option>
										<option value="12">Dec</option>
									</select>
								</label>
								<label style="max-width:40%; float: left;">
									<b class="required">Year</b>
									<?php print("<input class=\"form-control\" name=\"year\" type=\"number\" min=\"1980\" max=\"2500\" 
										value=\"" . date('Y') . "\" required=\"required\" />"); ?>
								</label>
								<p class="text-muted">Training start date</p>
							</div>
							<div class="col-md-5 text-left">
								<label>
									<b class="required">Training Type</b>
									<select name="trainingid" data-placeholder="Select training type..." class="chosen-select" 
										required="required">
										<?php
											print("<option disabled selected value>Select an option</option>");
											foreach($traintypes as $traintype)
											{
												print("<option value=\"" . $traintype["trainingid"] . "\" ");
												print(">" . htmlspecialchars($traintype["type"]) . "</option>\n");
											}
										?>							
									</select>
								</label>
							</div>
						</div>
						<div class="form-group clearfix">
							<div class="col-md-7 text-left">
								<label>
									<b>Description (Optional)</b>
									<input class="form-control autocomplete" name="description" id="newTrainDesc" type="text" maxlength="60" 
										placeholder="e.g. Scientific Update Med Chem Course" onfocus="setAutocompleteType('newTrainDesc')" />
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
						<div class="form-group clearfix">
							<label>
								<b>Other attendees on this training:</b>
								<select name="otherusers[]" id="otheruserstrain" data-placeholder="Other attendees..." 
									class="chosen-select" multiple style="width: 75%;">
									<?php
										foreach ($users as $user)
										{
											if($user["userid"] !== $_SESSION["userid"])
											{
												print("<option style=\"text-align: left;\" value=\"" . htmlspecialchars($user["userid"]) . "\">");
												print(htmlspecialchars($user["firstname"] . " " . $user["lastname"]) . "</option>\n");
											}
										}
									?>
								</select>
							</label>
						</div>
					</fieldset>
				</div>
				<div class="modal-footer">
					<fieldset>
						<button class="btn btn-success" type="submit">Submit</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetForm('addTrain')">Cancel</button>
					</fieldset>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal for adding new publication record -->
<div class="modal fade" id="modalNewPub" tabindex="-1" role="dialog" aria-labelledby="modalNewPub">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modalAddPubLabel">Add New Publication Record</h4>
				<p>To add an entry to your publication history please enter the following details:</p>
			</div>
			<form id="addPub" action="modifyrecord.php?type=newPub" method="post">
				<div class="modal-body">
					<fieldset class="formfieldgroup">
						<legend>Publication Record Information</legend>
						<div class="form-group clearfix">
							<div class="col-md-3 text-left">
								<label>
									<b class="required">Year</b>
									<?php print("<input class=\"form-control\" name=\"year\" type=\"number\" min=\"1980\" max=\"2500\" 
										value=\"" . date('Y') . "\" required=\"required\" />"); ?>
								</label>
							</div>
							<div class="col-md-5 text-left">
								<label>
									<b class="required">Reference Title</b>
									<input class="form-control autocomplete" name="title" id="newPubDesc" type="text" maxlength="60" 
										minlength="8" required="required" onfocus="setAutocompleteType('newPubDesc')" />
								</label>
								<p class="text-muted">Patent Reference (e.g. WO/2005/123456) or 
									<a href="https://images.webofknowledge.com/WOK46/help/WOS/J_abrvjt.html" target="_blank">
									ISI abbreviated</a> Journal Title (e.g. J. Am. Chem. Soc.)
								</p>
							</div>
							<div class="col-md-4 text-left">
								<label>
									<b class="required">Source of work</b>
									<input class="form-control autocomplete" name="source" id="newPubSource" type="text" maxlength="32" 
										minlength="3" required="required" onfocus="setAutocompleteType('newPubSource')" />
								</label>
								<p class="text-muted">
									Enter name of client for project work, "Internal" for Sygnature internal research and "External" for 
									non-Sygnature work.
								</p>
							</div>
						</div>
						<div class="form-group clearfix text-center">
							<p><b>Additional information for Journals</b></p>
							<div class="col-md-3 col-md-offset-1 text-left">
								<label>
									<b class="required">Publication Type</b>
									<select name="journal" required="required">
										<option disabled selected value>Select option</option>
										<option value="1">Journal</option>
										<option value="0">Patent</option>
									</select>
								</label>
							</div>
							<div class="col-md-2 text-left">
								<label>
									<b>Volume</b>
									<input class="form-control" name="volume" type="text" maxlength="6" />
								</label>
							</div>
							<div class="col-md-2 text-left">
								<label>
									<b>(Issue)</b>
									<input class="form-control" name="issue" type="number" min="0" max="100" />
								</label>
							</div>
							<div class="col-md-2 text-left">
								<label>
									<b>Start Page</b>
									<input class="form-control" name="startpage" type="text" maxlength="16" />
								</label>
							</div>
							<div class="col-md-2 text-left">
								<label>
									<b>(End Page)</b>
									<input class="form-control" name="endpage" type="number" min="0" max="10000" />
								</label>
							</div>
						</div>
					</fieldset>
					<br />
					<fieldset class="formfieldgroup">
						<div class="form-group clearfix">
							<div class="col-md-10 col-md-offset-2 text-left">
								<label>
									<b>Other Sygnature authors/inventors:</b>
									<select name="otherusers[]" id="otheruserspub" data-placeholder="Other attendees..." 
										class="chosen-select" multiple style="width: 75%;">
										<?php
											foreach ($users as $user)
											{
												if($user["userid"] !== $_SESSION["userid"])
												{
													print("<option style=\"text-align: left;\" value=\"" . htmlspecialchars($user["userid"]) . "\">");
													print(htmlspecialchars($user["firstname"] . " " . $user["lastname"]) . "</option>\n");
												}
											}
										?>
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
			</form>
		</div>
	</div>
</div>

<!-- Modal for deleting conference -->
<div class="modal fade" id="modalDelConf" tabindex="-1" role="dialog" aria-labelledby="modalDelConf">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modalDelConfLabel">Delete Conference</h4>
			</div>
			<form id="delConf" method="post">
				<div class="modal-body">
				<p>Are you sure you want to delete this conference record?</p>
				</div>
				<div class="modal-footer">
					<fieldset>
						<button class="btn btn-success" type="submit">Continue</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetForm('delConf')">Cancel</button>
					</fieldset>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal for deleting training record -->
<div class="modal fade" id="modalDelTrain" tabindex="-1" role="dialog" aria-labelledby="modalDelTrain">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modalDelTrainLabel">Delete Training Record</h4>
			</div>
			<form id="delTrain" method="post">
				<div class="modal-body">
				<p>Are you sure you want to delete this training record?</p>
				</div>
				<div class="modal-footer">
					<fieldset>
						<button class="btn btn-success" type="submit">Continue</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetForm('delTrain')">Cancel</button>
					</fieldset>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal for deleting publication record -->
<div class="modal fade" id="modalDelPub" tabindex="-1" role="dialog" aria-labelledby="modalDelPub">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modalDelPubLabel">Delete Training Record</h4>
			</div>
			<form id="delPub" method="post">
				<div class="modal-body">
				<p>Are you sure you want to delete this publication record?</p>
				</div>
				<div class="modal-footer">
					<fieldset>
						<button class="btn btn-success" type="submit">Continue</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetForm('delPub')">Cancel</button>
					</fieldset>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	// function to show modal with specified id, triggered by button in HTML
	function show_modal(id){
		$( "#"+id ).modal( "toggle" );
		$(".chosen-container").width("75%");
	}
	
	// reset form with specified id, used when cancel button is pressed
	function resetForm(id){
		$("#"+id).trigger("reset");
	}

	// keep track of which input box has focus and return appropriate autocomplete results
	function setAutocompleteType(type){
		var autocompleteType = type;

		// set up autocomplete using appropriate type
		$( "input.autocomplete" ).autocomplete({
			source: "getautocomplete.php?type=" + autocompleteType,
			minLength: 2
		});
	}
	
	// Bind Continue button to appropriate action
	function bindModalButton(modal_id, form_id, record_id, return_page, return_modal){
		$( "#" + form_id ).attr("action", "modifyrecord.php?type=" + form_id + "&page=" 
			+ return_page + "&id=" + record_id + return_modal);
		show_modal(modal_id);
	}

	$(document).ready(function(){

		// Make navMyHistory navbar item selected
		$('#navMyHistory').addClass("active");
	
		// Open accordion based on URL 
		var url = document.location.toString();
		if ( url.match('#') ) {
			$('#'+url.split('#')[1]).addClass("in");
		}
		else
		{
			$("#collapseConferenceHistory").addClass("in");
		}
		
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
			

			// Go to specified page
			<?php if (isset($_GET["page"])): ?>
				// Check this is the correct table by finding it in the expanded accordion
				if ($(".in").find($table).attr("class") !== undefined)
				{
					// Set page according to URL and repaginate table
					currentPage = <?= $_GET["page"] - 1 ?>;
					$table.trigger('repaginate');
					
					// Set appropriate page as active in pager
					$activepage = $(this).parent().find(".page-number:contains(" + (currentPage+1) + ")")[0];
					$($activepage).addClass('active').siblings().removeClass('active');
				}
			<?php endif ?>
		});
	});
</script>

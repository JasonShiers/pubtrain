<?php
	if (!isset($startyear)) $startyear = "1900";
	if (!isset($endyear)) $endyear = date("Y");
	$ROWSPERPAGE = 10;
?>

<form action="publicationsummary.php" method="post" class="form-horizontal">
	<div class="form-group">
		<?php if ((isset($_GET["admin"]) && $_GET["admin"] == 1) || (isset($admin) && $admin == 1)): ?>
			<input type="hidden" name="admin" value="1" />
		<?php endif ?>
		
		<div class="col-md-4 col-md-offset-1 text-left">
			<label>
				<b>Filter (Optional)</b>
				<input class="form-control autocomplete" name="title" id="newPubFilt2" type="text" maxlength="60" 
				placeholder="e.g. J. Med. Chem." onfocus="setAutocompleteType('newPubFilt', 'title')" 
				<?= (isset($pubtitle) && strlen($pubtitle)>0)?"value=\"" . $pubtitle . "\" ":"" ?> />
			</label>
		</div>
		<div class="col-md-2 text-left">
			<label>
				<b class="required">Start Year</b>
				<input class="form-control" name="startyear" type="number" required="required" value="<?= $startyear ?>" 
					min="1900" max="<?= date("Y") ?>" />
			</label>
			<label>
				<b class="required">End Year</b>
				<input class="form-control" name="endyear" type="number" required="required" value="<?= $endyear ?>"
					min="1900" max="<?= date("Y") ?>" />
			</label>
		</div>
		<div class="col-md-3 text-left">
			<div>
				<b>Publication Types</b><br />
				<label style="float: left;"><input type="checkbox" name="patents" value="patents" 
					<?php if((isset($patents) && $patents !== FALSE) || !isset($patents)): ?>
						checked="checked" 
					<?php endif ?>
					/>Patents&nbsp;&nbsp;
				</label>
				<label style="float: left;"><input type="checkbox" name="journals" value="journals" 
					<?php if((isset($journals) && $journals !== FALSE) || !isset($journals)): ?>
						checked="checked" 
					<?php endif ?>
					/>Journals&nbsp;&nbsp;
				</label>
			</div>
			<div style="clear: both;">
				<b>Sources</b><br />
				<label style="float: left;"><input type="checkbox" name="clients" value="clients" 
					<?php if((isset($clients) && $clients !== FALSE) || !isset($clients)): ?>
						checked="checked" 
					<?php endif ?>
					/>Clients&nbsp;&nbsp;
				</label>
				<label style="float: left;"><input type="checkbox" name="internal" value="internal" 
					<?php if((isset($internal) && $internal !== FALSE) || !isset($internal)): ?>
						checked="checked" 
					<?php endif ?>
					/>Internal&nbsp;&nbsp;
				</label>
				<label style="float: left;"><input type="checkbox" name="external" value="external" 
					<?php if((isset($external) && $external !== FALSE) || !isset($external)): ?>
						checked="checked" 
					<?php endif ?>
					/>External&nbsp;&nbsp;
				</label>
			</div>
		</div>
		<div class="col-md-1 text-center">
			<br />
			<button class="btn btn-info" type="submit">Submit</button>
		</div>
	</div>
</form>

<?php if (isset($publications) && isset($publications[0]["title"])): ?>
	<div>
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
					<th>
						Authors
					</th>
					<th class="right">
						Options
					</th>
				</tr>
			</thead>
			<tbody>
				<?php $rownumber = -1; ?>
				<?php foreach ($publications as $h): ?>
					<tr>
						<?php $rownumber++; ?>
						<td><?= htmlspecialchars($h["year"]) ?></td>
						<td>
							<?php if ($h["journal"] == 1): ?>
								<i><?= htmlspecialchars($h["title"] . ',') ?></i>
								<?php if($h["volume"] !== NULL): ?>
									<b><?= htmlspecialchars($h["volume"] . ',') ?></b>
								<?php else: ?>
									<b><?= htmlspecialchars($h["year"] . ',') ?></b>
								<?php endif ?>
								<?php if($h["issue"] !== NULL): ?>
									<?= htmlspecialchars('(' . $h["issue"] . '),') ?>
								<?php endif ?>
								<?= htmlspecialchars($h["startpage"])?>
								<?php if($h["endpage"] !== NULL): ?>
									- <?= htmlspecialchars($h["endpage"])?>
								<?php endif ?>
							<?php else: ?>
								<?= htmlspecialchars($h["title"]) ?>
							<?php endif ?>
						</td>
						<td><?= htmlspecialchars($h["source"]) ?></td>
						<td><?= htmlspecialchars($h["userlist"]) ?></td>
						<td>
							<button type='button' class='btn btn-warning btn-xs' 
								onclick='setupModalDetails("modalEditPub", <?= json_encode($h) ?>)'>
									&nbsp;
									<span class="glyphicon glyphicon-pencil" title="Edit" aria-hidden="true"></span>
									&nbsp;
							</button>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan=5>
						<button type="button" class="btn btn-primary btn-xs" onclick="show_modal('modalNewPub', 0)">
							<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add
						</button>
						 <button type="button" class="btn btn-default btn-xs" onclick="show_modal('modalExportPub')">
							<span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Export
						</button>
					</th>
				</tr>
			</tfoot>
		</table>
	</div>
	
	<!-- Modal for adding new publication -->
	<div class="modal fade" id="modalNewPub" tabindex="-1" role="dialog" aria-labelledby="modalNewPub">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modalAddPubLabel">Add New Publication</h4>
					<p>To add a new publication please enter the following details:</p>
				</div>
				<form id="addPub" action="modifyrecord.php?type=newPub&3rdParty=1" method="post">
					<div class="modal-body">
						<fieldset class="formfieldgroup">
							<legend>Publication Record Information</legend>
							<div class="form-group clearfix">
								<div class="col-md-3 text-left">
									<label>
										<b class="required">Year</b>
										<input class="form-control" name="year" type="number" min="1980" max="2500" 
											value="<?= date('Y') ?>" required="required" />
									</label>
								</div>
								<div class="col-md-5 text-left">
									<label>
										<b class="required">Reference Title</b>
										<input class="form-control autocomplete" name="title" id="newPubDesc" type="text" maxlength="60" 
											minlength="8" required="required" onfocus="setAutocompleteType('newPubDesc', 0, 1)" />
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
											minlength="3" required="required" onfocus="setAutocompleteType('newPubSource', 0, 1)" />
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
										<b>Sygnature authors/inventors:</b>
										<select name="otherusers[]" id="otheruserspub" data-placeholder="Other attendees... type here to filter list" 
											class="chosen-select" multiple style="width: 75%;">
											<?php enumerateselectusers($users, "", true); ?>
										</select>
										<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
									</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="modal-footer">
						<fieldset>
							<button class="btn btn-success" type="submit">Submit</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetForm('addPub')">Cancel</button>
						</fieldset>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Modal for editing publication -->
	<div class="modal fade" id="modalEditPub" tabindex="-1" role="dialog" aria-labelledby="modalEditPub">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modalEditPubLabel">Edit Publication Authors</h4>
					<p>To change the authors or delete the publication record for all authors, please use the following form:</p>
				</div>
				<form id="editPub" action="modifyrecord.php?type=editPub" method="post">
					<div class="modal-body">
						<fieldset class="formfieldgroup">
						<legend>Publication Record Information</legend>
						<div class="form-group clearfix">
							<div class="col-md-3 text-left">
								<b class="label-static">Year</b>
								<p class="form-control-static" id="editPubYear"></p>
							</div>
							<div class="col-md-5 text-left">
								<b class="label-static">Reference Title</b>
								<p class="form-control-static" id="editPubTitle"></p>
							</div>
							<div class="col-md-4 text-left">
								<b class="label-static">Source of work</b>
								<p class="form-control-static" id="editPubSource"></p>
							</div>
						</div>
						<div class="form-group clearfix text-center">
							<p><b>Additional information for Journals</b></p>
							<div class="col-md-3 text-left">
								<b class="label-static">Publication Type</b>
								<p class="form-control-static" id="editPubType"></p>
							</div>
							<div class="col-md-2 text-left">
								<b class="label-static">Volume</b>
								<p class="form-control-static" id="editPubVolume"></p>
							</div>
							<div class="col-md-2 text-left">
								<b class="label-static">(Issue)</b>
								<p class="form-control-static" id="editPubIssue"></p>
							</div>
							<div class="col-md-2 text-left">
								<b class="label-static">Start Page</b>
								<p class="form-control-static" id="editPubStartPage"></p>
							</div>
							<div class="col-md-2 text-left">
								<b class="label-static">(End Page)</b>
								<p class="form-control-static" id="editPubEndPage"></p>
							</div>
						</div>
						</fieldset>
						<br />
						<fieldset class="formfieldgroup">
							<div class="form-group clearfix">
								<div class="col-md-10 col-md-offset-2 text-left">
									<label>
										<b>Sygnature authors/inventors to add:</b>
										<select name="addusers[]" id="adduserspub" data-placeholder="Add attendees... type here to filter list" 
											class="chosen-select" multiple style="width: 75%;">
											<?php enumerateselectusers($users, "", true); ?>
										</select>
										<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
									</label>
								</div>
							</div>
							<div class="form-group clearfix">
								<div class="col-md-10 col-md-offset-2 text-left">
									<label>
										<b>Sygnature authors/inventors to delete:</b>
										<select name="deleteusers[]" id="deleteuserspub" data-placeholder="Delete attendees... type here to filter list" 
											class="chosen-select" multiple style="width: 75%;">
										</select>
										<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
									</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="modal-footer">
						<fieldset>
							<button class="btn btn-success" type="submit">Submit</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetForm('editPub')">Cancel</button>
						</fieldset>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php elseif (isset($success)): ?>
	<div>
		<div class="alert alert-success" role="alert">Publication record added successfully</div>
	</div>
<?php endif ?>

<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

	// function to show modal with specified id, triggered by button in HTML
	function show_modal(id){
		$( "#"+id ).modal( "toggle" );
		$('.chosen-select', this).chosen();
		$(".chosen-container").width("75%");
	}
	
	// reset form with specified id, used when cancel button is pressed
	function resetForm(id){
		$("#"+id).trigger("reset");
	}

	/* Populate the details in the edit publication modal
	 * modal_id is the id of the modal to be targeted and popped up 
	 * publication is the JSON encoded publication record */
	function setupModalDetails(modal_id, publication){
		$('#editPubYear').empty();
		$('#editPubYear').append(publication.year);
		$('#editPubTitle').empty();
		$('#editPubTitle').append(publication.title);
		$('#editPubSource').empty();
		$('#editPubSource').append(publication.source);
		$('#editPubType').empty();
		$('#editPubType').append((publication.journal==1)?'Journal':'Patent');
		$('#editPubVolume').empty();
		$('#editPubVolume').append(publication.volume);
		$('#editPubIssue').empty();
		$('#editPubIssue').append(publication.issue);
		$('#editPubStartPage').empty();
		$('#editPubStartPage').append(publication.startpage);
		$('#editPubEndPage').empty();
		$('#editPubEndPage').append(publication.endpage);
		$('#deleteuserspub').empty();
		var idlist = publication.idlist.split(', ');
		var userlist = publication.userlist.split(', ');
		for (i=0; i<idlist.length; i++){
			$('#deleteuserspub').append('<option value="' + idlist[i] + '">' + userlist[i] + '</option>');
		}
		$('#deleteuserspub').trigger("chosen:updated");
		show_modal(modal_id);
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

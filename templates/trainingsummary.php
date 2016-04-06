<?php
	if (!isset($startdate)) $startdate = "2005-01-01";
	if (!isset($enddate)) $enddate = date("Y-m-d");
	$ROWSPERPAGE = 5;
?>

<form action="trainingsummary.php" method="post" class="form-horizontal">
	<div class="form-group">
		<?php if ((isset($_GET["admin"]) && $_GET["admin"] == 1) || (isset($admin) && $admin == 1)): ?>
			<input type="hidden" name="admin" value="1" />
		<?php endif ?>
		
		<div class="col-md-4 col-md-offset-1 text-left">
			<label>
				<b class="required">Training type</b>
				<select name="trainingid" data-placeholder="Choose a training type..." class="chosen-select" style="width: 75%;">
					<?php
						print("<option disabled selected value>Select an option</option>");
						foreach($trainingopts as $opt)
						{
							print("<option value=\"" . $opt["trainingid"] . "\" ");
							if(isset($trainingid) && $opt["trainingid"] == $trainingid)
							{
								print("selected=\"selected\" ");
							}
							print(">" . htmlspecialchars($opt["type"]) 
								. "</option>\n");
						}
					?>							
				</select>
			</label>
		</div>
		<div class="col-md-2 text-left">
			<label>
				<b class="required">Start Date</b>
				<input class="form-control" name="startdate" type="date" required="required" value="<?= $startdate ?>" 
					min="2005-01-01" max="<?= date("Y-m-d") ?>" />
			</label>
		</div>
		<div class="col-md-2 text-left">
			<label>
				<b class="required">End Date</b>
				<input class="form-control" name="enddate" type="date" required="required" value="<?= $enddate ?>"
					min="2005-01-01" max="<?= date("Y-m-d") ?>" />
			</label>
		</div>
		<div class="col-md-1 text-center">
			<br />
			<button class="btn btn-success" type="submit">Submit</button>
		</div>
	</div>
</form>
<?php if (isset($verified)): ?>
	<div class="form-group">
		<div class="col-md-4">
			<table class="conflist paginated" style="width: 100%;">
				<thead>
					<th class="left right" style="width: 6em;">
						Verified Users
					</th>
				</thead>
				<tbody>
					<?php
						foreach ($verified as $user)
						{
							print("<tr>
										<td>" . htmlspecialchars($user["firstname"]) . " " 
											. htmlspecialchars($user["lastname"]) . "</td>
									</tr>");
						}
					?>
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			<form action="trainingverify.php" method="post">
				<table class="conflist paginated" style="width: 100%;">
					<thead>
						<th class="left right">
							Unverified Users
						</th>
					</thead>
					<tbody>
						<?php
							foreach ($unverified as $user)
							{
								print("<tr>
											<td><div class=\"button_check\"><label for=\"" . htmlspecialchars($user["userid"]) 
												. "\"><input type=\"checkbox\" id=\"" . htmlspecialchars($user["userid"]) . "\" value=\""
												. htmlspecialchars($user["userid"]) . "\" name=\"verify_users[]\" /><span>" . htmlspecialchars($user["firstname"]) . " " . htmlspecialchars($user["lastname"]) 
												. "</span></label></div></td>
										</tr>");
							}
						?>
					</tbody>
				</table>
				<button class="btn btn-success" type="submit">Verify Selected</button>
			</form>
		</div>
	</div>
<?php endif ?>

<script>	
	// Initiate checkbox styling
	/*$(function() {
		$('.buttonset').buttonset();
	});*/

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

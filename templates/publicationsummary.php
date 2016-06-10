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
				<?= (isset($pubtitle) && strlen($pubtitle)>0)?"value=\"" . $title . "\" ":"" ?> />
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

<?php dump($publications); ?>

<?php if (isset($publications)): ?>
<!-- Publication table here -->
<?php endif ?>

<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

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

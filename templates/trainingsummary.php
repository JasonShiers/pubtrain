<?php
	if (!isset($startdate)) $startdate = "2005-01-01";
	if (!isset($enddate)) $enddate = date("Y-m-d");
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


<script>
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
	});
</script>

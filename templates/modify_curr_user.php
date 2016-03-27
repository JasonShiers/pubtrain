<?php if (!(isset($_SESSION["department"]) && isset($_SESSION["linemgr"]))): ?>
	<div class="alert alert-danger" role="alert">To continue using the site, you must first set your line manager and department.</div>
<?php endif ?>
<form action="userinfo.php" method="post" class="form-horizontal">

	<?php print("<input type=\"hidden\" name=\"userid\" value=\"" 
		. htmlspecialchars($userinfo[0]["userid"]) . "\" />\n"); ?>

	<div class="col-md-4 col-md-offset-4 text-left">
		<fieldset class="formfieldgroup">
			<div class="form-group">
				<div class="col-md-12">
					<p class="label-static">First Name</p>
					<p class="form-control-static">
							<?php
								print(htmlspecialchars($userinfo[0]["firstname"]));
							?>
					</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<p class="label-static">Last Name</p>
					<p class="form-control-static">
						<?php
							print(htmlspecialchars($userinfo[0]["lastname"]));
						?>
					</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<p class="label-static">Email</p>
					<p class="form-control-static">
						<?php
							print(htmlspecialchars($userinfo[0]["email"]));
						?>
					</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label>
						<b>Line Manager</b>
						<select name="linemgr" data-placeholder="Choose a line manager..." class="chosen-select">
							<?php
								print("<option disabled selected value>Select an option</option>");
								foreach($linemgrs as $linemgr)
								{
									if($linemgr["userid"] !== $userinfo[0]["userid"])
									{
										print("<option value=\"" . $linemgr["userid"] . "\" ");
										if($linemgr["userid"] == $userinfo[0]["linemgr"])
										{
											print("selected=\"selected\" ");
										}
										print(">" . htmlspecialchars($linemgr["firstname"] . " " . $linemgr["lastname"]) 
											. "</option>\n");
									}
								}
							?>							
						</select>
					</label>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label>
						<b>Department</b>
						<select name="department" required>
							<?php
								if (isset($userinfo[0]["department"]))
								{
									print("<option value=\"" . htmlspecialchars($userinfo[0]["department"]) 
										. "\" selected=\"selected\">" 
										. htmlspecialchars($userinfo[0]["department"]) . "</option>");
								}
								else
								{
									print("<option disabled selected value>Select an option</option>");
								}
								foreach($departments as $department)
								{
									if(!isset($userinfo[0]["department"]) || $department["department"] !== $userinfo[0]["department"])
									{
										print("<option value=\"" . $department["department"] . "\">"
											. htmlspecialchars($department["department"]) 
											. "</option>\n");
									}
								}
							?>							
						</select>
					</label>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<div class="col-xs-6 text-center">
				<button class="btn btn-success" type="submit">Submit</button>
			</div>
			<div class="col-xs-6 text-center">
				<a href="javascript:history.back()"><img alt="back" src="img/back.png" /></a>
			</div>
		</fieldset>
	</div>
</form>

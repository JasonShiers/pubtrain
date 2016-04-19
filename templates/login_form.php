<form action="login.php" method="post">
	<fieldset>
		<h3>To continue, please log in</h3> 
		<img alt="Login" src="img/login.png" />
		<?php if (isset($failed) && $failed == 1): ?>
			<div class="alert alert-danger" role="alert">Username/password is incorrect. Please try again.</div>
		<?php endif?>
		<div class="form-group">
			<div class="text-center">
				<input autofocus class="form-control" name="username" placeholder="Username" type="text" style="display: inline; width: 10em;" />
				<span class="emailsign">@sygnaturediscovery.com</span>
			</div>
			<div class="text-center">
				<input class="form-control" name="password" placeholder="Password" type="password" style="display: inline; width: 25.5em;" />
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-4 col-xs-offset-4">
				<button type="submit" class="btn btn-default">Log In</button>
			</div>
		</div>
	</fieldset>
</form>

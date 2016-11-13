<form role="form" action="login.php" method="post" class="form-horizontal">
    <fieldset>
        <input type="hidden" name="token" value="<?= Token::generate() ?>" />
        <h3>To continue, please log in</h3>
        <img alt="Login" src="img/login.png" />
        <?php if (isset($failed) && $failed == 1): ?>
            <div class="alert alert-danger" role="alert">Username/password is incorrect. Please try again.</div>
        <?php endif ?>
        <div class="form-group">
            <?php if (Input::get("next", FALSE) !== FALSE): ?>
                <input name="next" type="hidden" value="<?= Input::get("next") ?>" />
            <?php endif ?>

            <div class="row">
                <label for="username" class="control-label col-xs-3 col-md-4">Username</label>
                <div class="col-xs-8 col-md-4">
                    <div class="input-group">
                        <input autofocus class="form-control" id="username" name="username" placeholder="Username" type="text" />
                        <span class="input-group-addon">@sygnaturediscovery.com</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <label for="password" class="control-label col-xs-3 col-md-4">Password</label>
                <div class="col-xs-8 col-md-4">
                    <div>
                        <input class="form-control" id="password" name="password" placeholder="Password" type="password" 
                            <?php if (isset($failed) && $failed == 1): ?>
                                aria-describedby="passwordHelp" 
                            <?php endif ?>
                                />
                    </div>
                    <?php if (isset($failed) && $failed == 1): ?>
                        <span id="passwordHelp" class="help-block text-left">
                            Your password is the same as you use to log in to your email account.
                            If your password is not being accepted, it may have recently expired.
                            Please change it by logging in to a PC.
                        </span>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-4 col-xs-offset-4">
                <button type="submit" class="btn btn-default">Log In</button>
            </div>
        </div>
    </fieldset>
</form>

            </div>
        </div>
        <script type="text/javascript">
            var config = {
                '.chosen-select'            : {},
                '.chosen-select-deselect'   : {allow_single_deselect:true},
                '.chosen-select-no-single'  : {disable_search_threshold:10},
                '.chosen-select-no-results' : {no_results_text:'Oops, nothing found!'},
                '.chosen-select-width'      : {width:"95%"}
            }
            for (var selector in config) {
                    $(selector).chosen(config[selector]);
            }
        </script>
        <?php if (Session::exists("timestamp") && 
            !in_array($_SERVER["PHP_SELF"], ["/pubtrain/login.php", "/pubtrain/logout.php"])): ?>
        
            <script src="js/bootstrap-session-timeout.min.js"></script>
            
            <script type="text/javascript">
                $.sessionTimeout({
                    message: 'Your session will be locked in one minute.',
                    keepAliveUrl: 'keep-alive.php',
                    keepAliveInterval: 60000,
                    logoutUrl: 'logout.php',
                    redirUrl: 'logout.php',
                    warnAfter: 540000,
                    redirAfter: 600000
                });
            </script>
        <?php endif ?>
    </body>
</html>

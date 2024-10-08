<?php
$ROWSPERPAGE = 5;

$token = Token::generate();

$successcode = Input::get("success", NULL);
?>

<?php if ($successcode !== NULL): ?>
    <div>
        <?php if ($successcode == 0): ?>
            <div class="alert alert-success" role="alert">
                Record added/amended successfully
            </div>
        <?php endif ?>
        <?php if ($successcode & 1): ?>
            <div class="alert alert-danger" role="alert">
                Could not insert record into database
            </div>
        <?php endif ?>
        <?php if ($_GET["success"] & 2): ?>
            <div class="alert alert-danger" role="alert">
                Could not insert record into database for one or more authors
            </div>
        <?php endif ?>
        <?php if ($successcode & 4): ?>
            <div class="alert alert-danger" role="alert">
                Could not delete entry
            </div>
        <?php endif ?>
        <?php if ($successcode & 8): ?>
            <div class="alert alert-danger" role="alert">
                You do not have permission to do this
            </div>
        <?php endif ?>
        <?php if ($successcode & 16): ?>
            <div class="alert alert-danger" role="alert">
                Could not verify one or more records
            </div>
        <?php endif ?>
        <?php if ($successcode & 32): ?>
            <div class="alert alert-danger" role="alert">
                Could not confirm this record
            </div>
        <?php endif ?>
        <?php if ($successcode & 64): ?>
            <div class="alert alert-danger" role="alert">
                Required form field was missing
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<div class="panel-group" id="accordion" role="tablist">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="ConferenceHistory">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" 
                   href="#collapseConferenceHistory" 
                   aria-controls="collapseConferenceHistory">
                    <b>
                        My Conference History 
                        <span class="glyphicon glyphicon-chevron-down" 
                              aria-hidden="true"></span>
                        <span class="glyphicon glyphicon-chevron-up" 
                              aria-hidden="true"></span>
                    </b>
                </a>
            </h4>
        </div>
        <div id="collapseConferenceHistory" class="panel-collapse collapse" 
             role="tabpanel" aria-labelledby="ConferenceHistory">
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
                        <?php require 'tablebody/confhistory.php'; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan=5>
                                <button type="button" class="btn btn-primary btn-xs" 
                                        onclick="show_modal('modalNewConf')">
                                    <span class="glyphicon glyphicon-plus-sign" 
                                          aria-hidden="true"></span> Add
                                </button>
                                conference not requested through ConferenceTracker.
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-default btn-xs" 
                                        onclick="show_modal('modalExportConf')">
                                    <span class="glyphicon glyphicon-save-file" 
                                          aria-hidden="true"></span> Export
                                </button>
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
                <a class="collapsed" role="button" data-toggle="collapse" 
                   data-parent="#accordion" href="#collapseTrainingHistory"
                   aria-controls="collapseTrainingHistory">
                    <b>My Training History 
                        <span class="glyphicon glyphicon-chevron-down" aria-hidden="true">
                        </span>
                        <span class="glyphicon glyphicon-chevron-up" aria-hidden="true">
                        </span>
                    </b>
                </a>
            </h4>
        </div>
        <div id="collapseTrainingHistory" class="panel-collapse collapse" 
             role="tabpanel" aria-labelledby="TrainingHistory">
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
                        <?php require 'tablebody/trainhistory.php'; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan=6>
                                <button type="button" class="btn btn-primary btn-xs" 
                                        onclick="show_modal('modalNewTrain', 0)">
                                    <span class="glyphicon glyphicon-plus-sign" 
                                          aria-hidden="true"></span> Add
                                </button>
                                <button type="button" class="btn btn-default btn-xs" 
                                        onclick="show_modal('modalExportTrain')">
                                    <span class="glyphicon glyphicon-save-file" 
                                          aria-hidden="true"></span> Export
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
                <a class="collapsed" role="button" data-toggle="collapse" 
                   data-parent="#accordion" href="#collapsePublicationHistory"
                   aria-controls="collapsePublicationHistory">
                    <b>My Publication History 
                        <span class="glyphicon glyphicon-chevron-down" 
                              aria-hidden="true"></span>
                        <span class="glyphicon glyphicon-chevron-up" 
                              aria-hidden="true"></span></b>
                </a>
            </h4>
        </div>
        <div id="collapsePublicationHistory" class="panel-collapse collapse" 
             role="tabpanel" aria-labelledby="PublicationHistory">
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
                        <?php require 'tablebody/pubhistory.php'; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan=4>
                                <button type="button" class="btn btn-primary btn-xs" 
                                        onclick="show_modal('modalNewPub', 0)">
                                    <span class="glyphicon glyphicon-plus-sign" 
                                          aria-hidden="true"></span> Add
                                </button>
                                <button type="button" 
                                        class="btn btn-default btn-xs" 
                                        onclick="show_modal('modalExportPub')">
                                    <span class="glyphicon glyphicon-save-file" 
                                          aria-hidden="true"></span> Export
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
                <input type="hidden" name="token" value="<?= $token ?>" />
                <div class="modal-body">
                    <fieldset class="formfieldgroup">
                        <legend>Conference Information</legend>
                        <div class="form-group clearfix">
                            <div class="col-md-3 text-left">
                                <label style="max-width:50%; float: left;">
                                    <b class="required">Month</b>
                                    <select class="form-control" name="month" 
                                            required="required">
                                        <?php enumeratemonthoptions(); ?>
                                    </select>
                                </label>
                                <label style="max-width:40%; float: left;">
                                    <b class="required">Year</b>
                                    <input class="form-control" name="year" 
                                           type="number" min="1980" max="2500" 
                                           <?= "value=\"" . date('Y') . "\"" ?> 
                                           required="required" />
                                </label>
                            </div>
                            <div class="col-md-9 text-left">
                                <label>
                                    <b class="required">Conference Name</b>
                                    <input class="form-control autocomplete" 
                                           name="title" id="newConfName" 
                                           required="required" 
                                           placeholder="Paste here, or type keyword for autocomplete" 
                                           type="text" pattern=".{,90}" 
                                           title="No more than 90 characters" 
                                           onfocus="setAutocompleteType('newConfName', 0, 2)" />
                                </label>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-md-7 text-left">
                                <label>
                                    <b class="required">Location</b>
                                    <input class="form-control autocomplete" 
                                           name="location" id="newConfLocation" 
                                           type="text" pattern=".{4,60}" 
                                           title="Between 4 and 60 characters" 
                                           required="required" 
                                           placeholder="e.g. Cambridge, UK" 
                                           onfocus="setAutocompleteType('newConfLocation', 0, 2)" />
                                </label>
                            </div>
                            <div class="col-md-3 text-left">
                                <label>
                                    <b class="required">Duration (days)</b>
                                    <input class="form-control" required="required" 
                                           name="days" type="number" min="0.5" 
                                           max="10" step="0.5"/>
                                </label>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label>
                                <b>Other attendees on this conference:</b>
                                <select name="otherusers[]" id="otherusersconf" 
                                        data-placeholder="Other attendees...type here to filter list" 
                                        class="chosen-select" multiple style="width: 75%;">
                                    <?php enumerateselectusers($users, ""); ?>
                                </select>
                                <span class="glyphicon glyphicon-search" 
                                      aria-hidden="true"></span>
                            </label>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <fieldset>
                        <button class="btn btn-success" type="submit">Submit</button>
                        <button type="button" class="btn btn-danger" 
                                data-dismiss="modal" onclick="resetForm('addConf')">
                            Cancel
                        </button>
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
                <input type="hidden" name="token" value="<?= $token ?>" />
                <div class="modal-body">
                    <fieldset class="formfieldgroup">
                        <legend>Training Record Information</legend>
                        <div class="form-group clearfix">
                            <p class="bg-warning">Please use this form to enter 
                                training records. For conferences 
                                <button type="button" 
                                        class="btn btn-warning btn-xs" 
                                        data-dismiss="modal" 
                                        onclick="show_modal('modalNewConf', 0)">
                                    click here
                                </button>
                            </p>
                            <div class="col-md-3 text-left">
                                <label style="max-width:50%; float: left;">
                                    <b class="required">Month</b>
                                    <select class="form-control" name="month" 
                                            required="required">
                                        <?php enumeratemonthoptions(); ?>
                                    </select>
                                </label>
                                <label style="max-width:40%; float: left;">
                                    <b class="required">Year</b>
                                    <?php print("<input class=\"form-control\" 
                                        name=\"year\" type=\"number\" min=\"1980\" 
                                        max=\"2500\" value=\"" . date('Y') . 
                                        "\" required=\"required\" />"); ?>
                                </label>
                                <p class="text-muted">Training start date</p>
                            </div>
                            <div class="col-md-5 text-left">
                                <label>
                                    <b class="required">Training Type</b>
                                    <select id="trainingid" name="id" 
                                            data-placeholder="Select training type..." 
                                            class="chosen-select" 
                                            required="required">
                                        <option disabled selected value>Select an option</option>
                                        <?php foreach ($traintypes as $traintype): ?>
                                            <option value="<?= $traintype["trainingid"] ?>">
                                                <?= escapeHTML($traintype["type"]) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-md-7 text-left">
                                <label for="trainingdesc" style="display: inline;">
                                    <b>Description (Optional) </b>
                                </label>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-xs btn-info" 
                                        data-toggle="popover" 
                                        title="Using the Description field" 
                                        data-placement="bottom" 
                                        data-content="An optional field used to 
                                        differentiate between activities of the 
                                        same type. Examples:<br /><br />
                                        <ul><li>General training types (e.g. 
                                        'Medicinal Chemistry', 'Managing People') 
                                        cover a number of different courses, so 
                                        please include the name of the course.
                                        </li><li>Sign-off records (e.g. 'Health 
                                        & Safety Review of COPs/BOPs') include 
                                        the title of the provided in the 
                                        sign-off sheet.</li></ul><br />
                                        Not needed for specific training 
                                        activities (e.g. equipment training and 
                                        SOPs)">
                                    (What is this?)
                                </button>
                                <b class="required">Training Description</b>
                                <select name="desc" id="trainingdesc" required 
                                        data-placeholder="Choose a training type..." 
                                        style="width: 75%;">
                                    <option disabled selected value>Select an option</option>
                                </select>
                            </div>
                            <div class="col-md-3 text-left">
                                <label for="newTrainDays" style="display: inline;">
                                    <b class="required">Total Duration (days)</b>
                                </label>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-xs btn-info" 
                                        data-toggle="popover" title="Training duration" 
                                        data-placement="bottom" 
                                        data-content="Please enter 0 for SOP/COP sign-off">
                                    ?
                                </button>
                                <input class="form-control" name="days" 
                                       id="newTrainDays" type="number" min="0" 
                                       max="10" step="0.1" 
                                       required="required" />
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
                                <select name="otherusers[]" id="otheruserstrain" 
                                        data-placeholder="Other attendees... type here to filter list" 
                                        class="chosen-select" multiple 
                                        style="width: 75%;">
                                    <?php enumerateselectusers($users, ""); ?>
                                </select>
                                <span class="glyphicon glyphicon-search" 
                                      aria-hidden="true"></span>
                            </label>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <fieldset>
                        <button class="btn btn-success" type="submit">
                            Submit
                        </button>
                        <button type="button" class="btn btn-danger" 
                                data-dismiss="modal" 
                                onclick="resetForm('addTrain')">
                            Cancel
                        </button>
                    </fieldset>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for adding new publication record -->
<div class="modal fade" id="modalNewPub" tabindex="-1" role="dialog" 
     aria-labelledby="modalNewPub">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalAddPubLabel">Add New Publication Record</h4>
                <p>To add an entry to your publication history please enter the following details:</p>
            </div>
            <form id="addPub" action="modifyrecord.php?type=newPub" method="post">
                <input type="hidden" name="token" value="<?= $token ?>" />
                <div class="modal-body">
                    <fieldset class="formfieldgroup">
                        <legend>Publication Record Information</legend>
                        <div class="form-group clearfix">
                            <div class="col-md-3 text-left">
                                <label>
                                    <b class="required">Year</b>
                                    <input class="form-control" name="year" 
                                           type="number" min="1980" max="2500" 
                                           <?= "value=\"" . date('Y') . "\"" ?> 
                                           required="required" />
                                </label>
                            </div>
                            <div class="col-md-5 text-left">
                                <label>
                                    <b class="required">Reference Title</b>
                                    <input class="form-control autocomplete" 
                                           name="title" id="newPubDesc" 
                                           type="text" pattern=".{4,60}" 
                                           title="Between 4 and 60 characters" 
                                           required="required" 
                                           onfocus="setAutocompleteType('newPubDesc', 0, 1)" />
                                </label>
                                <p class="text-muted">Patent Reference (e.g. WO 2005123456) or 
                                    <a href="https://images.webofknowledge.com/WOK46/help/WOS/J_abrvjt.html" 
                                       target="_blank">
                                        ISI abbreviated
                                    </a> 
                                    Journal Title (e.g. J. Am. Chem. Soc.)
                                </p>
                            </div>
                            <div class="col-md-4 text-left">
                                <label>
                                    <b class="required">Source of work</b>
                                    <input class="form-control autocomplete" 
                                           name="source" id="newPubSource" 
                                           type="text" pattern=".{3,32}" 
                                           title="Between 3 and 32 characters" 
                                           required="required" 
                                           onfocus="setAutocompleteType('newPubSource', 0, 1)" />
                                </label>
                                <p class="text-muted">
                                    Enter name of client for project work, 
                                    "Internal" for Sygnature internal research 
                                    and "External" for non-Sygnature work.
                                </p>
                            </div>
                        </div>
                        <div class="form-group clearfix text-center">
                            <p><b>Additional information for Journals</b></p>
                            <div class="col-md-3 col-md-offset-1 text-left">
                                <label>
                                    <b class="required">Publication Type</b>
                                    <select name="journal" required="required">
                                        <option disabled selected value>
                                            Select option
                                        </option>
                                        <option value="1">Journal</option>
                                        <option value="0">Patent</option>
                                    </select>
                                </label>
                            </div>
                            <div class="col-md-2 text-left">
                                <label>
                                    <b>Volume</b>
                                    <input class="form-control" name="volume" 
                                           type="text" pattern=".{,6}" 
                                           title="No more than 6 characters" />
                                </label>
                            </div>
                            <div class="col-md-2 text-left">
                                <label>
                                    <b>(Issue)</b>
                                    <input class="form-control" name="issue" 
                                           type="number" min="0" max="100" />
                                </label>
                            </div>
                            <div class="col-md-2 text-left">
                                <label>
                                    <b>Start Page</b>
                                    <input class="form-control" name="startpage" 
                                           type="text" pattern=".{,16}" 
                                           title="No more than 16 characters" />
                                </label>
                            </div>
                            <div class="col-md-2 text-left">
                                <label>
                                    <b>(End Page)</b>
                                    <input class="form-control" name="endpage" 
                                           type="number" min="0" max="99999" />
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
                                    <select name="otherusers[]" id="otheruserspub" 
                                            data-placeholder="Other attendees... type here to filter list" 
                                            class="chosen-select" multiple 
                                            style="width: 75%;">
                                        <?php enumerateselectusers($users, ""); ?>
                                    </select>
                                    <span class="glyphicon glyphicon-search" 
                                          aria-hidden="true"></span>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <fieldset>
                        <button class="btn btn-success" type="submit">
                            Submit
                        </button>
                        <button type="button" class="btn btn-danger" 
                                data-dismiss="modal" 
                                onclick="resetForm('addPub')">
                            Cancel
                        </button>
                    </fieldset>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals for deleting records of each type -->
<?php foreach (['DelConf' => 'conference', 
                'DelTrain' => 'training', 
                'DelPub' => 'publication'] 
        as $section => $description): ?>
    <div class="modal fade" <?= "id=\"modal" . $section . "\"" ?> tabindex="-1" 
         role="dialog" <?= "aria-labelledby=\"modal" . $section . "\"" ?>>
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" <?= "id=\"modal" . $section . "Label\"" ?>>
                        Delete <?= ucfirst($description) ?>
                    </h4>
                </div>
                <form <?="id=\"" . lcfirst($section) . "\"" ?> method="post">
                    <input type="hidden" name="token" value="<?= $token ?>" />
                    <div class="modal-body">
                        <p>Are you sure you want to delete this 
                           <?= $description ?> record?</p>
                    </div>
                    <div class="modal-footer">
                        <fieldset>
                            <button class="btn btn-success" type="submit">
                                Continue
                            </button>
                            <button type="button" class="btn btn-danger" 
                                    data-dismiss="modal" 
                                    onclick="resetForm('<?= $section ?>')">
                                Cancel
                            </button>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach ?>

<!-- Modals for exporting history of each section -->
<?php foreach (['ExportConf' => 'Conference History', 
                'ExportTrain' => 'Training History', 
                'ExportPub' => 'Publication History'] 
        as $section => $description): ?>
    <div class="modal fade" <?="id=\"modal" . $section . "\"" ?> tabindex="-1" 
         role="dialog" <?= "aria-labelledby=\"modal" . $section . "\"" ?>>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" <?= "id=\"modal" . $section . "Label\"" ?>>
                        Export <?= $description ?>
                    </h4>
                </div>
                <form <?= "id=\"" . $section . "\"" ?> method="post" 
                      action="exportdocument.php?type=<?= $section ?>">
                    <input type="hidden" name="token" value="<?= $token ?>" />
                    <div class="modal-body">
                        <p>Please choose from the date range and sections to include:</p>
                        <div class="form-group clearfix">
                            <div class="col-md-4 text-left">
                                <label>
                                    <b class="required">Start Date</b>
                                    <input class="form-control" name="fromdate" 
                                           type="date" 
                                           <?= "max=\"" . date("Y-m-d") . "\"" ?> 
                                           <?= "value=\"" 
                                                . date("Y-m-d", strtotime("-1 year")) 
                                                . "\""?> required="required" />
                                </label>
                            </div>
                            <div class="col-md-4 text-left">
                                <label>
                                    <b class="required">To Date</b>
                                    <input class="form-control" name="todate" 
                                           type="date" 
                                           <?= "max=\"" . date("Y-m-d") . "\""?> 
                                           <?= "value=\"" 
                                                . date("Y-m-d") 
                                                . "\"" ?> required="required" />
                                </label>
                            </div>
                            <div class="col-md-4 text-left">
                                <b class="required">Sections:</b><br />
                                <label>
                                    <input type="radio" name="sections" 
                                           value="current" checked /> Current
                                </label>
                                <br />
                                <label>
                                    <input type="radio" name="sections" 
                                           value="all" /> All
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <fieldset>
                            <button class="btn btn-success" type="submit">
                                Continue
                            </button>
                            <button type="button" class="btn btn-danger" 
                                    data-dismiss="modal" 
                                    onclick="resetForm('<?= $section ?>')">
                                Cancel
                            </button>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach ?>

<script type="text/javascript" src="js/chosen.jquery.min.js"></script>

<script>
    // function to show modal with specified id, triggered by button in HTML
    function show_modal(id) {
        $("#" + id).modal("toggle");
        $('.chosen-select', this).chosen();
        $(".chosen-container").width("75%");
    }

    // reset form with specified id, used when cancel button is pressed
    function resetForm(id) {
        $("#" + id).trigger("reset");
    }

    /* keep track of which input box has focus and return appropriate autocomplete results
     * type is submitted in the query string and determines which query is used to obtain results
     * filter specifies the id of the form element passed in the query string used to filter the
     * results (0 for no filtering)
     * minLength is the number of characters below which results will not be returned */
    function setAutocompleteType(type, filter, minLength) {
        var autocompleteType = type;

        var source = "getautocomplete.php?type=" + autocompleteType;
        if (filter != 0)
            source += "&filter=" + $("#" + filter).children(":selected").val();

        // set up autocomplete using appropriate type
        $("input.autocomplete").autocomplete({
            source: source,
            minLength: minLength
        });
    }

    /* Bind Continue button to appropriate action
     * modal_id is the id of the modal to be targeted and popped up 
     * form_id is the id of the form within modal_id. This should match the name of the modify record type.
     * record_id is the record id to be modified in the appropriate mySQL table
     * return_page is the current page in the table containing the action button
     * return_modal is the bookmark to expand the relevant modal (e.g. '#collapseModalName') */
    function bindModalButton(modal_id, form_id, record_id, return_page, return_modal) {
        $("#" + form_id).attr("action", "modifyrecord.php?type=" + form_id + "&page="
                + return_page + "&id=" + record_id + return_modal);
        show_modal(modal_id);
    }

    function getDescriptions(trainingid) {
        $.ajax({
            url: 'getautocomplete.php?type=newTrainDesc&filter='+trainingid,
            type: "GET",
            contentType: "application/json; charset=utf-8",
            data: "{}",
            dataType: "json",
            success: function( json ) {
                $('#trainingdesc').html('');
                $('#trainingdesc').append($('<option>').text('Please choose an option...').prop('disabled', '').prop('selected', '').prop('value', ''));
                if (json.length>0) {
                    $.each(json, function(i, value) {
                        $('#trainingdesc').append($('<option>').text(value).prop('value', value));
                    });
                }

                var selVal = '<?= (isset($description) && strlen($description) > 0) ? $description : "" ?>';
                if(selVal.length > 0){
                    $('#trainingdesc option[value="' + selVal + '"]').prop('selected', true);
                }       
            },
            error: function (result) {
                console.log("Error parsing ajax response");
            }
        });
    }

    $(document).ready(function () {

        // Make navMyHistory navbar item selected
        $('#navMyHistory').addClass("active");

        // Open accordion based on URL 
        var url = document.location.toString();
        if (url.match('#')) {
            $('#' + url.split('#')[1]).addClass("in");
        } else
        {
            $("#collapseConferenceHistory").addClass("in");
        }

        // Initiate Popover
        $('[data-toggle="popover"]').popover({
            html: true
        }).on("show.bs.popover", function () {
            $(this).data("bs.popover").tip().css("max-width", "100%");
        });

        $('#trainingid').change(function() {
            getDescriptions($('#trainingid').children(":selected").val());
            $('#otherdesc').hide();
        });

        // Initialise training descriptions on page load
        getDescriptions($('#trainingid').children(":selected").val());
        
        // DataTable
        var table = $('table.paginated').DataTable({
            ordering: false,
            stateSave: true,
            aLengthMenu: [ [5, 10, 25, 50], [5, 10, 25, 50] ],
            iDisplayLength: 5
        });

        /* Go to specified page
<?php if (Input::get("page", NULL)): ?>
            // Check this is the correct table by finding it in the expanded accordion
            if ($(".in").find($table).attr("class") !== undefined)
            {
                // Set page according to URL and repaginate table
                currentPage = <?= Input::get("page") - 1 ?>;
                $table.trigger('repaginate');

                // Set appropriate page as active in pager
                $activepage = $(this).parent().find(".page-number:contains(" + (currentPage + 1) + ")")[0];
                $($activepage).addClass('active').siblings().removeClass('active');
            }
<?php endif ?>
        });*/
    });
</script>
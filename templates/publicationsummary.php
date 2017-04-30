<?php
    if (!isset($startyear)) $startyear = "1900";
    if (!isset($endyear)) $endyear = date("Y");
    if (!isset($pubtitle)) $pubtitle = "";
    if (!isset($checkbox)) 
    {
        $checkbox = [
            "patents" => TRUE, 
            "journals" => TRUE,
            "clients" => TRUE, 
            "internal" => TRUE,
            "external" => TRUE];
    }
    $ROWSPERPAGE = 10;
    
    $successcode = Input::get("success", NULL);
?>

<form action="publicationsummary.php" method="post" class="form-horizontal">
    <input type="hidden" name="token" value="<?= Token::generate() ?>" />
    <div class="form-group">
        <?php if ((Input::get("admin") !== "") || (isset($admin) && $admin == 1)): ?>
            <input type="hidden" name="admin" value="1" />
        <?php endif ?>

        <div class="col-md-4 col-md-offset-1 text-left">
            <label>
                <b>Filter (Optional)</b>
                <input class="form-control autocomplete" name="title" 
                       id="newPubFilt" type="text" maxlength="60" 
                       placeholder="e.g. J. Med. Chem." 
                       onfocus="setAutocompleteType('newPubDesc', 0, 1)" 
                       <?= ($pubtitle !== "") 
                       ? "value=\"" . $pubtitle . "\" " 
                       : "" ?> />
            </label>
        </div>
        <div class="col-md-2 text-left">
            <label>
                <b class="required">Start Year</b>
                <input class="form-control" name="startyear" type="number" 
                       required="required" <?= "value=\"" . $startyear . "\"" ?> 
                       min="1900" <?= "max=\"" . date("Y") . "\"" ?> />
            </label>
            <label>
                <b class="required">End Year</b>
                <input class="form-control" name="endyear" type="number" 
                       required="required" <?= "value=\"" . $endyear . "\"" ?> 
                       min="1900" <?= "max=\"" . date("Y") . "\"" ?> />
            </label>
        </div>
        <div class="col-md-3 text-left">
                <div>
                    <b>Publication Types</b><br />
                    <?php foreach (["patents" => $checkbox["patents"],
                                    "journals" => $checkbox["journals"]] 
                            as $key => $value): ?>                    
                        <label style="float: left;">
                            <input type="checkbox" 
                            <?= "name=\"" . $key . "\" value=\"" . $key . "\"" ?>
                            <?php if($value): ?>
                                    checked="checked" 
                            <?php endif ?>
                            /><?= ucfirst($key) ?>&nbsp;&nbsp;
                        </label>
                    <?php endforeach ?>

                </div>
                <div style="clear: both;">
                    <b>Sources</b><br />
                    <?php foreach (["clients" => $checkbox["clients"],
                                    "internal" => $checkbox["internal"],
                                    "external" => $checkbox["external"]] 
                            as $key => $value): ?>                    
                        <label style="float: left;">
                            <input type="checkbox" 
                            <?= "name=\"" . $key . "\" value=\"" . $key . "\"" ?>
                            <?php if($value): ?>
                                    checked="checked" 
                            <?php endif ?>
                            /><?= ucfirst($key) ?>&nbsp;&nbsp;
                        </label>
                    <?php endforeach ?>
                </div>
        </div>
        <div class="col-md-1 text-center">
            <p>
            <button class="btn btn-info" type="submit" name="action" 
                    value="submit">Submit</button>
            </p><p>
            <button class="btn btn-default" type="submit" name="action"
                    value="export">
                <span class="glyphicon glyphicon-save-file" 
                      aria-hidden="true"></span> Export
            </button>
            </p>
        </div>
    </div>
</form>

<?php if ((Session::exists("publicationadmin") 
            && Session::get("publicationadmin") == 1) 
            || (Session::exists("admin") 
                    && Session::get("admin") == 1)): ?>
    <button type="button" class="btn btn-primary btn-xs" 
            onclick="show_modal('modalNewPub', 0)">
        <span class="glyphicon glyphicon-plus-sign" 
              aria-hidden="true"></span> Add new publication
    </button>
<?php endif ?>

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
                        <td><?= escapeHTML($h["year"]) ?></td>
                        <td>
                            <?php if ($h["journal"] == 1): ?>
                                <i><?= escapeHTML($h["title"] . ',') ?></i>
                                <?php if($h["volume"] !== ""): ?>
                                    <b><?= escapeHTML($h["volume"] . ',') ?></b>
                                <?php else: ?>
                                    <b><?= escapeHTML($h["year"] . ',') ?></b>
                                <?php endif ?>
                                <?php if($h["issue"] !== ""): ?>
                                    <?= escapeHTML('(' . $h["issue"] . '),') ?>
                                <?php endif ?>
                                <?= escapeHTML($h["startpage"])?>
                                <?php if($h["endpage"] !== ""): ?>
                                    - <?= escapeHTML($h["endpage"])?>
                                <?php endif ?>
                            <?php else: ?>
                                <?= escapeHTML($h["title"]) ?>
                            <?php endif ?>
                        </td>
                        <td><?= escapeHTML($h["source"]) ?></td>
                        <td><?= escapeHTML($h["userlist"]) ?></td>
                        <td>
                            <?php if ((Session::exists("publicationadmin") 
                                    && Session::get("publicationadmin") == 1) 
                                    || (Session::exists("admin") 
                                            && Session::get("admin") == 1)): ?>
                            <button type='button' class='btn btn-warning btn-xs' 
                                    onclick='setupModalDetails("modalEditPub", 
                                    <?= json_encode($h) ?>)'>
                                &nbsp;
                                <span class="glyphicon glyphicon-pencil" 
                                      title="Edit" aria-hidden="true"></span>
                                &nbsp;
                            </button>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>	
<?php elseif ($successcode !== NULL): ?>
    <?php if ($successcode == 0): ?>
        <div>
            <div class="alert alert-success" role="alert">
                Publication record added/amended successfully
            </div>
        </div>
    <?php endif ?>
    <?php if ($successcode & 1): ?>
        <div>
            <div class="alert alert-danger" role="alert">
                Could not insert record into database for self
            </div>
        </div>
    <?php endif ?>
    <?php if ($successcode & 2): ?>
        <div>
            <div class="alert alert-danger" role="alert">
                Could not insert record into database for one or more authors
            </div>
        </div>
    <?php endif ?>
    <?php if ($successcode & 4): ?>
        <div>
            <div class="alert alert-danger" role="alert">
                Could not delete entry for one or more authors
            </div>
        </div>
    <?php endif ?>
    <?php if ($successcode & 8): ?>
        <div>
            <div class="alert alert-danger" role="alert">
                You do not have permission to do this
            </div>
        </div>
    <?php endif ?>
    <?php if ($successcode & 16): ?>
        <div>
            <div class="alert alert-danger" role="alert">
                Could not verify one or more records
            </div>
        </div>
    <?php endif ?>
    <?php if ($successcode & 32): ?>
        <div>
            <div class="alert alert-danger" role="alert">
                Could not confirm this record
            </div>
        </div>
    <?php endif ?>
    <?php if ($successcode & 64): ?>
        <div>
            <div class="alert alert-danger" role="alert">
                Required form field was missing
            </div>
        </div>
    <?php endif ?>
<?php endif ?>
<!-- Modal for adding new publication -->
<div class="modal fade" id="modalNewPub" tabindex="-1" role="dialog" 
     aria-labelledby="modalNewPub">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" 
                    id="modalAddPubLabel">
                    Add New Publication
                </h4>
                <p>To add a new publication please enter the following details:</p>
            </div>
            <form id="addPub" action="modifyrecord.php?type=newPub&3rdParty=1" 
                  method="post">
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
                                            type="text" pattern=".{4,50}" 
                                            title="Between 4 and 20 characters" 
                                            required="required" 
                                            onfocus="setAutocompleteType('newPubDesc', 0, 1)" />
                                </label>
                                <p class="text-muted">Patent Reference (e.g. WO 2005123456) or 
                                    <a href="https://images.webofknowledge.com/WOK46/help/WOS/J_abrvjt.html" 
                                        target="_blank">
                                    ISI abbreviated</a> Journal Title (e.g. J. Am. Chem. Soc.)
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
                                    "Internal" for Sygnature internal 
                                    research and "External" for 
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
                                           type="text" maxlength="6" />
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
                                           type="text" maxlength="16" />
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
                                    <b>Sygnature authors/inventors:</b>
                                    <select name="otherusers[]" 
                                            id="otheruserspub" 
                                            data-placeholder="Other attendees... type here to filter list" 
                                            class="chosen-select" 
                                            multiple style="width: 75%;">
                                        <?php enumerateselectusers($users, "", true); ?>
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
<!-- Modal for editing publication -->
<div class="modal fade" id="modalEditPub" tabindex="-1" role="dialog" 
     aria-labelledby="modalEditPub">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalEditPubLabel">
                    Edit Publication Authors
                </h4>
                <p>To change the authors or delete the publication record 
                    for all authors, please use the following form:</p>
            </div>
            <form id="editPub" action="modifyrecord.php?type=editPub" method="post">
                <div class="modal-body">
                    <fieldset class="formfieldgroup">
                        <legend>Publication Record Information</legend>
                        <div class="form-group clearfix">
                            <div class="col-md-3 text-left">
                                <b class="label-static">Year</b>
                                <input class="form-control" id="editPubYear" 
                                       name="year" type="number" readonly />
                            </div>
                            <div class="col-md-5 text-left">
                                <b class="label-static">Reference Title</b>
                                <input class="form-control" id="editPubTitle" 
                                       name="title" type="text" readonly />
                            </div>
                            <div class="col-md-4 text-left">
                                <b class="label-static">Source of work</b>
                                <input class="form-control" id="editPubSource" 
                                       name="source" type="text" readonly />
                            </div>
                        </div>
                        <div class="form-group clearfix text-center">
                            <p><b>Additional information for Journals</b></p>
                            <div class="col-md-3 text-left">
                                <b class="label-static">Publication Type</b>
                                <select name="journal" readonly>
                                    <option id="editPubTypeJ" value="1">
                                        Journal
                                    </option>
                                    <option id="editPubTypeP" value="0">
                                        Patent
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 text-left">
                                <b class="label-static">Volume</b>
                                <input class="form-control" id="editPubVolume" 
                                       name="volume" type="text" readonly />
                            </div>
                            <div class="col-md-2 text-left">
                                <b class="label-static">(Issue)</b>
                                <input class="form-control" id="editPubIssue" 
                                       name="issue" type="number" readonly />
                            </div>
                            <div class="col-md-2 text-left">
                                <b class="label-static">Start Page</b>
                                <input class="form-control" id="editPubStartPage" 
                                       name="startpage" type="text" readonly />
                            </div>
                            <div class="col-md-2 text-left">
                                <b class="label-static">(End Page)</b>
                                <input class="form-control" id="editPubEndPage" 
                                       name="endpage" type="number" readonly />
                            </div>
                        </div>
                    </fieldset>
                    <br />
                    <fieldset class="formfieldgroup">
                        <div class="form-group clearfix">
                            <div class="col-md-10 col-md-offset-2 text-left">
                                <label>
                                    <b>Sygnature authors/inventors to add:</b>
                                    <select name="otherusers[]" id="adduserspub" 
                                            data-placeholder="Add attendees... type here to filter list" 
                                            class="chosen-select" multiple 
                                            style="width: 75%;">
                                            <?php enumerateselectusers($users, "", true); ?>
                                    </select>
                                    <span class="glyphicon glyphicon-search" 
                                          aria-hidden="true"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-md-10 col-md-offset-2 text-left">
                                <label>
                                    <b>Sygnature authors/inventors to delete:</b>
                                    <select name="deleteusers[]" id="deleteuserspub" 
                                            data-placeholder="Delete attendees... type here to filter list" 
                                            class="chosen-select" multiple 
                                            style="width: 75%;">
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
                                onclick="resetForm('editPub')">
                            Cancel
                        </button>
                    </fieldset>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
    
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

    /* keep track of which input box has focus and return appropriate autocomplete results
     * type is submitted in the query string and determines which query is used to obtain results
     * filter specifies the id of the form element passed in the query string used to filter the
     * results (0 for no filtering)
     * minLength is the number of characters below which results will not be returned */
    function setAutocompleteType(type, filter, minLength){
        var autocompleteType = type;

        var source = "getautocomplete.php?type=" + autocompleteType;
        if (filter != 0) source += "&filter=" + $("#" + filter).children(":selected").val();

        console.log(source);

        // set up autocomplete using appropriate type
        $( "input.autocomplete" ).autocomplete({
            source: source,
            minLength: minLength
        });
    }

    /* Populate the details in the edit publication modal
     * modal_id is the id of the modal to be targeted and popped up 
     * publication is the JSON encoded publication record */
    function setupModalDetails(modal_id, publication){
        $('#editPubYear').val(publication.year);
        $('#editPubTitle').val(publication.title);
        $('#editPubSource').val(publication.source);
        $('#editPubTypeJ').prop('selected', (publication.journal==1?true:false));
        $('#editPubTypeP').prop('selected', (publication.journal==1?false:true));
        $('#editPubVolume').val(publication.volume);
        $('#editPubIssue').val(publication.issue);
        $('#editPubStartPage').val(publication.startpage);
        $('#editPubEndPage').val(publication.endpage);
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

        <?php if (Input::get("admin") == 1 || (isset($admin) && $admin == 1)): ?>
        // Make navAdmin navbar item selected
        $('#navAdmin').addClass("active");
        <?php else: ?>
        // Make navSummaries navbar item selected
        $('#navSummaries').addClass("active");
        <?php endif ?>

        // Initiate chosen select box
        $('.chosen-select').chosen();

        /* Initialise each paginated table
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
        });*/
    
        // DataTable
        var table = $('table.paginated').DataTable({
            ordering: false,
            stateSave: true
        });
    });
</script>

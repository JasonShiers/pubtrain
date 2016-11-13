<?php $rownumber = -1; ?>
<?php foreach ($confhistory as $h): ?>
    <tr <?php if ($h["confirmed"] === 0) { ?>style="color: darkgray;" <?php } ?>>
        <?php $rownumber++; ?>
        <td><?= escapeHTML($h["confdate"]) ?></td>
        <td><?= escapeHTML($h["title"]) ?></td>
        <td><?= escapeHTML($h["location"]) ?></td>
        <td><?= escapeHTML($h["days"]) ?></td>
        <?php if ($h["attended"] == 1): ?>
            <td>
                <div class="imgdiv">
                    <span class="glyphicon glyphicon-thumbs-up" 
                          title="Confirmed by ConferenceTracker" 
                          style="color: darkblue;" aria-hidden="true"></span>
                </div>
                &nbsp;
                <a class="btn btn-info btn-xs" 
                   <?= "href=\"" . 
                        escapeHTML("//intranet/confdb/feedbackreview.php?"
                        . "id={$h['req_id']}&userid=" . $userid)
                        . "\"" ?> target="_blank">
                    &nbsp;
                    <span class="glyphicon glyphicon-list-alt" 
                          title="Review Feedback" aria-hidden="true"></span>
                    &nbsp;
                </a>

            </td>
        <?php elseif ($h["editable"] == 0): ?>
            <td>
                <div class="imgdiv">
                    <span class="glyphicon glyphicon-thumbs-up" 
                          title="Approved in ConferenceTracker" 
                          style="color: darkgray;" aria-hidden="true"></span>
                </div>
                &nbsp;
            </td>
        <?php endif ?>
        <?php if ($h["editable"] == 1): ?>
            <td>
                <?php if ($h["confirmed"] !== 0): ?>
                    <div class="imgdiv">
                        <span class="glyphicon glyphicon-thumbs-up" 
                              <?php if ($userid == Session::get("userid")): ?>
                                  <?= "title=\"" . (($h["confirmed"] === 1) ? 
                                      "Confirmed by you\"" : "Entered by you\"") ?>
                              <?php else: ?>
                                  <?= "title=\"" . (($h["confirmed"] === 1) ? 
                                      "Confirmed by user\"" : "Entered by user\"") ?>
                              <?php endif ?>
                              style="color: forestgreen;" aria-hidden="true">
                        </span>
                    </div>
                <?php else: ?>
                    <?php if ($userid == Session::get("userid")): ?>
                        <a class="btn btn-info btn-xs" href="<?= "modifyrecord.php?" 
                        . "type=confirmConf&id={$h['id']}&page=" 
                        . intval($rownumber / $ROWSPERPAGE + 1) ?>
                           ">
                            &nbsp;
                            <span class="glyphicon glyphicon-question-sign" 
                                  title="Confirm I Attended" aria-hidden="true"></span>
                            &nbsp;
                        </a>
                        &nbsp;
                        <button type="button" class="btn btn-danger btn-xs" 
                                onclick="bindModalButton('modalDelConf', 'delConf', 
                                <?= "{$h['id']}, " 
                                . intval($rownumber / $ROWSPERPAGE + 1) ?>, '')">
                            &nbsp;
                            <span class="glyphicon glyphicon-trash" 
                                  title="Delete" aria-hidden="true"></span>
                            &nbsp;
                        </button>
                    <?php else: ?>
                        <div class="imgdiv">
                            <span class="glyphicon glyphicon-question-sign" 
                                  title="Not Confirmed" aria-hidden="true"></span>
                        </div>
                    <?php endif ?>
                <?php endif ?>
            </td>
        <?php endif ?>
    </tr>
<?php endforeach ?>

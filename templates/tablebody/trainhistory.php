<?php $rownumber = -1; ?>
<?php foreach ($trainhistory as $h): ?>
    <tr <?php if ($h["confirmed"] === 0) { ?>
        style="color: darkgray;" <?php } ?>>
        <?php $rownumber++; ?>
        <td><?= escapeHTML($h["date"]) ?></td>
        <td>
            <?= escapeHTML($h["type"]) ?>
            <?php if ($h["description"] !== ""): ?>
                <br />
                <?= escapeHTML($h["description"]) ?>
            <?php endif ?>
        </td>
        <td>
            <?= ($h["internal_location"] == 0) ? "External" : "Internal" ?>
        </td>
        <td>
            <?php if ($h["internal_trainer"] === 0): ?>External
            <?php elseif ($h["internal_trainer"] == 1): ?>Internal
            <?php else: ?>N/A
            <?php endif ?>
        </td>
        <td><?= escapeHTML($h["total_days"]) ?></td>
        <td>
            <?php if ($h["confirmed"] !== 0): ?>
                <div class="imgdiv">
                    <span class="glyphicon glyphicon-thumbs-up" 
                          <?php if ($userid === Session::get("userid")): ?>
                                <?= "title=\"" . (($h["confirmed"] === 1) 
                                  ? "Confirmed by you\"" 
                                  : "Entered by you\"") ?> 
                          <?php else: ?>
                                <?= "title=\"" . (($h["confirmed"] === 1) 
                                  ? "Confirmed by user\"" 
                                  : "Entered by user\"") ?> 
                          <?php endif ?>
                          style="color: forestgreen;" aria-hidden="true"></span>
                </div>
            <?php else: ?>
                <?php if ($userid === Session::get("userid")): ?>
                    <a class="btn btn-info btn-xs" 
                       <?= "href=\"modifyrecord.php?type=confirmTrain"
                        . "&id={$h['id']}&page=" 
                        . intval($rownumber / $ROWSPERPAGE + 1) . "\"" ?>>
                        &nbsp;
                        <span class="glyphicon glyphicon-question-sign" 
                              title="Confirm I Attended" aria-hidden="true">
                        </span>
                        &nbsp;
                    </a>
                <?php else: ?>
                    <div class="imgdiv">
                        <span class="glyphicon glyphicon-question-sign" 
                              title="Not Confirmed" aria-hidden="true"></span>
                    </div>
                <?php endif ?>
            <?php endif ?>
            <?php if ($userid === Session::get("userid")): ?>
                &nbsp;
                <button type="button" class="btn btn-danger btn-xs" 
                        onclick="bindModalButton('modalDelTrain', 'delTrain',
                        <?= "{$h["id"]}, " . intval($rownumber / $ROWSPERPAGE + 1) ?>, 
                        '#collapseTrainingHistory')">
                    &nbsp;
                    <span class="glyphicon glyphicon-trash" title="Delete" 
                          aria-hidden="true"></span>
                    &nbsp;
                </button>
            <?php endif ?>
            &nbsp;
            <div class="imgdiv" <?= ($h["verified"] === 1) 
                ? "" : "style=\"visibility: hidden;\"" ?>>
                <span class="glyphicon glyphicon-ok-circle" title="Verified" 
                      style="color: green;" aria-hidden="true"></span>
            </div>
        </td>
    </tr>
<?php endforeach ?>
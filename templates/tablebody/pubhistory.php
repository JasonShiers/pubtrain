<?php $rownumber = -1; ?>
<?php foreach ($pubhistory as $h): ?>
    <tr <?php if ($h["confirmed"] === 0) { ?>style="color: darkgray;"<?php } ?>>
        <?php $rownumber++; ?>
        <td><?= escapeHTML($h["year"]) ?></td>
        <td>
            <?php if ($h["journal"] == 1): ?>
                <i><?= escapeHTML($h["title"] . ',') ?></i>
                <?php if ($h["volume"] !== ""): ?>
                    <b><?= escapeHTML($h["volume"] . ',') ?></b>
                <?php endif ?>
                <?php if ($h["issue"] !== ""): ?>
                    <?= escapeHTML('(' . $h["issue"] . '),') ?>
                <?php endif ?>
                <?= escapeHTML($h["startpage"]) ?>
                <?php if ($h["endpage"] !== ""): ?>
                    - <?= escapeHTML($h["endpage"]) ?>
                <?php endif ?>
            <?php else: ?>
                <?= escapeHTML($h["title"]) ?>
            <?php endif ?>
        </td>
        <td><?= escapeHTML($h["source"]) ?></td>
        <td>
            <?php if ($h["confirmed"] !== 0): ?>
                <div class="imgdiv">
                    <span class="glyphicon glyphicon-thumbs-up" 
                        <?php if ($userid === Session::get("userid")): ?>
                            <?= ($h["confirmed"] === 1) 
                                ? " title=\"Confirmed by you\" " 
                                : " title=\"Entered by you\" " ?>
                        <?php else: ?>
                            <?= ($h["confirmed"] === 1) 
                                ? " title=\"Confirmed by user\" " 
                                : " title=\"Entered by user\" " ?>
                        <?php endif ?>
                          style="color: forestgreen;" aria-hidden="true">
                    </span>
                </div>
            <?php else: ?>
                <?php if ($userid === Session::get("userid")): ?>
                    <a class="btn btn-info btn-xs" 
                       href="<?= "modifyrecord.php?type=confirmPub&id=" . $h["id"]
                    . "&page=" . intval($rownumber / $ROWSPERPAGE + 1)
                    ?> ">
                        &nbsp;
                        <span class="glyphicon glyphicon-question-sign" 
                              title="Confirm" 
                              aria-hidden="true"></span>
                        &nbsp;
                    </a>
                <?php else: ?>
                    &nbsp;
                    <span class="glyphicon glyphicon-question-sign" 
                          title="Not Confirmed" aria-hidden="true"></span>
                    &nbsp;
                <?php endif ?>
            <?php endif ?>
            <?php if ($userid === Session::get("userid")): ?>
                &nbsp;
                <button type="button" class="btn btn-danger btn-xs" 
                        onclick="bindModalButton('modalDelPub', 'delPub', 
                        <?= $h["id"] ?>, 
                        <?= intval($rownumber / $ROWSPERPAGE + 1) ?>, '')">
                    &nbsp;
                    <span class="glyphicon glyphicon-trash" 
                          title="Delete" aria-hidden="true"></span>
                    &nbsp;
                </button>
            <?php endif ?>
        </td>
    </tr>
<?php endforeach ?>
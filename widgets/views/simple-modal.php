<?php

/** @var string $class */
/** @var string $id */
/** @var string $title */
/** @var string $body */

?>

<div class="modal fade <?= $class ?: '' ?>" id="<?= $id ?>" role="dialog" aria-labelledby="<?= $id ?>">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title"><?= $title ?></p>
            </div>
            <div class="modal-body"><?= $body ?></div>
        </div>
    </div>
</div>

<?php if ($model->reason == 0) { ?>
    <div>
        <span>Question/Report an issue : </span><br><?= $model->detail; ?>
    </div>
<?php } else { ?>
    <div>
        <span>Name : </span><?= $model->name; ?>
    </div>
    <div>
        <span>Email : </span><?= $model->email; ?>
    </div>
    <div>
        <span>Organization : </span><?= $model->organization; ?>
    </div>
    <div>
        <span>Address : </span><?= $model->event_address; ?>
    </div>
    <div>
        <span>Date : </span><?= $model->event_date; ?>
    </div>
    <div>
        <span>Time : </span><?= $model->event_time; ?>
    </div>
    <div>
        <span>Event Title : </span> <?= $model->event_title; ?>
    </div>
    <div>
        <span>Categories and Services : </span><?= $model->event_categories_services; ?>
    </div>
    <div>
        <span>Event Fee : </span><?= $model->event_cost; ?>
    </div>
    <div>
        <span>Insurance : </span><?= $model->event_insurance; ?>
    </div>
    <div>
        <span>Detail : </span><?= $model->detail; ?>
    </div>
<?php } ?>
<table style="margin-bottom: 50px;text-align: center" align="center" >
    <tr align="center" style="text-align: center">
        <td style="padding-bottom: 50px">
            <h3 style="font-size: 22px;font-weight: 500">Hi, <?= $user_name ?></h3> 
        </td>
    </tr>
    <tr>
        <td style="padding-bottom: 50px">    
            <h5 style="font-size: 18px;font-weight: 500">
                The following event(s) has just been created and 
                fits the alert filter that you haved saved on HealthEventsLive.com: 
            </h5>
        </td>
    </tr>
</table>
<table align="center" style="margin-bottom: 50px;">
    <?php $event_links = array(); ?>
    <?php foreach ($events as $Single_array_events) { ?>
        <?php foreach ($Single_array_events as $event) { ?>
            <?php if (in_array((string) $event['_id'], $event_links)) { ?>
                <?php continue; ?>
            <?php } ?>
            <tr style="text-align: center">
            <a href="<?= \yii\helpers\Url::base(TRUE) ?>/event/detail?eid=<?= (string) $event['_id'] ?>" style="font-size:18px ">
                <?= \yii\helpers\Url::base(TRUE) ?>/event/detail?eid=<?= (string) $event['_id'] ?>
            </a>
        </tr>
        <?php array_push($event_links, (string) $event['_id']); ?>
    <?php } ?>
<?php } ?>
</table>
<table style="margin-bottom: 50px;margin-top:50px ;text-align: center" align="center" >
    <tr align="center" style="text-align: center">
        <td style="padding-bottom: 10px;padding-top: 50px;">
            <h5 style="font-size: 18px;font-weight: 500">Thanks</h5>
        </td>
    </tr>
    <tr>
        <td style="padding-bottom: 20px">    
            <h5 style="font-size: 18px;font-weight: 500">
                <a href="13.59.81.62/HEL/frontend/web/">www.healtheventlive.com</a>
            </h5>
        </td>
    </tr>
</table>
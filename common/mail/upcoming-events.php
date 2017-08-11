<table align="center" style="min-height: 400px">
    <tr>
    <p style="text-align: center">Follow these links of the relevent events </p>
    </tr>
    <?php foreach ($events as $event) { ?>
    <tr style="text-align: center"> 
        <a href="<?= \yii\helpers\Url::base(TRUE)?>/event/detail?eid=<?= (string) $event['_id'] ?>">
            <?= \yii\helpers\Url::base(TRUE)?>/event/detail?eid=<?= (string) $event['_id'] ?>
        </a>
    </tr>
<?php } ?>
</table>
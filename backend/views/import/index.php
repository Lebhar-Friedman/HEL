<?php
/* @var $this yii\web\View */

$this->title = 'Import csv';
$this->registerMetaTag(['name' => 'description', 'content' => 'kjsdhfjkdsfh jkdsfjsdkfhdsjk fdhgds gh']);
?>


<h1>Import csv</h1><br>

<form class="row" id="fileform" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="">
    <input id="import" class="" type="file" name="UploadForm[file]" accept=".csv">
    <select name="import_type">
        <option value="company">Company</option>
        <option value="event">Event</option>
    </select><br><br>
    <input type="button" value="Upload"  onclick="importcsv();"/>
</form>


<?php
/* @var $this View */
/* @var $form ActiveForm */
/* @var $model ContactForm */

use frontend\models\ContactForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Health Events Live: Contact';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$form = ActiveForm::begin([
            'id' => 'contact-form'
                ]
);
?>
<div class="container">
    <div class="contact-us-h">Contact Us</div>
    <div class="contact-us-wrapper">
        <div class="cotact-us-content">
            <label>Reason Of Contact:</label>
            <div class="textbox-cotent">
                <?= $form->field($model, 'reason')->dropDownList(array("Ask a question about the website / report an issue", "Submit a health event for posting"), ['class' => "contact-textbox select-option", 'id' => 'reason_to_contact', 'onchange' => 'reasonToContact(this)'])->label(false); ?>
            </div>
        </div>
        <div id="contact_fields">
            <div class="cotact-us-content">
                <label>Name:</label>
                <div class="textbox-cotent">
                    <?= $form->field($model, 'name')->textInput(['class' => "contact-textbox"])->label(FALSE) ?>
                </div>
            </div>
            <div class="cotact-us-content">
                <label>Email:</label>
                <div class="textbox-cotent">
                    <?= $form->field($model, 'email')->textInput(['class' => "contact-textbox"])->label(FALSE) ?>
                </div>
            </div>
            <div class="cotact-us-content">
                <label>Organization:</label>
                <div class="textbox-cotent">
                    <?= $form->field($model, 'organization')->textInput(['class' => "contact-textbox"])->label(FALSE) ?>
                </div>
            </div>
            <div class="cotact-us-content">
                <label>Address for event:</label>
                <div class="textbox-cotent">
                    <?= $form->field($model, 'event_address')->textInput(['class' => "contact-textbox"])->label(FALSE) ?>
                </div>
            </div>
            <div class="cotact-us-content">
                <label>Date:</label>
                <div class="textbox-cotent">
                    <?= $form->field($model, 'event_date')->input('date', ['class' => "contact-textbox"])->label(FALSE) ?>
                </div>
            </div>
            <div class="cotact-us-content">
                <label>Times (start and end):</label>
                <div class="textbox-cotent">
                    <?= $form->field($model, 'event_time')->textInput(['class' => "contact-textbox"])->label(FALSE) ?>
                </div>
            </div>
            <div class="cotact-us-content">
                <label>Event title:</label>
                <div class="textbox-cotent">
                    <?= $form->field($model, 'event_title')->textInput(['class' => "contact-textbox"])->label(FALSE) ?>
                </div>
            </div>
            <div class="cotact-us-content">
                <label>Health categories <br class="hide-on-mobile" />and services offered:</label>
                <div class="textbox-cotent">
                    <?= $form->field($model, 'event_categories_services')->textInput(['class' => "contact-textbox"])->label(FALSE) ?>
                </div>
            </div>
            <div class="cotact-us-content">
                <label>Cost:</label>
                <div class="textbox-cotent">
                    <?= $form->field($model, 'event_cost')->textInput(['class' => "contact-textbox"])->label(FALSE) ?>
                </div>
            </div>
            <div class="cotact-us-content">
                <label>Insurance required:</label>
                <div class="textbox-cotent">
                    <?= $form->field($model, 'event_insurance')->dropDownList(array("Yes" => "Yes", "No" => "No"), ['class' => "contact-textbox select-option2"])->label(false); ?>
                </div>
            </div>
        </div>
        <div class="cotact-us-content">
            <label class="textaraea-label">Any other important details:</label>
            <div class="textbox-cotent">
                <?= $form->field($model, 'detail')->textarea(['rows' => 6, 'class' => "contact-textbox contatc-textarea"])->label(FALSE) ?>
            </div>
        </div>
        <div class="cotact-us-content">
            <label></label>
            <div class="textbox-cotent text-center">
                <?= Html::submitButton('Submit', ['class' => 'contact-submit', 'name' => 'contact-button']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
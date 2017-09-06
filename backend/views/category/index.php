<?php

use yii\helpers\ArrayHelper;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$baseUrl = BaseUrl::base();
//print_r($sub_categories);die;
?>
<style>
    .form-group {
        margin-bottom: 0px !important; 
    }
    .help-block {
        margin-bottom: 0px !important; 
    }

</style>
<?php $this->registerJsFile('@web/js/categories.js', ['depends' => [JqueryAsset::className()]]); ?>
<?php $this->title = 'Categories'; ?>
<style>
    #overlay-sub-category{
        position: fixed;
        display: none;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 2;
        /*cursor: pointer;*/
    }
    #overlay-category{
        position: absolute;
        display: none;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 2;
        /*cursor: pointer;*/
    }
    #loader-sub-category{
        /*display: none;*/
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 50px;
        color: white;
        transform: translate(-50%,-50%);
        -ms-transform: translate(-50%,-50%);
    }
    #loader-category{
        /*display: none;*/
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 50px;
        color: white;
        transform: translate(-50%,-50%);
        -ms-transform: translate(-50%,-50%);
    }
</style>

<div id="overlay-sub-category">
    <div><img id="loader-sub-category"  src="<?= BaseUrl::base() . '/images/loader.gif' ?>"></div>
</div>
<?php Pjax::begin(['id' => 'page-content', 'timeout' => 30000, 'enablePushState' => TRUE]); ?>

<div class="col-lg-12">
    <div class="csv33-comp-content">
        <div class="upload-123 clearfix">
            <div id="overlay-category">
                <div><img id="loader-category"  src="<?= BaseUrl::base() . '/images/loader.gif' ?>"></div>
            </div>
            <div >

                <h3> <img class="lm" src="images/category.png" > Categories</h3>

            </div>
            <!--                        Category 1                      -->
            <!--<?/php Pjax::begin(['id' => 'category-content', 'timeout' => 30000, 'enablePushState' => TRUE]); ?>-->
            <?php
            foreach ($categories as $category) {
                $categories_model->attributes = $category->attributes;
                $categories_model->category_id = $category->_id;
                $form = ActiveForm::begin([
                            'id' => 'CategoryField-' . $category['_id'],
                            'action' => $baseUrl . '/category/update-category',
                            'enableAjaxValidation' => true,
                            'validateOnBlur' => false,
                            'validationUrl' => Yii::$app->urlManager->createUrl("category/category-validation"),
                            //'validateOnChange' => false,
                            //'enableClientValidation' => true,
                            'fieldConfig' => ['template' => "{input}{error}"],
                            'options' => ['enctype' => 'multipart/form-data']
                ]);
                ?>
                <div class ="row ab">
                    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-4  wd">
                        <div>
                            <?= $form->field($categories_model, 'name', ['inputOptions' => ['class' => 'qw', 'placeholder' => 'Category Name']])->textInput()->label(false); ?>
                            <?= $form->field($categories_model, 'category_id', ['options' => ['class' => 'hidden'], 'inputOptions' => ['class' => 'qw hidden', 'placeholder' => 'Category Name']])->textInput()->label(false); ?>
                            <!--<input type ="text" class ="qw" value="<?/= $category->name ?>">-->
                        </div>
                    </div>
                    <div class ="col-lg-1 col-md-1 col-sm-2 col-xs-4">
                        <div class ="add-add-btn">
                            <a href="javascript:;" onclick="$('#CategoryField-<?= $category->_id ?>').submit()" >Save</a>

                        </div>
                    </div>
                    <div class ="col-lg-1 col-md-1 col-sm-1 col-xs-4 tm" >
                        <a href="javascript:;" onclick="deleteCategory('<?= $category->_id ?>', this.id)" ><img class="tm" src="images/ing.png"></a>
                    </div>
                </div>
                <?php
                $form->end();
                if (!empty($category->sub_categories)) {
                    foreach ($category->sub_categories as $sub_category) {
                        ?>
                        <div class ="row cd">

                            <div class="col-lg-10 col-md-8 col-sm-8 col-xs-6 yyy">

                                <div><?= $sub_category ?></div>
                            </div>

                            <div class ="col-lg-1 col-md-2 col-sm-2 col-xs-6 rrr " >
                                <a href="javascript:;" onclick="deleteSubCategoryLink('<?= $category->_id ?>', '<?= $sub_category ?>', this.id)" ><img class="tm" src="images/ing.png"></a>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

                <div class ="row re">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-6 wwr ">
                        <select name="subCategoryList<?= $category->_id ?>" onchange="javascript:addSubCategoryLink('<?= $category->_id ?>', this);">
                            <option value="-1">
                                Select Sub-Category
                            </option>
                            <?php
                            if (is_array($category->sub_categories)) {
                                $model_sub_categories = $category->sub_categories;
                            } else {
                                $model_sub_categories = [$category->sub_categories];
                            }
                            foreach ($sub_categories_list as $sub_category) {
                                if (!ArrayHelper::isIn($sub_category->name, $model_sub_categories)) {
                                    ?>
                                    <option value="<?= $sub_category->name; ?>">
                                        <?= $sub_category->name; ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>

                    </div>

                    <div class ="col-lg-2 col-md-3 col-sm-2 col-xs-6 tm" >

                    </div>
                </div>
                <?php
            }
            ?>
        <!--<?/php Pjax::end() ?>-->

            <!--                        Category end                      -->
            <?php
            $categories_model->name = null;

            $form = ActiveForm::begin([
                        'id' => 'CategoryForm',
                        'action' => 'javascript:addCategory(event)',//$baseUrl . '/category/add-category',
                        'enableAjaxValidation' => true,
                        'validateOnBlur' => true,
                        'validationUrl' => Yii::$app->urlManager->createUrl("category/category-validation"),
                        //'validateOnChange' => false,
                        //'enableClientValidation' => true,
                        'fieldConfig' => ['template' => "{input}{error}"],
                        'options' => ['enctype' => 'multipart/form-data']
            ]);
            ?>
            <div class ="row gc">
                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-6 gd">
                    <div>
                        <?= $form->field($categories_model, 'name', ['inputOptions' => ['class' => 'qw', 'placeholder' => 'New Main Category']])->textInput()->label(false); ?>
                            <!--<input type ="text" class ="qw" placeholder="New Main Category">-->
                    </div>
                </div>
                <div class ="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                    <div class ="add-add-btn">
                        <!--<a href="#">Add</a>-->
                        <?= Html::submitButton('Add', ['class' => 'btn']) ?>
                    </div>
                </div>
            </div>
            <?php $form->end(); ?>
        </div>
    </div>
</div>

<div class="col-lg-12">

    <div class="csv5-comp-content" ">
        <div class="download clearfix">

            <div >

                <h3> <img class="fm" src="images/sub.png" >Sub Categories</h3>

            </div>            
            <!--<?/php Pjax::begin(['id' => 'sub-category-content', 'timeout' => 30000, 'enablePushState' => TRUE]); ?>-->
            <?php
            foreach ($sub_categories_list as $sub_category) {
                $sub_categories_model->attributes = $sub_category->attributes;
                $sub_categories_model->sub_category_id = $sub_category->_id;
                $form = ActiveForm::begin([
                            'id' => 'SubCategoryField-' . $sub_category['_id'],
                            'action' => 'javascript:updateSubCategory( \'SubCategoryField-'.$sub_category['_id'].'\', event)',//$baseUrl . '/category/update-sub-category',
                            'enableAjaxValidation' => true,
                            'validateOnBlur' => false,
                            'validationUrl' => Yii::$app->urlManager->createUrl("category/sub-category-validation"),
                            //'validateOnChange' => false,
                            //'enableClientValidation' => true,
                            'fieldConfig' => ['template' => "{input}{error}"],
                            'options' => ['enctype' => 'multipart/form-data']
                ]);
                ?>
                <div class ="row rt">
                    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-4 ss">
                        <div>
                            <?= $form->field($sub_categories_model, 'name', ['inputOptions' => ['class' => 'qw', 'placeholder' => 'Sub Category Name']])->textInput()->label(false); ?>
                            <?= $form->field($sub_categories_model, 'sub_category_id', ['options' => ['class' => 'hidden'], 'inputOptions' => ['class' => 'qw', 'placeholder' => 'Sub Category Name']])->textInput()->label(false); ?>
                            <!--<input type ="hidden" class ="qw" id="<?= $sub_category->_id ?>" value="<?= $sub_category->name ?>">-->
                        </div>
                    </div>
                    <div class ="col-lg-1 col-md-1 col-sm-2 col-xs-4">
                        <div class ="add-add-btn">
                            <!--<a href="javascript:;" onclick="$('#SubCategoryField-<?= $sub_category['_id'] ?>').submit()" >Save</a>-->
                            <?= Html::submitButton('Save', ['class' => 'btn']) ?>
                        </div>
                    </div>
                    <div class ="col-lg-1 col-md-1 col-sm-1 col-xs-4 tm" >
                        <a href="javascript:;" onclick="deleteSubCategory('<?= $sub_category['_id'] ?>', this.id)" ><img class="tm" src="images/ing.png"></a>
                    </div>
                </div>
                <?php
                $form->end();
            }
            ?> 
        <!--<?/php Pjax::end() ?>-->
            <?php
            $sub_categories_model->name = null;
            $form = ActiveForm::begin([
                        'id' => 'SubCategoryForm',
                        'action' => 'javascript:addSubCategory(event)',//$baseUrl . '/category/add-sub-category',
                        'enableAjaxValidation' => true,
                        'validateOnBlur' => true,
                        'validationUrl' => Yii::$app->urlManager->createUrl("category/sub-category-validation"),
                        //'validateOnChange' => false,
                        //'enableClientValidation' => true,
                        'fieldConfig' => ['template' => "{input}{error}"],
                        'options' => ['enctype' => 'multipart/form-data']
            ]);
            ?>
            <div class ="row mb" >
                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-4 ss">
                    <div>
                        <?= $form->field($sub_categories_model, 'name', ['inputOptions' => ['class' => 'qw', 'placeholder' => 'Sub Category Name']])->textInput()->label(false); ?>
                        <!--<input name="name" id="txt_sub_category" type ="text" class ="qw" placeholder="Diabetes Care">-->
                    </div>
                </div>
                <div class ="col-lg-1 col-md-1 col-sm-2 col-xs-4">
                    <div class =" add-add-btn ">
                        <!--<a href="#" id="add_sub_category">Add</a>-->
                        <?= Html::submitButton('Add', ['class' => 'btn']) ?>
                    </div>
                </div>
            </div>
            <?php $form->end(); ?>
        </div>
    </div>
</div>
<?php Pjax::end() ?>
            
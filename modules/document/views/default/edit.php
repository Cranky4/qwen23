<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    /* @var $this yii\web\View */
    /* @var $model app\modules\document\models\Document */
    /* @var $form ActiveForm */
    /* @var \app\modules\document\models\Attachment[] $attachments */
    /* @var array $storedFiles */
    $title = $model->isNewRecord ? "Добавить документ" : "Редактировать ".$model->name;
    $this->title = $title;
?>
<h1><?= $title ?></h1>

<div class="default-document">

    <?php $form = ActiveForm::begin(
        [
            'id'                     => 'document-form',
            'action'                 => \yii\helpers\Url::toRoute(
                ['/document/default/save/', 'id' => $model->primaryKey]
            ),
            'enableClientValidation' => true,
            'options'                => [
                'datatype' => 'multipart/formdata',

            ],
        ]
    ); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'description') ?>

    <?php
        $field = $form->field($model, 'attachments');
        $dropzoneWidgetHtml = \app\components\DropZone::widget(
            [
                'name'            => 'attachments',
                'url'             => Yii::$app->urlManager->createUrl('document/file/upload'), // upload url
                'storedFiles'     => $storedFiles, // stores files
                'eventHandlers'   => [
                    'success'     => 'function(file, response) {
                        //set id of uploaded file
                        var inputName = "'.Html::getInputName($model, 'attachments[]').'";
                        response = JSON.parse(response);
                        //add attachment id to preview template
                        $(file.previewTemplate).append("<input type=\"hidden\" name=\""+inputName+"\" value=\""+response.id+"\"/>");
                    }',
                    'removedfile' => 'function(file) {
                        //get attachment id for deleted file
                        var inputName = "'.Html::getInputName($model, 'attachments[]').'";
                        var deleteUrl = "'.Yii::$app->urlManager->createUrl('document/file/delete').'";

                        var attachment_id = $(file.previewTemplate).find("input:hidden").val();
                        $.post(deleteUrl, {"attachment_id": attachment_id}, function(response) {
                            response = JSON.parse(response);
                            console.log(response);
                        });
                    }',
                ], // dropzone event handlers
                'sortable'        => true, // sortable flag
                'sortableOptions' => [], // sortable options
                'htmlOptions'     => [], // container html options
                'options'         => [
                    'addRemoveLinks' => true,
                ], // dropzone js options
            ]
        );
        $field->template = "{label}$dropzoneWidgetHtml{error}";
        echo $field;
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
        <?php if (!$model->isNewRecord): ?>
            <?= Html::a(
                "Удалить",
                \yii\helpers\Url::toRoute(['/document/default/delete', 'id' => $model->primaryKey]),
                [
                    'class'        => 'btn btn-danger',
                    'title'        => 'Удалить',
                    'aria-label'   => 'Удалить',
                    'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                    'data-method'  => 'post',
                ]
            ) ?>
        <?php endif; ?>
        <?= Html::a("Список документов", \yii\helpers\Url::toRoute(['/document/default/index'])); ?>

    </div>
    <?php ActiveForm::end(); ?>

</div><!-- default-document -->

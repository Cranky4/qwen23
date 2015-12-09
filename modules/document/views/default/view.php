<?php
    /**
     * @var \yii\base\View $this
     * @var \app\modules\document\models\Document $model
     * @var \app\modules\document\models\Attachment[] $attachments
     */
    $this->title = $model->name;
?>
<h1><?= $model->name ?></h1>

<div class="document-view">
    <div class="row">
        <div class="description col-md-12">
            <?= $model->description; ?>
        </div>
        <?php if ($attachments): ?>
            <div class="files col-md-12">
                <h3>Приложение</h3>
                <table class="table table-striped">
                    <?php foreach ($attachments as $attachment): ?>
                        <tr>
                            <td class="link hidden-xs">
                                <?= \yii\helpers\Html::img(
                                    "/web".$attachment->getPreviewPath(),
                                    [
                                        'alt'   => $attachment->name,
                                        'class' => 'img file_preview',
                                    ]
                                ) ?>
                            </td>
                            <td class="filename">
                                <?= $attachment->name ?>
                            </td>
                            <td>
                                <a href="<?= $attachment->getPath() ?>"><span class="glyphicon glyphicon-search"></span></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>

        <div class="controls col-md-12">
            <?= \yii\helpers\Html::a(
                "Редактировать",
                \yii\helpers\Url::toRoute(['/document/default/update', 'id' => $model->primaryKey]),
                [
                    'class' => 'btn btn-warning',
                ]
            ); ?>
            <?= \yii\helpers\Html::a("Список документов", \yii\helpers\Url::toRoute(['/document/default/index'])); ?>
        </div>
    </div>
</div>

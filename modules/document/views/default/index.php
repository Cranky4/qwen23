<?php
    /**
     * @var $provider \yii\data\ActiveDataProvider
     */
?>
<div class="document-default-index">

    <?= \yii\grid\GridView::widget(
        [
            'dataProvider' => $provider,
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'name',
                'created:datetime',
                'updated:datetime',
                'attachmentsCount',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                ],
            ],
        ]
    ) ?>
    <a href="<?= \yii\helpers\Url::toRoute(['/document/default/add']) ?>" class="btn btn-info">Добавить документ</a>
</div>

<?php
    /**
     * @var $provider \yii\data\ActiveDataProvider
     * @var $this     \yii\base\View
     */
    $this->title = 'Документы';
?>
<h1>Документы</h1>
<div class="document-default-index">

    <?= \yii\grid\GridView::widget(
        [
            'dataProvider' => $provider,
            'columns'      => [
                'id',
                'name',
                [
                    'attribute'      => 'created',
                    'format'         => 'datetime',
                    'headerOptions'  => [
                        //hide column on small screens
                        'class' => 'hidden-xs',
                    ],
                    'contentOptions' => [
                        //hide column on small screens
                        'class' => 'hidden-xs',
                    ],
                ],
                [
                    'attribute'      => 'updated',
                    'format'         => 'datetime',
                    'headerOptions'  => [
                        //hide column on small screens
                        'class' => 'hidden-xs',
                    ],
                    'contentOptions' => [
                        //hide column on small screens
                        'class' => 'hidden-xs',
                    ],
                ],
                [
                    'attribute' => 'attachmentsCount',
                    'label'     => 'Файлы',
                ],
                [
                    'class'         => 'yii\grid\ActionColumn',
                    'template'      => '{update} {delete}',
                    'buttonOptions' => [
                        'class' => 'big',
                    ],
                ],
            ],
        ]
    ) ?>
    <a href="<?= \yii\helpers\Url::toRoute(['/document/default/add']) ?>" class="btn btn-info">Добавить документ</a>
</div>

<?php
    /**
     * Created by PhpStorm.
     * User: Cranky4
     * Date: 09.12.2015
     * Time: 6:27
     */
    namespace app\assets;

    use yii\web\AssetBundle;

    class JqueryUiAsset extends AssetBundle
    {
        public $sourcePath = '@bower/jquery-ui';

        public $js = [
            'jquery-ui.min.js'
        ];
    }
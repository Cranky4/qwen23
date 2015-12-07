<?php

    /**
     * Created by PhpStorm.
     * User: Cranky4
     * Date: 07.12.2015
     * Time: 11:52
     */
    namespace app\components;

    use app\modules\document\models\Document;
    use yii\helpers\Html;

    class DropZone extends \devgroup\dropzone\DropZone
    {

        protected function addFiles($files = [])
        {
            $this->view->registerJs('var files = '.\yii\helpers\Json::encode($files));
            $this->view->registerJs(
                '
                if(files) {
                    var inputName = "'.Html::getInputName(new Document(), 'attachments[]').'";
                    for (var i=0; i<files.length; i++) {
                        '.$this->dropzoneName.'.emit("addedfile", files[i]);
                        '.$this->dropzoneName.'.createThumbnailFromUrl(files[i], files[i]["imageUrl"]);
                        '.$this->dropzoneName.'.emit("complete", files[i]);

                        //add attachment id to preview template
                        $(files[i].previewTemplate).append("<input type=\"hidden\" name=\""+inputName+"\" value=\""+files[i].id+"\"/>");

                    }
                }'
            );
        }

    }
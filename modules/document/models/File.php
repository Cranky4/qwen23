<?php
    /**
     * Created by PhpStorm.
     * User: Cranky4
     * Date: 08.12.2015
     * Time: 10:37
     */

    namespace app\modules\document\models;

    use yii\base\Model;
    use yii\helpers\FileHelper;
    use yii\web\UploadedFile;

    class File extends Model
    {

        /**
         * @var UploadedFile
         */
        public $file;

        public function rules()
        {
            return [
                //
                [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, doc, txt, rtf, pdf, jpeg'],
            ];
        }

        /**
         * @return \app\modules\document\models\Attachment|bool
         * @throws \yii\base\Exception
         */
        public function upload()
        {
            if ($this->validate()) {
                $root = \Yii::getAlias('@webroot');
                $path = '/uploads/'.date('Y-m-d');
                $uploadPath = $root.$path;
                $file = $this->file;

                //check for the existence of a folder
                if (!file_exists($uploadPath)) {
                    FileHelper::createDirectory($uploadPath, 0777, true);
                }

                //check for file with same name is exists
                $srcPath = $path.'/'.$file->name;
                $savePath = $uploadPath.'/'.$file->name;
                if (file_exists($savePath)) {
                    $t = time();
                    $savePath = $uploadPath.'/'.$t."_".$file->name;
                    $srcPath = $path.'/'.$t."_".$file->name;
                }

                //move uploaded file and save it into database
                if ($file->saveAs($savePath)) {
                    return Attachment::createNew($file, $srcPath);
                }
            }

            return false;
        }


    }
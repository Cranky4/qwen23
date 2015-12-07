<?php
    /**
     * Created by PhpStorm.
     * User: Cranky4
     * Date: 06.12.2015
     * Time: 21:03
     */

    namespace app\modules\document\controllers;

    use app\modules\document\models\Attachment;
    use yii\helpers\FileHelper;
    use yii\helpers\VarDumper;
    use yii\web\Controller;

    class FileController extends Controller
    {

        public function actionUpload()
        {
            $fileName = 'attachments';
            $root = \Yii::getAlias('@webroot');
            $path = '/uploads/'.date('Y-m-d');
            $uploadPath = $root.$path;

            //check for the existence of a folder
            if (!file_exists($uploadPath)) {
                FileHelper::createDirectory($uploadPath, 0777, true);
            }

            //check for the existence of a sending file
            if (isset($_FILES[$fileName])) {
                $file = \yii\web\UploadedFile::getInstanceByName($fileName);

                if ($file->hasError) {
                    throw new \HttpException(500, 'Upload error');
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
                    $attachment = new Attachment();
                    $attachment->name = $file->name;
                    $attachment->size = $file->size;
                    $attachment->path = $srcPath;
                    if (!$attachment->save()) {
                        return false;
                    }

                    echo \yii\helpers\Json::encode($attachment);
                }
            }

            return false;
        }

        /**
         * Delete attachment from server and db.
         *
         * @var $attachment_id
         *
         * @return bool
         * @throws \Exception
         */
        public function actionDelete()
        {
            if (\Yii::$app->request->isPost && \Yii::$app->request->isAjax) {
                $attachment_id = \Yii::$app->request->post('attachment_id');
                $root = \Yii::getAlias('@webroot');
                //if attachment with id is exists
                if ($attachment = Attachment::findOne(["id" => $attachment_id])) {
                    //delete from db
                    $attachment->delete();

                    echo \yii\helpers\Json::encode(['status' => '1']);
                }
            }

            return false;
        }

    }
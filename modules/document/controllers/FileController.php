<?php
    /**
     * Created by PhpStorm.
     * User: Cranky4
     * Date: 06.12.2015
     * Time: 21:03
     */

    namespace app\modules\document\controllers;

    use app\modules\document\models\Attachment;
    use app\modules\document\models\File;
    use yii\web\Controller;
    use yii\web\HttpException;

    class FileController extends Controller
    {

        /**
         * @return bool
         * @throws \HttpException
         */
        public function actionUpload()
        {
            $fileName = 'attachments';

            //check for the existence of a sending file
            if (isset($_FILES[$fileName])) {
                $model = new File();
                $model->file = \yii\web\UploadedFile::getInstanceByName($fileName);
                if ($attachment = $model->upload()) {
                    echo \yii\helpers\Json::encode($attachment);
                } else {
                    throw new HttpException(400,"File upload error");
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
                //if attachment with id is exists
                if ($attachment = Attachment::findOne(["id" => $attachment_id])) {
                    /* @var Attachment $attachment */
                    $attachment->delete();

                    echo \yii\helpers\Json::encode(['status' => '1']);
                }
            }

            return false;
        }

    }
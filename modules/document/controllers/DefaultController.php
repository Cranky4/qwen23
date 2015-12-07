<?php

    namespace app\modules\document\controllers;

    use app\modules\document\models\Document;
    use yii\helpers\Url;
    use yii\helpers\VarDumper;
    use yii\web\Controller;
    use yii\web\HttpException;

    class DefaultController extends Controller
    {
        public function actionIndex()
        {
            return $this->render(
                'index',
                [
                    'provider' => Document::getDataProvider(),
                ]
            );
        }

        public function actionAdd()
        {
            return $this->render(
                'edit',
                [
                    'model' => new Document(),
                ]
            );
        }

        public function actionUpdate($id)
        {
            /**
             * @var Document $model
             */
            $model = Document::findOne(['id' => $id]);
            if (null == $model) {
                throw new HttpException(404);
            }

            return $this->render(
                'edit',
                [
                    'model'       => $model,
                    'storedFiles' => $model->getStoredFiles(),
                ]
            );
        }

        /**
         * @param null $id
         *
         * @throws \yii\web\HttpException
         */
        public function actionSave($id = null)
        {
            if (null !== $id) {
                $model = Document::findOne(['id' => $id]);
                if (null === $model) {
                    throw new HttpException(404);
                }
            } else {
                $model = new Document();
            }
            if ($model->load(\Yii::$app->request->post())) {
                if ($document_id = $model->saveDocument()) {
                    $this->redirect(Url::toRoute(['/document/default/update/', 'id' => $document_id]));
                }
            }
        }

        public function actionDelete($id)
        {
            /**
             * @var Document $document
             */
            if (\Yii::$app->request->isPost && ($document = Document::findOne(["id" => $id]))) {
                $document->delete();
                $this->redirect(Url::toRoute(['/document/default/index']));
            }
            throw new HttpException(404);
        }

    }

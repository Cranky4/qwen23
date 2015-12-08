<?php

    namespace app\modules\document\controllers;

    use app\modules\document\models\Document;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\web\HttpException;

    class DefaultController extends Controller
    {
        /**
         * Documents list page
         * @return string
         */
        public function actionIndex()
        {
            return $this->render(
                'index',
                [
                    'provider' => Document::getDataProvider(),
                ]
            );
        }

        /**
         * Add document page
         * @return string
         */
        public function actionAdd()
        {
            return $this->render(
                'edit',
                [
                    'model' => new Document(),
                ]
            );
        }

        /**
         * Update document page
         *
         * @param $id
         *
         * @return string
         * @throws \yii\web\HttpException
         */
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
         * Save or update document
         *
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

        /**
         * Delete document
         *
         * @param $id
         *
         * @throws \yii\web\HttpException
         */
        public function actionDelete($id)
        {
            /* @var Document $document */
            if (\Yii::$app->request->isPost && ($document = Document::findOne(["id" => $id]))) {
                $document->delete();
                $this->redirect(Url::toRoute(['/document/default/index']));
            }
            throw new HttpException(404);
        }

    }

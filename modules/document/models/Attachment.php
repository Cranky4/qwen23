<?php

    namespace app\modules\document\models;

    use Yii;

    /**
     * This is the model class for table "attachment".
     *
     * @property integer $id
     * @property string $name
     * @property integer $size
     * @property integer $document_id
     * @property string $path
     * @property integer $sequence
     *
     * @property Document $document
     */
    class Attachment extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'attachment';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['name'], 'required'],
                [['size', 'document_id', 'sequence'], 'integer'],
                [['name', 'path'], 'string', 'max' => 255],
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'          => 'ID',
                'name'        => 'Название файла',
                'size'        => 'Размер файла',
                'document_id' => 'Документ',
                'path'        => 'Путь',
                'sequence'    => 'Порядок',
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getDocument()
        {
            return $this->hasOne(Document::className(), ['id' => 'document_id']);
        }

        /**
         * Delete file
         */
        public function afterDelete()
        {
            $root = Yii::getAlias('@webroot');
            if (file_exists($root.$this->path)) {
                unlink($root.$this->path);
            }

            parent::afterDelete();
        }


    }

<?php

    namespace app\modules\document\models;

    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\FileHelper;
    use yii\web\UploadedFile;

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
        const DEFAULT_FILE_PREVIEW = "/uploads/default_file_preview.png";

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'attachments';
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

        /**
         * @param \yii\web\UploadedFile $file
         * @param $srcPath
         *
         * @return \app\modules\document\models\Attachment|bool
         */
        public static function createNew(UploadedFile $file, $srcPath)
        {
            $attachment = new self();
            $attachment->name = $file->name;
            $attachment->size = $file->size;
            $attachment->path = $srcPath;
            if (!$attachment->save()) {
                return false;
            }

            return $attachment;
        }

        public function behaviors()
        {
            return [
                //create and update timestamps after save model
                [
                    'class'              => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created',
                    'updatedAtAttribute' => 'updated',
                    'value'              => new Expression('UNIX_TIMESTAMP()'),
                ],
            ];
        }

        /**
         * @return string
         */
        public function getPath()
        {
            if ($this->path) {
                return "/web".$this->path;
            }

            return false;
        }

        /**
         * @return bool
         * @throws \yii\base\InvalidConfigException
         */
        public function isImage()
        {
            if ($file = $this->path) {
                $root = Yii::getAlias('@webroot');
                $mimeType = FileHelper::getMimeType($root.$file);
                $mimeTypePieces = explode("/", $mimeType);
                if ($mimeTypePieces[0] && $mimeTypePieces[0] == 'image') {
                    return true;
                }
            }

            return false;
        }

        /**
         * @return string
         */
        public function getPreviewPath()
        {
            if ($this->isImage()) {
                return $this->path;
            }

            return self::DEFAULT_FILE_PREVIEW;
        }

    }

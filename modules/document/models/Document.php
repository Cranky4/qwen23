<?php

    namespace app\modules\document\models;

    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\data\ActiveDataProvider;
    use yii\db\ActiveQuery;
    use yii\db\Expression;
    use yii\web\HttpException;

    /**
     * This is the model class for table "document".
     *
     * @property integer $id
     * @property string $name
     * @property string $description
     * @property integer $created
     * @property integer $updated
     *
     * @property Attachment[] $attachments
     */
    class Document extends \yii\db\ActiveRecord
    {
        /**
         * Array of attachments id
         * @var array
         */
        public $attachments;

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'documents';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['name'], 'required'],
                [['description'], 'string'],
                [['created', 'updated'], 'integer'],
                [['name'], 'string', 'max' => 255],
                [['attachments'], 'safe'],
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'               => 'ID',
                'name'             => 'Название',
                'description'      => 'Описание',
                'created'          => 'Дата создания',
                'updated'          => 'Дата обновления',
                'attachments'      => 'Приложение',
                'attachmentsCount' => 'Количество файлов',
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getAttachments()
        {
            return $this->hasMany(Attachment::className(), ['document_id' => 'id'])->orderBy('sequence');
        }

        /**
         * @return bool
         */
        public function saveAttachments()
        {
            if (is_array($this->attachments)) {
                $sequence = 0;
                foreach ($this->attachments as $attachment_id) {
                    //save link to document and sequence
                    if ($attachment = Attachment::findOne(['id' => $attachment_id])) {
                        $attachment->document_id = $this->primaryKey;
                        $attachment->sequence = $sequence;
                        $attachment->save();

                        $sequence++;
                    }
                }

                return true;
            }

            return false;
        }

        /**
         * Save document with attachments
         * @return bool|mixed
         * @throws \yii\web\HttpException
         */
        public function saveDocument()
        {
            if ($this->save()) {
                if ($this->attachments && !$this->saveAttachments()) {
                    throw new HttpException(500, "Ошибка сохранения приложения");
                }

                return $this->primaryKey;
            } else {
                Yii::$app->session->setFlash('saveDocumentStatus', $this->getErrors());
            }

            return false;
        }

        /**
         * @param \yii\db\ActiveQuery|null $query
         *
         * @return \yii\data\ActiveDataProvider
         */
        public static function getDataProvider(ActiveQuery $query = null)
        {
            if (null == $query) {
                $query = self::find();
            }

            return new ActiveDataProvider(
                [
                    'query'      => $query,
                    'pagination' => false,
                    'sort'       => [
                        'defaultOrder' => [
                            'created' => SORT_DESC,
                            'name'    => SORT_ASC,
                        ],
                    ],
                ]
            );
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
         * @return int|string
         */
        public function getAttachmentsCount()
        {
            return $this->hasMany(Attachment::className(), ['document_id' => 'id'])->count();
        }

        /**
         * @return array
         */
        public function getStoredFiles()
        {
            $attachments = $this->getAttachments()->all();
            $storedFiles = [];
            $_file = [];
            foreach ($attachments as $attachment) {
                $_file['id'] = $attachment->primaryKey;
                $_file['name'] = $attachment->name;
                $_file['size'] = $attachment->size;
                $_file['imageUrl'] = '/web'.$attachment->path;

                $storedFiles[] = $_file;
            }

            return $storedFiles;
        }

        /**
         * Delete linked attachments
         */
        public function beforeDelete()
        {
            if ($this->getAttachmentsCount()) {
                foreach ($this->getAttachments()->all() as $attachment) {
                    $attachment->delete();
                }
            }

            return parent::beforeDelete();
        }
    }

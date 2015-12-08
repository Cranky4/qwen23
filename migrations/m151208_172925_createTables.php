<?php

    use yii\db\Schema;
    use yii\db\Migration;

    class m151208_172925_createTables extends Migration
    {
        public function up()
        {
            //create table Documents
            $this->createTable(
                "{{%documents}}",
                [
                    "id"          => Schema::TYPE_PK." NOT NULL AUTO_INCREMENT",
                    "name"        => Schema::TYPE_STRING." NOT NULL DEFAULT '0' COMMENT 'Название'",
                    "description" => Schema::TYPE_TEXT." NULL COMMENT 'Описание'",
                    "created"     => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Дата создания'",
                    "updated"     => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Дата обновления'",
                ],
                "COMMENT='Документы' COLLATE='utf8_general_ci' ENGINE=InnoDB"
            );

            //create table Attachments
            $this->createTable(
                "{{%attachments}}",
                [
                    "id"          => Schema::TYPE_PK." NOT NULL AUTO_INCREMENT",
                    "name"        => Schema::TYPE_STRING." NOT NULL COMMENT 'Название файла'",
                    "size"        => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Размер файла'",
                    "document_id" => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Документ'",
                    "path"        => Schema::TYPE_STRING." NULL DEFAULT NULL COMMENT 'Путь'",
                    "sequence"    => Schema::TYPE_STRING." NULL DEFAULT NULL COMMENT 'Порядок'",
                    "created"     => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Дата создания'",
                    "updated"     => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Дата обновления'",
                ],
                "COMMENT='Приложение' COLLATE='utf8_general_ci' ENGINE=InnoDB"
            );

            //foreign keys
            $this->createIndex("FK_attachment_document", "attachments", "document_id");
            $this->addForeignKey(
                "FK_attachment_document",
                "attachments",
                "document_id",
                "documents",
                "id",
                "CASCADE",
                "SET NULL"
            );
        }

        public function down()
        {
            //drop database attachments
            $this->dropTable("{{%attachments}}");
            //drop database documents
            $this->dropTable('{{%documents}}');
        }
    }

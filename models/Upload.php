<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Upload model
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $record_id
 * @property string $dir
 * @property string $file_name
 * @property string $file_extensions
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 */

class Upload extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'upload';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            ['file', 'file', 'skipOnEmpty' => false, 'extensions' => 'zip, 7z, pdf, rtf, doc, docx, xls, xlsx, jpg, jpeg, png, txt', 'maxSize' => 25*(1024*1024), 'tooBig' => 'Превышен максимально допустимый размер (объём) файла в 25 Мб'],

            ['type', 'string', 'max' => 255],
            ['type', 'required'],

            ['record_id', 'integer'],
            ['record_id', 'required'],

            ['user_id', 'integer'],
            ['user_id', 'required'],

            ['name', 'string', 'max' => 255],
            ['name', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['name', 'trim'],
            ['name', 'required'],

            ['dir', 'string', 'max' => 255],
            ['dir', 'required'],

            ['file_name', 'string', 'max' => 255],
            ['file_name', 'required'],

            ['file_extensions', 'string', 'max' => 255],
            ['file_extensions', 'required'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            FileHelper::createDirectory("upload/$this->dir");
            $this->file->saveAs("upload/$this->dir/$this->file_name.$this->file_extensions");
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'file' => 'Файл',
        ];
    }
}
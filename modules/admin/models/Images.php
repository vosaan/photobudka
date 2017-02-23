<?php

namespace app\modules\admin\models;

use Yii;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property integer $album_id
 * @property string $path
 * @property string $is_cover
 */
class Images extends ActiveRecord
{
    public $imageFiles;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['album_id', 'path'], 'required'],
            [['album_id'], 'integer'],
            [['is_cover'], 'string'],
            [['path'], 'string', 'max' => 255],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'album_id' => 'Album ID',
            'path' => 'Path',
            'is_cover' => 'Is Cover',
        ];
    }

    public function uploadAndSave($albumId){
        if($this->validate(['imageFiles'])){
            $imagesArray = [];

            $this->album_id = $albumId;
            $this->checkDirectory($albumId);
            if(!$this->checkDirectory($albumId)){
                $count = 1;
            } else {
                $count = $this->checkDirectory($albumId) + 1;
            }

            foreach ($this->imageFiles as $file){
                $filename = $this->generateFileName($albumId, $count) . '.' . $file->extension;
                /*save original*/
                $file->saveAs("uploads/{$albumId}/" . $filename);

                /*get image size*/
                $width = Image::getImagine()->open("uploads/{$albumId}/" . $filename)->getSize()->getWidth();
                $height = Image::getImagine()->open("uploads/{$albumId}/" . $filename)->getSize()->getHeight();
                $newImageSize = [];

                /*save middle size*/
                $newImageSize = $this->getImageSize($width, $height, 800);

                Image::getImagine()
                    ->open("uploads/{$albumId}/" . $filename)
                    ->thumbnail(new Box($newImageSize['width'], $newImageSize['height']))
                    ->save("uploads/{$albumId}/middle/" . $filename , ['quality' => 70]);

                /*save thumbnail*/
                $newImageSize = $this->getImageSize($width, $height, 120);

                Image::getImagine()
                    ->open("uploads/{$albumId}/" . $filename)
                    ->thumbnail(new Box($newImageSize['width'], $newImageSize['height']))
                    ->save("uploads/{$albumId}/thumbnails/" . $filename , ['quality' => 50]);

                $imagesArray[] = [
                    $filename,
                    $albumId
                ];
                $count++;
            }

            $connection = Yii::$app->db;
            $connection->createCommand()->batchInsert($this::tableName(), ['path', 'album_id'], $imagesArray)->execute();
        }
    }

    /**/
    public function deleteAlbum($album_id){
        FileHelper::removeDirectory('uploads/'.$album_id);
        $this::deleteAll(['album_id' => $album_id]);
    }

    public function deleteImages($idArr)
    {
        foreach ($idArr as $id){
            $image = self::findOne($id);
            $album_id = $image->album_id;
            $path = $image->path;
            unlink('uploads/' . $album_id.'/' . $path);
            unlink('uploads/' . $album_id.'/middle/' . $path);
            unlink('uploads/' . $album_id.'/thumbnails/' . $path);
            $this::deleteAll(['id' => $id]);
        }

        return true;
    }

    /*Метод проверяет, существует ли папка альбома и если нет - создаёт (и подпапки тоже),
    иначе - вернуть порядковый номер последнего файла в этой папке*/
    private function checkDirectory($albumId){
        if(!file_exists("uploads/{$albumId}")){
            mkdir("uploads/{$albumId}", 0775, true);
            mkdir("uploads/{$albumId}/thumbnails", 0775, true);
            mkdir("uploads/{$albumId}/middle", 0775, true);
            return false;
        } else {
            $filesArray = FileHelper::findFiles("uploads/{$albumId}", ['only' => ['*.jpg'], 'recursive' => false]);
            return $this->getLastFileNumber($filesArray);
        }
    }

    /*Метод возвращает имя файла в формате <id_альбома>-<номер_файла.jpg>*/
    private function generateFileName($albumId, $count){
        return $albumId . '-' . substr('00' . $count, -3);
    }

    /*Метод возвращает массив со значением ширины и высоты картинки, размер которой нижно изменить. Получает оригинальный
    размер картинки и значение наибольшей стороны картинки*/
    private function getImageSize($width, $height, $maxsize){
        if($width > $height){
            $newWidth = $maxsize;
            $newHeight = (integer)ceil(($height * $maxsize) / $width);
            return ['width' => $newWidth, 'height' => $newHeight];
        } elseif ($width < $height){
            $newHeight = $maxsize;
            $newWidth = (integer)ceil(($width * $maxsize)/$height);
            return ['width' => $newWidth, 'height' => $newHeight];
        } elseif ($width == $height){
            return ['width' => $maxsize, 'height' => $maxsize];
        }
    }

    private function getLastFileNumber($filesArray)
    {
        $lastFileName = $filesArray[count($filesArray) - 1];
        $lastFileName = trim($lastFileName, '.jpg');
        return $number = (int)substr($lastFileName, -3); die;
    }

    /*Метод удаляет папку альбома со всеми файлами и подпапками*/
//    private function delAlbumFolderById($albumId){
//        if ($objs = glob('uploads/' . $albumId . "/*")) {
//            foreach($objs as $obj) {
//                is_dir($obj) ? $this->delAlbumFolderById($obj) : unlink($obj);
//            }
//        }
//        rmdir($albumId);
//
//    }
}

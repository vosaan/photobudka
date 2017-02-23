<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Album;
use app\modules\admin\models\Images;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Inflector;
use sbs\helpers\TransliteratorHelper;
/**
 * AlbumController implements the CRUD actions for Album model.
 */
class AlbumController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Album models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Album::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Album model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $imagesList = Images::find()->asArray()->where(['album_id' => $id])->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'imagesList' => $imagesList
        ]);
    }

    /**
     * Creates a new Album model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $modelAlbum = new Album();
        $modelImage = new Images();

        if ($modelAlbum->load(Yii::$app->request->post()) && $modelImage->load(Yii::$app->request->post())) {
            if($modelAlbum->validate()){
                $modelAlbum->save(false);
            }

            $modelImage->imageFiles = UploadedFile::getInstances($modelImage, 'imageFiles');
            $modelImage->uploadAndSave($modelAlbum->id);

            return $this->redirect(['view', 'id' => $modelAlbum->id]);
        } else {
            return $this->render('create', [
                'modelAlbum' => $modelAlbum,
                'modelImage' => $modelImage
            ]);
        }
    }

    /**
     * Updates an existing Album model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $modelImage = new Images();
        $imagesList = Images::find()->asArray()->where(['album_id' => $id])->all();

        $modelAlbum = $this->findModel($id);

        if ($modelAlbum->load(Yii::$app->request->post()) && $modelImage->load(Yii::$app->request->post())) {

            if($modelAlbum->validate()){
                $modelAlbum->edit_date = date('Y-m-d H:i:s');
                $modelAlbum->save(false);
            }

            if (Yii::$app->request->post('delImages')){
                $imagesIdToDeleteArr = Yii::$app->request->post('delImages');
                $modelImage->deleteImages($imagesIdToDeleteArr);
            }

            $modelImage->imageFiles = UploadedFile::getInstances($modelImage, 'imageFiles');
            $modelImage->uploadAndSave($modelAlbum->id);

            return $this->redirect(['view', 'id' => $modelAlbum->id]);
        } else {
            return $this->render('update', [
                'modelAlbum' => $modelAlbum,
                'modelImage' => $modelImage,
                'imagesList' => $imagesList
            ]);
        }
    }

    /**
     * Deletes an existing Album model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $image = new Images();

        $image->deleteAlbum($id);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Album model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Album the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Album::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGenerateSlug()
    {
        if(Yii::$app->request->isAjax){
            $title = Yii::$app->request->post('title');
            echo Inflector::slug(TransliteratorHelper::process($title), '-', true);
        }
    }
}

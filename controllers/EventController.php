<?php

namespace app\controllers;

use app\models\Event;
use app\models\EventSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index'],
                            'roles' => ['admin', 'viewEvents'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['view'],
                            'roles' => ['admin', 'viewEvents'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'roles' => ['admin', 'createEvents'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['update'],
                            'roles' => ['admin', 'updateEvents'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['delete'],
                            'roles' => ['admin'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Event models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Event model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $user_id = Yii::$app->user->identity->id;

        $model = $this->findModel($id);

        if ($model !== null and !Yii::$app->user->can('admin')) {
            if($model->resolution != NULL and !in_array($user_id, (array)$model->resolution) and $model->user_id != $user_id){
                throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
            }
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Event();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->user_id = Yii::$app->user->identity->id;

            if($model->resolution == NULL){
                if(Yii::$app->user->can('admin') or Yii::$app->user->can('eventsAdmin')){
                    $model->resolution == NULL;
                }else{
                    $model->resolution = [sprintf('%s', Yii::$app->user->identity->id)];
                }
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->isAccess($id);

        if($this->request->isPost && $model->load($this->request->post())){

            if($model->resolution == NULL){
                if(Yii::$app->user->can('admin') or Yii::$app->user->can('eventsAdmin')){
                    $model->resolution == NULL;
                }else{
                    $model->resolution = [sprintf('%s', Yii::$app->user->identity->id)];
                }
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }

    /**
     * @param $id
     * @return bool
     */
    public function isAccess($id)
    {
        $user_id = Yii::$app->user->identity->id;

        if ((Event::findOne(['id' => $id])) !== null and Yii::$app->user->can('admin')) {
            return true;
        }elseif((Event::findOne(['id' => $id, 'user_id' => $user_id])) !== null){
            return true;
        }else{
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }
    }
}

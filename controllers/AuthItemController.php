<?php

namespace app\controllers;

use app\models\AuthItem;
use app\models\AuthItemChild;
use app\models\AuthItemSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends Controller
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
                        'permission-add' => ['POST'],
                        'permission-delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index'],
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['view'],
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['update'],
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['permission-add'],
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['permission-delete'],
                            'roles' => ['admin'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all AuthItem models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $name Name
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($name)
    {
        $permission = new ActiveDataProvider([
            'query' => AuthItemChild::find()->where(['parent' => $name]),
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-permission',
                'params' => [
                    'name' => $name,
                    '#' => 'permission',
                    'page-permission' => Yii::$app->request->get('page-permission'),
                ],
            ],
        ]);

        $authItemChild = new AuthItemChild();

        return $this->render('view', [
            'model' => $this->findModel($name),
            'permission' => $permission,
            'authItemChild' => $authItemChild
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AuthItem();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->name = Inflector::slug($model->description);
            $model->type = 1;
            if ($model->save()) {
                return $this->redirect(['view', 'name' => $model->name]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $name Name
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($name)
    {
        $model = $this->findModel($name);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->name = Inflector::slug ($model->description);
            if($model->save()){
                return $this->redirect(['view', 'name' => $model->name]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $name Name
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    /*
    public function actionDelete($name)
    {
        $this->findModel($name)->delete();

        return $this->redirect(['index']);
    }
    */

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name Name
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name)
    {
        if (($model = AuthItem::findOne(['name' => $name])) !== null and AuthItem::findOne(['name' => $name])->name !== 'admin') {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }

    /**
     * @param $name
     * @return Response
     */
    public function actionPermissionAdd($name)
    {
        $model = new AuthItemChild();

        if($this->request->isPost){
            $model->parent = $name;
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['auth-item/view', 'name' => $name, '#' => 'permission']);
            }
        }

        return $this->redirect(['auth-item/view', 'name' => $name, '#' => 'permission']);
    }

    /**
     * @param $parent
     * @param $child
     * @return Response
     */
    public function actionPermissionDelete($parent, $child)
    {
        $auth = Yii::$app->authManager;

        if ($this->request->isPost) {
            $auth->removeChild($auth->getRole($parent), $auth->getPermission($child));
        }

        return $this->redirect(['auth-item/view', 'name' => $parent, '#' => 'permission']);
    }
}

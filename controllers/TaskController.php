<?php

namespace app\controllers;

use app\models\Discussion;
use app\models\Document;
use app\models\DocumentTask;
use app\models\Group;
use app\models\Task;
use app\models\TaskFavourites;
use app\models\TaskSearch;
use app\models\View;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
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
                            'roles' => ['admin', 'user', 'editor'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['view'],
                            'roles' => ['admin', 'user', 'editor'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'roles' => ['admin', 'editor'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['update'],
                            'roles' => ['admin', 'editor', 'user'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['delete'],
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['viewed'],
                            'roles' => ['admin', 'user', 'editor'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['publish'],
                            'roles' => ['admin', 'editor'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['add-document'],
                            'roles' => ['admin', 'editor'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['document-delete'],
                            'roles' => ['admin', 'editor'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['task-list'],
                            'roles' => ['admin', 'editor'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['favourites'],
                            'roles' => ['admin', 'editor', 'user'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['discussion-create'],
                            'roles' => ['admin', 'editor', 'user'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['discussion-delete'],
                            'roles' => ['admin', 'editor', 'user'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Task models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $document_task = new DocumentTask();

        if(!$this->isAccess($id) and $model != null){
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }

        $user_id = Yii::$app->user->identity->id;

        $viewed = new ActiveDataProvider([
            'query' => View::find()->where(['type' => 'task', 'record_id' => $id])->orderBy('id DESC'),
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-viewed',
                'params' => [
                    'id' => $id,
                    '#' => 'event',
                    'page-viewed' => Yii::$app->request->get('page-viewed'),
                ],
            ],
        ]);

        $query = $document_task->find()->joinWith('document')->where(['document_task.task_id' => $id]);

        if(Yii::$app->user->can('admin')){
            $documents = $query;
        }else{
            $documents = $query->andWhere(['like', 'document.resolution', sprintf('"%s"', $user_id)])->andWhere(['document.status' => Document::STATUS_ACTIVE])->orWhere(['document_task.task_id' => $id, 'document.resolution' => NULL, 'document.status' => Document::STATUS_ACTIVE])->orWhere(['document_task.task_id' => $id, 'document.user_id' => $user_id]);
        }

        $documents = new ActiveDataProvider([
            'query' => $documents,
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-document',
                'params' => [
                    'id' => $id,
                    '#' => 'document',
                    'page-document' => Yii::$app->request->get('page-document'),
                ],
            ],
        ]);

        if(is_array($this->findModel($id)->resolution)){
            $viewed_button = (View::findOne(['type' => 'task', 'record_id' => $id, 'user_id' => $user_id]) == null and (in_array($user_id, $this->findModel($id)->resolution) or $this->findModel($id)->executor_id == $user_id));
        }else{
            $viewed_button = View::findOne(['type' => 'task', 'record_id' => $id, 'user_id' => $user_id]) == null;
        }

        $favourites = TaskFavourites::findOne(['task_id' => $id, 'user_id' => Yii::$app->user->identity->getId()]);

        $discussion = new Discussion();
        $discussion_query = Discussion::find()->where(['type' => 'task', 'record_id' => $id])->orderBy('id DESC');

        $discussions = new ActiveDataProvider([
            'query' => $discussion_query,
            'pagination' => [
                'pageSize' => 4,
                'pageParam' => 'page-discussions',
                'params' => [
                    'id' => $id,
                    '#' => 'discussions',
                    'page-discussions' => Yii::$app->request->get('page-discussions'),
                ],
            ],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'viewed' => $viewed,
            'viewed_button' => $viewed_button,
            'documents' => $documents,
            'document_task' => $document_task,
            'favourites' => $favourites,
            'discussion' => $discussion,
            'discussion_count' => $discussion_query->count(),
            'discussions' => $discussions,
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Task();
        $user_id = Yii::$app->user->identity->id;

        $groups = new ActiveDataProvider([
            'query' => Group::find()->where(['status' => Group::STATUS_ACTIVE, 'user_id' => $user_id]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if ($this->request->isPost) {
            $model->user_id = Yii::$app->user->identity->id;
            $model->uniq_id = strtoupper(uniqid());
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'groups' => $groups,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->user->can('admin') or $model->user_id == Yii::$app->user->identity->id or $model->executor_id == Yii::$app->user->identity->id){
            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }else{
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Task|array|\yii\db\ActiveRecord
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = Task::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionViewed($id)
    {
        $model = $this->findModel($id);
        $view = new View();
        $user_id = Yii::$app->user->identity->id;

        if(is_array($model->resolution)){
            if(in_array($user_id, $model->resolution)){
                $view->type = 'task';
                $view->user_id = $user_id;
                $view->record_id = (int) $id;
            }
        }else{
            $view->type = 'task';
            $view->user_id = $user_id;
            $view->record_id = (int) $id;
        }

        if($model->user_id != $user_id){
            $view->save();
        }

        return $this->redirect(['view', 'id' => $model->id, '#' => 'event']);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionPublish($id)
    {
        $model = $this->findModel($id);

        if($model->status == Task::STATUS_DRAFT){
            $model->status = Task::STATUS_ACTIVE;
        }

        if($model->save()){
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->refresh();
    }

    /**
     * @param $id
     * @return bool
     */
    public function isAccess($id)
    {
        $user_id = Yii::$app->user->identity->id;

        if ((Task::findOne(['id' => $id])) !== null and Yii::$app->user->can('admin')) {
            return true;
        }elseif((Task::find()->where(['id' => $id])->andWhere(['like', 'resolution', sprintf('"%s"', $user_id)])->andWhere(['status' => [Task::STATUS_ACTIVE, Task::STATUS_INACTIVE]])->orWhere(['id' => $id, 'resolution' => NULL, 'status' => [Task::STATUS_ACTIVE, Task::STATUS_INACTIVE]])->orWhere(['id' => $id, 'user_id' => $user_id])->orWhere(['id' => $id, 'executor_id' => $user_id, 'status' => Document::STATUS_ACTIVE])->one()) !== null){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionAddDocument($id)
    {
        $model = new DocumentTask();

        if($this->findModel($id)->user_id == Yii::$app->user->identity->id or Yii::$app->user->can('admin')){
            if ($this->request->isPost) {
                $model->task_id = $id;
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $id, '#' => 'document']);
                }
            }
        }

        return $this->redirect(['view', 'id' => $id, '#' => 'document']);
    }

    /**
     * @param $id
     * @param $document
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDocumentDelete($id, $document)
    {

        if(($model = DocumentTask::findOne(['task_id' => $id, 'document_id' => $document])) != null){
            if($this->findModel($id)->user_id == Yii::$app->user->identity->id or Yii::$app->user->can('admin')){
                $model->delete();
            }
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'document']);
    }

    /**
     * @param $q
     * @param $id
     * @return array[]
     * @throws \yii\db\Exception
     */
    public function actionTaskList($q = null, $id = null) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $user_id = Yii::$app->user->identity->id;
        $q = trim($q);
        if (!is_null($q)) {
            $query = new Query;
            if(Yii::$app->user->can('admin')){
                $query->select(['id', "name AS text"])
                    ->from('task')
                    ->where([
                        'OR',
                        ['like', 'name', $q],
                        ['like', 'uniq_id', $q]
                    ])
                    ->andWhere(['status' => Document::STATUS_ACTIVE])
                    ->limit(30);
            }else{
                $query->select(['id', "name AS text"])
                    ->from('task')
                    ->orWhere(['resolution' => NULL, 'status' => [Document::STATUS_ACTIVE]])->andWhere([
                        'OR',
                        ['like', 'name', $q],
                        ['like', 'uniq_id', $q]
                    ])
                    ->orWhere(['user_id' => $user_id, 'status' => [Document::STATUS_ACTIVE]])->andWhere([
                        'OR',
                        ['like', 'name', $q],
                        ['like', 'uniq_id', $q]
                    ])
                    ->orWhere(['like', 'resolution', sprintf('"%s"', $user_id)])->andWhere(['status' => [Document::STATUS_ACTIVE]])->andWhere([
                        'OR',
                        ['like', 'name', $q],
                        ['like', 'uniq_id', $q]
                    ])
                    ->limit(30);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $document = Document::findOne($id);
            $text = $document->name;
            $out['results'] = ['id' => $id, 'text' => $text];
        }
        return $out;
    }

    /**
     * @param $id
     * @return Response
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionFavourites($id)
    {
        $model = new TaskFavourites();
        $user_id = Yii::$app->user->identity->id;

        if(($row = $model::findOne(['task_id' => $id, 'user_id' => $user_id])) !== null){
            $row->delete();
        }else{
            $model->task_id = $id;
            $model->user_id = $user_id;
            $model->save();
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDiscussionCreate($id)
    {
        $model = $this->findModel($id);
        $discussion = new Discussion();

        if ($this->request->isPost) {
            $discussion->user_id = Yii::$app->user->identity->id;
            $discussion->type = 'task';
            $discussion->record_id = $model->id;
            if ($model->discussion == $model::DISCUSSION_ENABLE && $discussion->load($this->request->post()) && $discussion->save()) {
                return $this->redirect(['view', 'id' => $model->id, '#' => 'discussions']);
            }
        } else {
            $model->refresh();
        }

        return $this->redirect(['view', 'id' => $model->id, '#' => 'discussions']);
    }

    /**
     * @param $id
     * @param $discussion
     * @return Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionDiscussionDelete($id, $discussion)
    {
        $model = $this->findModel($id);
        $discussion = Discussion::findOne($discussion);

        if($discussion !== null){
            if(Yii::$app->user->can('admin') or $discussion->user_id == Yii::$app->user->identity->id){
                $discussion->delete();
            }
        }

        return $this->redirect(['view', 'id' => $model->id, '#' => 'discussions']);
    }
}
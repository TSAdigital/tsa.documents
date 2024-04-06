<?php

namespace app\controllers;

use app\models\Discussion;
use app\models\Document;
use app\models\DocumentFavourites;
use app\models\DocumentSearch;
use app\models\DocumentTask;
use app\models\Group;
use app\models\SignDocument;
use app\models\Task;
use app\models\Upload;
use app\models\User;
use app\models\View;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
{
    /**
     * @return array
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
                        'file-delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index'],
                            'roles' => ['admin', 'viewDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['view'],
                            'roles' => ['admin', 'viewDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'roles' => ['admin', 'createDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['update'],
                            'roles' => ['admin', 'updateDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['delete'],
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['upload'],
                            'roles' => ['admin', 'updateDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['download'],
                            'roles' => ['admin', 'viewDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['file-delete'],
                            'roles' => ['admin', 'updateDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['viewed'],
                            'roles' => ['admin', 'viewDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['publish'],
                            'roles' => ['admin', 'createDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['document-list'],
                            'roles' => ['admin', 'viewDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['add-task'],
                            'roles' => ['admin', 'createTasks'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['task-delete'],
                            'roles' => ['admin', 'updateTasks'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['favourites'],
                            'roles' => ['admin', 'viewDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['discussion-create'],
                            'roles' => ['admin', 'viewDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['discussion-delete'],
                            'roles' => ['admin', 'viewDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['sign'],
                            'roles' => ['admin', 'viewDocuments'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['sign-info'],
                            'roles' => ['admin', 'viewDocuments'],
                        ],
                    ],
                ],
            ]
        );
    }


    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $document_task = new DocumentTask();

        if(!$this->isAccess($id) and $this->findModel($id)!= null){
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }

        $user_id = Yii::$app->user->identity->id;

        $file = new ActiveDataProvider([
            'query' => Upload::find()->where(['type' => 'document', 'record_id' => $id])->orderBy('id DESC'),
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-file',
                'params' => [
                    'id' => $id,
                    '#' => 'file',
                    'page-file' => Yii::$app->request->get('page-file'),
                ],
            ],
        ]);

        $viewed = new ActiveDataProvider([
            'query' => View::find()->where(['type' => 'document', 'record_id' => $id])->orderBy('id DESC'),
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

        $sign = new ActiveDataProvider([
            'query' => SignDocument::find()->where(['document_id' => $id])->orderBy('id DESC'),
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-sign',
                'params' => [
                    'id' => $id,
                    '#' => 'event',
                    'page-viewed' => Yii::$app->request->get('page-sign'),
                ],
            ],
        ]);

        $query = $document_task->find()->joinWith('task')->where(['document_task.document_id' => $id]);

        if(Yii::$app->user->can('admin')){
            $tasks = $query;
        }else{
            $tasks = $query->andWhere(['like', 'task.resolution', sprintf('"%s"', $user_id)])->andWhere(['task.status' => Task::STATUS_ACTIVE])->orWhere(['document_task.document_id' => $id, 'task.resolution' => NULL, 'task.status' => Task::STATUS_ACTIVE])->orWhere(['document_task.document_id' => $id, 'task.user_id' => $user_id]);
        }

        $tasks = new ActiveDataProvider([
            'query' => $tasks,
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-task',
                'params' => [
                    'id' => $id,
                    '#' => 'task',
                    'page-task' => Yii::$app->request->get('page-task'),
                ],
            ],
        ]);

        if(is_array($this->findModel($id)->resolution)){
            $viewed_button = (View::findOne(['type' => 'document', 'record_id' => $id, 'user_id' => $user_id]) == null and (in_array($user_id, $this->findModel($id)->resolution) or $this->findModel($id)->executor_id == $user_id));
        }else{
            $viewed_button = View::findOne(['type' => 'document', 'record_id' => $id, 'user_id' => $user_id]) == null;
        }

        if(SignDocument::findOne(['document_id' => $id, 'user_id' => $user_id])){
            $sing_button = false;
        }else{
            $sing_button = true;
        }

        $favourites = DocumentFavourites::findOne(['document_id' => $id, 'user_id' => Yii::$app->user->identity->getId()]);

        $discussion = new Discussion();
        $discussion_query = Discussion::find()->where(['type' => 'document', 'record_id' => $id])->orderBy('id DESC');

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
            'file' => $file,
            'viewed' => $viewed,
            'viewed_button' => $viewed_button,
            'tasks' => $tasks,
            'document_task' => $document_task,
            'favourites' => $favourites,
            'discussion' => $discussion,
            'discussion_count' => $discussion_query->count(),
            'discussions' => $discussions,
            'sign' => $sign,
            'sing_button' => $sing_button,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Document();
        $user_id = Yii::$app->user->identity->id;

        $groups = new ActiveDataProvider([
            'query' => Group::find()->where(['status' => Group::STATUS_ACTIVE, 'user_id' => $user_id])->orWhere(['status' => Group::STATUS_ACTIVE, 'visibility' => Group::VISIBILITY_PUBLIC]),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort'=> [
                'defaultOrder' => ['visibility' => SORT_DESC, 'name' => SORT_ASC]
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
     * @param $id
     * @return string|Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
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
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return Document|array|ActiveRecord|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }


    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpload($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->user->can('admin') or $model->user_id == Yii::$app->user->identity->id){
            $upload = new Upload();

            if ($this->request->isPost && $upload->load($this->request->post())) {

                $upload->file = UploadedFile::getInstance($upload, 'file');
                $upload->type = 'document';
                $upload->record_id = $id;
                $upload->dir = date("Y-m");
                $upload->file_name = md5(Yii::$app->security->generateRandomString());
                $upload->file_extensions = $upload->file->extension;
                $upload->user_id = Yii::$app->user->identity->id;

                if ($upload->save() && $upload->upload()) {

                    return $this->redirect(['view', 'id' => $model->id, '#' => 'file']);

                }
            } else {
                $model->loadDefaultValues();
            }
        }else{
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }

        return $this->render('upload', [
            'model' => $model,
            'upload' => $upload,
        ]);
    }

    /**
     * @param $id
     * @param $file
     * @return \yii\console\Response|Response
     * @throws NotFoundHttpException
     */
    public function actionDownload($id, $file)
    {
        $model = $this->findModel($id);

        if(!$this->isAccess($id) and $this->findModel($id) != null){
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }

        $data = Upload::findOne(['id' => $file, 'type' => 'document', 'record_id' => $model->id]);

        if ($data){
            $url = 'upload/' . $data->dir . '/' . $data->file_name . '.' . $data->file_extensions;
        }

        if(isset($url)){
            $file = Yii::getAlias($url);
            if(is_file($file) and !is_dir($file)) {
                return Yii::$app->response->sendFile($file, Inflector::slug ($data->name) . '.' . $data->file_extensions);
            }
        }

        return $this->redirect(['view', 'id' => $model->id, '#' => 'file']);
    }


    /**
     * @param $id
     * @param $file
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionFileDelete($id, $file)
    {
        $model = $this->findModel($id);

        if(Yii::$app->user->can('admin') or $model->user_id == Yii::$app->user->identity->id){
            $file = Upload::findOne(['id' => $file, 'type' => 'document', 'record_id' => $model->id]);

            if($file){
                $url = 'upload/' . $file->dir . '/' . $file->file_name . '.' . $file->file_extensions;
                $dir = 'upload/' . $file->dir;
            }

            if(isset($url)){
                $file->delete();
                $file = Yii::getAlias($url);
                if(is_file($file) and !is_dir($file)){
                    unlink($file);
                    if(isset($dir) and $this->dir_is_empty($dir))
                        rmdir($dir);
                }
            }
        }else{
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
        }

        return $this->redirect(['view', 'id' => $model->id, '#' => 'file']);
    }


    /**
     * @param $dir
     * @return bool
     */
    public function dir_is_empty($dir)
    {
        if (!is_dir($dir)) return false;

        foreach (scandir($dir) as $file){
            if (!in_array($file, array('.','..','.svn','.git'))) return false;
        }

        return true;
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

        if(is_array($model->resolution) or $user_id == $model->executor_id){
            if(in_array($user_id, $model->resolution) or ($user_id == $model->executor_id)){
                $view->type = 'document';
                $view->user_id = $user_id;
                $view->record_id = (int) $id;
            }
        }else{
            $view->type = 'document';
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

        if($model->status == Document::STATUS_DRAFT){
            $model->status = Document::STATUS_ACTIVE;
        }

        if($model->save()){
            if(is_array($model->resolution) and !empty($model->resolution) and Yii::$app->params['telegram'] === true) {
                $resolution = $model->resolution;
                $document_name = $model->name;
                $author = $model->user->getEmployee_name();
                $users = User::find()->select('chat_id')->where(['id' => $resolution])->all();
                foreach ($users as $user){
                    if(!empty($user->chat_id)){
                        Yii::$app->telegram->sendMessage([
                            'chat_id' => $user->chat_id,
                            'text' => "Опубликован новый документ \nНаименование: $document_name \nАвтор: $author",
                        ]);
                    }
                }
            }

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

        if ((Document::findOne(['id' => $id])) !== null and Yii::$app->user->can('admin')) {
            return true;
        }elseif((Document::find()->where(['id' => $id])->andWhere(['like', 'resolution', sprintf('"%s"', $user_id)])->andWhere(['status' => [Document::STATUS_ACTIVE, Document::STATUS_INACTIVE]])->orWhere(['id' => $id, 'resolution' => NULL, 'status' => [Document::STATUS_ACTIVE, Document::STATUS_INACTIVE]])->orWhere(['id' => $id, 'user_id' => $user_id])->orWhere(['id' => $id, 'executor_id' => $user_id, 'status' => Document::STATUS_ACTIVE])->one()) !== null){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $q
     * @param $id
     * @return array[]
     * @throws \yii\db\Exception
     */
    public function actionDocumentList($q = null, $id = null) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $user_id = Yii::$app->user->identity->id;
        $q = trim($q);
        if (!is_null($q)) {
            $query = new Query;
            if(Yii::$app->user->can('admin')){
                $query->select(['id', "name AS text"])
                    ->from('document')
                    ->where([
                        'OR',
                        ['like', 'name', $q],
                        ['like', 'uniq_id', $q]
                    ])
                    ->andWhere(['status' => Document::STATUS_ACTIVE])
                    ->limit(30);
            }else{
                $query->select(['id', "name AS text"])
                    ->from('document')
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
                    ->orWhere(['executor_id' => $user_id])->andWhere(['status' => [Document::STATUS_ACTIVE]])->andWhere([
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
     * @throws NotFoundHttpException
     */
    public function actionAddTask($id)
    {
        $model = new DocumentTask();

        if($this->findModel($id)->user_id == Yii::$app->user->identity->id or Yii::$app->user->can('admin')){
            if ($this->request->isPost) {
                $model->document_id = $id;
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $id, '#' => 'task']);
                }
            }
        }

        return $this->redirect(['view', 'id' => $id, '#' => 'task']);
    }

    /**
     * @param $id
     * @param $document
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionTaskDelete($id, $task)
    {

        if(($model = DocumentTask::findOne(['task_id' => $task, 'document_id' => $id])) != null){
            if($this->findModel($id)->user_id == Yii::$app->user->identity->id or Yii::$app->user->can('admin')){
                $model->delete();
            }
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'task']);
    }

    /**
     * @param $id
     * @return Response
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionFavourites($id)
    {
        $model = new DocumentFavourites();
        $user_id = Yii::$app->user->identity->id;

        if(($row = $model::findOne(['document_id' => $id, 'user_id' => $user_id])) !== null){
            $row->delete();
        }else{
            $model->document_id = $id;
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
            $discussion->type = 'document';
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

    /**
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionSign()
    {
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $sign = Yii::$app->request->post('sign');
            $document = $this->findModel($id);
            $model = new SignDocument();
            $model->sign = $sign;
            $model->document_id = $document->id;
            $model->user_id = Yii::$app->user->identity->id;
            if($model->save()){
                return true;
            }
        }
        return false;
    }

    /**
     * @return false|string
     * @throws NotFoundHttpException
     */
    public function actionSignInfo()
    {

        if(Yii::$app->request->isAjax){

            $id = Yii::$app->request->post('id');
            $model = $this->findSign($id);

            if($model){
                $sign = $model->sign;
                $id = $model->document->id;
                return json_encode(['id' => $id,'sign' => $sign]);
            }
            return false;
        }
        return false;
    }

    /**
     * @param $id
     * @return SignDocument|null
     * @throws NotFoundHttpException
     */
    protected function findSign($id)
    {
        if (($model = SignDocument::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная подпись не существует.');
    }
}
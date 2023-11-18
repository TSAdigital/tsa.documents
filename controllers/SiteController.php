<?php

namespace app\controllers;

use app\models\Document;
use app\models\DocumentFavourites;
use app\models\News;
use app\models\Task;
use app\models\TaskFavourites;
use app\models\User;
use hosannahighertech\calendar\models\Event;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'index', 'about', 'profile'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index', 'about', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'blank'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return Response
     */
    public function actionIndex()
    {
        return $this->redirect(['site/account']);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'blank';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays profile page.
     *
     * @return string
     */
    public function actionProfile($id)
    {
        $user = User::findOne(['id' => $id]);
        return $this->render('profile', ['user' => $user]);
    }

    /**
     * Displays account page.
     *
     * @return string
     */
    public function actionAccount()
    {
        $user_id = Yii::$app->user->identity->id;
        $user = User::findOne(['id' => $user_id]);

        $documents = Document::find()
            ->select(['document.id', 'document.name', 'document.date', 'document.created_at'])
            ->leftJoin('view', "view.record_id=document.id AND view.type='document' AND view.user_id=$user_id")
            ->where(['like', 'document.resolution', sprintf('"%s"', $user_id)])
            ->andWhere(['document.status' => Document::STATUS_ACTIVE])
            ->andWhere(['view.id' => NULL])
            ->orWhere(['document.executor_id' => $user_id])
            ->andWhere(['document.status' => Document::STATUS_ACTIVE])
            ->andWhere(['view.id' => NULL]);

        $new_document = new ActiveDataProvider([
            'query' => $documents,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 5,
                'pageParam' => 'page-document-new',
                'params' => [
                    '#' => 'document-new',
                    'page-document-new' => Yii::$app->request->get('page-document-new'),
                ],
            ],
        ]);

        $document_favourites = new ActiveDataProvider([
            'query' => DocumentFavourites::find()->where(['user_id' => $user_id]),
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 5,
                'pageParam' => 'page-document',
                'params' => [
                    '#' => 'favourites-document',
                    'page-document' => Yii::$app->request->get('page-document'),
                ],
            ],
        ]);

        $tasks = Task::find()
            ->select(['task.id', 'task.name', 'task.date', 'task.created_at'])
            ->leftJoin('view', "view.record_id=task.id AND view.type='task' AND view.user_id=$user_id")
            ->where(['like', 'task.resolution', sprintf('"%s"', $user_id)])
            ->andWhere(['task.status' => Task::STATUS_ACTIVE])
            ->andWhere(['view.id' => NULL])
            ->orWhere(['task.executor_id' => $user_id])
            ->andWhere(['task.status' => Task::STATUS_ACTIVE])
            ->andWhere(['view.id' => NULL]);

        $new_task = new ActiveDataProvider([
            'query' => $tasks,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 5,
                'pageParam' => 'page-task-new',
                'params' => [
                    '#' => 'task-new',
                    'page-task-new' => Yii::$app->request->get('page-task-new'),
                ],
            ],
        ]);

        $task_favourites = new ActiveDataProvider([
            'query' => TaskFavourites::find()->where(['user_id' => $user_id]),
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 5,
                'pageParam' => 'page-task',
                'params' => [
                    '#' => 'favourites-task',
                    'page-task' => Yii::$app->request->get('page-task'),
                ],
            ],
        ]);

        $news = new ActiveDataProvider([
            'query' => News::find()->where(['status' => News::STATUS_ACTIVE]),
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 5,
                'pageParam' => 'page-news',
                'params' => [
                    '#' => 'news',
                    'page-news' => Yii::$app->request->get('page-news'),
                ],
            ],
        ]);


        $events = [];

        $items = [
            [
                'id' => 1,
                'title' => 'Хорошего дня!',
                'start' => date('Y-m-d'),
                'color' => '#28a745',
            ],
        ];

        foreach ($items as $item) {
            $events[] = new Event($item);
        }


        return $this->render('account', [
            'user' => $user,
            'document_favourites' => $document_favourites,
            'task_favourites' => $task_favourites,
            'new_document' => $new_document,
            'new_task' => $new_task,
            'documents_count' => $documents->count(),
            'tasks_count' => $tasks->count(),
            'news' => $news,
            'events' => $events,
        ]);
    }
}
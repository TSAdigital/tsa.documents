<?php

namespace app\controllers;

use app\models\Telegram;
use app\models\TelegramSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * TelegramController implements the CRUD actions for Telegram model.
 */
class TelegramController extends Controller
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
                        'update' => ['POST'],
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
                            'actions' => ['update'],
                            'roles' => ['admin'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Telegram models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->params['telegram'] !== true){
            throw new ForbiddenHttpException('Телеграм не активен.');
        }

        $searchModel = new TelegramSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return Response
     */
    public function actionUpdate()
    {
        if(Yii::$app->params['telegram'] !== true){
            throw new ForbiddenHttpException('Телеграм не активен.');
        }

        if ($this->request->isPost) {
            $data = Yii::$app->telegram->getUpdates();
            foreach ($data["result"] as $value) {
                $model = new Telegram();
                $model->telegram = !empty($value["message"]["chat"]["id"]) ? (string)$value["message"]["chat"]["id"] : '-';
                $model->first_name = !empty($value["message"]["chat"]["first_name"]) ? (string)$value["message"]["chat"]["first_name"] : '-';
                $model->last_name = !empty($value["message"]["chat"]["last_name"]) ? (string)$value["message"]["chat"]["last_name"] : '-';
                $model->username = !empty($value["message"]["chat"]["username"]) ? (string)$value["message"]["chat"]["username"] : '-';
                $model->status = $model::STATUS_ACTIVE;
                $model->save();
            }
        }

        return $this->redirect(['telegram/index']);
    }
}

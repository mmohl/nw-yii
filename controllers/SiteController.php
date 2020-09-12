<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\Dashboard;
use mdm\admin\models\form\Login;
use yii\helpers\Url;

class SiteController extends Controller
{
    // public $layout = 'concept/main';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'concept/main';

        if (Yii::$app->user->isGuest) return $this->actionLogin();

        if (Yii::$app->user->identity->username == 'pelayan') {
            return $this->redirect(Url::to(['transaction/order']));
        }

        return $this->render('dashboard');
    }

    public function actionLogin()
    {
        if (!Yii::$app->getUser()->isGuest) return $this->goHome();

        $this->layout = 'concept/login';
        $model = new Login();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            Yii::$app->session->removeAllFlashes();
            return $this->goBack();
        } else {
            // if (!$model->login()) Yii::$app->session->setFlash('failure', "username atau password salah");
            return $this->render('login', [
                'model' => $model,
            ]);
        }
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

    public function actionDashboard()
    {
        $data = Dashboard::getDashboardInfo(Dashboard::RANGE_DAILY);

        return $this->asJson($data);
    } 
}

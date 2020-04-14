<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

use backend\models\Apple;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'get-apples', 'generate-apples'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * GetApples action.
     *
     * @return Json
     */
    public function actionGetApples()
    {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ( Yii::$app->request->isAjax ) {

            return Apple::getApplesList();

        }

    }

    /**
     * GenerateApples action.
     *
     * @return Json
     */
    public function actionGenerateApples()
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ( Yii::$app->request->isAjax ) {
            
            Apple::updateAll( ['deleted_at' => time()], ['IS', 'deleted_at', NULL] );

            // 12 apples genereted by default
            for ( $i = 0; $i < 12; $i++ ) {

                $apple = new Apple;
                $apple->created_by = Yii::$app->user->id;
                $apple->color = $apple->colors[array_rand(array('green', 'lime', 'red'))];
                //$apple->size // по умолчанию 100% будет
                //$apple->state //  висит на дереве
                $beginDateTime = time();
                $endDateTime = $beginDateTime + 432000; // 5 дней по умолчанию
                $createdAt = mt_rand($beginDateTime, $endDateTime);
                $apple->created_at = $createdAt;
                // $apple->fall_at // NULL
                // $apple->deleted_at // NULL
                $apple->save();

            }

            return Apple::getApplesList();

        }

    }




    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
}

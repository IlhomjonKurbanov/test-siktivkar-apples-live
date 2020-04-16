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
                        'actions' => ['logout', 'index', 'get-apples', 'generate-apples', 'fallen-apple', 'eaten-apple'],
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
     * GenerateApples action.
     *
     * @return Json
     */
    public function actionFallenApple()
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ( Yii::$app->request->isAjax ) {

            $data = Yii::$app->request->post();
            $id = json_decode( '[' . $data['id'] . ']', true );
            $id = $id[0];

            $currentApple = Apple::findOne($id);

            $appleStatus = "";

            switch ( $currentApple->state ) {
                case 1: 
                    $currentApple->state = Apple::FELL_TO_THE_GROUND;
                    $currentApple->fall_at = time();
                    $currentApple->save(false);
                    $appleStatus = "Яблоко упало";
                    break;
                case 2: 
                    $appleStatus = "Яблоко уже лежит на земле";
                    break;
                case 3: 
                    $appleStatus = "Гнилое яблоко";
                    break;
                default:
                    $appleStatus = "";

            }

            $respons = [ 'stateText' => $appleStatus,  'currentApple' => $currentApple ];

            return $respons;

        }

    }



    /**
     * GenerateApples action.
     *
     * @return Json
     */
    public function actionEatenApple()
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ( Yii::$app->request->isAjax ) {

            $data = Yii::$app->request->post();

            $id = json_decode( '[' . $data['id'] . ']', true );
            $id = $id[0];

            $eatenPart = json_decode( '[' . $data['eatenPart'] . ']', true );
            $eatenPart = $eatenPart[0];

            $currentApple = Apple::findOne($id);

            if ( $currentApple && $currentApple->state == Apple::FELL_TO_THE_GROUND ) {

                $currentAppleSize = $currentApple->size - $eatenPart; 

                switch ( $currentAppleSize ) {
                    case 75: 
                        $currentApple->size = 75;
                        $sizeText = "Остался 75%";
                        break;
                    case 50: 
                        $currentApple->size = 50;
                        $sizeText = "Остался 50%";
                        break;
                    case 25: 
                        $currentApple->size = 25;
                        $sizeText = "Остался 25%";
                        break;
                    default:
                        $currentApple->size = 0;
                        $currentApple->state = Apple::EATEN;
                        $currentApple->deleted_at = time();
                        $sizeText = "Cъедено";
                }
                
                $currentApple->save();

            } else if ( $currentApple && ( $currentApple->state == Apple::ON_TREE || $currentApple->state == Apple::ROTTEN ) ) {

                $sizeText = "Съесть нельзя, яблоко на дереве или гнилое яблоко!";

            } else if ( $currentApple && $currentApple->state == Apple::EATEN ) {

                $sizeText = "Яблоко съедено";

            }

            $respons = [ 'sizeText' => $sizeText,  'currentApple' => $currentApple ];
           
            return $respons;

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

<?php

namespace frontend\controllers;

use common\functions\GlobalFunctions;
use common\models\LoginForm;
use common\models\User;
use components\GlobalFunction;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use const YII_ENV_TEST;
use function GuzzleHttp\json_encode;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public $referer_url;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client) {
        $userAttributes = $client->getUserAttributes();
//        echo '<pre>';
//        print_r($userAttributes);
//        exit();
        if (is_array($userAttributes['name'])) {
            $first_name = $userAttributes['name']['givenName'];
            $last_name = $userAttributes['name']['familyName'];
            $user_email = $userAttributes['emails'][0]['value'];
        } else {
            $user_email = $userAttributes['email'];
            $first_name = $userAttributes['first_name'];
            $last_name = $userAttributes['last_name'];
        }
        $user = User::find()->where(['username' => $userAttributes['id']])->one();
        if (!empty($user)) {
            Yii::$app->user->login($user);
        } else {
            $user = new User();
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->username = $userAttributes['id'];
            $user->email = $user_email;
            $user->setPassword('');
            $user->generateAuthKey();
            $user->save();
            Yii::$app->user->login($user);
        }
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {

        $session = Yii::$app->session;
        if ($session->has('event_id') && !Yii::$app->user->isGuest) {

            $event_id = $session->get('event_id');
            return $this->redirect(['event/detail', 'eid' => $event_id, 'alert_added' => true]);
        }
        if ($session->has('zipcode') && !Yii::$app->user->isGuest) {

            $keywords = $session->get('keywords');
            $filters = $session->get('filters');
            $zip = $session->get('zip');
            $sort = $session->get('sort');
            $parameters = array();

            if (!empty($zip)) {
                $temp = ['zipcode' => $zip];
                array_merge($parameters, $temp);
            }
            if (is_array($keywords)) {
                $temp_keywords = array();
                foreach ($keywords as $keyword) {
                    array_merge($temp_keywords, ['keywords' => $keyword]);
                }
                array_merge($parameters, $temp_keywords);
            }
            return $this->redirect(['event/']);
        }

        $this->layout = 'home-layout';
        $zip_code = GlobalFunctions::getLatestSearchedZip();

        return $this->render('index', ['zip_code' => $zip_code]);
    }

    public function getZipCodeFromCookies() {

        $zip_code = '';
        $cookies = Yii::$app->request->cookies;
        if (($cookie_long = $cookies->get('longitude')) !== null && ($cookie_lat = $cookies->get('latitude'))) {
            $longitude = $cookie_long->value;
            $latitude = $cookie_lat->value;
            $temp_zip = GlobalFunction::getZipFromLongLat($longitude, $latitude);
            $zip_code = $temp_zip ? $temp_zip : $zip_code;
        }
        return $zip_code;
    }

    public function getZipCodeFromIp() {
        $ip = Yii::$app->request->userIP;
//        $ip = '72.229.28.185';
        $zip_code = Yii::$app->ip2location->getZIPCode($ip);
        return $zip_code;
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
//        $session = Yii::$app->session;
//        if ($session->has('signup_page')){
//            $session->remove('signup_page');
//            return $this->redirect(['site/signup','email'=>'hello']);
//        }
        $model = new LoginForm();
        $model->role = User::ROLE_USER;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['zeeshanEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        return $this->render('about');
    }

    public function actionTermsPrivacy() {
        return $this->render('terms-privacy');
    }

//    public function actionTerms() {
//        return $this->render('terms');
//    }

    public function actionAddAlertsSession() {
        $session = Yii::$app->session;
        if (Yii::$app->request->post('only_zip') !== null) {

            $zip_code = Yii::$app->request->post('zipcode');
            $event_id = Yii::$app->request->post('event_id');
            $street = Yii::$app->request->post('street');
            $city = Yii::$app->request->post('city');
            $state = Yii::$app->request->post('state');
            $store_number = Yii::$app->request->post('store_number');

            $keywords = array();
            $filters = array();
            $sort_by = 'Closest';
//            $session->set('only_zip', 'y');
            $session->set('event_id', $event_id);
            $session->set('type', 'exact_location');
            $session->set('street', $street);
            $session->set('city', $city);
            $session->set('state', $state);
            $session->set('store_number', $store_number);
        } else {
            $zip_code = Yii::$app->request->post('zipcode');
            $keywords = Yii::$app->request->post('keywords');
            $filters = Yii::$app->request->post('filters');
            $sort_by = Yii::$app->request->post('sortBy');
            $session->set('type', 'search_base');
        }

        $session->set('zipcode', $zip_code);
        $session->set('keywords', $keywords);
        $session->set('filters', $filters);
        $session->set('sort', $sort_by);
        $session->set('signup_page', 'Y');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $session = Yii::$app->session;
        $url = '';
        if ($session->get('url')) {
            $url = $session->get('url');
        }
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = $model->signup();
            if ($user) {
                $email = $model->welcomeEmail($user, $url);
                if ($email) {
//                    Yii::$app->getSession()->setFlash('success', 'Check Your email to complete registration.');
                    $loginModel = new LoginForm();
                    $loginModel->username = $model->email;
                    $loginModel->password = $model->password;
                    $loginModel->role = User::ROLE_USER;
                    if ($loginModel->login()) {
                        return $this->redirect('thankyou');
                    } else {
                        return $this->goBack();
                    }
                } else {
                    Yii::$app->getSession()->setFlash('warning', 'Failed to identify email, contact Admin!');
                }
                if (!isset($url)) {
                    return $this->goHome();
                } else {
                    $session->remove('url');
                    return $this->redirect($url);
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    public function actionThankyou() {
        return $this->render('signup-thankyou');
    }

    public function actionConfirm($id, $key, $url) {
        $user = User::find()->where([
                    '_id' => new \MongoDB\BSON\ObjectID($id),
                    'auth_key' => $key,
                    'status' => 0,
                ])->one();
        if (!empty($user)) {
            $user->status = 10;
            $user->save();
            Yii::$app->getSession()->setFlash('success', 'Email confirmed');
            Yii::$app->getUser()->login($user);
        } else {
            Yii::$app->getSession()->setFlash('warning', 'Failed!');
        }
        if (!isset($url)) {
            return $this->goHome();
        } else {
            return $this->redirect($url);
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                //return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    public function actionSaveEvent() {
        if (!Yii::$app->user->id) {
            $session = Yii::$app->session;
            $session->set('url', Yii::$app->request->referrer);
            return $this->redirect(['site/signup']);
        }

        $userID = Yii::$app->user->id;
        $eid = Yii::$app->request->get('eid');
        $store_number = Yii::$app->request->get('store_number');
        $zipcode = Yii::$app->request->get('zipcode');
        if (Yii::$app->request->get('flg')) {
            return $this->redirect(Yii::$app->homeUrl . 'event/detail?eid=' . $eid);
        }

        $retData = array();
        $user = User::find()->where(['_id' => $userID])->one();
        if (!empty($user)) {
            if (isset($user->saved_events)) {
                $can_save = true;
                foreach ($user->saved_events as $saved_event) {
                    if ($saved_event['event_id'] === $eid && $saved_event['store_number'] === $store_number && $saved_event['zip'] === $zipcode) {
                        $can_save = false;
                    }
                }
                if ($can_save) {
                    $user->saved_events = ArrayHelper::merge($user->saved_events, array(['event_id' => $eid, 'zip' => $zipcode, 'store_number' => $store_number]));
                }
            } else {
//                $user->saved_events = [$eid];
                $user->saved_events = array(['event_id' => $eid, 'zip' => $zipcode, 'store_number' => $store_number]);
            }
            $user->save();
            $retData['msgType'] = "SUC";
            $retData['msg'] = "Event saved successfully!";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "Login is required!";
        }

        exit(json_encode($retData));
    }

}

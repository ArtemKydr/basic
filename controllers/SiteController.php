<?php

namespace app\controllers;

use app\models\Documents;
use app\models\image\form\UploadForm;
use app\models\image\image;
use app\models\ManagerLogs;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\UploadDocumentForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\SignupForm;
use app\models\User;
use app\models\SendEmailForm;
use app\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

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
                    'logout' => ['get'],
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
    public function actionIndex($date = '')
    {

        return $this->render('index');
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('login');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->render('index');
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
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
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
    public function actionPersonalInformation()
    {
        $user_id = Yii::$app->user->id;
        $query = User::find()->where(['id'=>$user_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('personal-information',['dataProvider'=>$dataProvider]);
    }
    public function actionRequirements()
    {
        return $this->render('requirements');
    }


    public function actionSignUp()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new SignupForm();
        if ($model->load(\Yii::$app->request->post()) and $model->validate()) {
            $user = new User();
            $user->email = $model->email;
            $user->password = Yii::$app->security->generatePasswordHash($model->password);
            $user->fio = $model->fio;
            $user->phone = $model->phone;
            $user->city = $model->city;
            $user->organization = $model->organization;
            if ($user->save()) {
                Yii::$app->user->login($user);
                return $this->refresh();
            }
        }
        return $this->render('signup', compact('model'));
    }

    public function actionSendEmail()
    {
        $model = new SendEmailForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->sendEmail()):
                    Yii::$app->getSession()->setFlash('warning', 'Проверьте емайл.');
                    return $this->goHome();
                else:
                    Yii::$app->getSession()->setFlash('error', 'Нельзя сбросить пароль.');
                endif;
            }
        }

        return $this->render('sendEmail', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($key)
    {
        try {
            $model = new ResetPasswordForm($key);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->getSession()->setFlash('warning', 'Пароль изменен.');
                return $this->redirect(['/main/login']);
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionStudent()
    {
        $user_id = Yii::$app->user->id;
        $role = (User::find()->select('role')->where(['id'=>$user_id])->column())[0];
        if ($role === 'manager'){
            return $this->redirect(['access-error']);
        }
        return $this->render('student');
    }

    public function actionStudentDocument()
    {
        $user_id = Yii::$app->user->id;
        $role = (User::find()->select('role')->where(['id'=>$user_id])->column())[0];
        if ($role === 'manager'){
            return $this->redirect(['access-error']);
        }
        $user = User::find()->where(['id'=>$user_id])->one();
        $username = User::find()->select('fio')->where(['id'=>$user_id])->column();
        $form = Yii::$app->request->post('UploadDocumentForm');
        $draft_status = Yii::$app->request->post()['action'];
        $model = new UploadDocumentForm();
        if (Yii::$app->request->post('UploadDocumentForm')) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $result = $model->upload();
            if ($result==true){
                $form = Yii::$app->request->post('UploadDocumentForm');
                $document = new Documents();
                $filename = $model->file->baseName.'.'.$model->file->extension;
                $document->user_id = $user_id;
                $document->title = $form['title'];
                $document->fio = $user['fio'];
                $document->nr = $form['nr'];
                $document->coauthor = $form['coauthor'];
                $document->organization = $user['organization'];
                $document->authors = $user['fio'];
                $document->email = $user['email'];
                $document->phone = $user['phone'];
                $document->draft_status = $draft_status;
                if ($draft_status == 'draft'){
                    $document->document_status = 'In the draft';
                } else {
                    $document->document_status = 'Article under consideration';
                }
                $document->city = $user['city'];
                $document->university = $form['university'];
                $document->datetime = date('d.m.Y H:i:s');
                $document->source = 'UploadDocument/' . $filename;
                $document->save(false);
                if ($draft_status == 'draft'){
                    Yii::$app->session->setFlash('success', 'Черновик статьи успешно загружен');
                }
                else{
                    Yii::$app->session->setFlash('success', 'Статья отправлена на Антиплагиат. Проверка займет до рабочих 3 дней.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось загрузить статью');
            }
        }

        $query = Documents::find()->where(['email'=>$user['email']]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('student-document', ['model' => $model,'dataProvider'=>$dataProvider,'username'=>$username]);
    }

    public function actionManager()
    {

        $user_id = Yii::$app->user->id;
        $role = (User::find()->select('role')->where(['id'=>$user_id])->column())[0];
        if ($role === 'user'){
            return $this->redirect(['access-error']);
        }
        $query = Documents::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        return $this->render('manager',['dataProvider'=>$dataProvider,'query'=>$query]);
    }
    public function actionView($id)
    {
        $user_id = Yii::$app->user->id;
        $a = Documents::find()->select('user_id')->where(['id'=>$id])->column();
        $role = (User::find()->select('role')->where(['id'=>$user_id])->column())[0];
        if ($role === 'user'){
            return $this->redirect(['access-error']);
        }
        $query = Documents::find()->select('*')->where(['user_id'=>$a]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('view',['dataProvider'=>$dataProvider]);
    }
    public function actionUpdate($id)
    {

        $user_id = Yii::$app->user->id;
        $role = (User::find()->select('role')->where(['id'=>$user_id])->column())[0];
        if ($role === 'user'){
            return $this->redirect(['access-error']);
        }
        $student_id = Documents::find()->select('user_id')->where(['id'=>$id])->one();
        $manager = User::find()->where(['id'=>$user_id])->one();
        $student = User::find()->where(['id'=>$student_id])->one();
        $manager_model = new ManagerLogs();
        $model = Documents::findOne($id);
        if ($model->draft_status == 'draft'){
            return $this->redirect(['access-error']);
        }
        $form = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())){
            if ((int)$form['Documents']['originality']<70){
                $model->document_status = 'The article did not pass the originality test';
            }
            if ((int)$form['Documents']['originality']>=70 and $form['Documents']['document_status'] == 'The article did not pass the originality test'){
                Yii::$app->session->setFlash('error', 'Не удалось. Измените статус или проверьте значение оригинальности');
                return $this->redirect(['update','id'=>$id]);
                die();
            }
            $model->comment = $form['Documents']['comment'];
            $manager_model->manager_id = $manager['id'];
            $manager_model->user_id = $student['id'];
            $manager_model->manager_fio = $manager['fio'];
            $manager_model->user_fio = $student['fio'];
            $manager_model->document_status_change = $model->document_status;
            $manager_model->comment = $model->comment;
            $manager_model->datetime = date('d.m.Y H:i:s');
            $manager_model->save();
            $model->save();
            Yii::$app->session->setFlash('success', 'Успешно');
            return $this->redirect(['manager','id'=>$id]);
        }
        $data = ManagerLogs::find()->where(['user_id'=>$student_id])->all();
        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['id', 'name'],
            ],
        ]);
        return $this->render('update',['model'=>$model,'data'=>$data,'student'=>$student]);
    }

    public function actionAccessError()
    {
        return $this->render('access-error');
    }
}

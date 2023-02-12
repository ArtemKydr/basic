<?php

namespace app\controllers;

use app\models\AdditionalFiles;
use app\models\AdditionalStudentDocumentForm;
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

    public function actionStudentDocument()
    {
        $user_id = Yii::$app->user->id;
        $role = (User::find()->select('role')->where(['id'=>$user_id])->column())[0];
        if ($role === 'manager'){
            return $this->redirect(['access-error']);
        }
        $user = User::find()->where(['id'=>$user_id])->one();
        $username = User::find()->select('fio')->where(['id'=>$user_id])->column();

        $model = new UploadDocumentForm();
        if (Yii::$app->request->post('UploadDocumentForm')) {
            $form = Yii::$app->request->post('UploadDocumentForm');
            $draft_status = Yii::$app->request->post()['action'];
            $model->file = UploadedFile::getInstance($model, 'file');
            $result = $model->upload();
            if ($result==true){
                $form = Yii::$app->request->post('UploadDocumentForm');
                $document = new Documents();
                $filename = UploadDocumentForm::transliterate($model->file->baseName);
                $filename = mb_strtolower($filename).'.'.$model->file->extension;
                $document->user_id = $user_id;
                $document->title = $form['title'];
                $document->fio = $user['fio'];
                $document->nr = $form['nr'];
                $document->coauthor = $form['coauthor'];
                $document->organization = $user['organization'];
                $document->authors = $user['fio'];
                $document->email = $user['email'];
                $document->phone = $user['phone'];
                $document->collection = 'Almanac';
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
                return $this->redirect(['student-document']);
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось загрузить статью');
                return $this->redirect(['student-document']);
            }
        }
        $count_clear_document = Documents::find()->where(['user_id'=>$user_id])->andWhere(['draft_status'=>'clear'])->andWhere(['document_status'=>'The article did not pass the originality test'])->count();
        $query = Documents::find()->where(['email'=>$user['email']]);
        $document_status_forms = Documents::find()->select('document_status')->where(['email'=>$user['email']])->column();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('student-document', ['model' => $model,'dataProvider'=>$dataProvider,'username'=>$username,'document_status_forms'=>$document_status_forms,'count_clear_document'=>$count_clear_document]);
    }

    public function actionManager()
    {
        $user_id = Yii::$app->user->id;
        $role = (User::find()->select('role')->where(['id'=>$user_id])->column())[0];
        if ($role === 'user'){
            return $this->redirect(['access-error']);
        }
        $model = new Documents();
        $form = Yii::$app->request->post();
        $manager = User::find()->where(['id'=>$user_id])->one();
        if ($form==null or $form==[]){
            $flag = 1;
        }else {
            if (isset($form['Documents'])){
                $form = $form['Documents'];
            }
            for ($i=0;$i<count($form);$i++){
                $keys = array_keys($form);
                $manager_model = new ManagerLogs();
                $document_id = $keys[$i];
                $model = Documents::findOne($document_id);
                $student_id = Documents::find()->select('user_id')->where(['id'=>$document_id])->one();
                $student = User::find()->where(['id'=>$student_id])->one();
                $model->originality = $form[$document_id]['originality'];
                if ((int)$form[$document_id]['originality']<70 and $form[$document_id]['originality'] !=''){
                    $model->document_status = 'The article did not pass the originality test';
                    if($form[$document_id]['document_status']!='The article did not pass the originality test'){
                        Yii::$app->session->setFlash('error', 'Не удалось. Измените статус или проверьте значение оригинальности');
                    }
                }else if ((int)$form[$document_id]['originality']>=70 and $form[$document_id]['document_status'] == 'The article did not pass the originality test'){
                    Yii::$app->session->setFlash('error', 'Не удалось. Измените статус или проверьте значение оригинальности');
                    return $this->redirect(['manager']);
                    die();
                }
                else if ($form[$document_id]['originality'] >=70 and $form[$document_id]['document_status']!='The article has been checked for originality'){
                    if ($form[$document_id]['document_status']!='The article has been checked for originality'){
                        $model->document_status = $form[$document_id]['document_status'];
                    }else {
                        $model->document_status = 'The article has been checked for originality';
                    }
                }
                else if ($form[$document_id]['originality'] >=70){
                    $model->document_status = 'The article has been checked for originality';
                }else if ($form[$document_id]['originality'] !=''){
                    $model->document_status = 'Article under consideration';
                } else {
                    if (isset($form[$document_id]['document_status'])){
                        $model->document_status = $form[$document_id]['document_status'];
                    }else {
                        $model->document_status = 'In the draft';
                    }
                }
                $model->personal_data = $form[$document_id]['personal_data'];
                $model->comment = $form[$document_id]['comment'];
                //////////////////////////////////////////
                $check = Documents::find()->where(['id'=>$keys[$i]])->one();
                $personal_data1 = $check['personal_data'];
                $personal_data2 = $form[$document_id]['personal_data'];
                $comment1 = $check['comment'];
                $comment2 = $form[$document_id]['comment'];
                if (isset($form[$document_id]['document_status'])){
                    $document_status1 = $form[$document_id]['document_status'];
                }else{
                    $document_status1 = 'In the draft';
                }
                $document_status2 = $check['document_status'];
                $model->save(false);

                if ($personal_data1==$personal_data2 and $comment1==$comment2 and $document_status1==$document_status2)
                {
                    continue;
                }else if ($personal_data1!=$personal_data2 and $comment1!=$comment2){
                    $manager_model->comment = $model->comment;
                    $manager_model->personal_data_status = $form[$document_id]['personal_data'];
                }
                elseif ($personal_data1==$personal_data2 and $comment1!=$comment2 and $document_status1==$document_status2){
                        $manager_model->comment = $model->comment;
                }
                elseif ($personal_data1!=$personal_data2 and $comment1==$comment2){
                    $manager_model->personal_data_status = $form[$document_id]['personal_data'];
                }
                ////c
                elseif ($document_status1!=$document_status2 and $comment1!=$comment2){///////////////
                    $manager_model->document_status_change = $model->document_status;
                    $manager_model->comment = $model->comment;
                    $change_document_email_model = new SendEmailForm();
                    $change_document_email_model->sendEmailChangeDocumentStatus($document_id);
                }
                elseif ($document_status1==$document_status2 and $comment1!=$comment2){
                    $manager_model->comment = $model->comment;
                }
                elseif ($document_status1!=$document_status2 and $comment1==$comment2){
                    $manager_model->document_status_change = $model->document_status;
                    $change_document_email_model = new SendEmailForm();
                    $change_document_email_model->sendEmailChangeDocumentStatus($document_id);
                }
                ////b
                elseif ($personal_data1!=$personal_data2 and $document_status1!=$document_status2){
                    $manager_model->personal_data_status = $form[$document_id]['personal_data'];
                    $manager_model->document_status_change = $model->document_status;
                    $change_document_email_model = new SendEmailForm();
                    $change_document_email_model->sendEmailChangeDocumentStatus($document_id);
                }
                elseif ($personal_data1==$personal_data2 and $document_status1!=$document_status2){
                    $manager_model->document_status_change = $model->document_status;
                    $change_document_email_model = new SendEmailForm();
                    $change_document_email_model->sendEmailChangeDocumentStatus($document_id);
                }
                elseif ($personal_data1!=$personal_data2 and $document_status1==$document_status2){
                    $manager_model->personal_data_status = $form[$document_id]['personal_data'];
                }
                else if ($personal_data1!=$personal_data2)
                {
                    $manager_model->personal_data_status = $form[$document_id]['personal_data'];
                }
                else if ($comment1!=$comment2) {

                    $manager_model->comment = $model->comment;
                }
                else if ($document_status1!=$document_status2){

                    $manager_model->document_status_change = $model->document_status;
                    $change_document_email_model = new SendEmailForm();
                    $change_document_email_model->sendEmailChangeDocumentStatus($document_id);
                }
                $manager_model->document_id = $document_id;
                $manager_model->manager_id = $manager['id'];
                $manager_model->user_id = $student['id'];
                $manager_model->manager_fio = $manager['fio'];
                $manager_model->user_fio = $student['fio'];
                $manager_model->datetime = date('d.m.Y H:i:s');
                $manager_model->save();
            }
            return $this->redirect(['manager']);
        }
        $query = Documents::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        return $this->render('manager',['dataProvider'=>$dataProvider,'query'=>$query,'model'=>$model]);
    }
    public function actionView($id)
    {
        $user_id = Yii::$app->user->id;
        $user = Documents::find()->select('user_id')->where(['id'=>$id])->column();
        $role = (User::find()->select('role')->where(['id'=>$user_id])->column())[0];
        if ($role === 'user'){
            return $this->redirect(['access-error']);
        }
        $student_id = Documents::find()->select('user_id')->where(['id'=>$id])->one();
        $student = User::find()->where(['id'=>$student_id])->one();
        $query = Documents::find()->select('*')->where(['user_id'=>$user]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $additional_files = AdditionalFiles::find()->select('expert_name,expert_source,file_scan_name,file_scan_source,review_name,review_source')->where(['user_id'=>$user])->all();
        return $this->render('view',['dataProvider'=>$dataProvider,'student'=>$student,'additional_files'=>$additional_files]);
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
                if($form['document_status']!='The article did not pass the originality test'){
                    Yii::$app->session->setFlash('error', 'Не удалось. Измените статус или проверьте значение оригинальности');
                    return $this->redirect(['update','id'=>$id]);

                }
            }
            if ((int)$form['Documents']['originality']>=70 and $form['Documents']['document_status'] == 'The article did not pass the originality test'){
                Yii::$app->session->setFlash('error', 'Не удалось. Измените статус или проверьте значение оригинальности');
                return $this->redirect(['update','id'=>$id]);
            }
            $personal_data_status = Documents::find()->select('personal_data')->where(['user_id'=>$student_id])->column();
            $model->comment = $form['Documents']['comment'];
            $model->originality = $form['Documents']['originality'];

            $check = Documents::find()->where(['id'=>$id])->one();
            $personal_data1 = $check['personal_data'];
            $personal_data2 =  $check['personal_data'];
            $comment1 = $check['comment'];
            $comment2 =  $form['Documents']['comment'];
            $document_status1 =  $form['Documents']['document_status'];
            $document_status2 = $check['document_status'];
            $model->save();
            if ($personal_data1==$personal_data2 and $comment1==$comment2 and $document_status1==$document_status2)
            {
                Yii::$app->session->setFlash('success', 'Успешно');
                return $this->redirect(['update','id'=>$id]);
            }else if ($personal_data1!=$personal_data2 and $comment1!=$comment2){
                $manager_model->comment = $model->comment;
                $manager_model->personal_data_status = $form['Documents']['personal_data'];
            }
            elseif ($personal_data1==$personal_data2 and $comment1!=$comment2 and $document_status1==$document_status2){
                $manager_model->comment = $model->comment;
            }
            elseif ($personal_data1!=$personal_data2 and $comment1==$comment2){
                $manager_model->personal_data_status = $form['Documents']['personal_data'];
            }
            ////c
            elseif ($document_status1!=$document_status2 and $comment1!=$comment2){///////////////
                $manager_model->document_status_change = $model->document_status;
                $manager_model->comment = $model->comment;
                $change_document_email_model = new SendEmailForm();
                $change_document_email_model->sendEmailChangeDocumentStatusUpdate($id);
            }
            elseif ($document_status1==$document_status2 and $comment1!=$comment2){
                $manager_model->comment = $model->comment;
            }
            elseif ($document_status1!=$document_status2 and $comment1==$comment2){
                $manager_model->document_status_change = $model->document_status;
                $change_document_email_model = new SendEmailForm();
                $change_document_email_model->sendEmailChangeDocumentStatusUpdate($id);
            }
            ////b
            elseif ($personal_data1!=$personal_data2 and $document_status1!=$document_status2){
                $manager_model->document_status_change = $model->document_status;
                $change_document_email_model = new SendEmailForm();
                $change_document_email_model->sendEmailChangeDocumentStatusUpdate($id);
            }
            elseif ($personal_data1==$personal_data2 and $document_status1!=$document_status2){
                $manager_model->document_status_change = $model->document_status;
                $change_document_email_model = new SendEmailForm();
                $change_document_email_model->sendEmailChangeDocumentStatusUpdate($id);
            }
            elseif ($personal_data1!=$personal_data2 and $document_status1==$document_status2){
            }
            else if ($personal_data1!=$personal_data2)
            {
                $manager_model->personal_data_status = $check['personal_data'];;
            }
            else if ($comment1!=$comment2) {

                $manager_model->comment = $model->comment;
            }
            else if ($document_status1!=$document_status2){
                $manager_model->document_status_change = $model->document_status;
                $change_document_email_model = new SendEmailForm();
                $change_document_email_model->sendEmailChangeDocumentStatus($id);
            }
            $manager_model->document_id = $id;
            $manager_model->manager_id = $manager['id'];
            $manager_model->user_id = $student['id'];
            $manager_model->manager_fio = $manager['fio'];
            $manager_model->user_fio = $student['fio'];
            $manager_model->datetime = date('d.m.Y H:i:s');
            $manager_model->save();
            Yii::$app->session->setFlash('success', 'Успешно');
            return $this->redirect(['update','id'=>$id]);
        }
        $data = ManagerLogs::find()->where(['user_id'=>$student_id])->andWhere(['document_id'=>$id])->all();
        return $this->render('update',['model'=>$model,'data'=>$data,'student'=>$student]);
    }

    public function actionAccessError()
    {
        return $this->render('access-error');
    }

    public function actionAdditionalStudentDocument()
    {
        $user_id = Yii::$app->user->id;
        $user = User::find()->where(['id'=>$user_id])->one();
        $role = (User::find()->select('role')->where(['id'=>$user_id])->column())[0];
        if ($role === 'manager'){
            return $this->redirect(['access-error']);
        }
        $model = AdditionalFiles::find()->where(['user_id'=>$user_id])->one();
        if($model==null){
            $model = new AdditionalFiles();
        }
        if (Yii::$app->request->isPost) {
            $expert = $_FILES['AdditionalFiles']['name']['expert'];
            $expert = mb_strtolower(UploadDocumentForm::transliterate($expert));
            $review = $_FILES['AdditionalFiles']['name']['review'];
            $review =  mb_strtolower(UploadDocumentForm::transliterate($review));
            $file_scan = $_FILES['AdditionalFiles']['name']['file_scan'];
            $file_scan =  mb_strtolower(UploadDocumentForm::transliterate($file_scan));
            if ($expert!='' and $review!='' and $file_scan!='')
            {
                $model->expert = UploadedFile::getInstance($model, 'expert');
                $model->file_scan = UploadedFile::getInstance($model, 'file_scan');
                $model->review = UploadedFile::getInstance($model, 'review');
                $model->review_name = $review;
                $model->expert_name = $expert;
                $model->file_scan_name = $file_scan;
                $model->expert_source = 'UploadDocumentExpert/' . $expert;
                $model->review_source = 'UploadDocumentReview/' . $review;
                $model->file_scan_source = 'UploadDocumentFileScan/' . $file_scan;
            }else if ($expert!='' or $review!='' and $file_scan==''){
                if ($expert!=''){
                    $model->expert_name = $expert;
                    $model->expert_source = 'UploadDocumentExpert/' . $expert;
                    $model->expert = UploadedFile::getInstance($model, 'expert');
                }
                if ($review!=''){
                    $model->review_name = $review;
                    $model->review_source = 'UploadDocumentReview/' . $review;
                    $model->review = UploadedFile::getInstance($model, 'review');
                }
            }
            else if ($expert!=''or $file_scan!='' and $review==''){
                if ($file_scan!=''){
                    $model->file_scan_name = $file_scan;
                    $model->file_scan_source = 'UploadDocumentFileScan/' . $file_scan;
                    $model->file_scan = UploadedFile::getInstance($model, 'file_scan');
                }
                if ($expert!=''){
                    $model->expert_name = $expert;
                    $model->expert_source = 'UploadDocumentExpert/' . $expert;
                    $model->expert = UploadedFile::getInstance($model, 'expert');
                }
            }
            if ($model->upload()) {
                $model->user_id = $user_id;
                $model->fio = $user['fio'];
                $model->save();
                Yii::$app->session->setFlash('success', 'Успешно');
                return $this->redirect(['additional-student-document']);
            }
        }


        return $this->render('additional-student-document',['model' => $model,]);
    }
}

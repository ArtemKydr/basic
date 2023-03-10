<?php

namespace app\controllers;

use app\models\AdditionalFiles;
use app\models\AdditionalStudentDocumentForm;
use app\models\Documents;
use app\models\image\form\UploadForm;
use app\models\image\image;
use app\models\ManagerLogs;
use app\models\UploadChangeDocumentForm;
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
date_default_timezone_set("Europe/Moscow");
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

    public function actionAboutCollections()
    {
        return $this->render('about-collections');
    }

    public function actionPersonalInformation()
    {
        $user_id = Yii::$app->user->id;
        $query = User::find()->where(['id' => $user_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('personal-information', ['dataProvider' => $dataProvider]);
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
                    Yii::$app->getSession()->setFlash('warning', '?????????????????? ??????????.');
                    return $this->goHome();
                else:
                    Yii::$app->getSession()->setFlash('error', '???????????? ???????????????? ????????????.');
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
                Yii::$app->getSession()->setFlash('warning', '???????????? ??????????????.');
                return $this->redirect(['/login']);
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionStudentDocument()
    {
        date_default_timezone_set("Europe/Moscow");
        $user_id = Yii::$app->user->id;
        $role = (User::find()->select('role')->where(['id' => $user_id])->column())[0];
        if ($role === 'manager' or Yii::$app->user->isGuest) {
            return $this->redirect(['access-error']);
        }
        $user = User::find()->where(['id' => $user_id])->one();
        $username = User::find()->select('fio')->where(['id' => $user_id])->column();

        $model = new UploadDocumentForm();
        if (Yii::$app->request->post('UploadDocumentForm')) {
            $form = Yii::$app->request->post('UploadDocumentForm');
            $draft_status = Yii::$app->request->post()['action'];
            $model->file = UploadedFile::getInstance($model, 'file');
            $timestamp = date('dmYHis');
            $result = $model->upload($timestamp);
            if ($result == true) {
                $form = Yii::$app->request->post('UploadDocumentForm');
                $document = new Documents();
                $filename = UploadDocumentForm::transliterate($model->file->baseName);
                $filename = mb_strtolower($filename) . '_' . $timestamp . '.' . $model->file->extension;
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
                if ($draft_status == 'draft') {
                    $document->document_status = 'In the draft';
                } else {
                    $document->document_status = 'Article under consideration';
                }
                $document->city = $user['city'];
                $document->university = $form['university'];
                $document->datetime = date('d.m.Y H:i:s');
                $document->source = 'UploadDocument/' . $filename;
                $document->save(false);
                if ($draft_status == 'draft') {
                    Yii::$app->session->setFlash('success', '???????????????? ???????????? ?????????????? ????????????????');
                } else {
                    Yii::$app->session->setFlash('success', '???????????? ???????????????????? ???? ???????????????? ???? ??????????????????????. ???????????????? ???????????? ???? ?????????????? 3 ????????');
                }
                return $this->redirect(['student-document']);
            } else {
                Yii::$app->session->setFlash('error', '???? ?????????????? ?????????????????? ????????????');
                return $this->redirect(['student-document']);
            }
        }
        $count_clear_document = Documents::find()->where(['user_id' => $user_id])->andWhere(['draft_status' => 'clear'])->andWhere(['document_status' => 'The article did not pass the originality test'])->count();
        $query = Documents::find()->where(['email' => $user['email']]);
        $document_status_forms = Documents::find()->select('document_status')->where(['email' => $user['email']])->column();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('student-document', ['model' => $model, 'dataProvider' => $dataProvider, 'username' => $username, 'document_status_forms' => $document_status_forms, 'count_clear_document' => $count_clear_document]);
    }

    public function actionManager()
    {
        $user_id = Yii::$app->user->id;
        $role = (User::find()->select('role')->where(['id' => $user_id])->column())[0];
        if ($role === 'user' or Yii::$app->user->isGuest) {
            return $this->redirect(['access-error']);
        }
        $model = new Documents();
        $form = Yii::$app->request->post();
        $action = $_POST['action'];
        if ($action == 'search') {
            if ($form['Documents']['title'] != '' and $form['Documents']['fio'] != '' and $form['Documents']['document_status'] != '-1' and $form['Documents']['count_additional_document'] != '-1') {
                if ($form['Documents']['count_additional_document']=='2'){
                    $query = Documents::find()
                        ->where(['like', 'title', $form['Documents']['title']])
                        ->andWhere(['like', 'fio', $form['Documents']['fio']])
                        ->andWhere(['=', 'document_status', $form['Documents']['document_status']])
                        ->andWhere(['<=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }else {
                    $query = Documents::find()
                        ->where(['like', 'title', $form['Documents']['title']])
                        ->andWhere(['like', 'fio', $form['Documents']['fio']])
                        ->andWhere(['=', 'document_status', $form['Documents']['document_status']])
                        ->andWhere(['=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }
            }
            else if ($form['Documents']['title'] != '' and $form['Documents']['fio'] != '' and ($form['Documents']['document_status'] != '-1' or $form['Documents']['count_additional_document'] != '-1')) {
                if($form['Documents']['document_status'] != '-1'){
                    $query = Documents::find()
                        ->where(['like', 'title', $form['Documents']['title']])
                        ->andWhere(['like', 'fio', $form['Documents']['fio']])
                        ->andWhere(['=', 'document_status', $form['Documents']['document_status']]);
                }else if ($form['Documents']['count_additional_document']=='2'){
                    $query = Documents::find()
                        ->where(['like', 'title', $form['Documents']['title']])
                        ->andWhere(['like', 'fio', $form['Documents']['fio']])
                        ->andWhere(['<=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }else {
                    $query = Documents::find()
                        ->where(['like', 'title', $form['Documents']['title']])
                        ->andWhere(['like', 'fio', $form['Documents']['fio']])
                        ->andWhere(['=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }
            }else if ($form['Documents']['title'] == '' and $form['Documents']['fio'] != '' and ($form['Documents']['document_status'] != '-1' or $form['Documents']['count_additional_document'] != '-1')) {
                if($form['Documents']['document_status'] != '-1'){
                    $query = Documents::find()
                        ->andWhere(['like', 'fio', $form['Documents']['fio']])
                        ->andWhere(['=', 'document_status', $form['Documents']['document_status']]);
                }else if ($form['Documents']['count_additional_document']=='2'){
                    $query = Documents::find()
                        ->andWhere(['like', 'fio', $form['Documents']['fio']])
                        ->andWhere(['<=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }else {
                    $query = Documents::find()
                        ->andWhere(['like', 'fio', $form['Documents']['fio']])
                        ->andWhere(['=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }
            }else if ($form['Documents']['title'] != '' and $form['Documents']['fio'] == '' and ($form['Documents']['document_status'] != '-1' or $form['Documents']['count_additional_document'] != '-1')) {
                if($form['Documents']['document_status'] != '-1'){
                    $query = Documents::find()
                        ->where(['like', 'title', $form['Documents']['title']])
                        ->andWhere(['=', 'document_status', $form['Documents']['document_status']]);
                }else if ($form['Documents']['count_additional_document']=='2'){
                    $query = Documents::find()
                        ->where(['like', 'title', $form['Documents']['title']])
                        ->andWhere(['<=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }else {
                    $query = Documents::find()
                        ->where(['like', 'title', $form['Documents']['title']])
                        ->andWhere(['=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }
            }else if ($form['Documents']['title'] == '' and $form['Documents']['fio'] == '' and ($form['Documents']['document_status'] != '-1' and $form['Documents']['count_additional_document'] != '-1')) {
                if($form['Documents']['document_status'] != '-1' and $form['Documents']['count_additional_document']=='2'){
                    $query = Documents::find()
                        ->andWhere(['=', 'document_status', $form['Documents']['document_status']])
                        ->andWhere(['<=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }else {
                    $query = Documents::find()
                        ->andWhere(['=', 'document_status', $form['Documents']['document_status']])
                        ->andWhere(['=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }
            }
            else if (($form['Documents']['title'] != '' or $form['Documents']['fio'] == '') and $form['Documents']['document_status'] == '-1' and $form['Documents']['count_additional_document'] == '-1') {
                if($form['Documents']['title'] != ''){
                    $query = Documents::find()
                        ->where(['like', 'title', $form['Documents']['title']]);
                }else{
                    $query = Documents::find()
                        ->andWhere(['like', 'fio', $form['Documents']['fio']]);
                }
            } else if (($form['Documents']['title'] == '' and $form['Documents']['fio'] == '') and $form['Documents']['document_status'] != '-1' and $form['Documents']['count_additional_document'] == '-1') {
                $query = Documents::find()
                    ->andWhere(['=', 'document_status', $form['Documents']['document_status']]);
            } else if (($form['Documents']['title'] == '' and $form['Documents']['fio'] == '') and $form['Documents']['document_status'] == '-1' and $form['Documents']['count_additional_document'] != '-1') {
                if ($form['Documents']['count_additional_document']=='2'){
                    $query = Documents::find()
                        ->andWhere(['<=', 'count_additional_document', $form['Documents']['count_additional_document']]);
                }else {
                    $query = Documents::find()
                        ->where(['=', 'count_additional_document', (int)$form['Documents']['count_additional_document']]);
                }
            }else if (($form['Documents']['title'] == '' and $form['Documents']['fio'] != '') and $form['Documents']['document_status'] == '-1' and $form['Documents']['count_additional_document'] == '-1') {
                $query = Documents::find()
                    ->andWhere(['like', 'fio', $form['Documents']['fio']]);
            }else if (($form['Documents']['title'] != '' and $form['Documents']['fio'] == '') and $form['Documents']['document_status'] == '-1' and $form['Documents']['count_additional_document'] == '-1') {
                $query = Documents::find()
                    ->andWhere(['like', 'title', $form['Documents']['title']]);
            } else {
                $query = Documents::find();
            }
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 500,
                ],
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            ]);
            return $this->render('manager', ['dataProvider' => $dataProvider, 'query' => $query, 'model' => $model,'searchForm'=>$searchForm]);
        }
        $manager = User::find()->where(['id' => $user_id])->one();
        $form = $form['Documents'];
        if ($form == null or $form == []) {
            $flag = 1;
        } else {
            for ($i = 0; $i < count($form); $i++) {
                $keys = array_keys($form);
                $manager_model = new ManagerLogs();
                $document_id = $keys[$i];
                $model = Documents::findOne($document_id);
                $oldmodel = $model->attributes;
                $result = array_diff($form[$document_id],$oldmodel);
                $student_id = Documents::find()->select('user_id')->where(['id' => $document_id])->one();
                $student = User::find()->where(['id' => $student_id])->one();
                if ($result!=null or $result!=[]){
                    if (isset($result['originality'])){
                        $model->originality = $form[$document_id]['originality'];
                        if ((int)$form[$document_id]['originality'] < 70 and $form[$document_id]['originality'] != '') {
                            $model->document_status = 'The article did not pass the originality test';
                        } else if ($form[$document_id]['originality'] >= 70 and $form[$document_id]['document_status'] != 'The article has been checked for originality' and isset($form[$document_id]['document_status'])) {
                            if ($form[$document_id]['document_status'] != 'The article has been checked for originality' and $form[$document_id]['document_status'] != 'The article did not pass the originality test' and $form[$document_id]['document_status'] != 'Article under consideration') {
                                $model->document_status = $form[$document_id]['document_status'];
                            } else {
                                $model->document_status = 'The article has been checked for originality';
                            }
                        }
                    }
                    if (isset($result['document_status'])) {
                        $model->document_status = $form[$document_id]['document_status'];
                        $manager_model->document_status_change = $form[$document_id]['document_status'];
                        $change_document_email_model = new SendEmailForm();
                        $change_document_email_model->sendEmailChangeDocumentStatus($document_id);;
                    }
                    if (isset($result['comment'])) {
                        $model->comment = $form[$document_id]['comment'];
                        $manager_model->comment = $form[$document_id]['comment'];
                    }
                    if (isset($result['personal_data'])) {
                        $model->personal_data = $form[$document_id]['personal_data'];
                        $manager_model->personal_data_status = $form[$document_id]['personal_data'];
                    }
                }
//                } else if ($form[$document_id]['originality'] >= 70 and isset($form[$document_id]['document_status'])) {
//                    $model->document_status = 'The article has been checked for originality';
//                } else if ($form[$document_id]['originality'] != '' and isset($form[$document_id]['document_status'])) {
//                    $model->document_status = 'Article under consideration';
//                } else {
//                    if ($form[$document_id]['document_status']) {
//                        $model->document_status = $form[$document_id]['document_status'];
//                    } else {
//                        $model->document_status = 'In the draft';
//                    }
//                }
//                $model->personal_data = $form[$document_id]['personal_data'];
//                $model->comment = $form[$document_id]['comment'];
//                //////////////////////////////////////////
//                $check = Documents::find()->where(['id' => $keys[$i]])->one();
//                $personal_data1 = $check['personal_data'];
//                $personal_data2 = $form[$document_id]['personal_data'];
//                $comment1 = $check['comment'];
//                $comment2 = $form[$document_id]['comment'];
//                if (isset($form[$document_id]['document_status'])) {
//                    $document_status1 = $form[$document_id]['document_status'];
//                } else {
//                    $document_status1 = 'In the draft';
//                }
//                $document_status2 = $check['document_status'];
//                $model->save(false);
//
//                if ($personal_data1 == $personal_data2 and $comment1 == $comment2 and $document_status1 == $document_status2) {
//                    continue;
//                } else if ($personal_data1 != $personal_data2 and $comment1 != $comment2) {
//                    $manager_model->comment = $model->comment;
//                    $manager_model->personal_data_status = $form[$document_id]['personal_data'];
//                } elseif ($personal_data1 == $personal_data2 and $comment1 != $comment2 and $document_status1 == $document_status2) {
//                    $manager_model->comment = $model->comment;
//                } elseif ($personal_data1 != $personal_data2 and $comment1 == $comment2) {
//                    $manager_model->personal_data_status = $form[$document_id]['personal_data'];
//                } ////c
//                elseif ($document_status1 != $document_status2 and $comment1 != $comment2) {///////////////
//                    $manager_model->document_status_change = $model->document_status;
//                    $manager_model->comment = $model->comment;
//                    $change_document_email_model = new SendEmailForm();
//                    $change_document_email_model->sendEmailChangeDocumentStatus($document_id);
//                } elseif ($document_status1 == $document_status2 and $comment1 != $comment2) {
//                    $manager_model->comment = $model->comment;
//                } elseif ($document_status1 != $document_status2 and $comment1 == $comment2) {
//                    $manager_model->document_status_change = $model->document_status;
//                    $change_document_email_model = new SendEmailForm();
//                    $change_document_email_model->sendEmailChangeDocumentStatus($document_id);
//                } ////b
//                elseif ($personal_data1 != $personal_data2 and $document_status1 != $document_status2) {
//                    $manager_model->personal_data_status = $form[$document_id]['personal_data'];
//                    $manager_model->document_status_change = $model->document_status;
//                    $change_document_email_model = new SendEmailForm();
//                    $change_document_email_model->sendEmailChangeDocumentStatus($document_id);
//                } elseif ($personal_data1 == $personal_data2 and $document_status1 != $document_status2) {
//                    $manager_model->document_status_change = $model->document_status;
//                    $change_document_email_model = new SendEmailForm();
//                    $change_document_email_model->sendEmailChangeDocumentStatus($document_id);
//                } elseif ($personal_data1 != $personal_data2 and $document_status1 == $document_status2) {
//                    $manager_model->personal_data_status = $form[$document_id]['personal_data'];
//                } else if ($personal_data1 != $personal_data2) {
//                    $manager_model->personal_data_status = $form[$document_id]['personal_data'];
//                } else if ($comment1 != $comment2) {
//
//                    $manager_model->comment = $model->comment;
//                } else if ($document_status1 != $document_status2) {
//
//                    $manager_model->document_status_change = $model->document_status;
//                    $change_document_email_model = new SendEmailForm();
//                    $change_document_email_model->sendEmailChangeDocumentStatus($document_id);
//                }
                $model->save();
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
                'pageSize' => 500,
            ],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);
        return $this->render('manager', ['dataProvider' => $dataProvider, 'query' => $query, 'model' => $model]);
    }

    public function actionView($id)
    {
        $user_id = Yii::$app->user->id;
        $user = Documents::find()->select('user_id')->where(['id' => $id])->column();
        $role = (User::find()->select('role')->where(['id' => $user_id])->column())[0];
        if ($role === 'user' or Yii::$app->user->isGuest) {
            return $this->redirect(['access-error']);
        }
        $student_id = Documents::find()->select('user_id')->where(['id' => $id])->one();
        $student = User::find()->where(['id' => $student_id])->one();
        $query = Documents::find()->select('*')->where(['user_id' => $user]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $additional_files = AdditionalFiles::find()->select('expert_name,expert_source,file_scan_name,file_scan_source,review_name,review_source')->where(['user_id' => $user])->andWhere(['document_id' => $id])->all();
        return $this->render('view', ['dataProvider' => $dataProvider, 'student' => $student, 'additional_files' => $additional_files]);
    }

    public function actionUpdate($id)
    {
        $user_id = Yii::$app->user->id;
        $role = (User::find()->select('role')->where(['id' => $user_id])->column())[0];
        if ($role === 'user' or Yii::$app->user->isGuest) {
            return $this->redirect(['access-error']);
        }
        $student_id = Documents::find()->select('user_id')->where(['id' => $id])->one();
        $manager = User::find()->where(['id' => $user_id])->one();
        $student = User::find()->where(['id' => $student_id])->one();
        $manager_model = new ManagerLogs();
        $model = Documents::findOne($id);
        if ($model->draft_status == 'draft') {
            return $this->redirect(['access-error']);
        }
        $form = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            if ((int)$form['Documents']['originality'] < 70 and $form['Documents']['originality'] != '') {
                $model->document_status = 'The article did not pass the originality test';
            } else if ($form['Documents']['originality'] >= 70 and $form['Documents']['document_status'] != 'The article has been checked for originality' and isset($form['Documents']['document_status'])) {
                if ($form['Documents']['document_status'] != 'The article has been checked for originality' and $form['Documents']['document_status'] != 'The article did not pass the originality test' and $form['Documents']['document_status'] != 'Article under consideration') {
                    $model->document_status = $form['Documents']['document_status'];
                } else {
                    $model->document_status = 'The article has been checked for originality';
                }
            } else if ($form['Documents']['originality'] >= 70 and isset($form['Documents']['document_status'])) {
                $model->document_status = 'The article has been checked for originality';
            } else if ($form['Documents']['originality'] != '' and isset($form['Documents']['document_status'])) {
                $model->document_status = 'Article under consideration';
            } else {
                if ($form['Documents']['document_status']) {
                    $model->document_status = $form['Documents']['document_status'];
                } else {
                    $model->document_status = 'In the draft';
                }
            }
            $personal_data_status = Documents::find()->select('personal_data')->where(['user_id' => $student_id])->column();
            $model->comment = $form['Documents']['comment'];
            $model->originality = $form['Documents']['originality'];

            $check = Documents::find()->where(['id' => $id])->one();
            $personal_data1 = $check['personal_data'];
            $personal_data2 = $check['personal_data'];
            $comment1 = $check['comment'];
            $comment2 = $form['Documents']['comment'];
            $document_status1 = $form['Documents']['document_status'];
            $document_status2 = $check['document_status'];
            $model->save();
            if ($personal_data1 == $personal_data2 and $comment1 == $comment2 and $document_status1 == $document_status2) {
                Yii::$app->session->setFlash('success', '??????????????');
                return $this->redirect(['update', 'id' => $id]);
            } else if ($personal_data1 != $personal_data2 and $comment1 != $comment2) {
                $manager_model->comment = $model->comment;
                $manager_model->personal_data_status = $form['Documents']['personal_data'];
            } elseif ($personal_data1 == $personal_data2 and $comment1 != $comment2 and $document_status1 == $document_status2) {
                $manager_model->comment = $model->comment;
            } elseif ($personal_data1 != $personal_data2 and $comment1 == $comment2) {
                $manager_model->personal_data_status = $form['Documents']['personal_data'];
            } elseif ($document_status1 != $document_status2 and $comment1 != $comment2) {///////////////
                $manager_model->document_status_change = $model->document_status;
                $manager_model->comment = $model->comment;
                $change_document_email_model = new SendEmailForm();
                $change_document_email_model->sendEmailChangeDocumentStatusUpdate($id);
            } elseif ($document_status1 == $document_status2 and $comment1 != $comment2) {
                $manager_model->comment = $model->comment;
            } elseif ($document_status1 != $document_status2 and $comment1 == $comment2) {
                $manager_model->document_status_change = $model->document_status;
                $change_document_email_model = new SendEmailForm();
                $change_document_email_model->sendEmailChangeDocumentStatusUpdate($id);
            } ////b
            elseif ($personal_data1 != $personal_data2 and $document_status1 != $document_status2) {
                $manager_model->document_status_change = $model->document_status;
                $change_document_email_model = new SendEmailForm();
                $change_document_email_model->sendEmailChangeDocumentStatusUpdate($id);
            } elseif ($personal_data1 == $personal_data2 and $document_status1 != $document_status2) {
                $manager_model->document_status_change = $model->document_status;
                $change_document_email_model = new SendEmailForm();
                $change_document_email_model->sendEmailChangeDocumentStatusUpdate($id);
            } elseif ($personal_data1 != $personal_data2 and $document_status1 == $document_status2) {
            } else if ($personal_data1 != $personal_data2) {
                $manager_model->personal_data_status = $check['personal_data'];;
            } else if ($comment1 != $comment2) {

                $manager_model->comment = $model->comment;
            } else if ($document_status1 != $document_status2) {
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
            Yii::$app->session->setFlash('success', '??????????????');
            return $this->redirect(['update', 'id' => $id]);
        }
        $data = ManagerLogs::find()->where(['user_id' => $student_id])->andWhere(['document_id' => $id])->all();
        return $this->render('update', ['model' => $model, 'data' => $data, 'student' => $student]);
    }

    public function actionAccessError()
    {
        return $this->render('access-error');
    }

    public function actionAdditionalStudentDocument()
    {
        $document_id = $_GET['id'];
        $user_id = Yii::$app->user->id;
        $user = User::find()->where(['id' => $user_id])->one();
        $role = (User::find()->select('role')->where(['id' => $user_id])->column())[0];
        if ($role === 'manager' or Yii::$app->user->isGuest) {
            return $this->redirect(['access-error']);
        }
        $model = AdditionalFiles::find()->where(['user_id' => $user_id])->andWhere(['document_id' => $document_id])->one();
        $document_model = Documents::find()->where(['user_id' => $user_id])->andWhere(['id' => $document_id])->one();
        $upload_document_model = new UploadChangeDocumentForm();
        if ($model == null) {
            $model = new AdditionalFiles();
        }

        if (Yii::$app->request->isPost) {
            $title = $_POST['Documents']['title'];
            $document_model->title = $title;
            $document_model->save();
            $expert = $_FILES['AdditionalFiles']['name']['expert'];
            $expert = mb_strtolower(UploadDocumentForm::transliterate($expert));
            $review = $_FILES['AdditionalFiles']['name']['review'];
            $review = mb_strtolower(UploadDocumentForm::transliterate($review));
            $file_scan = $_FILES['AdditionalFiles']['name']['file_scan'];
            $file_scan = mb_strtolower(UploadDocumentForm::transliterate($file_scan));
            $model->document_id = $document_id;
            $timestamp = date('dmYHis');
            $document_status_model = $document_model->document_status;
            if ($document_status_model != 'The article did not pass the originality test' and $document_status_model != 'The article does not meet the requirements' and $document_status_model!=null and $_FILES['UploadChangeDocumentForm']['name']['file']!=null) {
                Yii::$app->session->setFlash('error', '???? ?????????????? ???????????????? ???????? ????????????, ?????? ?????? ???????? ???????????? ?????????? ???????????????? ?????? ???????????????? "???????????? ???? ???????????? ???????????????? ???? ????????????????????????????" ?? "???????????? ???? ?????????????????????????? ??????????????????????"');
            } else if(($document_status_model == 'The article did not pass the originality test' or $document_status_model == 'The article does not meet the requirements') and $_FILES['UploadChangeDocumentForm']['name']['file']!=null) {
                $upload_document_model->file = UploadedFile::getInstance($upload_document_model, 'file');
                $timestamp = date('dmYHis');
                $result = $upload_document_model->upload($timestamp);
                if ($result == true) {
                    $filename = UploadDocumentForm::transliterate($upload_document_model->file->baseName);
                    $filename = mb_strtolower($filename) . '_' . $timestamp . '.' . $upload_document_model->file->extension;
                    $document_model->document_status = 'Article under consideration';;
                    $document_model->datetime = date('d.m.Y H:i:s');
                    $document_model->source = 'UploadDocument/' . $filename;
                    $document_model->save(false);
                    Yii::$app->session->setFlash('success', '???????? ???????????? ?????????????? ??????????????');
                }
            }
            if ($expert != '' or $review != '' or $file_scan != ''){
                if ($expert != '' and $review != '' and $file_scan != '') {
                    $model->expert = UploadedFile::getInstance($model, 'expert');
                    $model->file_scan = UploadedFile::getInstance($model, 'file_scan');
                    $model->review = UploadedFile::getInstance($model, 'review');
                    $model->review_name = $review;
                    $model->expert_name = $expert;
                    $model->file_scan_name = $file_scan;
                    $model->expert_source = 'UploadDocumentExpert/' . $timestamp . '_' . $expert;
                    $model->review_source = 'UploadDocumentReview/' . $timestamp . '_' . $review;
                    $model->file_scan_source = 'UploadDocumentFileScan/' . $timestamp . '_' . $file_scan;
                } else if (($expert != '' or $review != '') and $file_scan == '') {
                    if ($expert != '') {
                        $model->expert_name = $expert;
                        $model->expert_source = 'UploadDocumentExpert/' . $timestamp . '_' . $expert;
                        $model->expert = UploadedFile::getInstance($model, 'expert');
                    }
                    if ($review != '') {
                        $model->review_name = $review;
                        $model->review_source = 'UploadDocumentReview/' . $timestamp . '_' . $review;
                        $model->review = UploadedFile::getInstance($model, 'review');
                    }
                } else if (($expert != '' or $file_scan != '') and $review == '') {
                    if ($file_scan != '') {
                        $model->file_scan_name = $file_scan;
                        $model->file_scan_source = 'UploadDocumentFileScan/' . $timestamp . '_' . $file_scan;
                        $model->file_scan = UploadedFile::getInstance($model, 'file_scan');
                    }
                    if ($expert != '') {
                        $model->expert_name = $expert;
                        $model->expert_source = 'UploadDocumentExpert/' . $timestamp . '_' . $expert;
                        $model->expert = UploadedFile::getInstance($model, 'expert');
                    }
                } else if (($review != '' or $file_scan != '') and $expert == '') {
                    if ($file_scan != '') {
                        $model->file_scan_name = $file_scan;
                        $model->file_scan_source = 'UploadDocumentFileScan/' . $timestamp . '_' . $file_scan;
                        $model->file_scan = UploadedFile::getInstance($model, 'file_scan');
                    }
                    if ($review != '') {
                        $model->review_name = $review;
                        $model->review_source = 'UploadDocumentReview/' . $timestamp . '_' . $review;
                        $model->review = UploadedFile::getInstance($model, 'review');
                    }

                }

                if ($model->upload()) {
                    $model->user_id = $user_id;
                    $model->fio = $user['fio'];
                    $model->save();
                    $count_additional_files = 0;
                    $expert_file_db = AdditionalFiles::find()->select('expert_name,expert_source')->where(['user_id' => $user])->andWhere(['document_id' => $document_id])->one();
                    $review_file_db = AdditionalFiles::find()->select('review_name,review_source')->where(['user_id' => $user])->andWhere(['document_id' => $document_id])->one();
                    $file_scan_file_db = AdditionalFiles::find()->select('file_scan_name,file_scan_source')->where(['user_id' => $user])->andWhere(['document_id' => $document_id])->one();
                    $exist_check = [$expert_file_db['expert_name'], $review_file_db['review_name'], $file_scan_file_db['file_scan_name']];
                    for ($i = 0; $i < 3; $i++) {
                        if ($exist_check[$i]) {
                            $count_additional_files += 1;
                        }
                    }
                    $model_document = Documents::findOne(['id' => $document_id]);
                    $model_document->count_additional_document = $count_additional_files;
                    $model_document->save();
                    if (Yii::$app->request->isPost) {
                        if ($count_additional_files == 3) {
                            $model_document->document_status = 'In processing';
                            $model_document->save();
                        }
                    }
                }
                Yii::$app->session->setFlash('success', '???????????????????????????? ?????????? ?????????????? ??????????????????');
            }

        }
        $additional_files = AdditionalFiles::find()->select('expert_name,expert_source,file_scan_name,file_scan_source,review_name,review_source')->where(['user_id'=>$user])->andWhere(['document_id'=>$document_id])->all();
        return $this->render('additional-student-document',['model' => $model,'additional_files'=>$additional_files,'document_model'=>$document_model,'upload_document_model'=>$upload_document_model]);

    }

    public function actionDeleteStudentDocument($id){
        $document = Documents::find()->where(['!=','document_status','The article did not pass the originality test'])->andWhere(['id'=>$id])->one();
        if($document){
            $document->delete();
            Yii::$app->session->setFlash('success', '???????????? ??????????????');
            return $this->redirect(['student-document']);
        }else{
            Yii::$app->session->setFlash('error', '???? ?????????????? ?????????????? ????????????');
            return $this->redirect(['student-document']);
        }
    }
    public function actionUpdatePersonalInformation($id){
        $personal_information_model = User::findOne($id);
        $document_personal_information = Documents::find()->where(['user_id'=>$id])->all();
        if ($id != Yii::$app->user->identity->id and Yii::$app->user->identity->role =='user') {
            return $this->redirect(['access-error']);
        }
        if (Yii::$app->request->isPost) {
            for ($i=0;$i<count($document_personal_information);$i++){
                $document_personal_information_model = $document_personal_information[$i];
                $document_personal_information_model->fio = $_POST['User']['fio'];
                $document_personal_information_model->phone = $_POST['User']['phone'];
                $document_personal_information_model->email = $_POST['User']['email'];
                $document_personal_information_model->authors = $_POST['User']['fio'];
                $document_personal_information_model->organization = $_POST['User']['organization'];
                $document_personal_information_model->save();
            }
            $personal_information_model->fio = $_POST['User']['fio'];
            $personal_information_model->phone = $_POST['User']['phone'];
            $personal_information_model->email = $_POST['User']['email'];
            $personal_information_model->organization = $_POST['User']['organization'];
            $personal_information_model->save();
            Yii::$app->session->setFlash('success', '??????????????');
        }
        return $this->render('update-personal-information',['personal_information_model' => $personal_information_model]);

    }
}

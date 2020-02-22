<?php

namespace app\controllers;

use app\models\Category;
use Yii;
use app\models\Menu;
use app\models\MenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex($category)
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(array_merge(Yii::$app->request->queryParams, ['category' => $category]));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category' => $category
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($category)
    {
        $model = new Menu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'category' => $category
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'category' => $model->category
        ]);
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $category = $model->category;

        $model->delete();

        return $this->redirect(['index', 'category' => $category]);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionMenuItems()
    {
        $page = Yii::$app->request->getQueryParam('page');
        $perPage = Yii::$app->request->getQueryParam('perPage');
        $category = Yii::$app->request->getQueryParam('category');
        // (page - 1) * perPage
        $offset = ($page - 1) * $perPage;

        $items = Menu::find()->where(['category' => $category])->offset($offset)->limit($perPage)->all();
        $total = Menu::find()->where(['category' => $category])->count();

        return $this->asJson(
            [
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'data' => $items
            ]
        );
    }

    public function actionMenuItem()
    {
        $id = Yii::$app->request->getQueryParam('id');

        $item = Menu::findOne(['id' => $id]);

        return $this->asJson(
            [
                'data' => $item
            ]
        );
    }
}

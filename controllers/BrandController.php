<?php

namespace app\controllers;

use yii\web\Controller;

class BrandController extends Controller {

    public $layout = false;
    
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionCreate() {
        return $this->render('create');
    }

}

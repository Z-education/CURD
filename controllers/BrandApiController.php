<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\controllers\tools\Api;
use yii\data\Pagination;
use yii\widgets\LinkPager;

class BrandApiController extends Controller {

    public $enableCsrfValidation = false;
    public $param;

    public function init() {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $this->param = $request->post();
        } else {
            $this->param = $request->get();
        }
        if (!Api::check_sign($this->param)) {
            return $this->response(500, Api::$error);
        }
        unset($this->param['sign']);
    }

    public function actionIndex() {
        $sql = 'select * from brand';
        $where = ' where 1=1';
//        print_r($this->param);
        if(isset($this->param['brand_name']) && !empty($this->param['brand_name'])){
            $where .= ' and brand_name like "%' . $this->param['brand_name'] . '%"'; 
        }
        if(isset($this->param['brand_type']) && !empty($this->param['brand_type'])){
            $where .= ' and brand_type = ' . $this->param['brand_type']; 
        }
        $sql .= $where;
        $count = Yii::$app->db->createCommand($sql)->execute();
        $pagination = new Pagination([
            'totalCount' => $count,
            'defaultPageSize' => 2
        ]);
        $sql .= ' limit ' . $pagination->offset . ',' . $pagination->limit;
        $data = [];
        $data['list'] = Yii::$app->db->createCommand($sql)->queryAll();
        $data['page'] = LinkPager::widget([
                    'pagination' => $pagination,
        ]);
        return $this->response(200, 'ok', $data);
    }

    public function actionCreate() {
        $res = Yii::$app->db->createCommand()->insert('brand', $this->param)->execute();
        $response = $res ? [] : [500, '添加失败'];
        return $this->response(...$response);
    }
    
    public function actionEdit(){
        $table = $this->param['table'];
        $pk = $this->param['pk'];
        $id = $this->param['id'];
        $column = $this->param['column'];
        $data = $this->param['data'];
        $res = Yii::$app->db->createCommand()->update($table, [
            $column => $data
        ], [
            $pk => $id
        ])->execute();
        $response = $res ? [] : [500, '添加失败'];
        return $this->response(...$response);
    }

    public function response($code = 200, $msg = 'ok', $data = []) {
        echo json_encode([
            'code' => $code,
            'message' => $msg,
            'data' => $data
        ]);
        exit();
    }

}

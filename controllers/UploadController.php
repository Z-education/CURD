<?php

namespace app\controllers;

use yii\web\Controller;

class UploadController extends Controller {
    
    public $enableCsrfValidation = false;
    
    public function actionUpload() {
        $file = $_FILES['upload_file'];
        if($file['error'] == 0){
            $filename = time() . rand(1000, 9999) . '.jpg';
            $filepath = './uploads/';
            if(move_uploaded_file($file['tmp_name'], $filepath . $filename)){
                $response = [200, '上传成功', ['file_path' =>  $filepath . $filename]];
            }else{
                $response = [500, '上传失败'];
            }
        }else{
            $response = [500, '上传失败'];
        }
        return $this->response(...$response);
    }

    public function response($code = 200, $msg = 'ok', $data = []){
        echo json_encode([
            'code' => $code,
            'message' => $msg,
            'data' => $data
        ]);
        exit();
    }
}

<?php

namespace app\controllers\tools;

class Api {

    //错误信息
    static $error;
    //存储当前对象的变量
    private static $class;

    //定义接口类型
    const API_TYPE = [
        'OMS' => 'http://www.oms.com/',
        'PMS' => 'http://www.pms.com/',
        'WMS' => 'http://www.wms.com/',
        'TMS' => 'http://www.tms.com/',
        'GMS' => 'http://www.gms.com/',
        'BMS' => 'http://www.bms.com/',
    ];
    //定义接口请求的密钥
    const API_KEY = '~!@#$%^&*()_+';

    private static function init() {
        self::$class = new SELF();
    }

    /*
     * 统一接口请求方法：静态   Api::request();
     * @param string $type      接口请求类型，根据API_TYPE选定接口请求的类型与前缀
     * @param string $route     接口路由，请求地址的控制器与方法  控制器/方法  Index/index
     * @param array  $param     接口请求的参数，数组格式 key => value
     * @return array $return    返回的接口请求数据
     */

    public static function request($type, $route, $param = []) {
        //类初始化
        self::init();
        //当前请求类型是否存在
        if (!array_key_exists($type, self::API_TYPE)) {
            self::$error = '当前请求类型不存在';
            return false;
        }
        //获取参数的query字符串
        $query = self::$class->get_query($param);
        //获取签名
        $sign = self::$class->get_sign($param);
        //拼接请求的url
        $url = self::API_TYPE[$type] . '?r=' . $route . '&' . $query . '&sign=' . $sign;
        //发送请求
        $res = self::$class->curl_api($url);
        //返回结果
        return json_decode($res, TRUE);
    }

    /*
     * 接口验证方法
     * @param array $param  接收到的请求参数（所有）
     */

    public static function check_sign($param) {
        self::init();
        if (!array_key_exists('sign', $param)) {
            self::$error = '签名参数不存在';
            return false;
        }
        $sign = $param['sign'];
        unset($param['sign']);
        unset($param['r']);
        $sign_ = self::$class->get_sign($param);
        if ($sign_ == $sign) {
            return TRUE;
        }
        self::$error = '签名错误';
        return false;
    }

    /*
     * 获取生成查询字符串
     * @param   array    $param  参数数组
     * @return  string   $query  查询字符串 key=value&a=b&c=d
     */

    private function get_query($param) {
        //拼接查询字符串
        $query = [];
        foreach ($param as $key => $val) {
            $query[] = $key . '=' . $val;
        }
        return implode('&', $query);
    }

    /*
     * 生成签名方法
     * @param array $param  参数数组
     * @return string $sign 签名字符串
     */

    private function get_sign($param) {
        //对数组进行去空操作
        foreach ($param as $key => $val) {
            if ($val === '') {
                unset($param[$key]);
            }
        }
        //对数组进行排序
        ksort($param);
        $query = self::$class->get_query($param);
//        echo $query;die;
        return md5($query . self::API_KEY);
    }

    /*
     * curl-request
     */

    private function curl_api($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

}

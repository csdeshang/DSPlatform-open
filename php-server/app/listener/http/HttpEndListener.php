<?php

namespace app\listener\http;


use think\Response;



use app\common\dao\admin\AdminLogsDao;
use app\common\dao\system\SysAccessLogsDao;


class HttpEndListener
{


    public function handle(Response $response)
    {



        // 访问根目录
        $root = str_replace("/", "", request()->rootUrl());

        // 管理员操作日志
        if ($root == 'adminapi') {
            $this->handleAdminlogs($response);
        }

        // 店铺操作日志
        if ($root == 'storeapi') {
            // $this->handleShoplogs($response);
        }


        // 所有访问日志
        if (env('app_debug', false) && $root != 'adminapi') {
            $this->handleSysAccesslogs($response);
        }
    }


    private function handleSysAccesslogs($response)
    {

        $request = request();
        $code = isset($response->getData('code')['code']) ? $response->getData('code')['code'] : 0;

        // 排除访问方法
        $exclude_method = array('GET');
        if(in_array($request->method(),$exclude_method)){
            return;
        }

        // 排除控制器
        // $exclude_controller = array('admin.AdminLogs','authorize.Authorize');
        // if(in_array($request->controller(),$exclude_controller)){
        //     return;
        // }
        // 排除方法
        // $exclude_action = array('login','logout');
        // if(in_array($request->action(),$exclude_action)){
        //     return;
        // }


        // 排除code
        // $exclude_code = array(10000);
        // if(in_array($code,$exclude_code)){
        //     return;
        // }

        // 排除HTTP_CODE
        // $exclude_http_code = array(200);
        // if(in_array($response->getCode(),$exclude_http_code)){
        //     return;
        // }


        $params = $request->param();
        //过滤字段
        $exclude_field = array('password', 'password_confirm', 'confirm_password', 'refresh_token', 'access_token');

        foreach ($exclude_field as $field) {
            if (isset($params[$field])) {
                $params[$field] = "******";
            }
        }




        $user_id = $request->user_id;
        $username = $request->username;




        $log = array(
            'user_id' => $user_id,
            'username' => $username,
            'ip' => $request->ip(),
            'root' => str_replace("/", "", request()->rootUrl()),
            'method' => $request->method(),
            'controller' => $request->controller(),
            'action' => $request->action(),
            'url' => $request->url(),
            'params' => json_encode($params, true), //请求参数 占用较大 不需要可删除
            'result' => $response->getContent(), //返回结果 占用较大 不需要可删除
            'duration' => ceil(microtime(true) * 1000 - ($request->time(true) * 1000)),
            'http_code' => $response->getCode(),
            'code' => $code,
        );
        (new SysAccessLogsDao())->createAccessLog($log);
    }

    // 管理员操作日志
    private function handleAdminlogs($response)
    {
        $request = request();
        $code = isset($response->getData('code')['code']) ? $response->getData('code')['code'] : 0;

        // 排除访问方法
        $exclude_method = array('GET');
        if(in_array($request->method(),$exclude_method)){
            return;
        }

        // 排除控制器
        $exclude_controller = array('admin.AdminLogs', 'authorize.Authorize');
        if (in_array($request->controller(), $exclude_controller)) {
            return;
        }
        // 排除方法
        $exclude_action = array('login', 'logout');
        if (in_array($request->action(), $exclude_action)) {
            return;
        }

        // 排除code
        // $exclude_code = array(10000);
        // if(in_array($code,$exclude_code)){
        //     return;
        // }

        // 排除HTTP_CODE
        // $exclude_http_code = array(200);
        // if(in_array($response->getCode(),$exclude_http_code)){
        //     return;
        // }


        $params = $request->param();
        //过滤字段
        $exclude_field = array('password', 'password_confirm', 'confirm_password', 'refresh_token', 'access_token');
        foreach ($exclude_field as $field) {
            if (isset($params[$field])) {
                $params[$field] = "******";
            }
        }


        // 不需要的字段可自行删除
        $log = array(
            'user_id' => $request->user_id,
            'username' => $request->username,
            'ip' => $request->ip(),
            'root' => str_replace("/", "", request()->rootUrl()),
            'method' => $request->method(),
            'controller' => $request->controller(),
            'action' => $request->action(),
            'url' => $request->url(),
            'params' => json_encode($params, true), //请求参数 占用较大 不需要可删除
            'result' => $response->getContent(), //返回结果 占用较大 不需要可删除
            'duration' => ceil(microtime(true) * 1000 - ($request->time(true) * 1000)),
            'http_code' => $response->getCode(),
            'code' => $code,
        );
        (new AdminLogsDao())->createAdminLogs($log);
    }
}

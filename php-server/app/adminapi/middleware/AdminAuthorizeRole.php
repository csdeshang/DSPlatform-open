<?php

namespace app\adminapi\middleware;

use app\Request;

use app\adminapi\service\admin\AdminMenuService;
use app\adminapi\service\admin\CurrentAdminService;

/**
 * admin用户权限验证
 */
class AdminAuthorizeRole
{
    public function handle(Request $request, \Closure $next)
    {

        //超级管理员不做权限验证
        if (1 === $request->admin_is_super) {
            return $next($request);
        }


        // 当前请求的路由规则信息
        $current_rule = 'adminapi/' . $request->rule()->getRule();


        //当前apiurl 是否存在 admin_menu 表中
        $AdminMenuService = new AdminMenuService();
        $api_url_list = $AdminMenuService->getAllAdminMenuApiurlList();


        // 如果当前请求的路由规则信息 不在 api_url_list 中，且是Get请求则不做权限验证
        if (!in_array($current_rule, $api_url_list) && $request->method() == 'GET') {
            return $next($request);
        }


        //判断当前管理员是否拥有当前apiurl的权限
        //获取当前管理员拥有的apiurl列表
        $api_url_list = (new CurrentAdminService)->getAuthorizeApiurlList();
        if (in_array($current_rule, $api_url_list)) {


            //对参数的判断 暂不处理

            return $next($request);
        }

        // 将<id>转换为HTML实体编码，确保前端正确显示
        $display_rule = str_replace('<id>', '&lt;id&gt;', $current_rule);

        return ds_json_error('您没有权限使用该接口' . $display_rule);
    }
}

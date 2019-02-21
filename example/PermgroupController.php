<?php
/**
 * Created by PhpStorm.
 * User: pangkai(116390143@qq.com)
 * Date: 2017/12/20 0020
 * File: PermgroupController.php
 * Using: 部门
 */
namespace app\modules\v1\controllers;

class PermgroupController extends ApiController
{
    /**
     * @apiDoc
     * @api /permgroup/newrec
     * @group permgroup
     * @name 新增权限组
     * @desc 注销登录
     * @method POST
     * @param group_name string 权限组名称，限长255字符
     * @param perm string 权限ID，多个英文逗号分隔
     * @param notes string 权限组备注，限长255字符
     * @return json
     {
        "code":"0",
        "msg":"成功",
        "data": "登出成功"
     }
    */
    public function actionNewrec()
    {
        
    }

    /**
     * @apiDoc
     * @api /permgroup/modifyrec
     * @group permgroup
     * @name 修改权限组
     * @desc 修改一条权限组记录
     * @method POST
     * @param perm_group_id int 权限组id
     * @param group_name string 权限组名称，限长255字符
     * @param perm string 权限ID，多个英文逗号分隔
     * @param notes string 权限组备注，限长255字符
     * @return json
     {
        "code":"0",
        "msg":"成功",
        "data": "登出成功"
     }
    */
    public function actionModifyrec()
    {
        
    }
}
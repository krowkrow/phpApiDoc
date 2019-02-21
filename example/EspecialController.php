<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2018年4月28日
 * File: EspecialController.php
 * Enconding: UTF-8
 * Using:
 */
class EspecialController
{
    /**
     * @apiDoc
     * @api /employee/login2
     * @name 登录
     * @group esp
     * @return json
     {
     "code":"0",
     "msg":"成功",
     "data":{
     "employee_id": "2",  //员工id
     "employee_name": "钟有文 ",  //员工们
     "struc_id": "2",   //自身绑定门店id
     "struc_name": "2",   //门店名
     "login_key": "ded0833f635131da395724f66a891042",   //token令牌
     "position_id": "2",   //岗位id
     "position_name": "部长",   //岗位名称
     "phone": "13350093791",   //电话
     "is_freezed": "0",   //是否冻结，1-冻结， 0-正常
     "address": "这是店铺地址，收货地址",   //店铺地址，收货地址
     "perm": [
     {
     "perm_id": "1",   //权限id
     "function_code": "struc",
     "path_info": "struc/newrec/"   //权限路径
     },
     {
     "perm_id": "2",
     "function_code": "struc",
     "path_info": "http://struc/search/"
     },
     {
     "perm_id": "3",
     "function_code": "struc",
     "path_info": "struc/modifyrec/"
     }
     ]
     }
     }
     */
    public function actionLogin() {}

    /**
     * @apiDoc
     * @api /employee/sss
     * @name 登录
     * @group esp
     * @param id int 测似乎
     * @param type int 类型
     * @param ext string 附加参数 和其他，或者是   水电费   noreq
     * @return json
     {
     "code":"0",
     "msg":"成功"
     }
     */
    public function actionSddd() {}
}
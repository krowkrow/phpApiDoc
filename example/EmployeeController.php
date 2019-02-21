<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2017年12月21日
 * File: EmployeeController.php
 * Enconding: UTF-8
 * Using:
 */
namespace app\modules\v1\controllers;

use app\modules\common\Verify;

/**
 * @GroupDoc
 * @code employee
 * @name 员工
 * @order 1
 */
class EmployeeController extends ApiController
{

    /**
     * @apiDoc
     * @api /employee/login
     * @name 登录
     * @desc 员工登录
     * @group employee
     * @method POST
     * @auth 庞凯
     * @param phone string 电话号码  noreq 0
     * @param password string 密码
     * @param code string 验证码 noreq 0
     * @return json
     {
    "code": "000000",
    "msg": "获取列表成功",
    "info": {
        "newest": [             //最新的
            {
                "id": "239",  //id
                "nid": "BamBooPEER1804201556225ad99d26360aa79929",   //订单id
                "title": "测试",              //title
                "show_name": "测试",          //显示的名字
                "up": "0",                      //点赞数
                "addtime": "18-04-20 15:56:22",    //添加时间
                "status": "1",                      //状态
                "pic": "",                          //头像
                "user_id": "184",                   //用户id
                "status_name": "发布成功",          //状态
            "pic_url": []                       //图片列表
        }
    ],
    "normal": [             //常规的
        {
            "id": "239",
            "nid": "BamBooPEER1804201556225ad99d26360aa79929",
            "title": "测试",
            "show_name": "测试",
            "up": "0",
            "addtime": "18-04-20 15:56:22",
            "status": "1",
            "pic": "",
            "user_id": "184",
            "status_name": "发布成功",
        "pic_url": []
    }
]
}
}
    */
    public function actionLogin()
    {

    }

}
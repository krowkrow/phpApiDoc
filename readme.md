## 用途

PHP命令行模式[CLI]下使用，输入目标目录，解析目录中的php文件的注释，输出到指定文件

    php index.php parseDirectory -o outFilePaht
    
    parseDirectory：需要解析的目标目录，如 /D/work/test/example/
    outFilePaht：输出的文件路径，如 /D/work/apidoc/example.json

## 规范

- 以@proDoc为一个api项目的注释文档
- 以@GroupDoc为一个controller文件的注释
- 以@apiDoc为一个api接口的注释文档


### 项目注释proDoc

项目注释，可以任意放一个地方；一个项目如果存在多个procDoc注释，会造成覆盖。

    /**
     * 这里随便注释，下一行是项目注释的开始
     * @proDoc
     * @auth 作者(pangkai)
     * @date 时间(2018-03-09)
     * @testUrl 测试地址(http://192.168.1.188:8021)
     * @version 版本号(v1.0)
     * @title 项目名称(车贷在线)
     * @desc 这里项目说明(车贷在线测试部分，具体如下，比比比比)
     * @input-type application/x-www-form-urlencoded  
     * @output-type application/json
     */
     
注意事项： 如果某项缺席了，则给与默认参数：

    auth: 未知
    date：当前时间
    testUrl: 未知
    version：v1.0
    title：未知
    desc：空
    input-type：application/x-www-form-urlencoded
    output-type：application/json

### 分组信息

这个注释置放于控制器的类注释里；

    /**
     * 这里随便注释，下一行是项目说明的开始
     * @GroupDoc
     * @code 组识别码(menu)
     * @name 分组名(菜单-menu)
     * @order 分组的排序(11)
     */
    class MenuController extends ApiController{}

注意事项： 如果某项缺席了，则给与报错或默认参数，具体如下：

    code：报错
    name: 默认为code的值
    order： 默认为1
    
### api注释apiDoc

这个注释置于接口的注释。

其中的@param解释，各部分是以空格分开的

    @param page int 分页大小 req|noreq 默认为15
    @param 参数       类型     参数名       是否必须         最后是默认值
    参数：必须
    类型：必须
    参数名：即参数说明，默认为参数
    是否必须：默认为必须
    默认值：默认为null
    
返回结构里面，  字段解释放置在后面，用双斜杠开头

模板如下：

    /**
     * 这里随便注释，下一行是项目说明的开始
     * @apiDoc
     * @api 接口路径(/menu/all)
     * @name 接口名(所有菜品)
     * @desc 接口描述
     * @group 组识别码(menu)
     * @method POST/GET
     * @auth 作者(pangkai)
     * @param page_size int 分页大小 req|noreq 默认为15
     * @return json
     {
        "code": 10000000,
        "msg": "成功！",
        "requestId": 0,
        "response": {
            "current": 1, //当前页码
            "pageSize": 20, //分页大小
            "list": [ //封号玩家列表
                {
                    "uid": 2, //封号uid
                    "app_id": "1", //封号app
                    "ct": 1470816945, //封号时间
                    "desc": "", //封号描述
                    "id": 2
                }
            ]
        }
     }
     */
    public function actionAll() 
    
注意事项： 如果某项缺席了，则给与报错或默认参数，具体如下：

    api：报错
    name：报错
    desc：默认为name的值
    group：默认为当前类的group的code，如果code不存在，则新建一个group，code为该处的值，
    method：默认为GET
    auth：默认为空
    return: 缺少就报错
    
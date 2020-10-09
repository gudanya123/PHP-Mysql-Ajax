<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2020-10-08
 * Time: 18:04
 */
/**
 * 1.建立连接
 * 2.写sql
 * 3.执行
 * 4.处理结果
 * 5.关闭
 */

require_once 'DB.php';

class App{
    private $db;

    /**
     * App constructor.
     */
    public function __construct(){
        $this->db = new DB([
            'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=PHP',
            'user' => 'root',
            'password' => 'Yb5210450',
            'charset' => 'utf-8'
        ]);

    }

    /**
     *  入口方法
     */
    public function run(){
        try{
            $pageSize = $_GET['page_size'] ?? 2;
            $pageIndex = $_GET['page_index'] ?? 1;

            $data = $this->pagination($pageSize,$pageIndex);

            $count = intval($this->getCount());

            $totalPage = ceil($count / $pageSize);

            $data = [
                'count' => $count,
                'totalPage' => $totalPage,
                'data' => $data
            ];

            return $this->returnSuccessData($data);


        }catch (Exception $err){
            return $this->returnData($err->getCode(),$err->getMessage());

        }

    }

    /**
     * 分页查询
     * @param $pageSize
     * @param $pageIndex
     * @return array
     * @throws MySQLException
     */
    public function pagination($pageSize,$pageIndex){
        $sql = 'select * from user limit ? offset ?';
        $limit = $pageSize;
        $offset = $pageSize * ($pageIndex - 1);

        $data = $this->db->query($sql,[
            [$limit,PDO::PARAM_INT],
            [$offset,PDO::PARAM_INT]
        ]);
        return $data;

    }
    /**
     *  //获取总的页数
     */
    public function getCount(){
        $sql = 'select count(id) as count from user';
        $data = $this->db->query($sql);

        return $data[0]['count'];

    }

    /**
     * 返回正常接口数据
     * @param $data
     * @return false|string
     */
    public function returnSuccessData($data){
        $content = [
            'code' => 0,
            'msg' => '成功',
            'data' => $data,
        ];

        return json_encode($content);
    }

    /**
     * 返回数据
     * @param $code
     * @param $msg
     * @param array $data
     * @return false|string
     */
    public function returnData($code,$msg,$data =[]){
        $content = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];

        return json_encode($content);
    }
}

$app = new App();
$re = $app->run();

echo $re;


?>

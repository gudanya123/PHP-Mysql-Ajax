<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2020-10-08
 * Time: 17:12
 */

class DB
{
    /**
     *  dsn mysql连接源
     *  @var mixed
     */
    private $dsn;
    /**
     *  mysql 数据库用户名
     *  @var mixed
     */
    private $user;
    /**
     *  mysql 数据库密码
     *  @var mixed
     */
    private $password;
    /**
     *  数据库字符集
     *  @var mixed
     */
    private $charset;
    /**
     *  pdo对象
     *  @var PDO
     */
    private $pdoInstance;
    /**
     * sql对象
     * @var PDOStatement
     */
    private $pdoStmt;
    /**
     * DB constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->dsn = $config['dsn'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->charset = $config['charset'];
        $this->connect();
    }

    /**
     *
     * 连接数据库
     *
     */
    private function connect()
    {
        if (!$this->pdoInstance) {
            //$options = [
                //PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES'.$this->charset
            //];

            //获取pdo对象
            $this->pdoInstance = new PDO($this->dsn,$this->user,$this->password);
            //$this->pdoInstance = new PDO($this->dsn, $this->user, $this->password, $options);

            //设置pdo对象错误处理方式
            $this->pdoInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * 通过sql query数据
     * @param $sql
     * @param array $parameters
     * @return array
     * @throws MySQLException
     */

    public function query($sql, $parameters = [])
    {
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }

        $this->pdoStmt = $this->pdoInstance->prepare($sql);
        $index = 1;

        foreach ($parameters as $parameter){
            $this->pdoStmt->bindValue($index++,$parameter[0] ?? $parameter,$parameter[1] ?? PDO::PARAM_INT);
        }
        $execRe = $this->pdoStmt->execute();
        if(!$execRe){
            throw new MySQLException($this->pdoStmt->errorInfo()[2],$this->pdoStmt->errorCode());
        }

        $data = $this->pdoStmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;

    }


}


class MySQLException extends Exception{

}

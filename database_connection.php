<?php
    const DB = "mysql";
    const DB_SERVER = "localhost";
    const DB_CHARSET = "utf8";

    class DataBase{

        private static $dbUser = "root";
        private static $dbPassword = "admin123";
        private static $dbServer = DB_SERVER;
        private static $dbName = "minesweeper";
        private static $dbCharset = DB_CHARSET;
        private $connection; 


        public function __construct(){ }
    
        public function connectToDB (){
            try{
                $parameters = "mysql:host=".self::$dbServer.";dbname=".self::$dbName;
                $pdo = new PDO($parameters,self::$dbUser,self::$dbPassword);
                $pdo->exec("SET CHARACTER SET ".self::$dbCharset);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
                
                $this->connection = $pdo;

            }catch(PDOException $e){
                exit ("ERROR: ".$e->getMessage());
            }
        }

        public function disconnectDB(){
            $this->connection = null;
        }

        public function getConnection(){
            return $this->connection;
        }
    }
?>

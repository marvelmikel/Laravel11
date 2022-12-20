<?php

    include ("./database_connection.php");

    class Scores extends DataBase{

        private $table = "scores";
        
        public function __construct(){ }

        public function getAllScores(){

            

            try{
                parent::connectToDB();
                
                $connection = parent::getConnection();
                $query = "SELECT * FROM ".$this->table."  ORDER BY time ASC";  
                $results = $connection->query($query)->fetchAll();
                
                parent::disconnectDB();
                
                return $results;
            }catch (Exception $e){
                exit ("ERROR: ".$e->getMessage());
            }

        }

        public function insertScore($name, $time, $level){
           
            
            try{
                parent::connectToDB();
                
                $connection = parent:: getConnection();
                $query = "INSERT INTO scores (Id, name, time, level) VALUES (NULL, ?, ?, ?);";
                $connection->prepare($query)->execute([$name, $time, $level]);
                
                parent::disconnectDB();
                
            }catch (Exception $e){
                exit ("ERROR: ".$e->getMessage());
            }


        }

    }

?>
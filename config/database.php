<?php
    class Database {
    
        private $database;
        
        function __construct($host,$dbname,$user,$password) {
            try {
                $this->database = new PDO("mysql:host=$host;dbname=$dbname","$user","$password");
                $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                routes('/500',"Database error => $e");
            }
        }

        public function query($query) {
            try {
                return $this->database->exec($query);
            } catch (Exception $e) {
                routes('/500',"Query error => $e");
                return [];
            }
        }

        public function execute($query) {
            try {
                $this->database->exec($query);
                return true;
            } catch (Exception $e) {
                routes('/500',"Query error => $e");
                return false;
            }
        }

        public function select($table, $value="*", $cond=null) {
            $args = "";
            if ($cond != null) {
                $args = "WHERE $cond";
            }
            $query = "SELECT $value FROM $table $args";
            try {
                $value = $this->database->prepare($query);
                $row = $value->execute();
                $row = $value->fetchAll();
                if (count($row) == 1) $row = $value->fetch();
                $value->closeCursor();
                if (count($row) > 0) {
                    return $row;
                }
                return [];
            } catch (Exception $e) {
                routes('/500',"Query error => $e");
                return false;
            }
        }

        public function insert($table,$data) {
            if ($data != null) {
                $name = '';
                $value = '';
                $i = 0;
                foreach($data as $k=>$v) {
                    if ($i > 0) {
                        $value .= ", ";
                        $name .= ", ";
                    }
                    $value .= ":$k";
                    $name .= "$k";
                    $i++;
                }
                $query = "INSERT INTO $table ($name) VALUES ($value)";
                try {
                    $this->database->prepare($query)->execute($data);
                    return true;
                } catch (Exception $e) {
                    routes('/500',"Query error => $e");
                    return false;
                }
            } else {
                routes('/500',"Query error => value undefined");;
                return false;
            }
        }

        public function update($table,$data,$cond) {
            $value = '';
            $i = 0;
            foreach($data as $k=>$v) {
                if ($i > 0) {
                    $value .= ", ";
                }
                $value .= "$k= :$k";
                $i++;
            }
            $query = "UPDATE $table SET $value WHERE $cond";
            try {
                $this->database->prepare($query)->execute($data);
                return true;
            } catch (Exception $e) {
                routes('/500',"Query error => $e");
                return false;
            }
        }

        public function delete($table,$cond=null) {
            $args = '';
            if ($cond != null) {
                $args = "WHERE $cond";
            }
            $query = "DELETE FROM $table $args";
            try {
                $this->database->exec($query);
                return true;
            } catch (Exception $e) {
                routes('/500',"Query error => $e");
                return false;
            }
        }
        
        public function getLastValue($table, $value="*") {
            $result = '';
            $query = $this->database->query("SELECT $value FROM $table");
            try {
                foreach($query as $v) {
	                $result = $v;
	            }
	            return $result;
            } catch (Exception $e) {
                routes('/500',"Query error => $e");
            }
            return $result;
        }

    }
?>

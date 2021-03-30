
<?php

    class FileDir {

        private $racine;
        
        function __construct(){
            $dir = dirname($_SERVER['PHP_SELF']);
            $v = 0;
            $concat = ""; 
            for ($i=0;$i<strlen($dir);$i++) {
                if ($dir[$i] == "/") {
                    $v++;
                }
            }
            if ($dir != "/") {
                for ($i=0;$i<$v;$i++) {
                    $concat .= "../";
                }
            }
            $this->racine = $concat;
        }

        function siteUrl() {
            global $config;
            return $config['site_url'];
        }

        function localDir() {
            return $this->racine;
        }

        function isDir($path) {
            return is_dir($path);
        }

        function createDir($dir) {
            try {
                mkdir($dir, 755);
                return true;
            } catch (Exception $e) {
                return $e;
            }
        }

        function renameDir($old_dir, $new_dir) {
            try {
                if (is_dir($old_dir)) {
                    rename($old_dir, $new_dir);
                    return true;
                }
                return "DIR_NOT_EXIST";
            } catch (Exception $e) {
                return $e;
            }
        }

        function deleteDir($dir) {
            try {
                rmdir($dir);
                return true;
            } catch (Exception $e) {
                return $e;
            }
        }

        function assets($value=null,$dir=false) {
            return ($dir ? $this->localDir() : $this->siteUrl()).'assets/'.$value;
        }

        function controller($value=null,$dir=false) {
            return ($dir ? $this->localDir() : $this->siteUrl()).'controller/'.$value;
        }

        function models($value=null,$dir=false) {
            return ($dir ? $this->localDir() : $this->siteUrl()) . 'models/' . $value;
        }

        function template($value=null,$dir=false) {
            return ($dir ? $this->localDir() : $this->siteUrl()).'template/'.$value;
        }
    }

?>
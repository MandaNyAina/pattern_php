<?php

    session_start();

    try {
        setlocale(LC_TIME, "fr_FR");
    } catch (Exception $e) {
        routes('/500', $e);
    }
    
    function setSession(string $name, string $value) {
        try {
            $_SESSION[$name] = $value;
            return true;
        } catch (Exception $e) {
            routes('/500', $e);
            return false;
        }
    }

    function getSession(string $name) {
        return $_SESSION[$name];
    }

    function clearSession(string $name=null) {
        try {
            if ($name == null) {
                session_unset();
                session_destroy();
            } else {
                $_SESSION[$name] = null;
            }
            return true;
        } catch (Exception $e) {
            routes('/500', $e);
            return false;
        }
    }

    function addToCookie($name, $value, $expire_jours) {
        try {
            $expire = time() + ($expire_jours * 86400);
            setcookie($name, $value, $expire, "/");
            return true;
        } catch (Exception $e) {
            routes('/500', $e);
            return false;
        }
    }

    function getCookie($name) {
        return $_COOKIE[$name];
    }

    function deleteCookie($name) {
        try {
            setcookie($name, null, 0, "/");
            return true;
        } catch (Exception $e) {
            routes('/500', $e);
            return false;
        }
    }

    function encrypt(string $str) {
        global $config;
        $key_encrypt = $config['key_encrypt'];
        return openssl_encrypt($str, "AES-128-CTR", $key_encrypt, 0, '8565825542115032');
    }

    function decrypt(string $str_encrypt) {
        global $config;
        $key_encrypt = $config['key_encrypt'];
        return openssl_decrypt($str_encrypt, "AES-128-CTR", $key_encrypt, 0, '8565825542115032');
    }

    function randomValue(int $length, bool $specialChar = false) {
        $result = "";
        $char = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        if ($specialChar) {
            $charSpec = ".*',.#^$!-:;=+_%";
            $full = $char.$charSpec;
            for($i=1;$i<=$length;$i++){
                $j = 0;
                while ($j<15) {
                    $random = round((rand(0,10) / 10) * strlen($full));
                    $j++;
                }
                $result = $result.$full[(int)($random-1)];
            }
        } else {
            for($i=1;$i<=$length;$i++){
                $j = 0;
                while ($j<15) {
                    $random = round((rand(0,10) / 10) * strlen($char));
                    $j++;
                }
                $result = $result.$char[(int)($random-1)];
            }
        }
        return $result;
    }

    function password_encrypt(string $str): string {
        return password_hash($str, PASSWORD_BCRYPT);
    }

    function password_match(string $encrypt, string $value) {
        if (password_verify($value,$encrypt)) {
            return true;
        }
        return false;
    }
    
    function password_validator(string $password_string, int $min_length = 8) {
        $correct = preg_match('/(?=^.{8,}$)(?=.*\d)((?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $password_string);
        if ($correct && strlen($password_string) > $min_length) {
            return true;
        }
        return false;
    }

    function clearString(string $str, bool $clear_special_char = false) {
        $str = trim($str);
        $str = stripslashes($str);
        $str = strip_tags($str);
        $str = htmlspecialchars($str);
        if ($clear_special_char) {
            $str = clearSpecialChar($str);
        }
        return $str;
    }

    function clearSpecialChar($str) {
        $normalizeChars = array(
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'AE','Ç'=> 'C',
            'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=> 'I', 'Ð'=>'Eth',
            'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O',
            'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'ae', 'ç'=>'c',
            
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'eth',
            'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o',
            'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y',
            
            'ß'=>'sz', 'þ'=>'thorn', 'ÿ'=>'y',
            
            '&'=>'en', '@'=>'at', '#'=>'h', '$'=>'s', '%'=>'perc', '^'=>'-','*'=>'-'
        );

        foreach ($normalizeChars as $k => $v) {
            if (str_contains($k, $str)) {
                $str = str_replace($k, $v, $str);
            }
        }
        
        return $str;
    }

    function currentDatetime($choice="datetime",$concat="-") {
        $return_value = "";
        switch ($choice) {
            case 'full':
                $return_value = date(" l  d  F  Y  H:i:s");
                break;
            
            case 'datetime':
                $return_value = date(" d$concat"."m$concat"."Y  H:i:s");
                break;

            case 'date':
                $return_value = date(" Y$concat"."m$concat"."d");
                break;
            
            case 'date_norme':
                $return_value = date(" d$concat"."m$concat"."Y");
                break;

            case 'time':
                $return_value = date("H:i:s");
                break;

            default:
                return 0;
                break;
        }
        return $return_value;
    }

    function compress_image($source, $destination, $quality) {
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        }
        imagejpeg($image, $destination, $quality);
    }

    function upload($files, $path, $filename, $type=null, $maxSize=204800, $allow_type=[], $need_compress=false) {
        $error_upload = null;
        $dir = new FileDir();
        if (!$dir->isDir($path)) {
            $dir->createDir($path);
        }
        $size = $files["size"];
        $name = $files["name"];
        $tmp = $files["tmp_name"];
        $extFile = strtolower(substr(strchr($name, "."),1));
        $fileDir = $path.$filename.".".$extFile;
        $unauthorized_extention = empty($allow_type) ? ["","js","ini","php","jvm","exe","py","c","cpp","ts","sql","psql","json","env","jar","dump"] : $allow_type;
        if (in_array($extFile, $unauthorized_extention)) {
            $error_upload = "UNAUTHORIZED_FILE_TYPE";
        } else {
            if ($size <= $maxSize) {
                switch ($type) {
                    case 'image':
                        $validExt = array("jpg","jpeg","png","gif");
                        if (in_array($extFile, $validExt)) {
                            if (!move_uploaded_file($tmp, $fileDir)) {
                                $error_upload = "IMPORT_IMAGE_ERROR";
                            } else {
                                if ($need_compress) {
                                    compress_image($fileDir, $fileDir."-compressed", 75);
                                }
                            }
                        } else {
                            $error_upload = "NOT_IMAGE_FILE";
                        }
                        break;
                    
                    case 'doc':
                        $validExt = array("pdf","doc","docx","odt","xls","xlsx");
                        if (in_array($extFile, $validExt)) {
                            if (!move_uploaded_file($tmp, $fileDir)) {
                                $error_upload = "IMPORT_DOCUMENT_ERROR";
                            } 
                        } else {
                            $error_upload = "NOT_DOCUMENT_FILE";
                        }
                        break;
        
                    case 'pdf':
                        $validExt = array("pdf");
                        if (in_array($extFile, $validExt)) {
                            if (!move_uploaded_file($tmp, $fileDir)) {
                                $error_upload = "IMPORT_PDF_ERROR";
                            } 
                        } else {
                            $error_upload = "NOT_PDF_FILE";
                        }
                        break;
        
                    case 'audio':
                        $validExt = array("mp3","ogg","wav");
                        if (in_array($extFile, $validExt)) {
                            if (!move_uploaded_file($tmp, $fileDir)) {
                                $error_upload = "IMPORT_AUDIO_ERROR";
                            }
                        } else {
                            $error_upload = "NOT_AUDIO_FILE";
                        }
                        break;
                    
                    case 'video':
                        $validExt = array("mp4","avi","mkv","webm");
                        if (in_array($extFile, $validExt)) {
                            if (!move_uploaded_file($tmp, $fileDir)) {
                                $error_upload = "IMPORT_VIDEO_ERROR";
                            }
                        } else {
                            $error_upload = "NOT_VIDEO_FILE";
                        }
                        break;
                    
                    default:
                        if (!move_uploaded_file($tmp, $fileDir)) {
                            $error_upload = "IMPORT_FILE_ERROR";
                        }
                        break;
                }
            } else {
                $error_upload = "ERROR_SIZE_FILE";
            }
        }
        if($error_upload) {
            return $error_upload;
        }
        return true;
    }

    function download($path,$file) {
        if(preg_match('/^[^.][-a-z0-9_.]+[a-z]$/i', $file)){
            $filepath = $path.$file;
            if(file_exists($filepath)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header("Content-Transfer-Encoding: Binary");
                header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filepath));
                flush();
                readfile($filepath);
                return true;
            } else {
                routes("/500", "Fichier introuvable");
                return false;
            }
        } else {
            routes("/500", "Erreur download");
            return false;
        }
    }

    function is_macth($value, $value_test) {
        return $value == $value_test;
    }

    function is_form_valid($data) {
        if (isset($data) && !empty($data)) {
            return true;
        }
        return false;
    }

    function str_contain($search_string, $string) {
        if (preg_match("/{$search_string}/i", $string)) {
            return true;
        }
        return false;
    }

    function validate_route() {
        $uri = explode("/", $_SERVER['REQUEST_URI']);
        $uri_invalid = str_contain("php", $uri[count($uri) - 1]) || str_contain("html", $uri[count($uri) - 1]) || str_contain("js", $uri[count($uri) - 1]) || str_contain("css", $uri[count($uri) - 1]) || str_contain("json", $uri[count($uri) - 1]) || str_contain("env", $uri[count($uri) - 1]) || str_contain("ini", $uri[count($uri) - 1]);
        if ($uri_invalid) {
            routes("/" . $uri[count($uri) - 1]);
        } 
    }

    function array_to_json($data) {
        return json_encode($data);
    }

    function json_to_array($json_data) {
        return json_decode($json_data, true);
    }
    
?>
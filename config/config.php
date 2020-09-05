<?php

    session_start();

    try {
        setlocale(LC_TIME, "fr_FR");
    } catch (Exception $e) {
        routes('/500', $e);
    }

    function getHost() {
        return $_SERVER['HTTP_HOST'];
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
        } catch (\Throwable $th) {
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
        $ini = parse_ini_file('config.ini');
        $key_encrypt = $ini['key_encrypt'];
        $encrypted_string = openssl_encrypt($str, "AES-128-CTR", $key_encrypt, 0, '8565825542115032');
        return $encrypted_string;
    }

    function decrypt(string $str_encrypt) {
        $ini = parse_ini_file('config.ini');
        $key_encrypt = $ini['key_encrypt'];
        $decrypted_string = openssl_decrypt($str_encrypt, "AES-128-CTR", $key_encrypt, 0, '8565825542115032');
        return $decrypted_string;
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
        } else {
            return false;
        }
    }
    
    function password_validator(string $password_string, int $min_length = 8) {
        $correct = preg_match('/(?=^.{8,}$)(?=.*\d)((?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $password_string);
        if ($correct && strlen($password_string) > $min_length) {
            return true;
        } else {
            return false;
        }
    }

    function clearString(string $str, bool $clear_special_char = false) {
        $str = trim($str);
        $str = stripslashes($str);
        $str = strip_tags($str);
        $str = htmlspecialchars($str);
        if ($clear_special_char) $str = clearSpecialChar($str);
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
            if (str_contains($k, $str)) $str = str_replace($k, $v, $str);
        }
        
        return $str;
    }

    function currentDatetime($choice="datetime",$concat="-") {
        switch ($choice) {
            case 'full':
                return date(" l  d  F  Y  H:i:s");
                break;
            
            case 'datetime':
                return date(" d$concat"."m$concat"."Y  H:i:s");
                break;

            case 'date':
                return date(" Y$concat"."m$concat"."d");
                break;
            
            case 'date_norme':
                return date(" d$concat"."m$concat"."Y");
                break;

            case 'time':
                return date("H:i:s");
                break;

            default:
                return 0;
                break;
        }
    }

    function upload($files,$path,$filename,$maxSize,$type) {
        $dir = new FileDir();
        if (!$dir->isDir($path)) $dir->createDir($path);
        $size = $files["size"];
        $name = $files["name"];
        $tmp = $files["tmp_name"];
        $extFile = strtolower(substr(strchr($name, "."),1));
        $fileDir = $path.$filename.".".$extFile;
        $unauthorized_extention = ["js","ini","php","jvm","exe","py","c","cpp","ts","sql","psql","json"];
        if (in_array($extFile,$unauthorized_extention)) {
            return "UNAUTHORIZED_FILE_TYPE";
        } else {
            if ($size <= $maxSize) {
                switch ($type) {
                    case 'image':
                        $validExt = array("jpg","jpeg","png");
                        if (in_array($extFile, $validExt)) {
                            if (move_uploaded_file($tmp, $fileDir)) {
                                return true;
                            } else {
                                return "IMPORT_IMAGE_ERROR";
                            }
                        } else {
                            return "NOT_IMAGE_FILE";
                        }
                        break;
                    
                    case 'doc':
                        $validExt = array("pdf","doc","docx","odt","xls","xlsx");
                        if (in_array($extFile, $validExt)) {
                            if (move_uploaded_file($tmp, $fileDir)) {
                                return true;
                            } else {
                                return "IMPORT_DOCUMENT_ERROR";
                            }
                        } else {
                            return "NOT_DOCUMENT_FILE";
                        }
                        break;
        
                    case 'pdf':
                        $validExt = array("pdf");
                        if (in_array($extFile, $validExt)) {
                            if (move_uploaded_file($tmp, $fileDir)) {
                                return true;
                            } else {
                                return "IMPORT_PDF_ERROR";
                            }
                        } else {
                            return "NOT_PDF_FILE";
                        }
                        break;
        
                    case 'audio':
                        $validExt = array("mp3","ogg","wav");
                        if (in_array($extFile, $validExt)) {
                            if (move_uploaded_file($tmp, $fileDir)) {
                                return true;
                            } else {
                                return "IMPORT_AUDIO_ERROR";
                            }
                        } else {
                            return "NOT_AUDIO_FILE";
                        }
                        break;
                    
                    case 'video':
                        $validExt = array("mp4","avi","mkv","webm");
                        if (in_array($extFile, $validExt)) {
                            if (move_uploaded_file($tmp, $fileDir)) {
                                return true;
                            } else {
                                return "IMPORT_VIDEO_ERROR";
                            }
                        } else {
                            return "NOT_VIDEO_FILE";
                        }
                        break;
                    
                    default:
                        return "ERROR_FILE_TYPE";
                        break;
                }
            } else {
                return "SIZE_MAX_ERROR";
            }
        }
    }

    function download($path,$file) {
        echo "<script>alert('".$path.$file."');</script>";
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
                return false;
            }
        } else {
            return false;
        }
    }

    function is_macth($value, $value_test) {
        return $value == $value_test;
    }

    function is_form_valid($data) {
        if (isset($data) && !empty($data)) {
            return true;
        } else {
            return false;
        }
    }

    function str_contains($search_string, $string) {
        if (preg_match("/{$search_string}/i", $string)) {
            return true;
        } else {
            return false;
        }
    }

    function array_to_json($data) {
        return json_encode($data);
    }

    function json_to_array($json_data) {
        return json_decode($json_data, true);
    }
    
?>
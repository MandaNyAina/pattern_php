<?php

    function goToUrl($url) {
        header("Location:$url");
        exit;
    }

    function redirectToUrl($url) {
        echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
    }

    function routes($path,$info=null) {
        $env = parse_ini_file('config.ini')['mode_env'];
        $dir = new FileDir();
        $parent = $dir->localDir();
        $dir = "$parent"."template$path/";
        switch ($path) {
            case '/':
                $file = "index.php";
                header("Location:$parent$file");
                break;
            /*
            case '':
                $file = "";
                header("Location:$dir$file");
                break;
            */

            case '/500':
                $file = "erreurserveur.html";
                if ($env=='production') {
                    header("Location:$dir$file");
                } else if ($env=='development'){
                    echo '
                    <div class="alert alert-danger w-100">
                        <h3 class="text-dark text-center">APP ERROR <i class="fa fa-bug" aria-hidden="true"></i></h3>
                        <b>Raison : </b>'.$info.'<br/><br/>
                        <b>Fichier : </b>'.$_SERVER['PHP_SELF'].'
                    </div>
                    ';
                }
                break;

            default:
                $file = "notfound.html";
                header("Location:$parent"."template/404/$file");
                break;
        }
    }
    
?>
<?php 
 
    function utility_GetIP(){
        // Determine customer's IP address
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            // They are using a proxy, so we grab the forwarded for IP
            $theirIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(isset($_SERVER['REMOTE_ADDR'])){
            // No proxy, grab the normal IP
            $theirIP = $_SERVER['REMOTE_ADDR'];
        }else{
            // No IP (unlikely).
            $theirIP = false;
        }
        return $theirIP;
        //return "Hola";
    }

    function pdo_getApplications($conn) {

        $rows = [];

        $select = 'SELECT COD_APP as code, TXT_NAME_APP as name, TXT_APP_DESC as description FROM tapp';
        //$select = $conn->quote($string);
        try {
            foreach($conn->query($select) as $row)
            $rows[] = $row;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        } 	
        return $rows; 
    }


    function main() {
    
        $smarty = new Smarty;

        $smarty->setTemplateDir(YAVIRE_DIR . 'templates');
        $smarty->setCompileDir(YAVIRE_DIR . 'templates_c');
        $smarty->setConfigDir(YAVIRE_DIR . 'configs');
        $smarty->setCacheDir(YAVIRE_DIR . 'cache');

        //$smarty->force_compile = true;
        $smarty->debugging = false;
        $smarty->caching = false;
        $smarty->cache_lifetime = 120;

        $user = $_SESSION['user'];

        $db = yavDatabase::instance();

        $smarty->assign("Name", 'Connected to database with user ' . $user, true);

        $apps = pdo_getApplications($db->getConnection());

        $smarty->assign("applications", $apps);
        $smarty->assign("userYav", $user);
        $smarty->display('yavire.tpl');

        //db_disconnectPDO($conn);

    }

    function string_encrypt($string, $key) {
        $crypted_text = mcrypt_encrypt(
                                MCRYPT_RIJNDAEL_128, 
                                $key, 
                                $string, 
                                MCRYPT_MODE_ECB
                            );
        return base64_encode($crypted_text);
    }

    function string_decrypt($encrypted_string, $key) {
        return mcrypt_decrypt(
                        MCRYPT_RIJNDAEL_128, 
                        $key, 
                        base64_decode($encrypted_string), 
                        MCRYPT_MODE_ECB
                        );
    }



 
?>
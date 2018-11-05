<?php

/**
 * login application library
 *
 */

include_once(YAVIRE_DIR . 'libs/yavire.lib.php');

class Login {

  // database object
  var $pdo = null;
  // smarty template object
  var $tpl = null;
  // error messages
  public $error = null;

  var $conn = null;

  /**
  * class constructor
  */
  function __construct() {
 
    // instantiate the template object
    $this->tpl = new Login_Smarty;

    //$database = yavDatabase::instance();
    //$error = $database->setConnection();
    //$conn =  $database->getConnection();

  }


  /**
  * display the login entry form
  *
  * @param array $formvars the form variables
  */
  function displayFormLogin($formvars = array()) {

        // assign the form vars
    $this->tpl->assign('post',$formvars);
    // assign error message
    $this->tpl->assign('error', $this->error);
    //echo $this->error;
    $this->tpl->display('login_form.tpl');

  }

  function displayApp($formvars = array()) {

    // assign the form vars
    $this->tpl->assign('post',$formvars);
    // assign error message
    $this->tpl->assign('error', $this->error);
    //$this->tpl->display('yavire.tpl');

  }

  /**
  * fix up form data if necessary
  *
  * @param array $formvars the form variables
  */
  function mungeFormData(&$formvars) {

    // trim off excess whitespace
    //print_r($formvars);
    $formvars['login'] = trim($formvars['login']);
    $formvars['password'] = trim($formvars['password']);

  }

  /**
  * test if form information is valid
  *
  * @param array $formvars the form variables
  */
  function isValidForm($formvars) {

    /*if (!ini_get('display_errors')) {
        ini_set('display_errors', '1');
    }
    else {
      ini_set('display_errors', '0');
    }
    error_reporting(E_ALL)
    */

    // reset error message
    $this->error = null;

    // test if "Name" is empty
    if(strlen($formvars['login']) == 0) {
      $this->error = 'User Empty';
      return false; 
    }

    // test if "Comment" is empty
    if(strlen($formvars['password']) == 0) {
      $this->error = 'Password empty';
      return false; 
    }

    $userForm = $formvars['login'];
    $passForm = $formvars['password'];


     $errorDB = null;
     $db = yavDatabase::instance();
     $sqlData = array(":userYav"=>$userForm);

     $apps = $db->selectQuery($db->getConnection(), "Select txt_id_user_apl as id, 
                            txt_name_user_apl as name, txt_pass_user_apl as pass 
                            from tuserapl where txt_id_user_apl = :userYav", $sqlData, $errorDB);

       echo "***********";
        print_r($errorDB);
        echo "***********";

     if(strlen($errorDB) == 0) {

        //If the user exists

        if (!empty($apps)) {

          $userDB = $apps['id'];
          $passDB = $apps['pass'];
          
          $passDecrypt = $this->yavDecryptRJ256($passDB);

          /*echo '*****( ' . $userForm . ')*****';
          echo '*****( ' . $passForm . ')*****';
          echo '*****( ' . $userDB . ')*****';
          echo '*****( ' . $passDB . ')*****';
          echo '*****( ' . $passDecrypt . ')*****';*/

          if (trim($passForm) != trim($passDecrypt)) {
             $this->error = "User or password invalid";
             return false;
          }
          else {
            // form passed validation
            $_SESSION['user'] = $formvars['login'];


            $this->error = null;
          }

        }
        else {
          $this->error = "User or password invalid";
           return false;

        }

     }
     else {
         $this->error = $errorDB;
         return false;
     }

    
    return true;
  }


  function yavDecryptRJ256($string_to_decrypt)
  {

    $key = 'lkirwf897+22#bbtrm8814z5qq=498k8'; // 32 * 8 = 256 bit key
    $iv = '741952hheeyy66#cs!9hjv887mxx7@8y'; // 32 * 8 = 256 bit iv


    $string_to_decrypt = base64_decode($string_to_decrypt);
    $rtn = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $string_to_decrypt, MCRYPT_MODE_CBC, $iv);
    $rtn = rtrim($rtn, "\4");
    return($rtn);

  }

  function yavEncryptRJ256($string_to_encrypt)
  {

    $key = 'lkirwf897+22#bbtrm8814z5qq=498k8'; // 32 * 8 = 256 bit key
    $iv = '741952hheeyy66#cs!9hjv887mxx7@8y'; // 32 * 8 = 256 bit iv

    $rtn = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string_to_encrypt, MCRYPT_MODE_CBC, $iv);
    $rtn = base64_encode($rtn);

    return($rtn);

  }

  
}

?>

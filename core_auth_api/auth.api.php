<?php
/***********************************************
* This file is part of PeoplePods
* (c) xoxco, inc  
* http://peoplepods.net http://xoxco.com
*
* core_authentication/join.php
* Handles requests to /join
*
* Documentation for this pod can be found here:
* http://peoplepods.net/readme
/**********************************************/

	include_once("../../lib/Core.php"); 

	$POD = new PeoplePod(array('authSecret'=>@$_COOKIE['pp_auth']));

 $status= array();

 switch ($_GET['command']) {
    //create a new user
    case 'join':
        if (@$_POST['email']&& @$_POST['name']) {
            if (!$_POST['password']) {
                $status['status']= "failed";
                $status['error'] = "no password";
            } else {
                $password = $_POST['password'];
                $NEWUSER = $POD->getPerson(array('nick'=>$_POST['name'],'email'=>$_POST['email'],'password'=>$password));
                $NEWUSER->save();
                
                if ($NEWUSER->success()){
                    if(@$_POST['remember_me']){
                        $days= 15;
                    }else{
                        $days= 1;
                    }
                    $status['status']= 'success';
                    $status['auth']=$NEWUSER->get('authSecret');
                    $status['days']= $days;

              }else {
                 $status['error']= $NEWUSER->error();
              }
            }
        }else{
            $status['status']= "failed";
            $status['error']= "missing email or name";
         }
         echo json_encode($status);

    break;
    //login an existing user
    case 'login':
        if (@$_POST['email'] && @$_POST['password']) {

            $POD = new PeoplePod(array('authSecret'=>md5($_POST['email'].$_POST['password'])));
            if (!$POD->success()){
                $status['status']= 'failed';
                $status['error']= $POD->error();
            }
            if (!$POD->isAuthenticated()) {
                $status['status']="failed";
                $status['error']= 'wrong username or password';
            } else {

                $days = 15;
                $status['status']=  'success';
                $status['auth']= $POD->currentUser()->get('authSecret');
                if ($_POST['remember_me']) {
                    $status['days']= $days;
                }else{
                    $status['days']= 1;
                }
           }
      }else{
          $status['status']= 'failed';
          $status['error']= 'no email or password';
      }
    echo json_encode($status);
    break;

    //logout a user
    case 'logout':

    setcookie('pp_user','',0,"/");
    setcookie('pp_pass','',0,"/");
    setcookie('pp_auth','',0,"/");
    session_destroy();

    $status['status']= 'logged out';
    
    echo json_encode($status);
    break;

}


	function generatePassword($length=9, $strength=8) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}
	 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}
 

?>
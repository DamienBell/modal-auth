<? 

	$POD->registerPOD('core_auth_api','Allows Login, Logout, and join via ajax',
                     array('^auth_api/(.*)'=>'core_auth_api/auth.api.php?command=$1')
                    ,array()
                   );
?>
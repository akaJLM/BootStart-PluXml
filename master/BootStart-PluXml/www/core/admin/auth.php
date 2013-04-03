<?php

/**
 * Page d'authentification
 *
 * @package PLX
 * @author	Stephane F et Florent MONTHEL
 * modification 30/03/2013 @author Jonathan Maris for © littleRabbitLabs
 **/

# Variable pour retrouver la page d'authentification
define('PLX_AUTHPAGE', true);

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminAuthPrepend'));

# Initialisation variable erreur
$error = '';
$msg = '';

# Control et filtrage du parametre $_GET['p']
$redirect=$plxAdmin->aConf['racine'].'core/admin/';
if(!empty($_GET['p'])) {
	$racine = parse_url($plxAdmin->aConf['racine']);
	$get_p = parse_url(urldecode($_GET['p']));
	$error = (!$get_p OR (isset($get_p['host']) AND $racine['host']!=$get_p['host']));
	if(!$error AND !empty($get_p['path']) AND file_exists(PLX_ROOT.'core/admin/'.basename($get_p['path']))) {
		# filtrage des parametres de l'url
		$query='';
		if(isset($get_p['query'])) {
			$query=strtok($get_p['query'],'=');
			$query=($query[0]!='d'?'?'.$get_p['query']:'');
		}
		# url de redirection
		$redirect=$get_p['path'].$query;
	}
}

# Déconnexion
if(!empty($_GET['d']) AND $_GET['d']==1) {

	$_SESSION = array();
	session_destroy();
	header('Location: auth.php');
	exit;

	$formtoken = $_SESSION['formtoken']; # sauvegarde du token du formulaire
	$_SESSION = array();
	session_destroy();
	session_start();
	$msg = L_LOGOUT_SUCCESSFUL;
	$_GET['p']='';
	$_SESSION['formtoken']=$formtoken; # restauration du token du formulaire
	unset($formtoken);
}

# Authentification
if(!empty($_POST['login']) AND !empty($_POST['password'])) {
	$connected = false;
	foreach($plxAdmin->aUsers as $userid => $user) {
		if ($_POST['login']==$user['login'] AND sha1($user['salt'].md5($_POST['password']))==$user['password'] AND $user['active'] AND !$user['delete']) {
			$_SESSION['user'] = $userid;
			$_SESSION['profil'] = $user['profil'];
			$_SESSION['hash'] = plxUtils::charAleatoire(10);
			$_SESSION['domain'] = $session_domain;
			$_SESSION['lang'] = $user['lang'];
			$connected = true;
		}
	}
	if($connected) {
		header('Location: '.htmlentities($redirect));
		exit;
	} else {
		$msg = L_ERR_WRONG_PASSWORD;
		$error = 'error';
	}
}
plxUtils::cleanHeaders();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $plxAdmin->aConf['default_lang'] ?>" lang="<?php echo $plxAdmin->aConf['default_lang'] ?>">
    <head>
    <meta name="robots" content="noindex, nofollow" />
    <title><?php echo L_AUTH_PAGE_TITLE ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo strtolower(PLX_CHARSET); ?>" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Bootstrap css-->
    <link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap.min.css" rel="stylesheet" media="screen">

    <!--Bootstrap extension - general-->
    <link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap.min.ext.css" rel="stylesheet">

    <!--Bootstrap extension - FontAwesome with ie7 support-->
    <link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap.font-awesome.min.css" rel="stylesheet">
    <!--[if IE 7]>
    <link rel="stylesheet" href="css/bootstrap.font-awesome-ie7.min.css">
    <![endif]-->
    <!--Bootstrap extension - FontAwesome new color icons-->
    <link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap.font-awesome.min.ext.css" rel="stylesheet">

    <!--Hack precognized for #issue-->
    <style type="text/css">
body {
	padding-top: 48px;
	padding-bottom: 40px;
}
 @media (max-width: 979px) {
body {
	padding-top: 0;
}
}
</style>

    <!--Bootstrap responsive-->
    <link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap-responsive.min.css" rel="stylesheet">

    <!--Bootstrap responsive - extension-->
    <link href="<?php echo PLX_CORE ?>admin/theme/css/bootstrap-responsive.min.ext.css" rel="stylesheet">

    <!--Theme css-->
    <link href="<?php echo PLX_CORE ?>admin/theme/css/theme.style.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <?php eval($plxAdmin->plxPlugins->callHook('AdminAuthEndHead')) ?>
    </head>

    <body>

<!--Menu top-->
<div id="navbar-user" class="navbar navbar-fixed-top">
      <div class="navbar-inner">
    <div class="container">
          <ul class="nav">
        <li><a class="brand" href="./" style="margin-left:0"><?php echo plxUtils::strCheck($plxAdmin->aConf['title']) ?></a></li>
      </ul>
          <div class="pull-right">
        <div class="btn-toolbar">
              <div class="btn-group"> <a href="<?php echo PLX_ROOT; ?>" class="btn btn-mini"> <i class="icon-home icon-white"></i>Home</a></div>
            </div>
      </div>
        </div>
  </div>
    </div>

<!--Header-->
<header class="subhead clearfix">
      <div class="subhead-inner">
    <div class="container"> 
          
          <!--Breadcrumb-->
          <ul class="btn-align breadcrumb pull-left nav-breadcrumb">
        <li> <a href="<?php echo PLX_ROOT; ?>" style="margin-left:0"><?php echo plxUtils::strCheck($plxAdmin->aConf['title']) ?></a> <span class="divider">/</span> </li>
        <li class="active"><?php echo L_AUTH_PAGE_TITLE ?></li>
      </ul>
        </div>
  </div>
    </header>

<!--Content-->
<div class="content clearfix">
      <div class="container-fluid">
    <div class="row-fluid"> 
          <!--Main content-->
          <div class="span12 main">
        <?php eval($plxAdmin->plxPlugins->callHook('AdminAuthTop')) ?>
        <?php (!empty($msg))?plxUtils::showMsg($msg, $error):''; ?>
        <form class="form-vertical" action="auth.php<?php echo !empty($redirect)?'?p='.plxUtils::strCheck(urlencode($redirect)):'' ?>" method="post" id="loginform">
              <?php echo plxToken::getTokenPostMethod() ?>
              <p><?php echo L_LOGIN_PAGE ?></p>
              <div class="control-group">
            <div class="controls">
                  <div class="input-prepend"> <span class="add-on"><i class="icon-user icon-grey"></i></span>
                <?php plxUtils::printInput('login', (!empty($_POST['login']))?plxUtils::strCheck($_POST['login']):'', 'text', '18-255');?>
              </div>
                </div>
          </div>
              <div class="control-group">
            <div class="controls">
                  <div class="input-prepend"> <span class="add-on"><i class="icon-lock icon-grey"></i></span>
                <?php plxUtils::printInput('password', '', 'password','18-255');?>
              </div>
                </div>
          </div>
              <div class="form-actions"><span class="pull-right">
                <?php eval($plxAdmin->plxPlugins->callHook('AdminAuth')) ?>
                <input class="btn submit" type="submit" value="<?php echo L_SUBMIT_BUTTON ?>" />
                </span> </div>
            </form>
      </div>
        </div>
  </div>
    </div>
<footer style="color:#999;">
      <p class="text-center"><a href="http://www.littlerabbitlabs.net" target="_blank" title="Tiny Web Agency &amp; Web Interactive Labs">© littleRabbitLabs</a> html5/css3 admin UI theme development</p>
    </footer>
<?php eval($plxAdmin->plxPlugins->callHook('AdminAuthEndBody')) ?>
</body>
</html>
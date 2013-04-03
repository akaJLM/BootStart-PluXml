<?php
# ------------------ BEGIN LICENSE BLOCK ------------------
#
# This file is part of PluXml : http://www.pluxml.org
#
# Copyright (c) 2010-2013 Stephane Ferrari and contributors
# Copyright (c) 2008-2009 Florent MONTHEL and contributors
# Copyright (c) 2006-2008 Anthony GUERIN
# Licensed under the GPL license.
# See http://www.gnu.org/licenses/gpl.html
#
# ------------------- END LICENSE BLOCK -------------------

define('PLX_ROOT', './');
define('PLX_CORE', PLX_ROOT.'core/');
include(PLX_ROOT.'config.php');
include(PLX_CORE.'lib/config.php');

# On démarre la session
session_start();

# On inclut les librairies nécessaires
include(PLX_CORE.'lib/class.plx.timezones.php');
include(PLX_CORE.'lib/class.plx.date.php');
include(PLX_CORE.'lib/class.plx.glob.php');
include(PLX_CORE.'lib/class.plx.utils.php');
include(PLX_CORE.'lib/class.plx.token.php');

# Chargement des langues
$lang = DEFAULT_LANG;
if(isset($_POST['default_lang'])) $lang=$_POST['default_lang'];
if(!array_key_exists($lang, plxUtils::getLangs())) {
	$lang = DEFAULT_LANG;
}

loadLang(PLX_CORE.'lang/'.$lang.'/install.php');
loadLang(PLX_CORE.'lang/'.$lang.'/core.php');

# On vérifie que PHP 5 ou superieur soit installé
if(version_compare(PHP_VERSION, '5.0.0', '<')){
    header('Content-Type: text/plain charset=UTF-8');
    echo utf8_decode(L_WRONG_PHP_VERSION);
    exit;
}

# On vérifie que PluXml n'est pas déjà installé
if(file_exists(path('XMLFILE_PARAMETERS'))) {
	header('Content-Type: text/plain charset=UTF-8');
	echo utf8_decode(L_ERR_PLUXML_ALREADY_INSTALLED);
	exit;
}

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Vérification de l'existence des dossiers data/images et data/documents
if(!is_dir(PLX_ROOT.'data/images')) {
	@mkdir(PLX_ROOT.'data/images',0755,true);
}
if(!is_dir(PLX_ROOT.'data/documents')) {
	@mkdir(PLX_ROOT.'data/documents',0755,true);
}
# Vérification de l'existence du dossier data/configuration/plugins
if(!is_dir(PLX_ROOT.PLX_CONFIG_PATH.'plugins')) {
	@mkdir(PLX_ROOT.PLX_CONFIG_PATH.'plugins',0755,true);
}

# Echappement des caractères
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$_POST = plxUtils::unSlash($_POST);
}

# Initialisation du timezone
$timezone = date_default_timezone_get();
if(isset($_POST['timezone'])) $timezone=$_POST['timezone'];
if(!array_key_exists($timezone, plxTimezones::timezones())) {
	$timezone = date_default_timezone_get();
}

# Configuration de base
$f = file(PLX_ROOT.'version');
$version = $f['0'];
$config = array('title'=>'PluXml',
				'description'=>plxUtils::strRevCheck(L_SITE_DESCRIPTION),
				'meta_description'=>'',
				'meta_keywords'=>'',
				'racine'=>plxUtils::getRacine(),
				'timezone'=>$timezone,
				'allow_com'=>1,
				'mod_com'=>0,
				'mod_art'=>0,
				'capcha'=>1,
				'style'=>'defaut',
				'clef'=>plxUtils::charAleatoire(15),
				'bypage'=>5,
				'bypage_archives'=>5,
				'bypage_admin'=>10,
				'bypage_admin_coms'=>10,
				'bypage_feed'=>8,
				'tri'=>'desc',
				'tri_coms'=>'asc',
				'images_l'=>800,
				'images_h'=>600,
				'miniatures_l'=>200,
				'miniatures_h'=>100,
				'thumbs'=>1,
				'images'=>'data/images/',
				'documents'=>'data/documents/',
				'racine_articles'=>'data/articles/',
				'racine_commentaires'=>'data/commentaires/',
				'racine_statiques'=>'data/statiques/',
				'racine_themes'=>'themes/',
				'racine_plugins'=>'plugins/',
				'homestatic'=>'',
				'urlrewriting'=>0,
				'gzip'=>0,
				'feed_chapo'=>0,
				'feed_footer'=>'',
				'version'=>$version,
				'default_lang'=>$lang,
				'userfolders'=>0,
				);

function install($content, $config) {

	# gestion du timezone
	date_default_timezone_set($config['timezone']);

	# Création du fichier de configuration
	$xml = '<?xml version="1.0" encoding="'.PLX_CHARSET.'"?>'."\n";
	$xml .= '<document>'."\n";
	foreach($config  as $k=>$v) {
		if(is_numeric($v))
			$xml .= "\t<parametre name=\"$k\">".$v."</parametre>\n";
		else
			$xml .= "\t<parametre name=\"$k\"><![CDATA[".plxUtils::cdataCheck($v)."]]></parametre>\n";
	}
	$xml .= '</document>';
	plxUtils::write($xml,path('XMLFILE_PARAMETERS'));

	# Création du fichier des utilisateurs
	$salt = plxUtils::charAleatoire(10);
	$xml = '<?xml version="1.0" encoding="'.PLX_CHARSET.'"?>'."\n";
	$xml .= "<document>\n";
	$xml .= "\t".'<user number="001" active="1" profil="0" delete="0">'."\n";
	$xml .= "\t\t".'<login><![CDATA['.trim($content['login']).']]></login>'."\n";
	$xml .= "\t\t".'<name><![CDATA['.trim($content['name']).']]></name>'."\n";
	$xml .= "\t\t".'<infos><![CDATA[]]></infos>'."\n";
	$xml .= "\t\t".'<password><![CDATA['.sha1($salt.md5(trim($content['pwd']))).']]></password>'."\n";
	$xml .= "\t\t".'<salt><![CDATA['.$salt.']]></salt>'."\n";
	$xml .= "\t\t".'<email><![CDATA[]]></email>'."\n";
	$xml .= "\t\t".'<lang><![CDATA['.$config['default_lang'].']]></lang>'."\n";
	$xml .= "\t</user>\n";
	$xml .= "</document>";
	plxUtils::write($xml,path('XMLFILE_USERS'));

	# Création du fichier des categories
	$xml = '<?xml version="1.0" encoding="'.PLX_CHARSET.'"?>'."\n";
	$xml .= '<document>'."\n";
	$xml .= "\t".'<categorie number="001" active="1" homepage="1" tri="'.$config['tri'].'" bypage="'.$config['bypage'].'" menu="oui" url="'.L_DEFAULT_CATEGORY_URL.'" template="categorie.php"><name><![CDATA['.plxUtils::strRevCheck(L_DEFAULT_CATEGORY_TITLE).']]></name><description><![CDATA[]]></description><meta_description><![CDATA[]]></meta_description><meta_keywords><![CDATA[]]></meta_keywords><title_htmltag><![CDATA[]]></title_htmltag></categorie>'."\n";
	$xml .= '</document>';
	plxUtils::write($xml,path('XMLFILE_CATEGORIES'));

	# Création du fichier des pages statiques
	$xml = '<?xml version="1.0" encoding="'.PLX_CHARSET.'"?>'."\n";
	$xml .= '<document>'."\n";
	$xml .= "\t".'<statique number="001" active="1" menu="oui" url="'.L_DEFAULT_STATIC_URL.'" template="static.php"><group><![CDATA[]]></group><name><![CDATA['.plxUtils::strRevCheck(L_DEFAULT_STATIC_TITLE).']]></name><meta_description><![CDATA[]]></meta_description><meta_keywords><![CDATA[]]></meta_keywords><title_htmltag><![CDATA[]]></title_htmltag></statique>'."\n";
	$xml .= '</document>';
	plxUtils::write($xml,path('XMLFILE_STATICS'));

	$cs = '<p><?php echo \''.plxUtils::strRevCheck(L_DEFAULT_STATIC_CONTENT).'\'; ?></p>';
	plxUtils::write($cs,PLX_ROOT.$config['racine_statiques'].'001.'.L_DEFAULT_STATIC_URL.'.php');

	# Création du premier article
	$xml = '<?xml version="1.0" encoding="'.PLX_CHARSET.'"?>'."\n";
	$xml .= '<document>
	<title><![CDATA['.plxUtils::strRevCheck(L_DEFAULT_ARTICLE_TITLE).']]></title>
	<allow_com>1</allow_com>
	<template><![CDATA[article.php]]></template>
	<chapo>
		<![CDATA[]]>
	</chapo>
	<content>
		<![CDATA[<p>'.plxUtils::strRevCheck(L_DEFAULT_ARTICLE_CONTENT).'</p>]]>
	</content>
	<tags>
		<![CDATA[PluXml]]>
	</tags>
	<meta_description>
		<![CDATA[]]>
	</meta_description>
	<meta_keywords>
		<![CDATA[]]>
	</meta_keywords>
	<title_htmltag>
		<![CDATA[]]>
	</title_htmltag>
</document>';
	plxUtils::write($xml,PLX_ROOT.$config['racine_articles'].'0001.001.001.'.date('YmdHi').'.'.L_DEFAULT_ARTICLE_URL.'.xml');

	# Création du fichier des tags servant de cache
	$xml = '<?xml version="1.0" encoding="'.PLX_CHARSET.'"?>'."\n";
	$xml .= '<document>'."\n";
	$xml .= "\t".'<article number="0001" date="'.date('YmdHi').'" active="1"><![CDATA[PluXml]]></article>'."\n";
	$xml .= '</document>';
	plxUtils::write($xml,path('XMLFILE_TAGS'));

	# Création du fichier des plugins
	$xml = '<?xml version="1.0" encoding="'.PLX_CHARSET.'"?>'."\n";
	$xml .= '<document>'."\n";
	$xml .= '</document>';
	plxUtils::write($xml,path('XMLFILE_PLUGINS'));

	# Création du premier commentaire
	$xml = '<?xml version="1.0" encoding="'.PLX_CHARSET.'"?>'."\n";
	$xml .= '<comment>
	<author><![CDATA[pluxml]]></author>
		<type>normal</type>
		<ip>127.0.0.1</ip>
		<mail><![CDATA[contact@pluxml.org]]></mail>
		<site><![CDATA[http://www.pluxml.org]]></site>
		<content><![CDATA['.plxUtils::strRevCheck(L_DEFAULT_COMMENT_CONTENT).']]></content>
	</comment>';
	plxUtils::write($xml,PLX_ROOT.$config['racine_commentaires'].'0001.'.date('U').'-1.xml');

}

$msg='';
if(!empty($_POST['install'])) {

	if(trim($_POST['name']=='')) $msg = L_ERR_MISSING_USER;
	elseif(trim($_POST['login']=='')) $msg = L_ERR_MISSING_LOGIN;
	elseif(trim($_POST['pwd']=='')) $msg = L_ERR_MISSING_PASSWORD;
	elseif($_POST['pwd']!=$_POST['pwd2']) $msg = L_ERR_PASSWORD_CONFIRMATION;
	else {
		install($_POST, $config);
		header('Location: '.plxUtils::getRacine());
		exit;
	}
	$name=$_POST['name'];
	$login=$_POST['login'];
}
else {
	$name='';
	$login='';
}
plxUtils::cleanHeaders();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $plxAdmin->aConf['default_lang'] ?>" lang="<?php echo $plxAdmin->aConf['default_lang'] ?>">
<head>
<meta name="robots" content="noindex, nofollow" />
<title><?php echo L_INSTALL_TITLE ?></title>
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
	padding-top: 41px;
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
</head>

<body onload="document.forms[0].name.focus();">

<!--Menu top-->
<div id="navbar-user" class="navbar navbar-fixed-top">
  
  <div class="navbar-inner" style="padding:0">
    
    <div class="container-fluid more-padding">
    
    <div id="issue">
    	<a class="brand" href="./" style="margin-left:0"><?php echo L_PLUXML_VERSION.' '.$version ?> - <?php echo L_INSTALL_TITLE ?></a></li>
        &nbsp;
     </div><!--issue-->
    
  </div><!--container-fluid-->

</div><!--navbar-inner-->

</div><!--navbar-user-->

<!--Header-->
<header class="subhead clearfix">
    
    <div class="container-fluid more-padding">
    
    	<div style="padding:0 20px;">
      		&nbsp;
     	</div>
  
  </div><!--container-fluid-->
  
</header>

<!--Content-->

<section class="content" style="margin-top:15px">
  <div class="container-fluid more-padding">
    <?php if($msg!='') echo '<div id="msg" class="alert alert-error">'.$msg.'</div>'; ?>
    <div class="row-fluid">
      <div class="span6 widget">
        <div class="widget-title"><span class="icon"><i class="icon-cogs icon-grey icon-shadowed"></i></span>
          <h5><?php echo L_INSTALL_TITLE ?></h5>
        </div>
        <div class="widget-content">
          <form class="form-horizontal" action="install.php" method="post">
            <div class="control-group">
              <label class="control-label" for="id_default_lang"><?php echo L_SELECT_LANG ?></label>
              <div class="controls">
                <?php plxUtils::printSelect('default_lang', plxUtils::getLangs(), $lang, false, 'span4') ?>
                <input class="btn" type="submit" name="select_lang" value="<?php echo L_INPUT_CHANGE ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="id_timezone"><?php echo L_TIMEZONE ?></label>
              <div class="controls">
                <?php plxUtils::printSelect('timezone', plxTimezones::timezones(), $timezone, false, 'span4'); ?>
              </div>
            </div>
            <?php echo plxToken::getTokenPostMethod() ?>
            <div class="control-group">
              <label class="control-label" for="id_name"><?php echo L_USERNAME ?></label>
              <div class="controls">
                <?php plxUtils::printInput('name', $name, 'text', '20-255', false, 'span12') ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="id_login"><?php echo L_LOGIN ?></label>
              <div class="controls">
                <?php plxUtils::printInput('login', $login, 'text', '20-255', false, 'span12') ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="id_pwd"><?php echo L_PASSWORD ?></label>
              <div class="controls">
                <?php plxUtils::printInput('pwd', '', 'password', '20-255', false, 'span12') ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="id_pwd2"><?php echo L_PASSWORD_CONFIRMATION ?></label>
              <div class="controls">
                <?php plxUtils::printInput('pwd2', '', 'password', '20-255', false, 'span12') ?>
              </div>
            </div>
            <?php plxUtils::printInput('version', $version, 'hidden') ?>
            <div class="control-group">
              <label class="control-label">&nbsp;</label>
              <div class="controls">
                <input class="btn" type="submit" name="install" value="<?php echo L_INPUT_INSTALL ?>" />
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="span6 widget">
        <div class="widget-title"><span class="icon"><i class="icon-cogs icon-grey icon-shadowed"></i></span>
          <h5><?php echo L_PLUXML_VERSION.' '.$version ?></h5>
        </div>
        <div class="widget-content">
          <div class="alert"> <strong><?php echo L_PLUXML_VERSION; ?> <?php echo $version; ?> (<?php echo L_INFO_CHARSET ?> <?php echo PLX_CHARSET ?>)</strong><br>
            <?php echo L_INFO_PHP_VERSION.' : '.phpversion() ?><br>
            <?php echo L_INFO_MAGIC_QUOTES.' : '.get_magic_quotes_gpc() ?> </div>
          <?php plxUtils::testWrite(PLX_ROOT.PLX_CONFIG_PATH) ?>
          <?php plxUtils::testWrite(PLX_ROOT.$config['racine_articles']) ?>
          <?php plxUtils::testWrite(PLX_ROOT.$config['racine_commentaires']) ?>
          <?php plxUtils::testWrite(PLX_ROOT.$config['racine_statiques']) ?>
          <?php plxUtils::testWrite(PLX_ROOT.$config['images']) ?>
          <?php plxUtils::testWrite(PLX_ROOT.$config['documents']) ?>
          <?php plxUtils::testModReWrite() ?>
          <?php plxUtils::testLibGD() ?>
          <?php plxUtils::testMail() ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!--Footer-->

<footer class="footer">
  <p class="text-center"><a href="http://www.littlerabbitlabs.net" target="_blank" title="Tiny Web Agency & Web Interactive Labs">© littleRabbitLabs</a> html5/css3 admin theme development - <a title="PluXml" href="http://www.pluxml.org">Pluxml <?php echo $plxAdmin->aConf['version'] ?></a> - license <a title="License publique générale GNU Version 3" target="_blank" href="http://org.rodage.com/gpl-3.0.fr.txt">GNU v3</a></p>
</footer>
</body>
</html>
<?php

/**
 * Edition des paramètres d'affichage
 *
 * @package PLX
 * @author	Florent MONTHEL
 * modification 30/03/2013 @author Jonathan Maris for © littleRabbitLabs
 **/

include(dirname(__FILE__).'/prepend.php');

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>

<div id="msg" class="alert alert-info"><?php echo L_CONFIG_INFOS_DESCRIPTION ?></div>
<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-book icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_CONFIG_INFOS_TITLE ?></h5>
    </div>
    <div class="widget-content"> <br />
      <div class="alert"><strong><?php echo L_PLUXML_VERSION; ?> <?php echo $plxAdmin->version; ?> (<?php echo L_INFO_CHARSET ?> <?php echo PLX_CHARSET ?>)</strong></div>
      <div class="alert"><?php echo L_INFO_PHP_VERSION; ?> : <?php echo phpversion(); ?></div>
      <div class="alert"><?php echo L_INFO_MAGIC_QUOTES; ?> : <?php echo get_magic_quotes_gpc(); ?></div>
      <div class="alert"><?php echo L_CONFIG_INFOS_NB_CATS ?> <?php echo sizeof($plxAdmin->aCats); ?></div>
      <div class="alert"><?php echo L_CONFIG_INFOS_NB_STATICS ?> <?php echo sizeof($plxAdmin->aStats); ?></div>
      <div class="alert"><?php echo L_CONFIG_INFOS_WRITER ?> <?php echo $plxAdmin->aUsers[$_SESSION['user']]['name'] ?></div>
      <div class="alert"><?php echo L_PLUXML_CHECK_VERSION ?>: <?php echo $plxAdmin->checkMaj(); ?></div>
    </div>
  </div>
</div>
<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-cogs icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_CONFIG_INFOS_TITLE ?></h5>
    </div>
    <div class="widget-content"> <br />
  	  <?php plxUtils::testWrite(PLX_ROOT.PLX_CONFIG_PATH); ?>
      <?php plxUtils::testWrite(PLX_ROOT.$plxAdmin->aConf['racine_articles']); ?>
      <?php plxUtils::testWrite(PLX_ROOT.$plxAdmin->aConf['racine_commentaires']); ?>
      <?php plxUtils::testWrite(PLX_ROOT.$plxAdmin->aConf['racine_statiques']); ?>
      <?php plxUtils::testWrite(PLX_ROOT.$plxAdmin->aConf['images']); ?>
      <?php plxUtils::testWrite(PLX_ROOT.$plxAdmin->aConf['documents']); ?>
      <?php plxUtils::testModReWrite() ?>
      <?php plxUtils::testLibGD() ?>
      <?php plxUtils::testMail() ?>
    </div>
  </div>
</div>
<?php eval($plxAdmin->plxPlugins->callHook('AdminSettingsInfos')) ?>
<?php
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>

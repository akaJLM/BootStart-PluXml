<?php

/**
 * Edition des paramètres de base
 *
 * @package PLX
 * @author	Stephane F et Florent MONTHEL
 * modification 30/03/2013 @author Jonathan Maris for © littleRabbitLabs
 **/

include(dirname(__FILE__).'/prepend.php');
include(PLX_CORE.'lib/class.plx.timezones.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);

# On édite la configuration
if(!empty($_POST)) {
	$plxAdmin->editConfiguration($plxAdmin->aConf,$_POST);
	header('Location: parametres_base.php');
	exit;
}

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-cog icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_CONFIG_BASE_CONFIG_TITLE ?></h5>
    </div>
    <div class="widget-content">
      <?php eval($plxAdmin->plxPlugins->callHook('AdminSettingsBaseTop')) # Hook Plugins ?>
      <form class="form form-horizontal" action="parametres_base.php" method="post" id="form_settings">
        <div class="control-group">
          <label class="control-label" for="id_title"><?php echo L_CONFIG_BASE_SITE_TITLE ?></label>
          <div class="controls">
            <?php plxUtils::printInput('title', plxUtils::strCheck($plxAdmin->aConf['title'])); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_description"><?php echo L_CONFIG_BASE_SITE_SLOGAN ?></label>
          <div class="controls">
            <?php plxUtils::printInput('description', plxUtils::strCheck($plxAdmin->aConf['description'])); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_racine"><?php echo L_CONFIG_BASE_SITE_URL ?></label>
          <div class="controls">
            <?php plxUtils::printInput('racine', $plxAdmin->racine);?>
            <a class="btn btn-mini" style="vertical-align:top" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_CONFIG_BASE_URL_HELP ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <?php plxUtils::printInput('artId',$artId,'hidden'); ?>
        <div class="control-group">
          <label class="control-label" for="id_meta_description"><?php echo L_CONFIG_META_DESCRIPTION ?></label>
          <div class="controls">
            <?php plxUtils::printInput('meta_description', plxUtils::strCheck($plxAdmin->aConf['meta_description'])); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_meta_keywords"><?php echo L_CONFIG_META_KEYWORDS ?></label>
          <div class="controls">
            <?php plxUtils::printInput('meta_keywords', plxUtils::strCheck($plxAdmin->aConf['meta_keywords'])); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_default_lang"><?php echo L_CONFIG_BASE_DEFAULT_LANG ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('default_lang', plxUtils::getLangs(), $plxAdmin->aConf['default_lang']) ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_timezone"><?php echo L_CONFIG_BASE_TIMEZONE ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('timezone', plxTimezones::timezones(), $plxAdmin->aConf['timezone']); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_allow_com"><?php echo L_CONFIG_BASE_ALLOW_COMMENTS ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('allow_com',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aConf['allow_com']); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_mod_com"><?php echo L_CONFIG_BASE_MODERATE_COMMENTS ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('mod_com',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aConf['mod_com']); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_mod_art"><?php echo L_CONFIG_BASE_MODERATE_ARTICLES ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('mod_art',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aConf['mod_art']); ?>
          </div>
        </div>
        <?php eval($plxAdmin->plxPlugins->callHook('AdminSettingsBase')) # Hook Plugins ?>
        <?php echo plxToken::getTokenPostMethod() ?>
        <div class="control-group">
          <label class="control-label">&nbsp;</label>
          <div class="controls">
            <input class="btn btn-responsive update" type="submit" value="<?php echo L_CONFIG_BASE_UPDATE ?>" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminSettingsBaseFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>

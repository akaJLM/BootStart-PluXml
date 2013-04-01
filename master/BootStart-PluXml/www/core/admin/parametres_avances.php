<?php
/**
 * Edition des paramètres avancés
 *
 * @package PLX
 * @author	Stephane F et Florent MONTHEL
 * modification 30/03/2013 @author Jonathan Maris for © littleRabbitLabs
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);

# On édite la configuration
if(!empty($_POST)) {
	$plxAdmin->editConfiguration($plxAdmin->aConf,$_POST);
	unset($_SESSION['medias']); # réinit de la variable de session medias (pour medias.php) au cas si changmt de chemin images/documents
	header('Location: parametres_avances.php');
	exit;
}

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>
<?php if(plxUtils::testModRewrite(false)) : $inactive = false; $class = ''; ?>
<?php if(is_file(PLX_ROOT.'.htaccess') AND $plxAdmin->aConf['urlrewriting']==0) { ?>

<div id="msg" class="alert alert-info"><?php echo L_CONFIG_ADVANCED_URL_REWRITE_ALERT ?></div>
<?php } ?>
<?php else: $inactive = true; $class = 'uneditable-input'; ?>
<div id="msg" class="alert alert-error"><?php echo L_MODREWRITE_NOT_AVAILABLE ?></div>
<?php endif; ?>
<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-cogs icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_CONFIG_ADVANCED_DESC ?></h5>
    </div>
    <div class="widget-content">
      <?php eval($plxAdmin->plxPlugins->callHook('AdminSettingsAdvancedTop')) # Hook Plugins ?>
      <form class="form form-horizontal" action="parametres_avances.php" method="post" id="form_settings">
        <div class="control-group">
          <label class="control-label" for="id_urlrewriting"><?php echo L_CONFIG_ADVANCED_URL_REWRITE ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('urlrewriting',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aConf['urlrewriting'], $inactive, $class); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_gzip"><?php echo L_CONFIG_ADVANCED_GZIP ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('gzip',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aConf['gzip']);?>
            <a style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_CONFIG_ADVANCED_GZIP_HELP ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_capcha"><?php echo L_CONFIG_ADVANCED_CAPCHA ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('capcha',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aConf['capcha']);?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_userfolders"><?php echo L_CONFIG_ADVANCED_USERFOLDERS ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('userfolders',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aConf['userfolders']);?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_clef"><?php echo L_CONFIG_ADVANCED_ADMIN_KEY ?></label>
          <div class="controls">
            <?php plxUtils::printInput('clef', $plxAdmin->aConf['clef'], 'text', '30-30'); ?>
            <a style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_CONFIG_ADVANCED_KEY_HELP ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_config_path"><?php echo L_CONFIG_ADVANCED_CONFIG_FOLDER ?></label>
          <div class="controls">
            <?php plxUtils::printInput('config_path', PLX_CONFIG_PATH) ?>
            <a  style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_HELP_SLASH_END ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_racine_articles"><?php echo L_CONFIG_ADVANCED_ARTS_FOLDER ?></label>
          <div class="controls">
            <?php plxUtils::printInput('racine_articles', $plxAdmin->aConf['racine_articles']); ?>
            <a style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_HELP_SLASH_END ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_racine_commentaires"><?php echo L_CONFIG_ADVANCED_COMS_FOLDER ?></label>
          <div class="controls">
            <?php plxUtils::printInput('racine_commentaires', $plxAdmin->aConf['racine_commentaires']); ?>
            <a style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_HELP_SLASH_END ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_racine_statiques"><?php echo L_CONFIG_ADVANCED_STATS_FOLDER ?></label>
          <div class="controls">
            <?php plxUtils::printInput('racine_statiques', $plxAdmin->aConf['racine_statiques']); ?>
            <a style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_HELP_SLASH_END ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_images"><?php echo L_CONFIG_ADVANCED_PICS_FOLDER ?></label>
          <div class="controls">
            <?php plxUtils::printInput('images', $plxAdmin->aConf['images']); ?>
            <a style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_HELP_SLASH_END ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_documents"><?php echo L_CONFIG_ADVANCED_DOCS_FOLDER ?></label>
          <div class="controls">
            <?php plxUtils::printInput('documents', $plxAdmin->aConf['documents']); ?>
            <a style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_HELP_SLASH_END ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_racine_themes"><?php echo L_CONFIG_ADVANCED_THEMES_FOLDER ?></label>
          <div class="controls">
            <?php plxUtils::printInput('racine_themes', $plxAdmin->aConf['racine_themes']); ?>
            <a style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_HELP_SLASH_END ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_racine_plugins"><?php echo L_CONFIG_ADVANCED_PLUGINS_FOLDER ?></label>
          <div class="controls">
            <?php plxUtils::printInput('racine_plugins', $plxAdmin->aConf['racine_plugins']); ?>
            <a style="vertical-align:top" target="_blank" class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_HELP_SLASH_END ?>"><i class="icon icon-bullhorn"></i></a> </div>
        </div>
        <?php eval($plxAdmin->plxPlugins->callHook('AdminSettingsAdvanced')) ?>
        <?php echo plxToken::getTokenPostMethod() ?>
        <div class="control-group">
          <label class="control-label" for="id_racine_plugins">&nbsp;</label>
          <div class="controls">
            <input class="btn btn-responsive update" type="submit" value="<?php echo L_CONFIG_ADVANCED_UPDATE ?>" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminSettingsAdvancedFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>

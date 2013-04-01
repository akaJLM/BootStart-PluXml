<?php

/**
 * Gestion de la configuration d'un plugin
 *
 * @package PLX
 * @author	Stephane F
 * modification 30/03/2013 @author Jonathan Maris for © littleRabbitLabs
 **/
include(dirname(__FILE__).'/prepend.php');

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);

$plugin = isset($_GET['p'])?urldecode($_GET['p']):'';
$plugin = plxUtils::nullbyteRemove($plugin);

$output='';
# chargement du fichier d'administration du plugin
$filename = realpath(PLX_PLUGINS.$plugin.'/config.php');
if(is_file($filename)) {
	# si le plugin n'est pas actif, aucune instance n'a été créée, on va donc la créer
	if(!isset($plxAdmin->plxPlugins->aPlugins[$plugin]) OR !is_object($plxAdmin->plxPlugins->aPlugins[$plugin]['instance'])) {
		$plxAdmin->plxPlugins->aPlugins[$plugin]['instance'] = $plxAdmin->plxPlugins->getInstance($plugin);
	}
	if(is_object($plxAdmin->plxPlugins->aPlugins[$plugin]['instance'])) {
		$plxAdmin->plxPlugins->aPlugins[$plugin]['instance']->getInfos();
	}
	# utilisation de la variable plxPlugin pour faciliter la syntaxe dans les devs des plugins
	$plxPlugin = $plxAdmin->plxPlugins->aPlugins[$plugin]['instance'];
	# Control des autorisation d'accès à l'écran config.php du plugin
	$plxAdmin->checkProfil($plxPlugin->getConfigProfil());
	# chargement de l'écran de parametrage du plugin config.php
	ob_start();
	?>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-wrench icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_CONFIG_ADVANCED_PLUGINS_PLUGIN_CONFIG ?></h5>
      <a href="parametres_plugins.php" class="btn btn-mini pull-right" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BACK_TO_PLUGINS ?>"><i class="icon icon-undo"></i></a> </div>
    <div class="widget-content">
      <?php
	
	include($filename);
	
	?>
    </div>
  </div>
</div>
<?php
	$output=ob_get_clean();
}
else {
	plxMsg::Error(L_NO_ENTRY);
	header('Location: parametres_plugins.php');
	exit;
}

# On inclut le header
include(dirname(__FILE__).'/top.php');
# Affichage des données
echo $output;
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>

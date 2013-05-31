<?php

/**
 * Gestion de l'administration d'un plugin
 *
 * @package PLX
 * @author	Stephane F
 * modification 30/03/2013
 * @author akaJLM
 **/
include(dirname(__FILE__).'/prepend.php');

$plugin = isset($_GET['p'])?urldecode($_GET['p']):'';
$plugin = plxUtils::nullbyteRemove($plugin);

$output='';
# chargement du fichier d'administration du plugin
$filename = realpath(PLX_PLUGINS.$plugin.'/admin.php');
if($plxAdmin->plxPlugins->aPlugins[$plugin]['activate'] AND is_file($filename)) {
	# on récupère les infos des plugins
	$plxAdmin->plxPlugins->aPlugins[$plugin]['instance']->getInfos();
	# utilisation de la variable plxPlugin pour faciliter la syntaxe dans les devs des plugins
	$plxPlugin = $plxAdmin->plxPlugins->aPlugins[$plugin]['instance'];
	# Control des autorisation d'accès à l'écran admin.php du plugin
	$plxAdmin->checkProfil($plxPlugin->getAdminProfil());
	ob_start();
		?>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-wrench icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_CONFIG_ADVANCED_PLUGINS_PLUGIN_ADMIN ?></h5>
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
	header('Location: index.php');
	exit;
}

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>
<?php
# Affichage des données
echo $output;
?>
<?php
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>

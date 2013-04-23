<?php if(!defined('PLX_ROOT')) exit; ?>
<?php

# Control du token du formulaire
plxToken::validateFormToken($_POST);

if(!empty($_POST)) {
	$plxPlugin->setParam('nbwords', $_POST['nbwords'], 'numeric');
	$plxPlugin->saveParams();
	header('Location: parametres_plugin.php?p=plxMyAutoMetaDescription');
	exit;
}
$nbwords = $plxPlugin->getParam('nbwords')!='' ? $plxPlugin->getParam('nbwords') : 30;

?>

<h2><?php echo $plxPlugin->getInfo('title') ?></h2>

<form action="parametres_plugin.php?p=plxMyAutoMetaDescription" method="post" id="form_plxMyAutoMetaDescription">
	<fieldset>
		<?php $plxPlugin->lang('L_NBWORDS_HELP') ?>
		<p class="field"><label for="id_nbwords"><?php $plxPlugin->lang('L_NBWORDS') ?></label></p>
		<?php plxUtils::printInput('nbwords',$nbwords,'text','4-4') ?>
		<p>
			<?php echo plxToken::getTokenPostMethod() ?>
			<input type="submit" name="submit" value="<?php $plxPlugin->lang('L_SAVE') ?>" />
		</p>
	</fieldset>
</form>
<?php

class plxUtilsExt extends plxUtils {
	
/**
	* Méthode qui formate un lien pour la barre des menus sous Bootstrap
	*
	* @param	name	string 			titre du menu
	* @param	href	string 			lien du menu
	* @param	title	string			contenu de la balise title
	* @param	params	string			extra balises data-* Bootstrap
	* @param	icon	string			extra icon Bootstrap/FontAwesome élément <i>
	* @param	class	string			contenu de la balise class
	* @param	onclick	string			contenu de la balise onclick
	* @param	extra	string			extra texte à afficher
	* @return			string			élément <a> formaté
	* @author	Jonathan M.
	**/
	public static function formatMenuBootstrap($name, $href, $title=false, $params='', $icon='', $class=false, $onclick=false, $extra='', $highlight=true) {
		$menu = '';
		$basename = explode('?', basename($href));
		$active = ($highlight AND ($basename[0] == basename($_SERVER['SCRIPT_NAME']))) ? ' active':'';
		if($basename[0]=='plugin.php' AND isset($_GET['p']) AND $basename[1]!='p='.$_GET['p']) $active='';
		$title = $title ? ' title="'.$title.'"':'';
		$params = $params ? ' '.$params.'':'';
		$class = $class ? ' '.$class.'':'';
		$onclick = $onclick ? ' onclick="'.$onclick.'"':'';
		$icon = $icon ? '<i class="icon-'.$icon.' icon-blue icon-shadowed-white icon-2x"></i>': '';
		$menu = '<li class="menu'.$active.$class.'"><a href="'.$href.'"'.$onclick.$title.$params.'>'.$extra.''.$icon.'<span class="visible-desktop">'.$name.'</span></a></li>';
		return $menu;
	}
}
<?php

class plxMyAutoMetaDescription extends plxPlugin {

	/**
	 * Constructeur de la classe
	 *
	 * @param	default_lang	langue par défaut utilisée par PluXml
	 * @return	null
	 * @author	Stephane F
	 **/
	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);

		# droits pour accèder à la page config.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);

		# Déclarations des hooks
		$this->addHook('plxShowMeta', 'plxShowMeta');

	}

	/**
	 * Méthode qui extrait n mots dans une chaine de caractères
	 *
	 * @param 	$string 	chaine à couper
	 * @param 	$len 		nombre de mots à garder
	 * @param 	$ending 	caractères de fin
	 * @param 	$char 		caractère de séparation
	 * @return	string
	 * @author	Stephane F
	 **/
	public static function subtok($string,$len=25,$ending='...',$chr=' ') {
		$explode=explode($chr,$string);
		if(sizeof($explode)>$len)
			return implode($chr,array_slice($explode,0,$len)).$ending;
		else
  			return implode($chr,array_slice($explode,0,$len));
	}

	/**
	 * Méthode renseigne le meta description de l'article
	 *
	 * @return	stdio
	 * @author	Stephane F
	 **/
	public function plxShowMeta() {

		echo '<?php
			if($this->plxMotor->mode=="article" AND strtolower($meta)=="description") {

				$description=trim($this->plxMotor->plxRecord_arts->f("meta_description"));
				if(!empty($description)) {
					echo "<meta name=\"description\" content=\"".$description."\" />\n";
					return true;
				}

				$chapo=trim($this->plxMotor->plxRecord_arts->f("chapo"));
				$content=trim($this->plxMotor->plxRecord_arts->f("content"));
				$description=strip_tags($chapo." ".$content); # suppression des balises html
				if(!empty($description)) {
					$description = str_replace("\"","\'",$description); # pour protéger le champ content de la balise meta
					$description = plxMyAutoMetaDescription::subtok($description,'.$this->getParam('nbwords').'); # on coupe
					echo "<meta name=\"description\" content=\"".$description."\" />\n";
					return true;
				}

				$description=trim($this->plxMotor->aConf["meta_description"]);
				if(!empty($description)) {
					echo "<meta name=\"description\" content=\"".$description."\" />\n";
					return true;
				}
			}
		?>';

	}

}
?>
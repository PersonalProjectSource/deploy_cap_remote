<?php

require_once("Validator.php");
require_once("ReleaseManager.php");

class GitManager {
	
	const UP_TO_DATE = "Already up-to-date.";
	const CLONE_ALREADY_EXIST = "fatal: destination path 'Diagonalisation' already exists and is not an empty directory.";
	const PULL_DONE = "Checking connectivity... done."; // Check la valeur avec la commande
	const CLONE_DONE = "Checking connectivity... done.";
	const NOTHING = "";

	const PULL_COMMAND = "git pull ";
	const CLONE_COMMAND = "git clone ";

	const RELEASE_SOURCE_FOLDER = "sourceRelease/";
	const RELEASE_BASE_NAME = "CustomName";
	
	protected $url;
	protected $validator;
	protected $releaseManager;

    /**
     * Constructeur
     * @param $url
     */
	public function __construct ($url) {

		$this->url = $url;
		$this->validator = new Validator();
		$this->releaseManager = new ReleaseManager();
	}

    /**
     * Gere le git Clone
     * @throws Exception
     */
	public function cloneSource () {
		
		// Extraire la commande system dans une methode pour plus de lisibilité.
		// Voir le role que pourrait jouer la classe ReleaseManager dans la suite de la commande

        // Release traitement.
		$iTokkenId = time();
		//$sReleasePath = self::RELEASE_SOURCE_FOLDER.self::RELEASE_BASE_NAME."_".$iTokkenId;
        $sReleasePath = self::RELEASE_SOURCE_FOLDER.$iTokkenId;

        //$sReleasePath = self::RELEASE_SOURCE_FOLDER.self::RELEASE_BASE_NAME."_1432048725"; // Supprimer apres les tests

        $this->releaseManager->aRealeaseIndex['last'] = $sReleasePath;
        $this->releaseManager->aRealeaseIndex['token'] = $iTokkenId;
        //$this->releaseManager->aRealeaseIndex['list'][] = $iTokkenId; // a supprimer apres verification de l'impact.

		$sResult = system(self::CLONE_COMMAND." ".$this->url." ".$sReleasePath."/");
        //$sResult = ""; // Supprimer apres les tests.
		if ($this->urlIsAgreed()) { // TODO lbrau : Voir les conditions de validation du validator.
			switch ($sResult) {
				case self::CLONE_DONE:
					$this->validator->sourceValidator(); // TODO lbrau : idem above
					$this->releaseManager->addRelease();
					break;
				case self::CLONE_ALREADY_EXIST:
					throw new Exception("Le depot existe deja \n"); // TODO 403 actuellement aucun moyen de verifier l'existant avec les retours fonction php.
					break;
				case self::NOTHING:
                    $this->releaseManager->addRelease(); // TODO voir si suppression apres test
					throw new Exception("L'erreur n'a pas pu etre identifiee. Faire une recherche sur 'TODO 403' dans les sources\n");
					break;
				default:
					throw new Exception("Probleme lors de la mise à jour des sources\n");
					break;
			}
		}
		else {
			throw new Exception("l'url n'est pas valide\n");
		}
	}

	public function checkout () {
		$bDone = true;
		return $bDone;
	}

    /**
     * Fait le pull uniquement si les condition de validation sont remplies
     *
     * @param null $argv
     * @param null $sParam
     * @throws Exception
     */
	public function pull ($argv = null, $sParam = null) {

		$sResult = system(self::TEST_COMMAND." ".$argv[1]);

		if ($this->urlIsAgreed()) { // TODO lbrau : Voir les conditions de validation du validator.
			switch ($sResult) {
				case self::UP_TO_DATE:
					// Code de mise a jour des sources
					break;
				case self::PULL_DONE:
					// Code de mise a jour des sources
					// Lancement des tests pour passer les sources téléchargé dans le validator.
					$this->validator->sourceValidator(); // TODO lbrau : Voir les conditions de validation du validator.
					break;
				case self::OTHER_CODE:
					throw new Exception("Probleme lors de la mise à jour des sources\n");
					break;
			}
		}
		else {
			throw new Exception("l'url n'est pas valide\n");
		}
	}

    /**
     * Check avec une regex la conformité de l'url ($this->url) du depot github
     * @return bool
     */
	private function urlIsAgreed () {
		return true;
	}

	public function push ($sParam) {
		
	}

	public function merge ($sParam) {
		
	}



}
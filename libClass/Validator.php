<?php

class Validator {

	/**
	*	Execute les conditions du validator. Throw une exception si une erreur est levée.
	*/
	public function sourceValidator() {
		$traitementAFaire = true;
		if (false == $traitementAFaire) {
			throw new Exception("La derniere release ne satisfait pas aux conditions de validations.\n Retour a la derniere release fonctionnelle.");
		}

		return true;
	}

	public function test() {

		return " validator ok ";
	}
}
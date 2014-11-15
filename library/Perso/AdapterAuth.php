<?php

/**
 * Mon Adapteur d'authentification perso
 * Permet d'authentifier l'identité d'une personne
 * 
 * @author Mignonat Laurent 
 */
class Perso_AdapterAuth implements Zend_Auth_Adapter_Interface {
    protected $_username;
    protected $_password ;
    
    /**
     * Définition de l'identifiant et du mot de passe
     * pour authentification
     */
    public function __construct($identifiant, $motdepasse) {
        $this->_username = $identifiant ;
        $this->_password = $motdepasse ;
        require_once "Perso/Util.php" ;
    }

    /**
     * Réalise une tentative d'authentification
     * @throws Zend_Auth_Adapter_Exception : Si l'authentification ne peut pas être réalisée
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        try {
            $personne = Util::getRepository('Personne')->getPersonneByCourriel($this->_username) ;
            if ($personne == null) {
                return new Zend_Auth_Result(
                                Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, 
                                $this->_username,
                                array('Utilisateur inconnu !')) ;
            }
            
            if ($personne->verifierPassword($this->_password)) {
                return new Zend_Auth_Result(
                        Zend_Auth_Result::SUCCESS, 
                        $this->_username) ;
            } else {
                return new Zend_Auth_Result(
                        Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, 
                        $this->_username,
                        array('Mauvais mot de passe !')) ;
            }
        } catch (Exception $ex) {
            return new Zend_Auth_Result(
                        Zend_Auth_Result::FAILURE_UNCATEGORIZED, 
                        $this->_username,
                        array("Erreur : ".$ex->getMessage())) ;
        }
    }
}

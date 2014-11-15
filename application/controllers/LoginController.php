<?php

/**
 * @author MIGNONAT Laurent
 * Gestion de l'authentification
 */
class LoginController extends Zend_Controller_Action {

    public function init() {
        Util::afficherMessagesFlash($this->_helper->FlashMessenger, $this->view) ;
    }

    /**
     * Action : Affiche la liste des films présents en BDD
     * Param  : Aucun
     * Form   : Aucun
     * Divers : Utilise mon propre authAdapter : Perso_AdapterAuth 
     *          dans library/Perso/Perso_AdapterAuth.php
     * View   : login/index (Redirection si login OK vers Index/index)
     */
    public function indexAction() {
        $request = $this->getRequest();
        $form = new Application_Form_Login();
        
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                
                try {
                    $auth = Zend_Auth::getInstance() ;
                    
                    $authAdaptateur = new Perso_AdapterAuth(
                                        $values['courriel'],
                                        $values['password']) ;
                    
                    $resultat = $auth->authenticate($authAdaptateur);
                    
                    switch ($resultat->getCode()) {

                       case Zend_Auth_Result::SUCCESS:
                           //Recupération de l'entité Personne
                           $personne = Util::getRepository("Personne")
                                                ->getPersonneByCourriel($values['courriel']) ;
                           
                           //Démarrage de la session du user
                           $authNamespace = new Zend_Session_Namespace('Zend_Auth');
                           //Settage des infos de l'utilisateur qui vont me servir dans les vues
                           $authNamespace->user = $personne->getIdentite() ;
                           $authNamespace->personneId = $personne->getId() ;
                           $authNamespace->role = ($personne->getEstEmploye())? "Employé" : "Client" ;
                           $authNamespace->courriel = $personne->getCourriel() ;
                           //Message de succès
                           Util::message("Bienvenue ".$personne->getIdentite()." !", 
                                          Util::success, 
                                          $this->_helper->FlashMessenger) ;
                           //Redirection vers la page d'acceuil
                           $this->redirect('Index/index');
                           break;

                       default:
                           Util::message("Erreur : courriel ou mot de passe incorrect !", 
                                          Util::error, 
                                          $this->view) ;
                           //On s'assure de la deconnexion
                           $this->deconnexionAction() ;
                           break;
                    }
                } catch (Exception $ex) {
                    Util::message("Erreur lors de la création du film : " . $ex->getMessage(), 
                                   Util::error, 
                                   $this->view) ;
                }
            }
        }
        $this->view->form = $form;
    }
    
    /**
     * Action : Deconnecte l'utilisateur en effacant les infos en session du user
     * Param  : Aucun
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : Redirection vers Login/index
     */
    public function deconnexionAction() {
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        $authNamespace->unsetAll();
        Zend_Session::forgetMe();
        $this->redirect('Login/index');
    }
}

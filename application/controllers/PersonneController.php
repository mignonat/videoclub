<?php

/**
 * @author MIGNONAT Laurent
 * Gestion des personnes
 */
class PersonneController extends Zend_Controller_Action {

    public function init() {
        Util::afficherMessagesFlash($this->_helper->FlashMessenger, $this->view) ;
    }

    /**
     * Action : Affiche la liste des clients et des employés présents en base
     * Param  : Aucun
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : personne/index
     */
    public function indexAction() {
        $this->testerDroit() ;
        
        $clients = Util::getRepository('Personne')->getAllClient();
        $this->view->clients = $clients;
        $employes = Util::getRepository('Personne')->getAllEmploye();
        $this->view->employes = $employes;
    }

    /**
     * Action : Ajoute une personne en base de donnée.
     * Param  : Aucun
     * Form   : Application_Form_Personne initialisé en 'création'
     * Ajax   : Aucun
     * View   : personne/ajouter
     */
    public function ajouterAction() {
        $this->testerDroit() ;
        
        $request = $this->getRequest();
        $form = new Application_Form_Personne(Application_Form_Personne::creation);
        
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {//CREATION DE LA PERSONNE
                $password1 = $form->getElement('password1')->getValue() ;
                $password2 = $form->getElement('password2')->getValue() ;
                
                if ($password1 != $password2) {
                    $form->getElement('password2')
                         ->addError('Les mots de passe ne correspondent pas !');
                } else {
                    $values = $form->getValues();
                    try {
                        Util::getRepository('Personne')
                                    ->creer(
                                       $values['nom'], 
                                       $values['prenom'], 
                                       $values['password1'], 
                                       $values['courriel'], 
                                       $values['adresse1'], 
                                       $values['adresse2'], 
                                       $values['codePostal'],
                                       $values['ville'], 
                                       $values['estEmploye']);
                        Util::message("Création de ".$values['nom']." ".$values['prenom']." réussi !", 
                                       Util::success, 
                                       $this->view) ;
                        $form->reset(); //On efface le formulaire
                    } catch (Exception $ex) {
                        Util::message("Erreur : " . $ex->getMessage(), 
                                       Util::error, 
                                       $this->view) ;
                    }
                }
            }
            
        }
        
        $this->view->form = $form;
        $this->_helper->viewRenderer->setRender('ajouter');
    }
    
    /**
     * Action : Modifie une personne
     * Param  : 'idPersonne' => Identifiant de la personne à modifier
     * Form   : Application_Form_Personne initialisé en 'modification'
     * Ajax   : Aucun
     * View   : personne/modifier
     */
    public function modifierAction() {
        $this->testerDroit() ;
        
        $request = $this->getRequest();
        $form = new Application_Form_Personne(Application_Form_Personne::modification);
        
        $idPersonne = $request->getParam("idPersonne");
        if ($idPersonne == '') {
            Util::message("Erreur : Aucun identifiant passé en paramètre", 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
            $this->redirect('Personne/index');
        }
        
        $personne = Util::getRepository('Personne')->getPersonne($idPersonne);
        if ($personne == null) {
            Util::message("Erreur, aucune personne trouvée en base pour l'identifiant ".$idPersonne, 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
            $this->redirect('Personne/index');
        }

        if (!$request->isPost()) { //1er Appel de l'action on renseigne le formulaire
            $form->getElement('id')         ->setValue($personne->getId());
            $form->getElement('nom')        ->setValue($personne->getNom());
            $form->getElement('prenom')     ->setValue($personne->getPrenom());
            $form->getElement('password1')  ->setValue("");
            $form->getElement('password2')  ->setValue("");
            $form->getElement('courriel')   ->setValue($personne->getCourriel());
            $form->getElement('adresse1')   ->setValue($personne->getAdresse1());
            $form->getElement('adresse2')   ->setValue($personne->getAdresse2());
            $form->getElement('codePostal') ->setValue($personne->getCodePostal());
            $form->getElement('ville')      ->setValue($personne->getVille());
            $form->getElement('estActif')   ->setValue($personne->getEstActif());
            $form->getElement('estEmploye') ->setValue($personne->getEstEmploye());
        } else { //sinon c'est un post
            if ($request->isPost()) {
                if ($form->isValid($request->getPost())) {//CREATION DE LA PERSONNE
                    $password = $erreur = "" ;
                    $password1 = $form->getElement('password1')->getValue() ;
                    $password2 = $form->getElement('password2')->getValue() ;
                    
                    if ($password1 != "" || $password2 != "") {
                        //L'employé a saisi un nouveau mot de passe
                        if ($password1 != $password2) {
                            $erreur = "Les mots de passe ne correspondent pas !" ;
                            $form->getElement('password2')->addError($erreur);
                        } else {
                            $password = $password1 ;
                        }
                    } //sinon pas de changement de mot de passe
                    
                    if ($erreur === "") {
                        $values = $form->getValues();
                        try {
                            Util::getRepository('Personne')
                                        ->modifier(
                                           $values['id'],
                                           $values['nom'], 
                                           $values['prenom'], 
                                           $password, //Si vide pas pris en compte
                                           $values['courriel'], 
                                           $values['adresse1'], 
                                           $values['adresse2'], 
                                           $values['codePostal'],
                                           $values['ville'], 
                                           $values['estEmploye'],
                                           $values['estActif']);
                            Util::message("Modification de ".$values['nom']." ".$values['prenom'] . " réussie !", 
                                           Util::success, 
                                           $this->_helper->FlashMessenger) ;
                            $this->redirect('Personne/consulter/idPersonne/'.$values['id']);
                        } catch (Exception $ex) {
                            Util::message("Erreur : " . $ex->getMessage(), 
                                           Util::error, 
                                           $this->view) ;
                        }
                    }
                }
            }
        }
        
        $this->view->form = $form;
        $this->view->personne = $personne;
        $this->_helper->viewRenderer->setRender('modifier');
    }
    
    /**
     * Action : Consultation de la fiche d'une personne
     * Param  : idPersonne : Identifiant de la personne à consulter
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : personne/consulter
     */
    public function consulterAction() {
        $request = $this->getRequest();
        
        $idPersonne = $request->getParam("idPersonne");
        
        $this->testerDroitConsultation($idPersonne) ;
        
        if ($idPersonne == '') {
            Util::message("Erreur : Aucun identifiant passé en paramètre", 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
            $this->redirect('Personne/index');
        }
        
        $personne = Util::getRepository('Personne')->getPersonne($idPersonne);
        if ($personne == null) {
            Util::message("Erreur, aucune personne trouvée en base pour l'identifiant ".$idPersonne, 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
            $this->redirect('Personne/index');
        }

        $locationsEnCours = Util::getRepository('location')->getAllLocationEnCours($personne->getCourriel());
        
        $this->view->personne = $personne;
        $this->view->locationsEnCours = $locationsEnCours ;
        $this->_helper->viewRenderer->setRender('consulter');
    }

    /**
     * Action : Change l'état du compte d'une personne : actif ou inactif
     * Param  : idPersonne : Identifiant de la personne
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : Redirection vers personne/index
     */
    public function changerAction() {
        $this->testerDroit() ;
        
        $request = $this->getRequest();
        $idPersonne = $request->getParam("idPersonne");
        
        if ($idPersonne == '') {
            Util::message("Erreur : Aucun identifiant passé en paramètre", 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
            $this->redirect('Personne/index');
        }
        
        try {
            $personne = Util::getRepository('Personne')->changerEtatCompte($idPersonne);
            $statutCompte = (($personne->getEstActif())? "actif" : "inactif") ;
            Util::message("Le compte de ".$personne->getIdentite()." est maintenant ".$statutCompte." !", 
                           Util::success, 
                           $this->_helper->FlashMessenger) ;
        } catch (Exception $ex) {
            Util::message("Erreur : ".$ex->getMessage(), 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
        }
        
        $this->redirect('Personne/index');
    }
    
    /**
     * Action : Renvoie la liste des personne dont le nom contient 'nom'
     * Param  : 'nom' => les noms des personnes doivent contenir cette chaine de caractère 
     * Form   : Aucun
     * Ajax   : Renvoie le resultat (liste des personnes) au format JSON
     * View   : Aucune
     */
    public function rechercherAction() {
        $this->testerDroit(true) ;
        
        $request = $this->getRequest();
        
        $this->getHelper('Layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json; charset=UTF-8');

        $nom = $request->getParam("nom");
        if ($nom == '') {
            echo Util::errorJSON("Veuillez saisir un nom !") ;
            return;
        }
        $result = array() ;
        try {
            $personnes = Util::getRepository('Personne')->rechercher($nom) ; 
            foreach ($personnes as $personne) {
                array_push($result,array('id' => $personne->getId(),'nom' => $personne->getIdentite()));
            }
        } catch (Exception $ex) {
            echo Util::errorJSON("Erreur '".$ex->getMessage()) ;
            return;
        }
        
        echo json_encode(array('success'=> true, 'result'=> $result)) ;
        return;
    }
    
    /**
     * Teste si l'utilisateur est authentifié
     * Si ce n'est pas un employé, il ne peut consulter que la fiche qui le concerne
     * @author Mignonat Laurent
     */
    private function testerDroitConsultation($idPersonne) {
        $erreur = false ;

        $infosUser = new Zend_Session_Namespace('Zend_Auth');
        
        if (!isset($infosUser->role)) {//Pas loggé
            $erreur = true ;
        } else if ($infosUser->role != "Employé") {//Loggé mais pas employé
            if ($infosUser->personneId != $idPersonne) {
                $erreur = true ;
            }
        }
        
        if ($erreur) {
            Util::message("Vous n'avez pas les droits nécessaires !", 
                           Util::error,
                           $this->_helper->FlashMessenger) ;
            $this->redirect('Error/unauthorized');
        }
    }
    
    /**
     * Teste si l'utilisateur est authentifié, et s'il possède les droits nécessaires
     * pour accéder à certaines actions
     * @param Boolean $jsonError : true  renvoie erreur au format JSON, 
     *                             false redirige vers la page 'Error/unauthorized'
     * @author Mignonat Laurent
     */
    private function testerDroit($jsonError=false) {
        $infosUser = new Zend_Session_Namespace('Zend_Auth');
        
        if (!isset($infosUser->role) || $infosUser->role != "Employé") {
            //Pas loggé ou pas employé
            if (!$jsonError) {
                Util::message("Vous n'avez pas les droits nécessaires !", 
                               Util::error, 
                               $this->_helper->FlashMessenger) ;
                $this->redirect('Error/unauthorized');
            } else {
                echo Util::errorJSON("Vous n'avez pas les droits nécessaires !") ;
                return;
            }
        }
    }
}

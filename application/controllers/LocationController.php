<?php

/**
 * @author MIGNONAT Laurent
 * Gestion des locations
 */
class LocationController extends Zend_Controller_Action {
    /* 
     * Si guest   : aucun droit
     * Si client  : acces à ses locations seulement
     * Si employé : acces a toutes les locations
     */
    private $_roleUser = "guest" ;
    private $_courrielUser = null ;
    
    public function init() {
        Util::afficherMessagesFlash($this->_helper->FlashMessenger, $this->view) ;
        
        //Si l'utilisateur est loggé on récupere ses infos
        $infosUser = new Zend_Session_Namespace('Zend_Auth');
        if (isset($infosUser->user)) {
            $this->_courrielUser = $infosUser->courriel ;
            $this->_roleUser = $infosUser->role ;
            $this->_idUser = $infosUser->personneId ;
        }
    }

    /**
     * Action : Affiche la liste des location en cours suivant les droits de l'utilisateur
     * Param  : Aucun
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : location/index
     */
    public function indexAction() {
        $this->interdireGuest() ;
        
        if ($this->_roleUser == "Employé") {
            $locations = Util::getRepository('Location')->getAllLocationEnCours();
        }
        
        if (!isset($locations)) {
            $locations = Util::getRepository('Location')->getAllLocationEnCours($this->_courrielUser);
        }
        
        $this->view->locations = $locations;
    }
    
    /**
     * Action : Ajoute une location en base de donnée.
     * Param  : Aucun
     * Form   : Application_Form_Location
     * Ajax   : Aucun
     * View   : location/ajouter
     */
    public function ajouterAction() {
        $this->testerDroit() ;
        
        $request = $this->getRequest();
        $form = new Application_Form_Location();
        
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                try {
                    $repository = Util::getRepository('Location');
                    $repository->creer($values['idMedia'], $values['idPersonne']);
                    Util::message("Location du film enregistrée !", 
                                   Util::success, 
                                   $this->_helper->FlashMessenger) ;
                    $this->redirect('Location/index');
                } catch (Exception $ex) {
                    Util::message("Erreur lors de la création du film : " . $ex->getMessage(), 
                                   Util::error, 
                                   $this->view) ;
                }
            }
        }
        
        $this->view->form = $form;
        $this->_helper->viewRenderer->setRender('ajouter');
    }
    
    /**
     * Action : Consultation de la fiche d'une location
     * Param  : idLocation : Identifiant de la location à consulter
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : location/consulter
     */
    public function consulterAction() {
        $this->interdireGuest() ;
        
        $request = $this->getRequest();
        $idLocation = $request->getParam("idLocation");
        if ($idLocation == '') {
            Util::message("Erreur : Aucun identifiant passé en paramètre", 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
            $this->redirect('Location/index');
        }
        
        $location = Util::getRepository('Location')->getLocation($idLocation);
        if ($location == null) {
            Util::message("Erreur, aucune location trouvée en base pour l'identifiant " . $idLocation, 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
            $this->redirect('Film/index');
        }
        
        $this->view->location = $location ;
        $this->view->media = $location->getMedia() ;
        $this->view->film = $location->getMedia()->getFilm() ;
        $this->view->personne = $location->getPersonne() ;
        $this->_helper->viewRenderer->setRender('consulter');
    }
    
    /**
     * Action : Affiche la liste des locations qui ne sont plus en cours de validité
     *          suivant les droits de l'utilisateur.
     * Param  : Aucun
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : location/historique
     */
    public function historiqueAction() {
        $this->interdireGuest() ;
        
        $request = $this->getRequest();
        $idPersonne = $request->getParam("idPersonne");
        $personne = null ;
        
        if ($this->_roleUser == "Employé") {
            
            if ($idPersonne != '' and $idPersonne!=$this->_idUser) {
                
                //Si une personne est passé en paramètre on reduit le resultat à ses locations
                $personne = Util::getRepository('Personne')->getPersonne($idPersonne);
                if ($personne == null) {
                    Util::message("Erreur, aucune personne trouvée en base pour l'identifiant ".$idPersonne, 
                                   Util::error, 
                                   $this->_helper->FlashMessenger) ;
                    $this->redirect('Location/index');
                }
                //On reduit l'historique à la personne
                $locations = Util::getRepository('Location')->getAllLocationTerminee($personne->getCourriel());
                
            } else {
                
                //Pas de personne en parametre on affiche les locations de tout le monde
                $locations = Util::getRepository('Location')->getAllLocationTerminee();
                
            }
            
        }
        
        if (!isset($locations)) {
            if ($idPersonne != '' and $idPersonne!=$this->_idUser) {
                Util::message("Erreur : Vous ne pouvez consulter que votre historique !", 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
                $this->redirect('Location/index');
            }
            $locations = Util::getRepository('Location')->getAllLocationTerminee($this->_courrielUser);
        }
        
        $this->view->personne = $personne ;
        $this->view->locations = $locations ;
        $this->_helper->viewRenderer->setRender('historique');
    }
       
    /**
     * Action : Enregistre le retour d'un média loué (DVD/BLURAY) en base de donnée
     * Param  : 'idLocation' => Identifiant de la location
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : Redirection vers Location/index
     */
    public function retournerAction() {
        $this->testerDroit() ;
        
        $request = $this->getRequest();
        $idLocation = $request->getParam("idLocation");
        if ($idLocation == '') {
            Util::message("Erreur : Aucun identifiant passé en paramètre", 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
            $this->redirect('Location/index');
        }
        
        try {
            Util::getRepository('Location')->retourner($idLocation) ;
            Util::message("Retour du média pris en compte !", 
                           Util::success, 
                           $this->_helper->FlashMessenger) ;
        } catch (Exception $ex) {
            Util::message("Erreur lors du retour du média : ".$ex->getMessage(), 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
        }
        $this->redirect('Location/index');
    }
    
    /**
     * Si $this->_roleUser = "guest", 
     * l'utilisateur est redirigé vers la page d'erreur 'Error/unauthorized'
     */
    private function interdireGuest() {
        if ($this->_roleUser == "guest") {
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
        if ($this->_roleUser != "Employé") {
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

<?php

/**
 * @author MIGNONAT Laurent
 * Gestion des films (pas des médias)
 */
class FilmController extends Zend_Controller_Action {

    public function init() {
        Util::afficherMessagesFlash($this->_helper->FlashMessenger, $this->view) ;
    }

    /**
     * Action : Affiche la liste des films présents en BDD
     * Param  : Aucun
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : film/index
     */
    public function indexAction() {        
        $films = Util::getRepository('Film')->getAllFilm();
        $this->view->films = $films;
    }

    /**
     * Action : Ajoute un film en base de donnée.
     * Param  : Aucun
     * Form   : Application_Form_Film initialisé en 'création'
     * Ajax   : Aucun
     * View   : film/ajouter
     */
    public function ajouterAction() {
        $this->testerDroit() ;
        
        $request = $this->getRequest();
        $form = new Application_Form_Film(Application_Form_Film::creation);
        
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {//CREATION DU FILM
                $values = $form->getValues();
                try {
                    $repository = Util::getRepository('Film');
                    $repository->creer(
                                    $values['nom'],
                                    $values['resume'], 
                                    $values['dateFilm'], 
                                    $values['realisateur_id'], 
                                    $values['acteur1_id'], 
                                    $values['acteur2_id'], 
                                    $values['acteur3_id']);
                    Util::message("Création du film " . $values['nom'] . " réussi !", 
                                    Util::success, 
                                    $this->view) ;
                    $form->reset(); //formulaire réinitialisé
                } catch (Exception $ex) {
                    Util::message("Erreur lors de la création du film : " . $ex->getMessage(), 
                                    Util::error, 
                                    $this->view) ;
                }
            }
        }
        $this->view->form = $form;
        $this->view->listeActeursRealisateurs = $this->getActeursRealisateurs() ;
        $this->_helper->viewRenderer->setRender('ajouter');
    }

    /**
     * Action : Modifie un film
     * Param  : 'idFilm' => Identifiant du film à modifier
     * Form   : Application_Form_Film initialisé en 'modification'
     * Ajax   : Aucun
     * View   : film/modifier
     */
    public function modifierAction() {
        $this->testerDroit() ;
        
        $request = $this->getRequest();
        $form = new Application_Form_Film(Application_Form_Film::modification);
        
        $idFilm = $request->getParam("idFilm");
        if ($idFilm == '') {
            Util::message("Erreur : Aucun identifiant de film passé en paramètre", 
                            Util::error, 
                            $this->_helper->FlashMessenger) ;
            $this->redirect('Film/index');
        }
        
        $film = Util::getRepository('Film')->getFilm($idFilm);
        if ($film == null) {
            Util::message("Erreur, aucun film trouvé en base pour l'identifiant " . $idFilm, 
                            Util::error, 
                            $this->_helper->FlashMessenger) ;
            $this->redirect('Film/index');
        }
            
        if (!$request->isPost()) { //1er Appel de l'action on peuple le formulaire
            $form->getElement('id')->setValue($film->getId());
            $form->getElement('nom')->setValue($film->getNom());
            $form->getElement('resume')->setValue($film->getResume());
            $form->getElement('dateFilm')->setValue($film->getDateFilm()->format("d/m/Y"));
            $form->getElement('realisateur_id')->setValue($film->getRealisateur()->getId());
            $form->getElement('acteur1_id')->setValue($film->getActeur1()->getId());
            if ($film->getActeur2()!=null) { 
                $form->getElement('acteur2_id')->setValue($film->getActeur2()->getId()); 
            }
            if ($film->getActeur3()!=null) { 
                $form->getElement('acteur3_id')->setValue($film->getActeur3()->getId()); 
            }
        } else { //Il s'agit d'un post on traite les modifications
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                try {
                    Util::getRepository('Film')->modifier(
                                    $values['id'], 
                                    $values['nom'], 
                                    $values['resume'], 
                                    $values['dateFilm'], 
                                    $values['realisateur_id'], 
                                    $values['acteur1_id'], 
                                    $values['acteur2_id'], 
                                    $values['acteur3_id']);
                    Util::message("Modification du film " . $values['nom'] . " réussi !", 
                                    Util::success, 
                                    $this->_helper->FlashMessenger) ;
                    $this->redirect('Film/index');
                } catch (Exception $ex) {
                    Util::message("Erreur lors de la modification du film : " . $ex->getMessage(), 
                                    Util::error, 
                                    $this->view) ;
                }
            }
        }
        
        $this->view->form = $form;
        $this->view->film = $film;
        $this->view->medias = $this->getMedias($idFilm);
        $this->view->listeActeursRealisateurs = $this->getActeursRealisateurs() ;
        
        $this->_helper->viewRenderer->setRender('modifier'); 
    }

    /**
     * Action : Consultation de la fiche d'un film
     * Param  : IdFilm : Identifiant du film à consulter
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : film/consulter
     */
    public function consulterAction() {
        $request = $this->getRequest();
        
        $idFilm = $request->getParam("idFilm");
        if ($idFilm == '') {
            Util::message("Erreur : Aucun identifiant passé en paramètre", 
                            Util::error, 
                            $this->_helper->FlashMessenger) ;
            $this->redirect('Film/index');
        }
        
        $film = Util::getRepository('Film')->getFilm($idFilm);
        if ($film == null) {
            Util::message("Erreur, aucun film trouvé en base pour l'identifiant " . $idFilm, 
                            Util::error, 
                            $this->_helper->FlashMessenger) ;
            $this->redirect('Film/index');
        }

        $this->view->film = $film;
        $this->view->medias = $this->getMedias($idFilm);
        
        $this->_helper->viewRenderer->setRender('consulter'); 
    }

    /**
     * Action : Désactive tous les médias (DVD/BLURAY) d'un film donné
     * Param  : 'idFilm' => Identifiant du film
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : Redirection vers Film/index
     */
    public function desactiverAction() {
        $this->testerDroit() ;
        
        $request = $this->getRequest();
        $idFilm = $request->getParam("idFilm");
        if ($idFilm == '') {
            Util::message("Erreur : Aucun identifiant passé en paramètre", 
                            Util::error, 
                            $this->_helper->FlashMessenger) ;
            $this->redirect('Film/index');
        }
        
        $film = Util::getRepository('Film')->getFilm($idFilm);
        if ($film == null) {
            Util::message("Erreur, aucun film trouvé en base pour l'identifiant " . $idFilm, 
                            Util::error, 
                            $this->_helper->FlashMessenger) ;
            $this->redirect('Film/index');
        }
        
        try {
            Util::getRepository('Film')->desactiver($idFilm);
            Util::message("Désactivation de tous les médias (DVD & BLURAY) du film "
                           .$film->getNom()." réussie !", 
                           Util::success, 
                           $this->_helper->FlashMessenger) ;
        } catch (Exception $ex) {
            Util::message("Erreur lors de la désactivation de tous les médias (DVD & BLURAY) du film "
                           .$film->getNom()." : ".$ex->getMessage(), 
                           Util::error, 
                           $this->_helper->FlashMessenger) ;
        }
        
        $this->redirect('Film/index');
    }
    
    /**
     * Action : Renvoie la liste des films dont le nom contient 'nom'
     * Param  : 'nom' => les noms des films doivent contenir cette chaine de caractère 
     * Form   : Aucun
     * Ajax   : Renvoie le resultat (liste de film) au format JSON
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
            $films = Util::getRepository('Film')->rechercher($nom) ; 
            foreach ($films as $film) {
                array_push($result,array('id' => $film->getId(),'nom' => $film->getNom()));
            }
        } catch (Exception $ex) {
            echo Util::errorJSON("Erreur '".$ex->getMessage()) ;
            return;
        }
        
        echo json_encode(array('success'=> true, 'result'=> $result)) ;
        return;
    }
    
    //Renvoi la liste des médias associé a un film
    private function getMedias($idFilm) {
        return Util::getRepository('Media')->getMedias($idFilm);
    }
    
    //Renvoie la liste de toutes les occurences d'ActeurRealisateurs en base de données
    private function getActeursRealisateurs() {
        return Util::getRepository("ActeurRealisateur")->getActeursRealisateurs() ;
    }
    
    /**
     * Teste si l'utilisateur est authentifié, et s'il possède les droits nécessaires
     * pour accéder à certaines actions
     * @param Boolean $jsonError : true  renvoie erreur au format JSON, 
     *                             false redirige vers la page 'Error/unauthorized'
     * @author Mignonat Laurent
     */
    private function testerDroit($jsonError=false) {
        //Si l'utilisateur est loggé on récupere ses infos
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

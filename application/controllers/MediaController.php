<?php

use Entities\Media;

/**
 * @author MIGNONAT Laurent
 * Gestion des médias.
 * 
 * Toutes les actions sont faites pour être appelé en AJAX
 * et ne retourne que des données au format JSON
 */
class MediaController extends Zend_Controller_Action {
    
    public function init() { }

    public function indexAction() {
        //Pas de page par défault
        throw new Zend_Controller_Action_Exception('This page does not exist', 404);
    }

    /**
     * Action : Ajoute, pour un film donné, un média.
     * Param  : 'idFilm'    => le film concerné
     *          'typeMedia' => le type de média : DVD ou BLURAY
     * Ajax   : Retourne un message de success avec les infos concernant le média créé
     */
    public function ajouterAction() {
        $this->testerDroit(true) ;
        
        $this->getHelper('Layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json; charset=UTF-8');

        $request = $this->getRequest();
        
        $idFilm = $request->getParam("idFilm");
        if (!is_numeric($idFilm)) {  
            echo Util::errorJSON("Identifiant du film incorrect : '".$idFilm."' !") ;
            return;
        }
        
        $typeMedia = $request->getParam("typeMedia");
        switch ($typeMedia) {
            case Media::BLURAY ; break ;
            case Media::DVD    ; break ;
            default : 
                echo Util::errorJSON("Le type de media renseigné est incorrect : '".$typeMedia."' !") ;
                return;
        }
        
        try {
            $media = Util::getRepository('Media')->ajouter($idFilm, $typeMedia) ;
        } catch (Exception $ex) {
            echo Util::errorJSON("Erreur '".$ex->getMessage()) ;
            return;
        }
        
        echo json_encode(array('success'=> true, 
                               'id'=> $media->getId(),
                               'type'=> $media->getType(),
                               'numero'=> $media->getNumero(),
                               'estActif'=> $media->getEstActif())) ;
        return;
    }
    
    /**
     * Action : Modifie le statut d'un média : actif ou inactif
     * Param  : 'idMedia' => le média concerné
     * Ajax   : Retourne un message de success et donne le nouveau statut du média
     */
    public function modifierAction() {
        $this->testerDroit(true) ;
        
        $this->getHelper('Layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json; charset=UTF-8');

        $request = $this->getRequest();
        
        $idMedia = $request->getParam("idMedia");
        if (!is_numeric($idMedia)) {  
            echo Util::errorJSON("Identifiant du média incorrect : '".$idMedia."' !") ;
            return;
        }
        
        try {
            $media = Util::getRepository('Media')->modifier($idMedia) ; 
        } catch (Exception $ex) {
            echo Util::errorJSON("Erreur '".$ex->getMessage()) ;
            return;
        }
        
        echo json_encode(array('success'=> true,
                               'estActif'=> $media->getEstActif(), 
                               'statut'=> $media->getStatut() )) ;
        return;
    }
    
    /**
     * Action : Renvoie les médias disponibles pour un film donné
     * Param  : 'idFilm' => le film concerné
     * Ajax   : La liste des médias disponibles
     */
    public function rechercherAction() {
        $this->testerDroit(true) ;
        
        $this->getHelper('Layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json; charset=UTF-8');

        $request = $this->getRequest();
        
        $idFilm = $request->getParam("idFilm");
        if ($idFilm == '') {  
            echo jUtil::errorJSON("Aucun identifiant de film !") ;
            return;
        }
        $result = array() ;
        try {
            $medias = Util::getRepository('Media')->getMediasDisponible($idFilm) ; 
            foreach ($medias as $media) {
                array_push($result,array('id' => $media->getId(),'nom' => $media->getType()." ".$media->getNumero()));
            }
        } catch (Exception $ex) {
            echo Util::errorJSON("Erreur '".$ex->getMessage()) ;
            return;
        }
        
        echo json_encode(array('success'=> true, 'result'=> $result)) ;
        return;
    }
    
    /**
     * Teste si l'utilisateur est authentifié, et si c'est bien un employé
     * @author Mignonat Laurent
     */
    private function testerDroit() {
        //Si l'utilisateur est loggé on recupere ses infos
        $infosUser = new Zend_Session_Namespace('Zend_Auth');
        if (!isset($infosUser->role) || $infosUser->role != "Employé") {
            echo Util::errorJSON("Vous n'avez pas les droits nécessaires !") ;
            return;
        }
    }
}

<?php

/**
 * @author MIGNONAT Laurent
 * Controller de la page d'acceuil du site
 */
class IndexController extends Zend_Controller_Action {

    public function init() {
        Util::afficherMessagesFlash($this->_helper->FlashMessenger, $this->view) ;
    }

    /**
     * Action : Affiche Top10 des films les + loués, et les 8 derniers ajouts de film
     * Param  : Aucun
     * Form   : Aucun
     * Ajax   : Aucun
     * View   : index/index
     */
    public function indexAction() {
        //Renvoie les 8 derniers films créés en BDD
        $nouveautes = Util::getRepository('Film')->getNouveautes(6);
        $this->view->nouveautes = $nouveautes ;
        
        //Renvoie les 8 films les plus loués, triés par nombre de location DESC
        $filmsLesPlusLoues = Util::getRepository('Location')->getFilmsLesPlusLoues(10);
        $this->view->filmsLesPlusLoues = $filmsLesPlusLoues ;
    }
    
}

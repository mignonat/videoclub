<?php

class Util {

    const success = "success" ;
    const error   = "error" ;
    
    /**
     * Envoie le message $texte, de type success ou error, suivant le conteneur (FlashMessenger ou View)
     * 
     * @param String $texte : Texte à afficher
     * @param Boolean $estSucces : true si succes, false si erreur
     * @param mixed $conteneur : Instance de FlassMessenger (si redirection) ou de View (si aucune redirection)
     * @throws Exception : Type de conteneur inconnue
     * @author Mignonat Laurent
     */
    public static function message($texte, $estSucces, $conteneur) {
        switch (get_class($conteneur)) {
            
            case "Zend_Controller_Action_Helper_FlashMessenger" :
                $conteneur->setNamespace(($estSucces == self::success)? self::success : self::error)->addMessage($texte);
                return ;
                
            case "Zend_View" :
                if ($estSucces == self::success) {
                    $conteneur->messagesSuccess = array($texte) ;
                } else {
                    $conteneur->messagesError = array($texte) ;
                }
                return ;
                
            default : throw new Exception("Type de conteneur inconnu") ;
        }
    }
    
    /**
     * Si des messages FlashMessenger ont été émis, ils sont pris en compte et envoyés à la vue
     * 
     * @param FlashMessenger $flashMessenger : Le FlashMessenger
     * @param View $view : Vue courante
     * @author Mignonat Laurent
     */
    public static function afficherMessagesFlash($flashMessenger, $view) {
        if ($flashMessenger->setNamespace(self::success)->hasMessages()) {
            $view->messagesSuccess = $flashMessenger->getMessages();
        }
        if ($flashMessenger->setNamespace(self::error)->hasMessages()) {
            $view->messagesError = $flashMessenger->getMessages();
        }
    }
    
    /**
     * Renvoi le repository associé au nom de l'entité
     * 
     * @param String $entityName : Ex Film, ou Personne
     * @return Repository doctrine pour l'entité passé en paramètre
     * @author Mignonat Laurent
     */
    public static function getRepository($entityName) {
        return Zend_Registry::get("em")->getRepository("Entities\\" . $entityName);
    }
    
    
    /**
     * Retourne le message $message au format JSON.
     * Ainsi tous les messages d'erreurs ont la même structure
     * 
     * @param String $message : Le message d'erreur à afficher
     * @return le message au format JSON
     * @author Mignonat Laurent
     */
    public static function errorJSON($message) {
        return json_encode(array(self::success => false, 'message'=> $message)) ;
    }
    
    /**
     * Renvoie vrai si la chaine $aTester est bonne
     * 
     * @param string $aTester : La chaine à tester
     * @param boolean $testNumeric : Vrai si on doit tester si la chaine est un integer
     * @return boolean : true si la chaine est OK, false sinon
     */
    public static function estValide($aTester, $testNumeric=false) {
        if ($aTester != null) {
            if (!is_object($aTester) && $aTester == '') {
                return false;
            }
            if ($testNumeric) {
                if (!is_numeric($aTester)) {
                    return false ;
                }
            }
            return true;
        } else {
            return false;
        }
    }
    
    public static function baseUrl() {
        $baseUrl = str_replace("index.php", "", Zend_controller_front::getInstance()->getBaseUrl());

        if ($baseUrl == "/") {
            return "" ;
        } else {
            return $baseUrl;
        }
    }
}

?>

<?php

class Application_Form_Login extends Zend_Form {

    public function init() {
        // La méthode HTTP d'envoi du formulaire
        $this->setMethod('post');
        
        $this->addElement('text', 'courriel', array(
            'label' => 'Votre courriel : *',
            'required' => true,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(1, 45))
            ),
        ));
        
        $this->addElement('password', 'password', array(
            'label' => 'Votre mot de passe : *',
            'required' => false,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 45))
            ),
        ));
        
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Connexion',
        ));

        // Protection anti CSRF
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
        $this->getElement("submit")->removeDecorator('DtDdWrapper') ;
        
        $elements = array ("courriel", "password", "csrf") ;
        foreach ($elements as $element) {
            $element = $this->getElement($element) ;
            if ($element!=null) {
                $element->removeDecorator('htmlTag');
            }
        }
    }

}

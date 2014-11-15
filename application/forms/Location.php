<?php

class Application_Form_Location extends Zend_Form {

    public function init() {
        $this->setMethod('post');
        
        $this->addElement('text', 'nomPersonne', array(
            'label'       => 'Rechercher une personne : (saisir 2 caractères min)',
            'required'    => false,
            'placeholder' =>'Saisir le nom de la personne',
            'filters' => array('StripTags', 'StringTrim'),
            'validators'  => array(
                array('validator' => 'StringLength', 'options' => array(0, 75))
            ),
        ));
        
        $this->addElement('text', 'nomFilm', array(
            'label'       => 'Rechercher un film à louer : (saisir 2 caractères min)',
            'required'    => false,
            'placeholder' =>'Saisir le nom du film',
            'filters' => array('StripTags', 'StringTrim'),
            'validators'  => array(
                array('validator' => 'StringLength', 'options' => array(0, 75))
            ),
        ));
        
        $this->addElement('hidden', 'idMedia', array('required' => true));
        $this->addElement('hidden', 'idPersonne', array('required' => true));
        
        $this->addElement('submit', 'submit', array('ignore' => true, 'label' => 'Louer',));
        $this->getElement("submit")->removeDecorator('DtDdWrapper') ;
        
        // Protection anti CSRF
        $this->addElement('hash', 'csrf', array('ignore' => true,));
        
        $elements = array ("idMedia", "idPersonne") ;
        foreach ($elements as $element) {
            $element = $this->getElement($element) ;
            if ($element!=null) {
                $element->removeDecorator('label');
                $element->removeDecorator('htmlTag');
            }
        }
        
        $elements = array ("nomPersonne", "nomFilm") ;
        foreach ($elements as $element) {
            $element = $this->getElement($element) ;
            if ($element!=null) {
                $element->removeDecorator('htmlTag');
            }
        }
    }

}

<?php

class Application_Form_Personne extends Zend_Form {
    
    private $typeForm;

    const creation = 1;
    const modification = 2;
    
    /*
     * Surcharge du constructeur pour adapter le formulaire suivant que ce soit 
     * une création ou une modification
     * 
     * $typeForm : 1 si creation, 2 si modification
     */
    public function __construct($typeForm = 0, $options = null) {
        switch ($typeForm) {
            case self::creation : break;
            case self::modification : break;
            default :
                throw new \Exception("Le type de formulaire n'est pas connue !");
        }
        $this->typeForm = $typeForm;
        parent::__construct($options);
    }
    
    public function init() {
        // La méthode HTTP d'envoi du formulaire
        $this->setMethod('post');
        
        if ($this->typeForm == self::modification) {                
            $this->addElement('hidden', 'id', array('required' => true));
            $this->getElement("id")->removeDecorator('label')->removeDecorator('htmlTag') ;

            $this->addElement('checkbox', 'estActif', array(
                'label' => 'Le compte est actif :',
                'use_hidden_element' => true,
                'checked_value' => 'actif',
                'unchecked_value' => 'inactif',
                'required' => true
            ));
        }
        
        $this->addElement('text', 'nom', array(
            'label' => 'Nom : *',
            'required' => true,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(1, 45))
            ),
        ));
        
        $this->addElement('text', 'prenom', array(
            'label' => 'Prénom : *',
            'required' => true,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(1, 45))
            ),
        ));
        
        $password1Label = ($this->typeForm == self::creation)
                                ? 'Saisir le mot de passe :' 
                                : 'Modifier le mot de passe : (laissez vide si pas de modif)' ;
        
        $password2Label = ($this->typeForm == self::creation)
                                ? 'Saisir mot de passe à nouveau :' 
                                : 'Re-saisir le nouveau mot de passe :' ;
        
        $passwordRequired = ($this->typeForm == self::creation) ;
        
        $this->addElement('password', 'password1', array(
            'label' => $password1Label,
            'required' => $passwordRequired,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 45))
            ),
        ));
        
        $this->addElement('password', 'password2', array(
            'label' => $password2Label,
            'required' => $passwordRequired,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 45))
            ),
        ));
        
        $this->addElement('text', 'courriel', array(
            'label' => 'Courriel : *',
            'required' => true,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                'EmailAddress',
                array('validator' => 'StringLength', 'options' => array(1, 45))
            )
        ));
        
        $this->addElement('text', 'adresse1', array(
            'label' => 'Adresse principale :',
            'required' => false,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 100))
            ),
        ));
        
        $this->addElement('text', 'adresse2', array(
            'label' => 'Autre adresse :',
            'required' => false,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 100))
            ),
        ));
        
        $this->addElement('text', 'codePostal', array(
            'label' => 'Code postal : *',
            'required' => true,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(5, 5))
            ),
        ));
        
        $this->addElement('text', 'ville', array(
            'label' => 'Ville : *',
            'required' => true,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(1, 50))
            ),
        ));
        
        $this->addElement('checkbox', 'estEmploye', array(
            'label' => 'La personne est un employé :',
            'use_hidden_element' => true,
            'checked_value' => 'Employé',
            'unchecked_value' => 'Client',
            'required' => true
        ));
        
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => ($this->typeForm == self::creation) ? 'Créer' : 'Modifier',
        ));

        // Protection anti CSRF
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
        
        $this->getElement("submit")->removeDecorator('DtDdWrapper') ;
        $this->getElement("csrf")->removeDecorator('label') ;
             
        $elements = array ("nom", "prenom", "password1", "password2", "courriel", "adresse1", "adresse2", "codePostal", "ville") ;
        
        foreach ($elements as $element) {
            $element = $this->getElement($element) ;
            if ($element!=null) {
                $element->removeDecorator('htmlTag');
            }
        }
    }

}

<?php

class Application_Form_Film extends Zend_Form {

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
        }

        $this->addElement('text', 'nom', array(
            'label' => 'Nom du film : *',
            'required' => true,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 75))
            ),
        ));

        $this->addElement('textarea', 'resume', array(
            'label' => 'Résumé : *',
            'required' => true,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 200))
            )
        ));

        $this->addElement('text', 'dateFilm', array(
            'label'         => 'Date de production : *',
            'required'      => true,
            'filters' => array('StripTags', 'StringTrim'),
            'class'         => 'date',
            'validators'    => array(
                array('validator' => new Zend_Validate_Date(
                        array('format' => 'dd/MM/yyyy', 'locale' => 'fr'))
            ))
        ));

        $this->addElement('hidden', 'realisateur_id', array(
            'required' => true
        ));

        $this->addElement('hidden', 'acteur1_id', array(
            'required' => true
        ));

        $this->addElement('hidden', 'acteur2_id', array(
            'required' => false
        ));

        $this->addElement('hidden', 'acteur3_id', array(
            'required' => false
        ));
        
        $this->addElement('hidden', 'realisateur_label', array(
            'required' => false
        ));

        $this->addElement('hidden', 'acteur1_label', array(
            'required' => false
        ));

        $this->addElement('hidden', 'acteur2_label', array(
            'required' => false
        ));

        $this->addElement('hidden', 'acteur3_label', array(
            'required' => false
        ));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => ($this->typeForm == self::creation) ? 'Ajouter' : 'Modifier',
        ));

        // Protection anti CSRF
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));

        $this->getElement("submit")->removeDecorator('DtDdWrapper') ;
        
        $elements = array ("id", "realisateur_id", "acteur1_id", "acteur2_id",
                           "acteur3_id", "realisateur_label", "acteur1_label",
                           "acteur2_label", "acteur3_label", "csrf") ;
        
        foreach ($elements as $element) {
            $element = $this->getElement($element) ;
            if ($element!=null) {
                $element->removeDecorator('label');
                $element->removeDecorator('htmlTag');
            }
        }
        
        $elements = array ("nom", "resume", "dateFilm") ;
        
        foreach ($elements as $element) {
            $element = $this->getElement($element) ;
            if ($element!=null) {
                $element->removeDecorator('htmlTag');
            }
        }
    }
}

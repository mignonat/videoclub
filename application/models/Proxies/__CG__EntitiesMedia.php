<?php

namespace Proxies\__CG__\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Media extends \Entities\Media implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function estDisponible()
    {
        $this->__load();
        return parent::estDisponible();
    }

    public function getStatut()
    {
        $this->__load();
        return parent::getStatut();
    }

    public function getLocationEnCours()
    {
        $this->__load();
        return parent::getLocationEnCours();
    }

    public function setType($type)
    {
        $this->__load();
        return parent::setType($type);
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function getType()
    {
        $this->__load();
        return parent::getType();
    }

    public function getNumero()
    {
        $this->__load();
        return parent::getNumero();
    }

    public function getFilm()
    {
        $this->__load();
        return parent::getFilm();
    }

    public function getEstActif()
    {
        $this->__load();
        return parent::getEstActif();
    }

    public function getListeLocation()
    {
        $this->__load();
        return parent::getListeLocation();
    }

    public function setId($id)
    {
        $this->__load();
        return parent::setId($id);
    }

    public function setEstActif($etat)
    {
        $this->__load();
        return parent::setEstActif($etat);
    }

    public function setNumero($numero)
    {
        $this->__load();
        return parent::setNumero($numero);
    }

    public function setFilm(\Entities\Film $film)
    {
        $this->__load();
        return parent::setFilm($film);
    }

    public function setListeLocation($listeLocation)
    {
        $this->__load();
        return parent::setListeLocation($listeLocation);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'type', 'estActif', 'numero', 'film', 'listeLocation');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}
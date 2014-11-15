<?php

namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\ActeurRealisateurRepository")
 * @Table(name="ACTEUR_REALISATEUR")
 * @author Mignonat Laurent
 */
class ActeurRealisateur {

    /**
     * @var integer $id
     * 
     * @Column(name="ID", type="integer", length=11, nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $nom
     * 
     * @Column(name="NOM", type="string", length=60, nullable=false)
     */
    private $nom;

    /**
     * @var string $prenom
     * 
     * @Column(name="PRENOM", type="string", length=45, nullable=false)
     */
    private $prenom;

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function __toString() {
        return $this->nom." ".$this->prenom ;
    }
}

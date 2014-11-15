<?php

namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\LocationRepository")
 * @Table(name="LOCATION")
 * @author Mignonat Laurent
 */
class Location {

    /**
     * @var integer $id
     * 
     * @Column(name="ID_LOCATION", type="integer", length=11, nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var DateTime $dateLocation
     * 
     * @Column(name="DATE_LOCATION", type="datetime", nullable=false)
     */
    private $dateLocation;

    /**
     * @var DateTime $dateRetour
     * 
     * @Column(name="DATE_RETOUR", type="datetime", nullable=true)
     */
    private $dateRetour = NULL ;

    /**
     * @var Media $media
     * 
     * @ManyToOne(targetEntity="Media", inversedBy="listeLocation")
     * @JoinColumn(name="MEDIA_ID", referencedColumnName="ID", nullable=false)
     */
    private $media;

    /**
     * @var Personne $personne
     * 
     * @ManyToOne(targetEntity="Personne", inversedBy="listeLocation")
     * @JoinColumn(name="PERSONNE_ID", referencedColumnName="ID", nullable=false)
     */
    private $personne;

    public function getId() {
        return $this->id;
    }

    public function getDateLocation() {
        return $this->dateLocation;
    }

    public function getDateRetour() {
        return $this->dateRetour;
    }

    public function getMedia() {
        return $this->media;
    }

    public function getPersonne() {
        return $this->personne;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDateLocation($dateLocation) {
        $this->dateLocation = $dateLocation;
    }

    public function setDateRetour($dateRetour) {
        $this->dateRetour = $dateRetour;
    }

    public function setMedia(Media $media) {
        $this->media = $media;
    }

    public function setPersonne(Personne $personne) {
        $this->personne = $personne;
    }

}

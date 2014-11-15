<?php

namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\FilmRepository")
 * @Table(name="FILM")
 * @author Mignonat Laurent
 */
class Film {

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
     * @Column(name="NOM", type="string", length=75, nullable=false)
     */
    private $nom;

    /**
     * @var string $dateFilm
     * 
     * @Column(name="DATE_FILM", type="datetime", nullable=false)
     */
    private $dateFilm;

    /**
     * @var string $resume
     * 
     * @Column(name="RESUME", type="string", length=200, nullable=false)
     */
    private $resume;

    /**
     * @var string $dateCreation
     * 
     * @Column(name="DATE_CREATION", type="datetime", nullable=false)
     */
    private $dateCreation;
	
    /**
     * @var array<Media> $listeMedia
     * 
     * @OneToMany(targetEntity="Media", mappedBy="film")
     */
    private $listeMedia;

    /**
     * @var ActeurRealisateur $realisateur
     * 
     * @ManyToOne(targetEntity="ActeurRealisateur", inversedBy="listeRealisation")
     * @JoinColumn(name="REALISATEUR_ID", referencedColumnName="ID", nullable=false)
     */
    private $realisateur;

    /**
     * @var ActeurRealisateur $acteur1
     * 
     * @ManyToOne(targetEntity="ActeurRealisateur")
     * @JoinColumn(name="ACTEUR1", referencedColumnName="ID", nullable=false)
     */
    private $acteur1;

    /**
     * @var ActeurRealisateur $acteur1
     * 
     * @ManyToOne(targetEntity="ActeurRealisateur")
     * @JoinColumn(name="ACTEUR2", referencedColumnName="ID", nullable=true)
     */
    private $acteur2;

    /**
     * @var ActeurRealisateur $acteur3
     * 
     * @ManyToOne(targetEntity="ActeurRealisateur")
     * @JoinColumn(name="ACTEUR3", referencedColumnName="ID", nullable=true)
     */
    private $acteur3;
    
    /**
     * Renvoie vrai s'il existe au moins un media disponible pour ce film
     * @return boolean
     */
    public function estDisponible() {
        if (count($this->getListeMedia())==0) {
            return false ;
        }
        $countIndispo = 0 ;
        foreach ($this->getListeMedia() as $media) {
            if (!$media->estDisponible()) {
                $countIndispo++ ;
            }
        }
        if ($countIndispo == count($this->getListeMedia())) {
            return false ;
        }
        return true ;
    }
    
    /**
     * Renvoie vrai si il existe au moins un media actif pour ce film
     * @return boolean
     */
    public function existeUnMediaActif() {
        if (count($this->getListeMedia())==0) {
            return false ;
        }
        foreach ($this->getListeMedia() as $media) {
            if ($media->getEstActif()) {
                return true ;
            }
        }
        return false ;
    }

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getDateFilm() {
        return $this->dateFilm;
    }

    public function getResume() {
        return $this->resume;
    }

    public function getDateCreation() {
        return $this->dateCreation;
    }

    public function getListeMedia() {
        return $this->listeMedia;
    }

    public function getRealisateur() {
        return $this->realisateur;
    }

    public function getActeur1() {
        return $this->acteur1;
    }

    public function getActeur2() {
        return $this->acteur2;
    }

    public function getActeur3() {
        return $this->acteur3;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = ucfirst($nom);
    }

    public function setDateFilm($dateFilm) {
        $this->dateFilm = $dateFilm;
    }

    public function setResume($resume) {
        $this->resume = $resume;
    }

    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;
    }

    public function setListeMedia($listeMedia) {
        $this->listeMedia = $listeMedia;
    }

    public function setRealisateur(ActeurRealisateur $realisateur) {
        $this->realisateur = $realisateur;
    }

    public function setActeur1(ActeurRealisateur $acteur1) {
        $this->acteur1 = $acteur1;
    }

    public function setActeur2(ActeurRealisateur $acteur2=null) {
        $this->acteur2 = $acteur2;
    }

    public function setActeur3(ActeurRealisateur $acteur3=null) {
        $this->acteur3 = $acteur3;
    }

}

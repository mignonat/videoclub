<?php

namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\MediaRepository")
 * @Table(name="MEDIA", uniqueConstraints={@UniqueConstraint(name="search_idx", columns={"NUMERO"})})
 * 
 * @author Mignonat Laurent
 */
class Media {

    const DVD = "DVD";
    const BLURAY = "BLURAY";
	
    /**
     * @var integer $id
     * 
     * @Column(name="ID", type="integer", length=11, nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $type
     * 
     * @Column(name="TYPE_MEDIA", type="string", nullable=false, columnDefinition="ENUM('DVD', 'BLURAY')")
     */
    private $type;
	
    /**
     * @var string $estActif
     * 
     * @Column(name="EST_ACTIF", type="boolean", nullable=false)
     */
    private $estActif = false ;
	
    /**
     * @var Film $film
     * 
     * @ManyToOne(targetEntity="Film", inversedBy="listeMedia")
     * @JoinColumn(name="FILM_ID_FILM", referencedColumnName="ID")
     */
    private $film;

    /**
     * @var string $numero
     * 
     * @Column(name="NUMERO", type="integer", nullable=false)
     */
    private $numero ;
    
    /**
     * @var array<Location> $listeLocation
     * 
     * @OneToMany(targetEntity="Location", mappedBy="media")
     */
    private $listeLocation;

    /**
     * Renvoie vrai si le media est actif, et qu'il n'est pas actuellement loué 
     * @return boolean
     */
    public function estDisponible() {
        if (!$this->getEstActif()) {
            return false ;
        }
        foreach ($this->getListeLocation() as $location) {
            if ($location->getDateRetour()==null) {
                return false ;
            }
        }
        return true ;
    }
    
    /**
     * Renvoie le statut du film : disponible, loué, désactivé
     * @return string
     */
    public function getStatut() {
        if (!$this->getEstActif()) {
            return "désactivé" ;
        }
        foreach ($this->getListeLocation() as $location) {
            if ($location->getDateRetour()==null) {
                return "loué" ;
            }
        }
        return "disponible" ;
    }
    
    /**
     * Retourne l'objet Location corresondant à la location en cours
     * @return Location ou null si pas de location en cours
     */
    public function getLocationEnCours() {
        if (count($this->listeLocation)>0) {
            foreach ($this->listeLocation as $location) {
                if ($location->getDateRetour()==null) {
                    return $location ;
                }
            }
        }
        return null ;
    }
    
    /**
     * On ne peut setter que suivant les constantes de cette classe (une sorte d'ENUM en PHP ...)
     * @param const $type : DVD ou BLURAY
     * @throws \Exception : Le type est inconnu
     */
    public function setType($type) {
        switch ($type) {
            case self::DVD : break;
            case self::BLURAY : break;
            default : throw new \Exception("Le type de média renseigné est inconnu");
        }
        $this->type = $type;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }
    
    public function getNumero() {
        return $this->numero;
    }

    public function getFilm() {
        return $this->film;
    }

	public function getEstActif() {
        return $this->estActif;
    }
	
    public function getListeLocation() {
        return $this->listeLocation;
    }

    public function setId($id) {
        $this->id = $id;
    }
	
    public function setEstActif($etat) {
        $this->estActif = $etat;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function setFilm(Film $film) {
        $this->film = $film;
    }

    public function setListeLocation($listeLocation) {
        $this->listeLocation = $listeLocation;
    }
        
}

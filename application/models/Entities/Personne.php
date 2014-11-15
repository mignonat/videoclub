<?php

namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\PersonneRepository")
 * @Table(name="PERSONNE", uniqueConstraints={@UniqueConstraint(name="courriel_UNIQUE", columns={"COURRIEL"})})
 * @author Mignonat Laurent
 */
class Personne {

    /**
     * @var integer $id
     * 
     * @Column(name="ID", type="integer", length=11, nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $prenom
     * 
     * @Column(name="PRENOM", type="string", length=45, nullable=false)
     */
    private $prenom;

    /**
     * @var string $nom
     * 
     * @Column(name="NOM", type="string", length=45, nullable=false)
     */
    private $nom;

    /**
     * @var string $password
     * 
     * @Column(name="PASSWORD", type="string", length=45, nullable=false)
     */
    private $password ;
    
    /**
     * @var string $courriel
     * 
     * @Column(name="COURRIEL", type="string", length=100, nullable=false)
     */
    private $courriel;

    /**
     * @var string $adresse1
     * 
     * @Column(name="ADRESSE1", type="string", length=100, nullable=true)
     */
    private $adresse1 = null ;

    /**
     * @var string $adresse2
     * 
     * @Column(name="ADRESSE2", type="string", length=100, nullable=true)
     */
    private $adresse2 = null ;

    /**
     * @var string $codePostal
     * 
     * @Column(name="CODE_POSTAL", type="string", length=5, nullable=false)
     */
    private $codePostal;

    /**
     * @var string $ville
     * 
     * @Column(name="VILLE", type="string", length=50, nullable=false)
     */
    private $ville;

    /**
     * @var boolean $estActif
     * 
     * @Column(name="EST_ACTIF", type="boolean", nullable=false)
     */
    private $estActif = 1 ;

    /**
     * @var boolean $estEmploye
     * 
     * @Column(name="EST_EMPLOYE", type="boolean", nullable=false)
     */
    private $estEmploye = 0 ;
    
    /**
     * @var string $dateCreation
     * 
     * @Column(name="DATE_CREATION", type="datetime", nullable=false)
     */
    private $dateCreation;

    /**
     * @var string $numeroAdherent
     * 
     * @Column(name="NUMERO_ADHERENT", type="string", length=10, nullable=false)
     */
    private $numeroAdherent;

    /**
     * @var array<Location> $listeLocation
     * 
     * @OneToMany(targetEntity="Location", mappedBy="client")
     */
    private $listeLocation;

    /**
     * Crypte le password avant de setter l'attribut
     * @param type $password
     */
    public function setPassword($password) {
        $this->password = crypt($password);
    }
    
    /**
     * Test si le mot de passe est celui de l'utilisateur
     * @param type $password : password à vérifier
     * @return boolean
     */
    public function verifierPassword($password) {
        return (crypt($password, $this->password) == $this->password) ;
    }
    
    public function __toString() {
        return $this->getIdentite() ;
    }
    
    public function getIdentite() {
        return $this->nom." ".$this->prenom ;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getCourriel() {
        return $this->courriel;
    }

    public function getAdresse1() {
        return $this->adresse1;
    }

    public function getAdresse2() {
        return $this->adresse2;
    }

    public function getCodePostal() {
        return $this->codePostal;
    }

    public function getVille() {
        return $this->ville;
    }

    public function getEstActif() {
        return $this->estActif;
    }

    public function getEstEmploye() {
        return $this->estEmploye;
    }

    public function getDateCreation() {
        return $this->dateCreation;
    }

    public function getNumeroAdherent() {
        return $this->numeroAdherent;
    }

    public function getListeLocation() {
        return $this->listeLocation;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPrenom($prenom) {
        $this->prenom = ucfirst($prenom);
    }

    public function setNom($nom) {
        $this->nom = ucfirst($nom);
    }

    public function setCourriel($courriel) {
        $this->courriel = $courriel;
    }

    public function setAdresse1($adresse1=null) {
        $this->adresse1 = $adresse1;
    }

    public function setAdresse2($adresse2=null) {
        $this->adresse2 = $adresse2;
    }

    public function setCodePostal($codePostal) {
        $this->codePostal = $codePostal;
    }

    public function setVille($ville) {
        $this->ville = ucfirst($ville);
    }

    public function setEstActif($estActif) {
        $this->estActif = $estActif;
    }

    public function setEstEmploye($estEmploye) {
        $this->estEmploye = $estEmploye;
    }

    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;
    }

    public function setNumeroAdherent($numeroAdherent) {
        $this->numeroAdherent = $numeroAdherent;
    }

    public function setListeLocation($listeLocation) {
        $this->listeLocation = $listeLocation;
    }
    
}

<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Entities\Personne;
use Util ;

/**
 * Logique métier des personnes
 * @author Mignonat Laurent
 */
class PersonneRepository extends EntityRepository {

    /**
     * Retourne toutes les personnes en BDD
     * 
     * @return array<Personne>
     */
    public function getAllPersonne() {
        $qb = $this ->createQueryBuilder('p')
                    ->select('p')
                    ->orderBy('p.nom');
        return $qb->getQuery()->getResult();
    }

    /**
     * Retourne tous les clients en BDD
     * 
     * @return array<Personne>
     */
    public function getAllClient() {
        $qb = $this ->createQueryBuilder('p')
                    ->select('p')
                    ->where('p.estEmploye = 0')
                    ->orderBy('p.nom');
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Retourne tous les employés en BDD
     * 
     * @return array<Personne>
     */
    public function getAllEmploye() {
        $qb = $this ->createQueryBuilder('p')
                    ->select('p')
                    ->where('p.estEmploye = 1')
                    ->orderBy('p.nom');
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Retourne l'entity Personne correspondant à cet identifiant
     * 
     * @param integer $id : Identifiant de la personne
     * @return <Personne> : Occurence de la personne
     * @throws \Exception : Probleme avec l'identifiant passé en paramètre
     */
    public function getPersonne($id) {
        if (!Util::estValide($id, true)) { throw new \Exception("La variable id est incorrecte"); }
        
        $qb = $this ->createQueryBuilder('p')
                    ->select('p')
                    ->where("p.id = :id")
                    ->setParameter("id", $id);
        if (count($qb->getQuery()->getResult())>0) {
            return $qb->getQuery()->getResult()[0] ;
        } else {
            return null ;
        }
    }
    
    /**
     * Retourne l'entity Personne associé à ce courriel
     * 
     * @param string $courriel : Courriel de la personne
     * @return <Personne>
     * @throws \Exception : Pas de personne pour ce courriel en BDD
     */
    public function getPersonneByCourriel($courriel) {        
        if (!Util::estValide($courriel)) { throw new \Exception("La variable courriel est vide"); }
        
        $qb = $this ->createQueryBuilder('p')
                    ->select('p')
                    ->where("p.courriel = :courriel")
                    ->setParameter("courriel", $courriel);
        if (count($qb->getQuery()->getResult())>0) {
            return $qb->getQuery()->getResult()[0] ;
        } else {
            return null ;
        }
    }
    
    /**
     * Crée une Personne en BDD
     * 
     * @param string $nom
     * @param string $prenom
     * @param string $password
     * @param string $courriel
     * @param string $adresse1
     * @param string $adresse2
     * @param string $codePostal
     * @param string $ville
     * @param boolean $estEmploye
     * @throws \Exception : Problème avec les paramètres en entrée
     *                      Probleme lors de l'enregistrement en BDD
     */
    public function creer($nom, $prenom, $password, $courriel, $adresse1, $adresse2, $codePostal, $ville, $estEmploye) {
        if (!Util::estValide($nom))        { throw new \Exception("La variable nom est vide"); }
        if (!Util::estValide($prenom))     { throw new \Exception("La variable prenom est vide"); }
        if (!Util::estValide($password))   { throw new \Exception("La variable password est vide"); }
        if (!Util::estValide($courriel))   { throw new \Exception("La variable courriel est vide"); }
        if (!Util::estValide($codePostal)) { throw new \Exception("La variable codePostal est vide"); }
        if (!Util::estValide($ville))      { throw new \Exception("La variable ville est vide"); }
        if (!Util::estValide($estEmploye)) { throw new \Exception("La variable estEmploye est vide"); }
        if (!Util::estValide($adresse1))   { $adresse1 = null ; }
        if (!Util::estValide($adresse2))   { $adresse2 = null ; }

        if ($this->getPersonneByCourriel($courriel)!=null) {
            throw new \Exception("Une personne possédant le courriel '".$courriel."' existe déjà en base !");
        }
        
        try {
            $personne = new Personne();
            $personne->setNom($nom);
            $personne->setPrenom($prenom);
            $personne->setDateCreation(new \DateTime());
            $personne->setPassword($password);
            $personne->setCourriel($courriel);
            $personne->setAdresse1($adresse1);
            $personne->setAdresse2($adresse2);
            $personne->setCodePostal($codePostal);
            $personne->setVille($ville);
            $personne->setEstEmploye($estEmploye);
            $personne->setEstActif(true);
            $personne->setNumeroAdherent($this->getNextNumero()) ;
            $this->_em->persist($personne);
            $this->_em->flush();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
    
    /**
     * Modifie une personne en BDD
     * 
     * @param integer $id
     * @param string $nom
     * @param string $prenom
     * @param string $password
     * @param string $courriel
     * @param string $adresse1
     * @param string $adresse2
     * @param string $codePostal
     * @param string $ville
     * @param boolean $estEmploye
     * @param boolean $estActif
     * @throws \Exception : Problème avec les paramètres en entrée
     *                      Probleme lors de l'enregistrement en BDD
     */
    public function modifier($id, $nom, $prenom, $password, $courriel, $adresse1, $adresse2, $codePostal, $ville, $estEmploye, $estActif) {
        if (!Util::estValide($id, true))   { throw new \Exception("La variable id est incorrecte"); }
        if (!Util::estValide($nom))        { throw new \Exception("La variable nom est vide"); }
        if (!Util::estValide($prenom))     { throw new \Exception("La variable prenom est vide"); }
        if (!Util::estValide($courriel))   { throw new \Exception("La variable courriel est vide"); }
        if (!Util::estValide($codePostal)) { throw new \Exception("La variable codePostal est vide"); }
        if (!Util::estValide($ville))      { throw new \Exception("La variable ville est vide"); }
        if (!Util::estValide($estEmploye)) { throw new \Exception("La variable estEmploye est vide"); }
        if (!Util::estValide($estActif))   { throw new \Exception("La variable estACtif est vide"); }
        if (!Util::estValide($adresse1))   { $adresse1 = null ; }
        if (!Util::estValide($adresse2))   { $adresse2 = null ; }
        
        try {
            $personne = $this->_em->find("Entities\Personne", $id);
            if (!$personne) { throw new \Exception("Aucune personne en base avec l'ID " . $id); }
            
            if ($personne->getCourriel() != $courriel) {
                if ($this->getPersonneByCourriel($courriel)!=null) {
                    throw new \Exception("Une personne possédant le courriel '".$courriel."' existe déjà en base !");
                }
            }
            $personne->setNom($nom);
            $personne->setPrenom($prenom);
            if (Util::estValide($password)) { $personne->setPassword($password); }
            $personne->setCourriel($courriel);
            $personne->setAdresse1($adresse1);
            $personne->setAdresse2($adresse2);
            $personne->setCodePostal($codePostal);
            $personne->setVille($ville);
            $personne->setEstEmploye($estEmploye);
            $personne->setEstActif($estActif);
            $this->_em->flush();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Change l'état du compte d'une personne : si il est actif, elle le désactive, et vice-versa
     * @param integer $id : Identifiant de la personne
     * @return <Personne>
     * @throws \Exception : Probleme avec l'identifiant en paramètre
     */
    public function changerEtatCompte($id) {
        if (!Util::estValide($id, true)) { throw new \Exception("La variable personne est vide"); }
        
        $personne = $this->_em->find("Entities\Personne", $id);
        if (!$personne) { throw new \Exception("Aucune personne en base avec l'identifiant " . $id); }
        $personne->setEstActif(!$personne->getEstActif()) ;
        $this->_em->flush();
        
        return $personne ;
    }
    
    /**
     * Recherche des personnes en BDD suivant leur nom
     * 
     * @param string $nom : Le nom des personnes doit contenir cette chaine
     * @param boolean $filtreActif : si true ne retourne que les personnes avec le statut = actif
     * @return array<Personne>
     * @throws \Exception : Problème avec le paramètre en entrée
     */
    public function rechercher($nom, $filtreActif=true) {        
        if (!Util::estValide($nom)) { throw new \Exception("La variable nom est vide"); }
        
        $qb = $this ->createQueryBuilder('p')
                    ->select('p')
                    ->where("p.nom LIKE :nom") ;
        if ($filtreActif) {
            $qb->andWhere("p.estActif = 1") ;
        }
        
        $qb->setParameter("nom", "%".$nom."%")
            ->orderBy("p.prenom")
            ->orderBy("p.nom");
        return $qb->getQuery()->getResult() ;
    }
    
    /**
     * Donne le prochain numéro d'adhérent disponible.
     * 
     * @return int : Le prochain numéro d'adhérent disponible
     */
    private function getNextNumero() {
        if (count($this->getAllPersonne())>0) {
            $qb = $this ->createQueryBuilder('p')
                    ->select('max(p.numeroAdherent) as numeroMax') ;
            $result = $qb->getQuery()->getResult();
            if (count($result)>0) {
                return ($result[0]['numeroMax'] + 1) ;
            }
        } else {
            return 1 ;
        }
    }
    
}

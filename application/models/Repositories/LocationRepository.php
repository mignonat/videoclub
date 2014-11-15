<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Entities\Location;
use Util ;

/**
 * Logique métier des locations
 * @author Mignonat Laurent
 */
class LocationRepository extends EntityRepository {
    
    /**
     * Retourne l'entity Location correspondant a l'identifiant $idLocation
     * 
     * @param integer $idLocation
     * @return <Location>
     */
    public function getLocation($idLocation) {
        return $this->_em->find("Entities\Location", $idLocation);
    }    
    
    /**
     * Retourne toutes les locations présentent en BDD
     * 
     * @return array<Location>
     */
    public function getAllLocation() {
        $qb = $this ->createQueryBuilder('l')
                    ->select('l')
                    ->orderBy('l.dateLocation');
        return $qb->getQuery()->getResult();
    }

    /**
     * Retourne toutes les locations en cours :
     *      si $courriel  = null : de tous le monde
     *      si $courriel != null : de la personne possedant ce courriel
     *  
     * @param string $courriel
     * @return array<Location>
     */
    public function getAllLocationEnCours($courriel=null) {        
        $qb = $this ->createQueryBuilder('l')
                    ->select('l') ;
        if ($courriel!=null) {
            $qb->join("l.personne", "p")
               ->where("p.courriel = :courriel")
               ->setParameter("courriel", $courriel) ;
        }
        
        $qb->andWhere('l.dateRetour IS NULL')
           ->orderBy('l.dateLocation');
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Retourne toutes les locations terminées :
     *      si $courriel  = null : de tous le monde
     *      si $courriel != null : de la personne possedant ce courriel
     * 
     * @param string $courriel
     * @return array<Location>
     */
    public function getAllLocationTerminee($courriel=null) {        
        $qb = $this ->createQueryBuilder('l')
                    ->select('l') ;
        
        if ($courriel!=null) {
            $qb->join("l.personne", "p")
               ->where("p.courriel = :courriel")
               ->setParameter("courriel", $courriel) ;
        }
        
        $qb->andWhere('l.dateRetour IS NOT NULL')
           ->orderBy('l.dateLocation');
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Retourne les films les plus loués, et le nombre de location pour chaque 
     * 
     * @param integer $maxResult : Le nombre max de film à retourner
     * @return array<Film, nbrLocation>
     */
    public function getFilmsLesPlusLoues($maxResult=8) {
        $dql = 'SELECT f, COUNT(loc) AS nbLoc 
                FROM Entities\Film f
                JOIN f.listeMedia m
                JOIN m.listeLocation loc
                GROUP BY f
                ORDER BY nbLoc DESC, f.nom ASC' ;
        
        return $this->_em->createQuery($dql)
                         ->setMaxResults($maxResult)
                         ->getResult(Query::HYDRATE_ARRAY);
    }
    
    /**
     * Créer une location en BDD
     * 
     * @param integer $idMedia : L'identifiant du média
     * @param type $idPersonne : L'identifiant de la personne
     * @throws \Exception : Probleme avec les paramètres en entrée
     *                      Probleme avec la BDD
     */
    public function creer($idMedia, $idPersonne) {
        if (!Util::estValide($idMedia, true))  { throw new \Exception("La variable idMedia est incorrecte"); }
        if (!Util::estValide($idPersonne, true)) { throw new \Exception("La variable idClient est incorrecte"); }
        
        $media = $this->_em->find("Entities\Media", $idMedia);
        if (!$media) { throw new \Exception("Aucun media en base avec l'ID " . $idMedia); }
        
        $client = $this->_em->find("Entities\Personne", $idPersonne);
        if (!$client) { throw new \Exception("Aucun client en base avec l'ID " . $idPersonne); }
        
        $libre = true ;
        if (count($media->getListeLocation()) > 0) {
            foreach ($media->getListeLocation() as $location) {
                if ($location->getDateRetour()==null) {
                    $libre = false ;
                }
            }
        }
        
        if ($libre) {
            try {
                $location = new Location();
                $location->setMedia($media) ;
                $location->setPersonne($client);
                $location->setDateLocation(new \DateTime()) ;
                $this->_em->persist($location);
                $this->_em->flush();
            } catch (\Exception $ex) {
                throw new \Exception($ex->getMessage());
            }
        } else {
            throw new \Exception("Le média n'est plus libre !");
        }
    }
    
    /**
     * Mets fin à une une location (On renseigne la date de retour)
     * 
     * @param integer $idLocation : Identifiant de la location
     * @throws \Exception : Probleme avec les paramètres en entrée
     *                      Probleme avec la BDD
     */
    public function retourner($idLocation) {
        if (!Util::estValide($idLocation, true))  { throw new \Exception("La variable idLocation est incorrecte"); }
        
        $location = $this->_em->find("Entities\Location", $idLocation);
        if (!$location) { throw new \Exception("Aucune location en base avec l'ID " . $idLocation); }
        
        try {
            $location->setDateRetour(new \DateTime()) ;
            $this->_em->flush();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
    
}

<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Entities\Media;
use Util ;

/**
 * Logique métier des médias
 * @author Mignonat Laurent
 */
class MediaRepository extends EntityRepository {

    /**
     * Renvoie l'entity Media correspondant a l'identifiant $idMedia
     * 
     * @param integer $idMedia : Identifiant du media
     * @return <Media>
     * @throws \Exception : Probleme avec l'identifiant en entrée
     *                      Probleme avec la BDD
     */
    public function getMedia($idMedia) {
        if (!Util::estValide($idMedia, true)) {
            throw new \Exception("La variable media est vide");
        }
        return $this->_em->find("Entities\Media", $idMedia);
    }
    
    /**
     * Retourne tous les médias associés à un film donné
     * 
     * @param integer $idFilm : Identifiant du film
     * @return array<Media>
     * @throws \Exception : Probleme avec l'identifiant en entrée
     *                      Probleme avec la BDD
     */
    public function getMedias($idFilm) { 
        if (!Util::estValide($idFilm, true)) {
            throw new \Exception("La variable idFilm est incorrect");
        }
        
        $qb = $this ->createQueryBuilder('m')
                    ->select('m')
                    ->where('m.film = :film')
                    ->orderBy('m.type')
                    ->setParameter('film', $idFilm);
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Retourne tous les médias d'un film donné, qui possede le statut actif
     * 
     * @param integer $idFilm : Identifiant du film
     * @return array<Media>
     * @throws \Exception : Probleme avec l'identifiant en entrée
     *                      Probleme avec la BDD
     */
    public function getMediasActif($idFilm) {        
        if (!Util::estValide($idFilm, true)) {
            throw new \Exception("La variable idFilm est incorrecte");
        }
        
        $qb = $this ->createQueryBuilder('m')
                    ->select('m')
                    ->where('m.film = :film')
                    ->andWhere('m.estActif = 1')
                    ->orderBy('m.type')
                    ->setParameter('film', $idFilm);
        return $qb->getQuery()->getResult();
    }

    /**
     * Retourne tous les médias disponibles (actif et non loué), pour un film donné
     * 
     * @param integer $idFilm : Identifiant du film
     * @return array<Media>
     * @throws \Exception : Probleme avec l'identifiant en entrée
     *                      Probleme avec la BDD
     */
    public function getMediasDisponible($idFilm) {        
        if (!Util::estValide($idFilm, true)) {
            throw new \Exception("La variable idFilm est incorrecte");
        }
        
        //Celle requete m'as donné un peu de fil à retordre ...
        $dql = 'SELECT m1
                FROM Entities\Media m1
                JOIN m1.film f WITH f = :film
                LEFT JOIN m1.listeLocation loc1 
                WHERE m1.estActif = 1
                AND NOT EXISTS (
                    SELECT loc2 
                    FROM Entities\Location loc2
                    JOIN loc2.media m2
                    WHERE m2 = m1
                    AND loc2.dateRetour IS NOT NULL
                )' ;
        
        return $this->_em->createQuery($dql)
                         ->setParameter("film", $idFilm)
                         ->getResult();
    }
    
    /**
     * Retourne tous les médias en BDD
     * 
     * @return array<Media>
     */
    public function getAllMedia() {
        $qb = $this ->createQueryBuilder('m')
                    ->select('m') ;
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Ajoute un média en BDD, pour un film donné.
     * 
     * @param type $idFilm : Identifiant du film
     * @param type $typeMedia : Type de média : BLURAY ou DVD
     * @return <Media> : L'occurence créé
     * @throws \Exception : Probleme avec les paramètres en entrées
     *                      Probleme avec la base de données
     */
    public function ajouter($idFilm, $typeMedia) {
        if (!Util::estValide($idFilm, true)) {
            throw new \Exception("La variable idFilm est incorrecte !");
        } 
        
        $film = $this->_em->find("Entities\Film", $idFilm);
        if (!$film) {
            throw new \Exception("Aucun film en base avec l'identifiant " . $idFilm);
        }
        
        switch ($typeMedia) {
            case Media::BLURAY ; break ;
            case Media::DVD    ; break ;
            default : 
                throw new \Exception("Le type de media renseigné est incorrect : : '".$typeMedia."' !" ) ;
        }
        
        try {
            $media = new Media() ;
            $media->setFilm($film) ;
            $media->setNumero($this->getNextNumero()) ;
            $media->setEstActif(true) ;
            $media->setType($typeMedia);
            $this->_em->persist($media) ;
            $this->_em->flush() ;
            $this->_em->refresh($media) ;
            return $media ;
        } catch (Exception $ex) {
            throw new \Exception("Erreur : ".$ex->getMessage() ) ;
        }
    }
    
    /**
     * Modifie l'état d'un média : Si actif, le désactive, et vice-versa
     * 
     * @param integer $idMedia : Identifiant du média
     * @return <Media> l'occurence modifié du média
     * @throws \Exception : Probleme avec le paramètre en entrée
     *                      Probleme avec la base de données
     */
    public function modifier($idMedia) {
        if (!Util::estValide($idMedia, true)) { throw new \Exception("La variable idMedia est vide"); }
        
        try {
            $media = $this->_em->find("Entities\Media", $idMedia);
            if (!$media) {
                throw new \Exception("Aucun média en base avec l'ID " . $idMedia);
            }
            $media->setEstActif(!$media->getEstActif());
            $this->_em->flush();
            return $media ;
        } catch (\Exception $ex) {
            throw new \Exception("Erreur durant le changement d'état du média : " . $ex->getMessage());
        }
    }
    
    /**
     * Donne le prochain numéro de média disponible.
     * 
     * @return int : Le prochain numéro de média
     */
    private function getNextNumero() {
        if (count($this->getAllMedia())>0) {
            $qb = $this ->createQueryBuilder('m')
                    ->select('max(m.numero) as numeroMax') ;
            $result = $qb->getQuery()->getResult();

            if (count($result)>0) {
                return ($result[0]['numeroMax'] + 1) ;
            }
        } else {
            return 1 ;
        }
    }
}

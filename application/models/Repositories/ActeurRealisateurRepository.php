<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Entities\Location;

/**
 * Logique métier des ActeursRealisateurs
 * Volontairement non terminé car hors-sujet
 * 
 * @author Mignonat Laurent
 */
class ActeurRealisateurRepository extends EntityRepository {

    /**
     * Retourne toutes les occurences d'ActeurRealisateur
     * @return array<ActeurRealisateur>
     */
    public function getActeursRealisateurs() {
        $qb = $this->createQueryBuilder('ar');
        $qb->select('ar');
        return $qb->getQuery()->getResult();
    }

    /**
     * Retourne l'entité correspondant à l'id $idActeurRealisateur
     * @param integer $idActeurRealisateur : Identifiant de l'ActeurRealisateur
     * @return <ActeurRealisateur>
     * @throws \Exception : Aucun ActeurRealisateur pour l'identifiant
     */
    public function get($idActeurRealisateur) {
        if (is_object($idActeurRealisateur)) {
            $idActeurRealisateur = $idActeurRealisateur->getId();
        } else {
            if (!is_numeric($idActeurRealisateur)) {
                throw new \Exception("La variable idActeurRealisateur n'est pas un entier !") ;
            }
        }
        return $this->_em->find("Entities\ActeurRealisateur", $idActeurRealisateur);
    }
}

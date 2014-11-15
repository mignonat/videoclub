<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Entities\Film;
use Util ;

/**
 * Logique métier des Films
 * @author Mignonat Laurent
 */
class FilmRepository extends EntityRepository {

    /**
     * Retourne l'entity Film correspondant a l'id $idFilm
     * @param integer $idFilm
     * @return <Film>
     */
    public function getFilm($idFilm) {
        return $this->_em->find("Entities\Film", $idFilm);
    }    
    
    /**
     * Retourne tous les films présent en base
     * @return array<Film>
     */
    public function getAllFilm() {
        $qb = $this ->createQueryBuilder('f')
                    ->select('f')
                    ->orderBy('f.nom') ;
        return $qb->getQuery()->getResult();
    }

    /**
     * Créer un nouveau film en BDD.
     * 
     * @param string $nom
     * @param string $resume
     * @param string $dateFilm
     * @param integer $realisateur
     * @param integer $acteur1
     * @param integer $acteur2
     * @param integer $acteur3
     * @throws \Exception : Si probleme avec parametres en entrée
     *                      Si erreur durant l'enregistrement en BDD
     */
    public function creer($nom, $resume, $dateFilm, $realisateur, $acteur1, $acteur2 = null, $acteur3 = null) {
        if (!Util::estValide($nom))               { throw new \Exception("La variable nom est vide"); }
        if (!Util::estValide($dateFilm))          { throw new \Exception("La variable dateFilm est vide"); }
        if (!Util::estValide($realisateur, true)) { throw new \Exception("La variable realisateur est incorrecte"); }
        if (!Util::estValide($acteur1, true))     { throw new \Exception("La variable acteur1 est incorrecte"); }

        if (!is_object($dateFilm)) {
            $dateFilm = \DateTime::createFromFormat('d/m/Y', $dateFilm);
        }
        
        if ($dateFilm == null) { throw new \Exception("La variable 'dateFilm' ne peut pas être convertie en DateTime"); }

        $realisateur = $this->_em->getRepository("Entities\ActeurRealisateur")->get($realisateur);
        if ($realisateur == null) { throw new \Exception("La variable 'realisateur' n'existe pas !"); }
        
        $acteur1 = $this->_em->getRepository("Entities\ActeurRealisateur")->get($acteur1);
        if ($acteur1 == null) { throw new \Exception("La variable 'acteur1' n'existe pas !"); }

        if (Util::estValide($acteur2, true)) {
            $acteur2 = $this->_em->getRepository("Entities\ActeurRealisateur")->get($acteur2);
            if ($acteur2 == null) { throw new \Exception("La variable 'acteur2' renseignée est introuvable en base !"); }
        } else {
            $acteur2 = null ;
        }

        if (Util::estValide($acteur3, true)) {
            $acteur3 = $this->_em->getRepository("Entities\ActeurRealisateur")->get($acteur3);
            if ($acteur3 == null) { throw new \Exception("La variable 'acteur3' renseignée est introuvable en base !"); }
        } else {
            $acteur3 = null ;
        }

        try {
            $film = new Film();
            $film->setNom($nom);
            $film->setDateFilm($dateFilm);
            $film->setDateCreation(new \DateTime());
            $film->setResume($resume);
            $film->setRealisateur($realisateur);
            $film->setActeur1($acteur1);
            if ($acteur2!=null) { $film->setActeur2($acteur2); }
            if ($acteur3!=null) { $film->setActeur3($acteur3); }
            $this->_em->persist($film);
            $this->_em->flush();
        } catch (\Exception $ex) {
            throw new \Exception("Erreur durant la création du film : " . $ex->getMessage());
        }
    }

    /**
     * Modifie les informations d'un film en BDD
     * 
     * @param integer $idFilm
     * @param string $nom
     * @param string $resume
     * @param string $dateFilm
     * @param integer $realisateur
     * @param integer $acteur1
     * @param integer $acteur2
     * @param integer $acteur3
     * @throws \Exception : Si probleme avec parametres en entrée
     *                      Si aucun film avec l'identifiant $idFilm en base de donnée
     *                      Si erreur durant l'enregistrement en BDD
     */
    public function modifier($idFilm, $nom, $resume, $dateFilm, $realisateur, $acteur1, $acteur2 = null, $acteur3 = null) {
        if (!Util::estValide($nom))               { throw new \Exception("La variable nom est vide"); }
        if (!Util::estValide($dateFilm))          { throw new \Exception("La variable dateFilm est vide"); }
        if (!Util::estValide($realisateur, true)) { throw new \Exception("La variable realisateur est incorrecte"); }
        if (!Util::estValide($acteur1, true))     { throw new \Exception("La variable acteur1 est incorrecte"); }

        if (!is_object($dateFilm)) {
            $dateFilm = \DateTime::createFromFormat('d/m/Y', $dateFilm);
        }
        
        if ($dateFilm == null) { throw new \Exception("La variable 'dateFilm' ne peut pas être convertie en DateTime"); }
        
        $realisateur = $this->_em->getRepository("Entities\ActeurRealisateur")->get($realisateur);
        if ($realisateur == null) { throw new \Exception("La variable 'realisateur' n'existe pas !"); }
        
        $acteur1 = $this->_em->getRepository("Entities\ActeurRealisateur")->get($acteur1);
        if ($acteur1 == null)     { throw new \Exception("La variable 'acteur1' n'existe pas !"); }

        if (Util::estValide($acteur2, true)) {
            $acteur2 = $this->_em->getRepository("Entities\ActeurRealisateur")->get($acteur2);
            if ($acteur2 == null) { throw new \Exception("La variable 'acteur2' renseignée est introuvable en base !"); }
        } else {
            $acteur2 = null ;
        }

        if (Util::estValide($acteur3, true)) {
            $acteur3 = $this->_em->getRepository("Entities\ActeurRealisateur")->get($acteur3);
            if ($acteur3 == null) { throw new \Exception("La variable 'acteur3' renseignée est introuvable en base !"); }
        } else {
            $acteur3 = null ;
        }

        try {
            $film = $this->_em->find("Entities\Film", $idFilm);
            
            if (!$film) { throw new \Exception("Aucun film en base avec l'ID " . $idFilm); }
            
            $film->setNom($nom);
            $film->setDateFilm($dateFilm);
            $film->setResume($resume);
            $film->setRealisateur($realisateur);
            $film->setActeur1($acteur1);
            $film->setActeur2($acteur2);
            $film->setActeur3($acteur3);
            $this->_em->flush();
        } catch (\Exception $ex) {
            throw new \Exception("Erreur durant la mise à jour du film : " . $ex->getMessage());
        }
    }

    /**
     * Retourne les derniers films ajoutés en base de données
     * 
     * @param integer $maxResult : Nombre max de film à retourner
     * @return array<Film>
     * @throws \Exception : Si probleme de connexion avec la base de données
     */
    public function getNouveautes($maxResult=8) {
        $qb = $this ->createQueryBuilder('f')
                    ->select('f')
                    ->orderBy("f.dateCreation", "DESC")
                    ->setMaxResults($maxResult);
        return $qb->getQuery()->getResult() ;
    }
    
    /**
     * Recherche de film en base suivant le nom
     * @param string $nom : Le nom des films doivent contenir cette chaine
     * @return array<Film>
     * @throws \Exception : Si probleme de connexion avec la base de données
     */
    public function rechercher($nom) {
        if (!Util::estValide($nom)) { throw new \Exception("La variable nom est vide"); }
                
        $qb = $this ->createQueryBuilder('f')
                    ->select('f')
                    ->where("f.nom LIKE :nom")
                    ->setParameter("nom", "%".$nom."%")
                    ->orderBy("f.nom") ;
        
        return $qb->getQuery()->getResult() ;
    }
    
    /**
     * Désactive tous les médias d'un film donné, pour le rendre indisponible
     * 
     * @param integer $idFilm : Identifiant du film
     * @throws \Exception : Si probleme lors de l'enregistrement en BDD
     */
    public function desactiver($idFilm) {
        if (!Util::estValide($idFilm, true)) { throw new \Exception("La variable idFilm est incorrecte"); }
        
        try {
            $listeMedia = $this->_em->getRepository("Entities\Media")->getMedias($idFilm) ;
            foreach ($listeMedia as $media) {
                $media->setEstActif(false) ;
            }
            $this->_em->flush();
        } catch (\Exception $ex) {
            throw new \Exception("Erreur durant la désactivation du film : " . $ex->getMessage());
        }
    }
}

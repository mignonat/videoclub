<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
 
    protected function _initDoctrine() 
    {
        $options = $this->getOptions();
        // On inclus l'autoloader Doctrine
        $doctrinePath = $options['includePaths']['library'];
        require_once $doctrinePath . '/Doctrine/Common/ClassLoader.php';
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
        $classLoader->register();

        // Autoloader pour les Entities
        $entitiesLoader = new \Doctrine\Common\ClassLoader('Entities', realpath($options['doctrine']['path'][0]['models']));
        $entitiesLoader->register();

       // Autoloader pour les repositories
        $repoLoader = new \Doctrine\Common\ClassLoader('Repositories', realpath($options['doctrine']['path'][0]['models']));
        $repoLoader->register();

        $cache = new \Doctrine\Common\Cache\ArrayCache();

        $config = new \Doctrine\ORM\Configuration();
        $config->setMetadataCacheImpl($cache);

        // Utiliser les annotations pour la description du modèle
        $driverImpl = $config->newDefaultAnnotationDriver($options['doctrine']['path'][0]['models']);
        $config->setMetadataDriverImpl($driverImpl);        
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir($options['doctrine']['path'][0]['models']. '/Proxies');
        $config->setProxyNamespace('Proxies');

        // Ne générer les class proxy qu'en developpement
        $config->setAutoGenerateProxyClasses(APPLICATION_ENV == "development");

        // Connexion à la BDD (valeurs définies dans le fichier application.ini)
        $connectionOptions = array(
           'dbname' => $options['doctrine']['conn'][0]['dbname'],
           'user' => $options['doctrine']['conn'][0]['username'],
           'password' => $options['doctrine']['conn'][0]['password'],
           'host' => $options['doctrine']['conn'][0]['host'],
           'driver' => $options['doctrine']['conn'][0]['driver'],
           'charset' => $options['doctrine']['conn'][0]['charset'],
           'driverOptions' => array( 1002=>'SET NAMES utf8' )
       );

        $entityManager = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
        Zend_Registry::set('em', $entityManager);
        return $entityManager; 
    }

}


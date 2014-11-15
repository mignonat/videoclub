<?php
require_once __DIR__ . '/../../library/Doctrine/Common/ClassLoader.php';
chdir(__DIR__ . '/../../library/') ; //On se place dans le repertoire qui contient la librairie Doctrine
$model_path = __DIR__.'/../models' ; //Chemin relatif vers le repertoire qui contient le model

$classLoader = new \Doctrine\Common\ClassLoader('Entities', $model_path);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', $model_path);
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$config->setProxyDir($model_path.'/Proxies');
$config->setProxyNamespace('Proxies');
$driverImpl = $config->newDefaultAnnotationDriver($model_path.'/Entities');
$config->setMetadataDriverImpl($driverImpl);

$connectionOptions = array(
    'driver'	=> 'pdo_mysql',
    'user'	=> 'root',
    'password'	=> 'root',
    'dbname'    => 'mydb',
    'host'      => '127.0.0.1',
    'charset'	=> 'utf8'
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));

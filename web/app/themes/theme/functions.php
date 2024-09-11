<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';

use App\Bootstrap;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

$container = new ContainerBuilder();

$loader = new YamlFileLoader($container, new FileLocator(__DIR__));
$loader->load('services.yml');

$container->compile();

add_action('after_setup_theme', function () use ($container) {
    $container->get(Bootstrap::class);
});

<?php

namespace App\Controllers;

use Symfony\Bridge\Twig\Extension\DumpExtension;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Twig\Extra\Html\HtmlExtension;

class TwigController
{
    public function __construct()
    {
        $this->setupDumpExtension();
        $this->setupHTMLExtra();
        $this->addWrapperAttributesAlias();
    }

    private function setupDumpExtension()
    {
        add_filter('timber/twig', function (\Twig\Environment $twig) {
            $cloner = new VarCloner();
            $dumper = new HtmlDumper();

            $twig->addExtension(new DumpExtension($cloner, $dumper));

            return $twig;
        });
    }

    private function setupHTMLExtra()
    {
        add_filter('timber/twig', function (\Twig\Environment $twig) {
            $twig->addExtension(new HtmlExtension());

            return $twig;
        });
    }

    private function addWrapperAttributesAlias()
    {
        add_filter('timber/twig', function (\Twig\Environment $twig) {
            $twig->addFunction(new \Twig\TwigFunction('wrapper_attributes', function ($args = []) {
                return get_block_wrapper_attributes($args);
            }));

            return $twig;
        });
    }
}

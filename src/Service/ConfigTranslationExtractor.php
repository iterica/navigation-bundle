<?php

namespace Iterica\NavigationBundle\Service;

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Model\MessageCatalogue;
use JMS\TranslationBundle\Translation\Extractor\FileVisitorInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Parser;

class ConfigTranslationExtractor implements FileVisitorInterface
{
    public function visitFile(\SplFileInfo $file, MessageCatalogue $catalogue)
    {
        if ('navigation.yml' !== substr($file, -14)) {
            return;
        }

        $yaml = new Parser();
        $config = $yaml->parse(file_get_contents($file));

        if (!isset($config['navigation'])) {
            return;
        }

        foreach ($config['navigation'] as $scope=>$nav) {
            $this->iterateRecursive($nav, $catalogue);
        }

        return $catalogue;
    }

    public function visitPhpFile(\SplFileInfo $file, MessageCatalogue $catalogue, array $ast)
    {
    }

    public function visitTwigFile(\SplFileInfo $file, MessageCatalogue $catalogue, \Twig_Node $node)
    {
    }

    protected function iterateRecursive($tree, MessageCatalogue $catalogue, $path = [])
    {
        foreach ($tree as $id => $item) {
            $currentPath = $path;
            array_push($currentPath, $id);

            if (isset($item['label'])) {
                $message = new Message($item['label'], 'navigation');
                $message->setDesc($item['label']);

                if (!$catalogue->has($message)) {
                    $catalogue->add($message);
                }
            }

            if (isset($item['children'])) {
                $this->iterateRecursive($item['children'], $catalogue, $currentPath);
            }
        }
    }
}

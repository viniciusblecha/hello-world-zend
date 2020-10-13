<?php

namespace Blog\Factory;


use Interop\Container\ContainerInterface;
use Blog\Model\PostCommand;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PostCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PostCommand($container->get(AdapterInterface::class));
    }
}

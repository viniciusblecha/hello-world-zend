<?php

namespace Blog\Factory;

use Blog\Model\Post;
use Interop\Container\ContainerInterface;
use Blog\Model\PostRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;

class PostRepositoryFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requesteName, array $options = null)
    {
        return new PostRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator,
            new Post('', '')
        );
    }
}

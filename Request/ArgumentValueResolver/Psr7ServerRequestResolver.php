<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sensio\Bundle\FrameworkExtraBundle\Request\ArgumentValueResolver;

use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Injects the RequestInterface, MessageInterface or ServerRequestInterface when requested.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
final class Psr7ServerRequestResolver implements ArgumentValueResolverInterface
{
    private static $supportedTypes = array(
        'Psr\Http\Message\ServerRequestInterface' => true,
        'Psr\Http\Message\RequestInterface' => true,
        'Psr\Http\Message\MessageInterface' => true,
    );

    private $httpMessageFactory;

    public function __construct(HttpMessageFactoryInterface $httpMessageFactory)
    {
        $this->httpMessageFactory = $httpMessageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return isset(self::$supportedTypes[$argument->getType()]);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->httpMessageFactory->createRequest($request);
    }
}

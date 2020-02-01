<?php

namespace Experiment\Demo\Http;

use Neos\Flow\Http\Component\ComponentChain;
use Neos\Flow\Http\Component\ComponentContext;
use Neos\Flow\Http\Component\ComponentInterface;

/**
 * !!! be aware that this component enables CORS for the entire application !!!
 *
 * add this config to the Settings.yaml of your package
 *
 * Neos:
 *   Flow:
 *     http:
 *       chain:
 *         'preprocess':
 *           chain:
 *             'allowCors':
 *               component: 'Experiment\Demo\Http\CorsComponent'
 *               componentOptions:
 *                 origin: '%env:CORS_ORIGIN%'
 *
 * Sets the CORS headers to allow foreign origins
 */
class CorsComponent implements ComponentInterface
{

    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options The component options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * @param ComponentContext $componentContext
     * @return void
     */
    public function handle(ComponentContext $componentContext)
    {
        $origin = $this->options['origin'] ?: '*';
        $request = $componentContext->getHttpRequest();
        $response = $componentContext->getHttpResponse();

        $response->withHeader('Access-Control-Allow-Origin', $origin);
        $response->withHeader('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
        $response->withHeader('Access-Control-Allow-Headers', 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range');
        if ($request->getMethod() === 'OPTIONS') {
            $response->withHeader('Access-Control-Max-Age', '1728000');
            $response->withStatus(204 /* no content */);
            $componentContext->setParameter(ComponentChain::class, 'cancel', true);
        } else {
            $response->withHeader('Access-Control-Expose-Headers', 'DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range');
        }
    }
}
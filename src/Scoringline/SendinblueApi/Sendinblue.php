<?php

namespace Scoringline\SendinblueApi;

use Nekland\BaseApi\ApiFactory;
use Nekland\BaseApi\Cache\CacheFactory;
use Nekland\BaseApi\Http\Auth\AuthFactory;
use Nekland\BaseApi\Http\HttpClientFactory;
use Nekland\BaseApi\Transformer\TransformerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Sendinblue
 *
 * @method \Scoringline\SendinblueApi\Api\Sms getSmsApi()
 */
class Sendinblue extends ApiFactory
{
    /**
     * @var array
     */
    private $options = [
        'base_url'   => 'https://api.sendinblue.com/v2.0',
        'user_agent' => 'php-sendinblue-api (https://github.com/Scoringline/SendinblueApi)'
    ];

    public function __construct(
        array $options = [],
        HttpClientFactory $httpClientFactory = null,
        EventDispatcher $dispatcher = null,
        TransformerInterface $transformer = null,
        AuthFactory $authFactory = null,
        CacheFactory $cacheFactory = null
    ) {
        $this->options = array_merge($this->options, $options);
        parent::__construct(new HttpClientFactory($this->options), $dispatcher, $transformer, $authFactory, $cacheFactory);

        $this->getAuthFactory()->addNamespace('Scoringline\Sendinblue\Http\Auth');
    }

    /**
     * {inheritdoc}
     */
    protected function getApiNamespaces()
    {
        return ['Scoringline\SendinblueApi\Api'];
    }
}

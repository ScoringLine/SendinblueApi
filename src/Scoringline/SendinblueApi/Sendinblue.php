<?php

/**
 * This file is a part of scoringline sendinblue api package
 *
 * (c) Scoringline <m.veber@scoringline.com>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

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
 * @method \Scoringline\SendinblueApi\Api\Email getEmailApi()
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
        parent::__construct(
            $httpClientFactory === null ? new HttpClientFactory($this->options) : $httpClientFactory,
            $dispatcher,
            $transformer,
            $authFactory,
            $cacheFactory
        );

        $this->getAuthFactory()->addNamespace('Scoringline\SendinblueApi\Http\Auth');
    }

    /**
     * {inheritdoc}
     */
    protected function getApiNamespaces()
    {
        return ['Scoringline\SendinblueApi\Api'];
    }
}

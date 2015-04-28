<?php

/**
 * This file is a part of scoringline sendinblue api package
 *
 * (c) Scoringline <m.veber@scoringline.com>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace Scoringline\SendinblueApi\Http\Auth;

use Nekland\BaseApi\Exception\MissingOptionException;
use Nekland\BaseApi\Http\Auth\AuthStrategyInterface;
use Nekland\BaseApi\Http\Event\RequestEvent;

class ApiKey implements AuthStrategyInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param array $options
     * @return self
     * @throws MissingOptionException
     */
    public function setOptions(array $options)
    {
        if (empty($options['key'])) {
            throw new MissingOptionException(
                sprintf('You have to define the "key" option in order to make %s auth work.', get_class($this))
            );
        }

        $this->options = $options;
    }

    /**
     * @param RequestEvent $request
     */
    public function auth(RequestEvent $request)
    {
        $request->getRequest()->setHeader('api-key', $this->options['key']);
    }
}

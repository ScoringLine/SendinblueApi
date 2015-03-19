<?php

namespace spec\Scoringline\SendinblueApi\Http\Auth;

use Nekland\BaseApi\Http\Event\RequestEvent;
use Nekland\BaseApi\Http\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiKeySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Scoringline\SendinblueApi\Http\Auth\ApiKey');
        $this->shouldHaveType('Nekland\BaseApi\Http\Auth\AuthStrategyInterface');
    }

    function it_should_throw_error_when_missing_option()
    {
        $this
            ->shouldThrow('\Nekland\BaseApi\Exception\MissingOptionException')
            ->duringSetOptions([])
        ;
    }

    function it_should_authenticate_request(RequestEvent $requestEvent, Request $request)
    {
        $requestEvent->getRequest()->willReturn($request);
        $request->setHeader('api-key', 'foo')->shouldBeCalled();

        $this->setOptions(['key' => 'foo']);
        $this->auth($requestEvent);
    }
}

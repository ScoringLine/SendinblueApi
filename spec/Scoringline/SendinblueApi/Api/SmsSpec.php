<?php

namespace spec\Scoringline\SendinblueApi\Api;

use Nekland\BaseApi\Http\AbstractHttpClient;
use Nekland\BaseApi\Transformer\TransformerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SmsSpec extends ObjectBehavior
{
    function let(AbstractHttpClient $client, TransformerInterface $transformer)
    {
        $this->beConstructedWith($client, $transformer);

        $client->send(Argument::any())->willReturn('res');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Scoringline\SendinblueApi\Api\Sms');
        $this->shouldHaveType('Nekland\BaseApi\Api\AbstractApi');
    }

    function it_should_send_sms(TransformerInterface $transformer)
    {
        $res = [
            'data' => [
                'status' => 'OK'
            ]
        ];

        $transformer->transform('res')->willReturn($res);

        $this->sendSms('+331234567890', 'Scoringline', 'Some text')->shouldReturn($res);
    }

    function it_should_throw_exception_when_send_sms_failed(AbstractHttpClient $client, TransformerInterface $transformer)
    {
        $res = [
            'data' => [
                'status' => 'KO',
                'description' => 'Failed'
            ]
        ];

        $transformer->transform('res')->willReturn($res);

        $this
            ->shouldThrow('\RuntimeException')
            ->duringSendSms('+331234567890', 'Scoringline', 'Some text')
        ;
    }

    function it_should_create_campaign(TransformerInterface $transformer)
    {
        $res = [
            'code' => 'success'
        ];

        $transformer->transform('res')->willReturn($res);

        $this->createCampaign('Join us')->shouldReturn($res);
    }

    function it_should_throw_error_when_api_error_while_creating_campaign(TransformerInterface $transformer)
    {
        $res = [
            'code' => 'fail',
            'message' => 'failed'
        ];

        $transformer->transform('res')->willReturn($res);

        $this
            ->shouldThrow('\RuntimeException')
            ->duringCreateCampaign('Join us')
        ;
    }

    function it_should_update_campaign(TransformerInterface $transformer)
    {
        $res = [
            'code' => 'success'
        ];

        $transformer->transform('res')->willReturn($res);

        $this->updateCampaign(12, 'Join us')->shouldReturn($res);
    }

    function it_should_throw_error_when_api_fails_while_updating_campaign(TransformerInterface $transformer)
    {
        $res = [
            'code' => 'fails',
            'message' => 'something wrong'
        ];

        $transformer->transform('res')->willReturn($res);

        $this
            ->shouldThrow('\RuntimeException')
            ->duringUpdateCampaign(12, 'Join us')
        ;
    }

    function it_should_send_campaign(TransformerInterface $transformer)
    {
        $res = [
            'code' => 'success',
            'data' => [
                'status' => 'OK'
            ]
        ];

        $transformer->transform('res')->willReturn($res);

        $this->sendCampaign(12, '+33685965788')->shouldReturn($res);
    }

    function it_should_throw_error_if_api_fails_while_send_campaign(TransformerInterface $transformer)
    {
        $res = [
            'code' => 'fails',
            'message' => 'something wrong',
            'data' => [
                'status' => 'KO'
            ]
        ];

        $transformer->transform('res')->willReturn($res);

        $this
            ->shouldThrow('\RuntimeException')
            ->duringSendCampaign(12, '+33685965788')
        ;
    }

    function it_should_sanitize_phone_number()
    {
        $this->sanitizePhoneNumber('+1 234-324-6532')->shouldReturn('12343246532');
        $this->sanitizePhoneNumber('+33(0)62453-27-32')->shouldReturn('330624532732');
    }
}

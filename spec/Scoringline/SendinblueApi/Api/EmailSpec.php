<?php

namespace spec\Scoringline\SendinblueApi\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmailSpec extends ObjectBehavior
{
    /**
     * @param \Nekland\BaseApi\Http\AbstractHttpClient            $client
     * @param \Nekland\BaseApi\Transformer\TransformerInterface   $transformer
     */
    function let($client, $transformer)
    {
        $this->beConstructedWith($client, $transformer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Scoringline\SendinblueApi\Api\Email');
        $this->shouldHaveType('Nekland\BaseApi\Api\AbstractApi');
    }

    /**
     * @param \Nekland\BaseApi\Http\AbstractHttpClient            $client
     * @param \Nekland\BaseApi\Transformer\TransformerInterface   $transformer
     */
    function it_should_send_simple_email($client, $transformer)
    {
        $result = [
            'code' => 'success',
            'message' => 'Email sent successfully',
            'data' => []
        ];

        $resultString = json_encode($result);
        $client->send(Argument::any())->willReturn($resultString);
        $transformer->transform($resultString)->willReturn($result);

        $this->sendSimpleEmail(
            ['from@example.com', 'from name!'],
            ['to@example.com' => 'to name!'],
            'Invitation',
            '<h1>HTML</h1> content'
        )->shouldReturn($result);
    }


    /**
     * @param \Nekland\BaseApi\Http\AbstractHttpClient            $client
     * @param \Nekland\BaseApi\Transformer\TransformerInterface   $transformer
     * @param \Scoringline\SendinblueApi\Model\Email              $email
     */
    function it_should_send_advance_email_with_attachment_and_inline_image_when_exists_files($client, $transformer, $email)
    {
        $result = [
            'code' => 'success',
            'message' => 'Email sent successfully',
            'data' => []
        ];

        $data = [
            'to' => ['to@example.com' => 'to name']
        ];

        $resultString = json_encode($result);
        $client->send(Argument::any())->willReturn($resultString);
        $transformer->transform($resultString)->willReturn($result);

        $email->toArray()->willReturn($data)->shouldBeCalled();

        $this->sendEmail($email)->shouldReturn($result);
    }
}

<?php

namespace spec\Scoringline\SendinblueApi\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Scoringline\SendinblueApi\Model\Email;
use Symfony\Component\HttpFoundation\File\File;

class EmailSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Scoringline\SendinblueApi\Model\Email');
    }

    function it_should_throw_exception_when_file_does_not_exists()
    {
        $this
            ->shouldThrow('\Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException')
            ->duringAddAttachment('none.png')
        ;
    }

    function it_should_build_an_array()
    {
        $this->setTo('hello@scoringline.com', 'scoringline');
        $this->addTo('hello@sendinblue.com', 'sendinblue');

        $this
            ->toArray()
            ->shouldHaveKeyWithValue('to', [
                'hello@scoringline.com' => 'scoringline',
                'hello@sendinblue.com' => 'sendinblue'
            ])
        ;
    }

    function it_should_throw_exception_when_inline_image_is_missing()
    {
        $this->setHtml('<html><body><img src="{inline-image.png}" /></body></html>');
        $this->shouldThrow('\Scoringline\SendinblueApi\Exception\InvalidHtmlException')->duringToArray();
    }

    function it_should_encode_files()
    {
        $file = new File(__DIR__ . '/../../../fixtures/logo.png');
        $this->setAttachments([$file]);
        $this->toArray()->shouldHaveKeyWithValue(
            'attachment',
            ['logo.png' => Email::encodeFileContent($file)]
        );
    }
}

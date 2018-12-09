<?php

/**
 * This file is a part of scoringline sendinblue api package
 *
 * (c) Scoringline <m.veber@scoringline.com>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
namespace Scoringline\SendinblueApi\Model;

use Scoringline\SendinblueApi\Exception\InvalidHtmlException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Email
 *
 * @author Joni Rajput <joni@sendinblue.com>
 * @author Maxime Veber <nek.dev@gmail.com>
 */
class Email
{
    /**
     * @var array
     */
    private $to;

    /**
     * @var array
     */
    private $from;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $html;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $replyTo;

    /**
     * @var array
     */
    private $cc;

    /**
     * @var array
     */
    private $bcc;

    /**
     * @var array
     */
    private $attachments;

    /**
     * @var array
     */
    private $inlineImages;

    /**
     * CONSTRUCTOR
     * @param string $encoding
     */
    public function __construct($encoding = 'utf-8')
    {
        $this->to = [];
        $this->from = [];
        $this->subject = '';
        $this->html = '';
        $this->text = '';
        $this->headers = ["Content-Type" => "text/html; charset=" . $encoding];
        $this->replyTo = [];
        $this->cc = [];
        $this->bcc = [];
        $this->attachments = [];
        $this->inlineImages = [];
    }

    /**
     * @param string $email
     * @param string $name (optional)
     * @return Email
     */
    public function setTo($email, $name = null)
    {
        $name = $this->guessName($name, $email);
        $email = $this->standardizeEmail($email, $name);
        $this->to = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $email
     * @param string $name (optional)
     * @return Email
     */
    public function addTo($email, $name = null)
    {
        $name = $this->guessName($name, $email);
        $email = $this->standardizeEmail($email, $name);
        $this->to = array_merge($this->to, $email);

        return $this;
    }

    /**
     * @param string $email
     * @param string $name (optional)
     * @return Email
     */
    public function setFrom($email, $name = null)
    {
        $name = $this->guessName($name, $email);
        $email = $this->standardizeSingleEmail($email, $name);
        $this->from = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $subject
     * @return Email
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     *
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param array $text
     * @return Email
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * This is an HTML string. You can use images.
     * Use fullpath in the src of your image so the lib can retrieve it.
     *
     * You also can use {image.ext} as src path but you need to set corresponding
     * inline images.
     *
     * @param array $html
     * @return Email
     */
    public function setHtml($html)
    {
        // Replacing real paths (ie /home/images/foo.png)
        // by inline image (ie {foo.png}) for sendinblue
        preg_match_all('/<img[^>]+src="([^">]+)"/', $html, $matches);
        foreach($matches[1] as $srcValue) {
            if (preg_match('/^{.*}$/', $srcValue) !== 1) {
                $file = new File($srcValue);
                $html = str_replace($srcValue, '{' . $file->getFilename() . '}', $html);
                $this->addInlineImage($file, '{' . $file->getFilename() . '}');
            }
        }

        $this->html = $html;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param array $headers
     * @return Email
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array|string $header an array like ['name' => 'value'] or its name
     * @param string       $value
     * @return Email
     */
    public function addHeader($header, $value = null)
    {
        if (!is_array($header)) {
            if ($value !== null) {
                $header = [$header => $value];
            } else {
                throw new \InvalidArgumentException(sprintf('You must give a value to your header'));
            }
        }
        $this->headers = array_merge($this->headers, $header);

        return $this;
    }

    /**
     * @param string $email
     * @param string $name (optional)
     * @return Email
     */
    public function setReplyTo($email, $name = null)
    {
        $name = $this->guessName($name, $email);
        $email = $this->standardizeSingleEmail($email, $name);
        $this->replyTo = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param string $email
     * @param string $name (optional)
     * @return Email
     */
    public function setCc($email, $name = null)
    {
        $name = $this->guessName($name, $email);
        $email = $this->standardizeEmail($email, $name);
        $this->cc = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param string $email
     * @param string $name (optional)
     * @return Email
     */
    public function addCc($email, $name = null)
    {
        $name = $this->guessName($name, $email);
        $email = $this->standardizeEmail($email, $name);
        $this->cc = array_merge($this->cc, $email);

        return $this;
    }

    /**
     * @param string $email
     * @param string $name
     * @return Email
     */
    public function setBcc($email, $name = null)
    {
        $name = $this->guessName($name, $email);
        $email = $this->standardizeEmail($email, $name);
        $this->bcc = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param string $email
     * @param string $name
     * @return Email
     */
    public function addBcc($email, $name = null)
    {
        $name = $this->guessName($name, $email);
        $email = $this->standardizeEmail($email, $name);
        $this->bcc = array_merge($this->bcc, $email);

        return $this;
    }

    /**
     * @param array $attachments
     * @return Email
     */
    public function setAttachments(array $attachments)
    {
        foreach($attachments as $name => $file) {
            if (!is_string($name)) {
                $name = null;
            }
            $this->addAttachment($file, $name);
        }

        return $this;
    }

    /**
     * @param string|File $attachment
     * @param string      $name         (optional)
     * @return array
     */
    public function addAttachment($attachment, $name = null)
    {
        if (!$attachment instanceof File) {
            $attachment = new File($attachment);
        }

        if ($name === null) {
            $name = $attachment->getFilename();
        }

        $this->attachments = array_merge($this->attachments, [$name => $attachment]);

        return $this->attachments;
    }

    /**
     * @return array
     *
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $inlineImages
     * @return Email
     */
    public function setInlineImages(array $inlineImages)
    {
        foreach($inlineImages as $name => $image) {
            $this->addInlineImage($name, $image);
        }

        return $this;
    }

    /**
     * @param string|File $inlineImage
     * @param string      $name
     * @return array
     */
    public function addInlineImage($inlineImage, $name)
    {
        if(!$inlineImage instanceof File) {
            $inlineImage = new File($inlineImage);
        }
        $this->inlineImages = array_merge($this->inlineImages, [$name => $inlineImage]);

        return $this->inlineImages;
    }

    public function toArray()
    {
        $html = $this->getHtml();
        $inlineImages = $this->getInlineImages();
        preg_match_all('/<img[^>]+src="([^">]+)"/', $html, $matches);
        foreach($matches[1] as $srcValue) {
            if (empty($inlineImages[str_replace(['{', '}'], '', $srcValue)])) {
                throw new InvalidHtmlException(
                    sprintf('The image %s does not exists in inline images', $srcValue)
                );
            }
        }

        return [
            'to'           => $this->getTo(),
            'from'         => $this->getFrom(),
            'subject'      => $this->getSubject(),
            'text'         => $this->getText(),
            'html'         => $html,
            'headers'      => $this->getHeaders(),
            'replyTo'      => $this->getReplyTo(),
            'cc'           => $this->getCc(),
            'bcc'          => $this->getBcc(),
            'attachment'   => $this->transformFiles($this->getAttachments()),
            'inline_image' => $this->transformFiles($this->getInlineImages()),
        ];
    }

    /**
     * @return array
     */
    public function getInlineImages()
    {
        return $this->inlineImages;
    }

    /**
     * @param array $items
     * @return array
     */
    private function transformFiles(array $items)
    {
        foreach($items as $key => $file) {
            $items[$key] = Email::encodeFileContent($file);
        }

        return $items;
    }

    /**
     * Find a name from an email if the name is null.
     *
     * @param string|null $name
     * @param string      $email
     * @return mixed
     */
    private function guessName($name, $email)
    {
        if (is_array($email)) {
            return null;
        }
        if ($name !== null) {
            return $name;
        }

        return explode('@', $email)[0];
    }

    /**
     * @param string $email
     * @param string $name
     * @return array
     */
    private function standardizeEmail($email, $name)
    {
        if (is_array($email)) {
            return $email;
        }

        return [$email => $name];
    }

    /**
     * @param string $email
     * @param string $name
     * @return array
     */
    private function standardizeSingleEmail($email, $name)
    {
        if (is_array($email)) {
            return $email;
        }

        return [$email, $name];
    }

    /**
     * Encode file to base_64 nad split into chunks
     * @param File $file
     * @return string
     */
    public static function encodeFileContent(File $file)
    {
        return chunk_split(base64_encode(file_get_contents($file)));
    }
}


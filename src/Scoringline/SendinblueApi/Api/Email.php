<?php

/**
 * This file is a part of scoringline sendinblue api package
 *
 * (c) Scoringline <m.veber@scoringline.com>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
namespace Scoringline\SendinblueApi\Api;

use GuzzleHttp\Exception\RequestException;
use Nekland\BaseApi\Api\AbstractApi;
use Scoringline\SendinblueApi\Exception\EmailSendFailureException;
use Scoringline\SendinblueApi\Model\Email as EmailModel;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class Email
 *
 * @author Joni Rajput <joni@sendinblue.com>
 * @author Maxime Veber <nek.dev@gmail.com>
 */
class Email extends AbstractApi
{
    const API_URL = '/email';

    /**
     * @param array $from
     * @param array $to
     * @param string $subject
     * @param string $content
     * @param array $attachments Ie: ['YourFileName.Extension' => File] (file is a File object of symfony or a string)
     * @param array $extraParams Ie: cc, bcc, replyTo, headers, text
     * @return array
     * @throws EmailSendFailureException
     */
    public function sendSimpleEmail($from, $to, $subject, $content, $attachments = [], $extraParams = [])
    {
        $email = new EmailModel();
        $email
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setHtml($content)
        ;
        if (!empty($attachments)) {
            $email->setAttachments($attachments);
        }
        if (!empty($extraParams)) {
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach($extraParams as $name => $value) {
                $accessor->setValue($email, $name, $value);
            }
        }

        try {
            return $this->post(self::API_URL, json_encode($email->toArray()));
        } catch(RequestException $e) {
            $error = json_decode((string) $e->getResponse()->getBody());
            throw new EmailSendFailureException($error->message);
        }
    }

    /**
     * @param EmailModel $email
     * @return array
     * @throws EmailSendFailureException
     */
    public function sendEmail(EmailModel $email)
    {
        try {
            return $this->post(self::API_URL, json_encode($email->toArray()));
        } catch(RequestException $e) {
            $error = json_decode((string) $e->getResponse()->getBody());
            throw new EmailSendFailureException($error->message);
        }
    }

    /**
     * @param array $param Ie. Associative array for to, from, subject, html etc
     * @return array
     * @throws EmailSendFailureException
     */
    public function sendEmailWithData($param)
    {
        $email = new EmailModel();
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach($param as $name => $value) {
            $accessor->setValue($email, $name, $value);
        }

        try {
           return $this->post(self::API_URL, json_encode($email->toArray()));
        } catch(RequestException $e) {
            $error = json_decode((string) $e->getResponse()->getBody());
            throw new EmailSendFailureException($error->message);
        }
    }
}

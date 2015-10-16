Sendinblue API
==============

[![Build Status](https://travis-ci.org/ScoringLine/SendinblueApi.svg?branch=master)](https://travis-ci.org/ScoringLine/SendinblueApi)

Clean and simple to use lib for use Sendinblue API.

- [x] tested
- [x] stable
- [x] extensible


> For now the only API available is the SMS api, more is coming later, PRs accepted with love.

Installation
------------

**Requirements:**

* PHP 5.4+
* composer

Launch the following command to install:

```bash
$ composer require scoringline/sendinblue-api
```

Authentication
--------------

```php
<?php

require 'vendor/autoload.php';

use Scoringline\SendinblueApi\Sendinblue;

$sendinblue = new Sendinblue();

$sendinblue->useAuthentication('ApiKey', ['key' => 'YourPrivateApiKey']);
```


SMS Api usage
-------------

```php
<?php

require 'vendor/autoload.php';

use Scoringline\SendinblueApi\Sendinblue;

$sendinblue = new Sendinblue();

$sendinblue->getSmsApi()->sendSms('+33600000000', 'Your name', 'The message you want to send');
```

Email Api usage
-------------
```php
<?php

require 'vendor/autoload.php';

use Scoringline\SendinblueApi\Sendinblue;
use Scoringline\SendinblueApi\Model\Email;
use Symfony\Component\HttpFoundation\File\File;

$sendinblue = new Sendinblue();

// Send basic email without model    
$sendinblue
    ->getEmailApi()
    ->sendSimpleEmail(
        ['from@example.com', 'from name!'], 
        ['to@example.com' => 'to name!'], 
        'Subject', 
        '<h1>Html</h1> message you want to send'
    )
;      

// Send basic email with array data
$params = [
   'to' => ['to@example.com' => 'to name!'],
   'from' => ['from@example.com', 'from name!'],
   'subject' => 'Subject',
   'html' => '<h1>Html</h1> message you want to send'
];

$email->getEmailApi()->sendEmailWithData($params);

// Send advance email with attachment
$file = new File('fixtures/test.txt');
$email = new Email($sendinblue->getEmailApi());
$email
    ->setTo(['to@example.com' => 'to name!'])
    ->setFrom(['from@example.com', 'from name!'])
    ->setSubject('Subject')
    ->setText('Option text')
    ->setHtml('<h1>Html</h1> message you want to send')
    ->setAttachments(['fixtures/logo.png', $file])
    ->setInlineImages(['fixtures/logo.png', 'fixtures/logo_one.png'])
;
    
$sendinblue->getEmailApi()->sendEmail($email);        
    
// Send advance email with cc, bcc etc
$email
    ->setTo(['to@example.com' => 'to name!'])
    ->setFrom(['from@example.com', 'from name!'])
    ->setSubject('Invitation')
    ->setText('You are invited for giving test');
    ->setHtml('This is the <h1>HTML</h1>')
    ->setReplyTo(['replyto@example.com', 'replyto name'])
    ->setCc(['cc@example.com' => 'cc name'])
    ->setBcc(['bcc@example.com' => 'Bcc name'])
;

$sendinblue->getEmailApi()->sendEmail($email); 

```
----------------------------------------------------------------

This library is provided to you by [Scoringline](http://en.scoringline.com), if you're searching for more efficient hiring, checkout our application !

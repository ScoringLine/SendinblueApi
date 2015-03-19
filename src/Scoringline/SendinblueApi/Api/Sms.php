<?php

namespace Scoringline\SendinblueApi\Api;

use Nekland\BaseApi\Api\AbstractApi;

class Sms extends AbstractApi
{
    const TYPE_MARKETING = 'marketing';
    const TYPE_TRANSACTIONAL = 'transactional';
    const API_URL = '/sms';

    /**
     * @param string $to     I.e: "+331234567890"
     * @param string $from   I.e: "Scoringline"
     * @param string $text   I.e: "Hey, the next step is available, click here to continue !"
     * @param string $webUrl The url called in case of success of the sms, i.e. "http://you.com/sms-success/CodeSms"
     * @param string $tag    A tag associate to the message
     * @param string $type   One of the 2 types available as constant on this class
     *
     * @return array
     * @throws \RuntimeException
     */
    public function sendSms($to, $from, $text, $webUrl = '', $tag = '', $type = '')
    {
        $result = $this->post(self::API_URL, json_encode([
            'to'      => $to,
            'from'    => $from,
            'text'    => $text,
            'web_url' => $webUrl,
            'tag'     => $tag,
            'type'    => $type,
        ]));

        if ($result['data']['status'] === 'KO') {
            throw new \RuntimeException($result['data']['description']);
        }

        return $result;
    }

    /**
     * @param string    $name           Name of the SMS campaign
     * @param string    $sender         Name of the sender of the SMS (max 11)
     * @param string    $content        Content of the message
     * @param null      $tester         Mobile number to send test sms (+33625436456)
     * @param array     $listId         List of ids which the SMS campaign is sent
     * @param array     $excludeList    List of ids which will be excluded from the SMS campaign
     * @param \DateTime $scheduledDate  The day on which the SMS campaign is supposed to run
     *
     * @return array
     * @throws \RuntimeException
     */
    public function createCampaign(
        $name,
        $sender = '',
        $content = '',
        $tester = null,
        array $listId = [],
        array $excludeList = [],
        \DateTime $scheduledDate = null
    ) {
        $request = $this->getCampaignRequest($name, $sender, $content, $tester, $listId, $excludeList, $scheduledDate);
        $result = $this->post(self::API_URL, json_encode($request));

        if ($result['code'] !== 'success') {
            throw new \RuntimeException(sprintf(
                'Impossible to create SMS campaign, the API returned the status % with message "%s"',
                $result['code'],
                $result['message']
            ));
        }

        return $result;
    }

    /**
     * @param integer   $campaignId     Id of the campaign
     * @param string    $name           Name of the SMS campaign
     * @param string    $sender         Name of the sender of the SMS (max 11)
     * @param string    $content        Content of the message
     * @param null      $tester         Mobile number to send test sms (+33625436456)
     * @param array     $listId         List of ids which the SMS campaign is sent
     * @param array     $excludeList    List of ids which will be excluded from the SMS campaign
     * @param \DateTime $scheduledDate The day on which the SMS campaign is supposed to run
     *
     * @return array
     * @throws \RuntimeException
     */
    public function updateCampaign(
        $campaignId,
        $name,
        $sender = '',
        $content = '',
        $tester = null,
        array $listId = [],
        array $excludeList = [],
        \DateTime $scheduledDate = null
    ) {
        $request = $this->getCampaignRequest($name, $sender, $content, $tester, $listId, $excludeList, $scheduledDate);

        $result = $this->put(self::API_URL . '/' . $campaignId, json_encode($request));

        if ($result['code'] !== 'success') {
            throw new \RuntimeException(sprintf(
                'Impossible to update SMS campaign, the API returned the status % with message "%s"',
                $result['code'],
                $result['message']
            ));
        }

        return $result;
    }

    /**
     * @param integer $id
     * @param string $to
     * @return array
     * @throws \RuntimeException
     */
    public function sendCampaign($id, $to)
    {
        $result = $this->get(self::API_URL . '/' . $id, json_encode(['to' => $to]));

        if ($result['code'] !== 'success' || $result['data']['status'] === 'KO') {
            throw new \RuntimeException(sprintf(
                'Impossible to send SMS campaign to %s, the API returned the status % with message "%s"',
                $to,
                $result['data']['status'],
                $result['message']
            ));
        }

        return $result;
    }

    /**
     * @param string    $name
     * @param string    $sender
     * @param string    $content
     * @param string    $tester
     * @param array     $listId
     * @param array     $excludeList
     * @param \DateTime $scheduledDate
     * @return array
     */
    private function getCampaignRequest($name, $sender, $content, $tester, $listId = [], $excludeList = [], \DateTime $scheduledDate = null)
    {
        $request = [
            'name'    => $name,
        ];

        if (!empty($sender)) {
            $request['sender'] = $sender;
        }
        if (!empty($content)) {
            $request['content'] = $content;
        }
        if (!empty($tester)) {
            $request['content'] = $tester;
        }
        if (!empty($listId)) {
            $request['content'] = $listId;
        }
        if (!empty($excludeList)) {
            $request['content'] = $excludeList;
        }

        if ($scheduledDate !== null && empty($listId)) {
            throw new \RuntimeException(
                'If you precise a scheduled date, you need also to specify a list of ids that will receive the sms campaign'
            );
        }

        if ($scheduledDate !== null) {
            $request['scheduled_date'] = $scheduledDate->format('Y-m-d H:i:s');
        }

        return $request;
    }
}

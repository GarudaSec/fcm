<?php

namespace Bratasena\Fcm;

class FCM
{
 protected $baseUrl = 'https://fcm.googleapis.com/fcm/send';
    protected $destination;
    protected $topic;
    protected $data;
    protected $notification;
    protected $timeToLive;
    protected $priority;
    protected $serverKey;

    public function __construct($serverKey)
    {
        return $this->serverKey = $serverKey;

    }

    public function to(array $destination)
    {
        $this->destination = $destination;
        return $this;
    }
    public function toTopic(string $topic)
    {
        $this->topic = $topic;
        return $this;
    }
    public function data(array $data = [])
    {
        $this->data = $data;
        return $this;
    }
    public function notification(array $notification = [])
    {
        $this->notification = $notification;
        return $this;
    }
    public function priority(string $priority)
    {
        $this->priority = $priority;
        return $this;
    }
    public function timeToLive(int $timeToLive)
    {
        if ($timeToLive < 0) {
            $timeToLive = 0; // (0 seconds)
        }
        if ($timeToLive > 2419200) {
            $timeToLive = 2419200; // (28 days)
        }
        $this->timeToLive = $timeToLive;
        return $this;
    }
    public function send()
    {
        $payloads = [
          'content_available' => true,
          'priority' => isset($this->priority) ? $this->priority : 'high',
          'data' => $this->data,
          'notification' => $this->notification
        ];

        if($this->topic){
            $payloads['to'] = "/topics/{$this->topic}";
        }else{
            $payloads['registratiopm_ids'] = $this->destination;
        }

        if($this->timeToLive !== null && $this->timeToLive >= 0){
            $payloads['time_to_live'] = (int) $this->timeToLive;
        }

        $headers = [
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payloads));
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $result;
    }
}
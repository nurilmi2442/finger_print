<?php

namespace App\Globalclass;
/**
 * A helper class to log and get the Mautic webhook request
 */
class WebhookTest {

    /**
     * Log a message to a file
     *
     * @param  mixed $message
     * @param  string $type [info|error|request]
     */
    public function log($message, $type = 'info')
    {
        $prefix = 'webhookLog_';
        $file = $type . '.log';
        $date = new \DateTime();
        error_log($date->format('Y-m-d H:i:s') . ' ' . $message . "\n\n", 3, $prefix . $file);
    }

    /**
     * Get the request JSON object and log the request
     *
     * @return object
     */
    public function getRequest()
    {
        $rawData = file_get_contents("php://input");
        $headers = apache_request_headers();
        $request = \Request::getRequestUri();
        $this->log(print_r($headers, true), 'headers');
        $this->log($request, 'request');
        return json_decode($rawData);
    }
}

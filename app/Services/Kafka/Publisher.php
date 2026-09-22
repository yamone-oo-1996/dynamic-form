<?php

namespace App\Services\Kafka;

use Exception;
use Illuminate\Support\Facades\Log;

class Publisher
{
    public function sendToKafka($data, $consumerName, $headers = [])
    {
        Log::debug('Through kafka...');
        try {
            $brokers = config('kafka.kafka_brokers');
            $topicGroup = config('kafka')['topic'] ?? [];
            $topicName = $topicGroup[$consumerName] ?? [];
            $this->sendMessage($data, $topicName, $headers, $brokers);
        } catch (Exception $e) {
            Log::debug('Something happens in kafka >>> ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendMessage($payload, array $topicGroup, array $headers = [], $brokerList = '')
    {
        $conf = new \RdKafka\Conf();
        $kafkaConfig = config('kafka');
        if (empty($brokerList)) {
            $brokerList = $kafkaConfig['kafka_brokers'];
        }
        $conf->set('metadata.broker.list', $brokerList);
        $conf->set('security.protocol', $kafkaConfig['security_protocol']);
        $conf->set('sasl.mechanism', $kafkaConfig['sasl_mechanisms']);
        $conf->set('sasl.username', $topicGroup['sasl_username']);
        $conf->set('sasl.password', $topicGroup['sasl_password']);
        $topicName = $topicGroup['topic'];

        $producer = new \RdKafka\Producer($conf);
        $topic = $producer->newTopic($topicName);

        $message = json_encode($payload);

        // Set headers if provided
        $topic->producev(
            RD_KAFKA_PARTITION_UA,
            0,
            $message,
            null,
            $headers
        );

        $producer->poll(0);
        for ($flushRetries = 0; $flushRetries < 3; $flushRetries++) {
            $result = $producer->flush(10000);
            if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                break;
            }
        }

        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new \RuntimeException('Was unable to flush, messages might be lost!');
        } else {
            Log::info('Topic Name = (' . $topicName . ') Messages were sent to Kafka!');
        }
    }
}

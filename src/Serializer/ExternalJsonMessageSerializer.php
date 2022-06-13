<?php

namespace App\Serializer;

use App\Message\Review;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface as MessengerSerializerInterface;

class ExternalJsonMessageSerializer implements MessengerSerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $data = json_decode($encodedEnvelope['body'], true);

        $headers = $encodedEnvelope['headers'];

        // in case of redelivery, unserialize any stamps
        $allStamps = [];
        if (isset($headers['stamps'])) {
            $allStamps = unserialize(json_decode($headers['stamps']));
        }

        $stamps = [];

        foreach ($allStamps as $stamp) {
            if ($stamp instanceof RedeliveryStamp) {
                $stamps[] = new RedeliveryStamp($stamp->getRetryCount() + 1);
            }
        }

        return new Envelope(new Review($data['content']), $stamps);
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        $data = [];

        if ($message instanceof Review) {
            $data['content'] = $message->getContent();
        } else {
            throw new \Exception('Unsupported message class');
        }

        $stamps = [];

        $allStamps = $envelope->all();


        foreach ($allStamps as $stamp) {
            if ($stamp[0] instanceof RedeliveryStamp) {
                $stamps[] = $stamp[0];
            }
        }

        $message = [
            'body' => json_encode($data),
            'headers' => [
                'stamps' => json_encode(serialize($stamps)),
            ],
        ];

        return $message;
    }
}

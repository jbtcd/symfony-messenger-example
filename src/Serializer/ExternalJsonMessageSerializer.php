<?php

namespace App\Serializer;

use App\Message\Review;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface as MessengerSerializerInterface;

class ExternalJsonMessageSerializer implements MessengerSerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $data = json_decode($encodedEnvelope['body']);

        return new Envelope(new Review($data['content']));
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
        return [
            'body' => json_encode($data)
        ];
    }
}

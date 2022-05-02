<?php

namespace App\MessageHandler;

use App\Message\Review;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ReviewHandler
{
    public function __invoke(Review $review)
    {
        dump($review);
    }
}

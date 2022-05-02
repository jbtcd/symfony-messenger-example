<?php

namespace App\Command;

use App\Message\Review;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendMessageCommand extends Command
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:message:send');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->messageBus->dispatch(new Review('This is a great Review!'));

        return Command::SUCCESS;
    }
}

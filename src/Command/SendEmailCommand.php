<?php

namespace App\Command;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'SendEmail',
    description: 'Add a short description for your command',
)]
class SendEmailCommand extends Command
{  private $mailer;
    public function __construct(MailerInterface $mailer)
    {
        parent::__construct();
        $this->mailer = $mailer;
    }
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       // Je créer une instance de Email
       $email = (new Email())
       ->from('hello@example.com')
       ->to('you@example.com')
       //->cc('cc@example.com')
       //->bcc('bcc@example.com')
       //->replyTo('fabien@example.com')
       //->priority(Email::PRIORITY_HIGH)
       ->subject('Time for Symfony Mailer!')
       ->text('Sending emails is fun again!')
       ->html('<p>See Twig integration for better HTML integration!</p>');

   $this->mailer->send($email);
   return Command::SUCCESS;
}
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Commands\ImapIdleCommand;
use Webklex\PHPIMAP\Message;

class CustomImapIdleCommand extends ImapIdleCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom_command';

    /**
     * The console command description.
     *
     * @var string
     */
    //protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    protected $account = "default";



    public function onNewMessage(Message $message) {
        $this->info('New message received!' . $message->subject);
    }

}

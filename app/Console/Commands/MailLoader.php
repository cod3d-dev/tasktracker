<?php

namespace App\Console\Commands;
use App\Models\User;
use Carbon\Carbon;
use Collective\Html\FormFacade as Form;

use App\Models\Mail;
use Illuminate\Console\Command;

//use tidy;
use Webklex\IMAP\Facades\Client;
use Filament\Notifications\Notification;

class MailLoader extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = Client::account("default");
        $client->connect();

//        dd($client->getFolders(false));

        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
        $folders = $client->getFolders(false);

        /** @var \Webklex\PHPIMAP\Folder $folder */

        $totalMessagesInbox = 0;
        foreach ($folders as $folder) {

            if ($folder->path == "INBOX") {
                $messages = $folder->messages()->all()->get();


//                $this->info("Number of messages: " . $totalMessagesInbox);

                /** @var \Webklex\PHPIMAP\Message $message */


                foreach ($messages as $message) {
                    $config = [
                        'indent' => true,
                        'output-xhtml' => true,
                        'wrap' => 200
                    ];

                    if(Mail::where('message_id', $message->getMessageId())->count() === 0) {
                        $totalMessagesInbox++;
                        $mail = new Mail();
                        $mail->message_id = $message->getMessageId();
                        $mail->subject = $message->getSubject();
                        $mail->from = $message->getFrom();
                        $mail->date = $message->getDate();
                        $mail->account = $message->getTo();
                        $mail->body = $message->getHTMLBody();
                        $mail->htmlBody = $message->getHTMLBody();
                        $mail->processed = false;
                        $mail->save();
                    }


                    //                    $mail->headers = $message->getHeader()->text();
//                    $mail->save();

//                    if($message->move('Translations/Processed') == true) {
//                        $moved = true;
//                    }


                }


            }



        }

        if ($totalMessagesInbox > 0) {
            $recipient =  User::find(1);
            $date = Carbon::now();

            Notification::make()
                ->title($date . ' — ' . $totalMessagesInbox . ' new messages' )
                ->sendToDatabase($recipient);
        }
        return 0;

    }


}

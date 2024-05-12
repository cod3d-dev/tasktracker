<?php

namespace App\Console\Commands;
use App\Models\task;
use Collective\Html\FormFacade as Form;

use App\Models\Mail;
use Illuminate\Console\Command;
use tidy;
use Webklex\IMAP\Facades\Client;
use PHPHtmlParser\Dom;

class ImapTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imap:test';

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

        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
        $folders = $client->getFolders(false);

        /** @var \Webklex\PHPIMAP\Folder $folder */
        foreach ($folders as $folder) {
            if ($folder->path == "Translations") {
                $messages = $folder->messages()->all()->limit(17, 0)->get();

                $this->info("Number of messages: " . $messages->count());

                /** @var \Webklex\PHPIMAP\Message $message */


                foreach ($messages as $message) {
                    $newTask = false;
                    //                dd($message);

                    $this->info('Sender: ' . $message->getFrom());
                    $config = [
                        'indent' => true,
                        'output-xhtml' => true,
                        'wrap' => 200
                    ];

                    $sender = $message->getFrom();

                    $task_posted = $message->getDate();

                    $subject = $message->getSubject();


                    $tidy = new Tidy;
                    $clean = $tidy->repairString($message->getHTMLBody(), $config);

                    $dom = new Dom;
                    $dom->load($clean);


                    $mail = new Mail();
                    $mail->message_id = $message->getMessageId();
                    $mail->subject = $message->getSubject();
                    $mail->from = $message->getFrom();
                    $mail->date = $message->getDate();
                    $mail->body = $clean;
                    $mail->processed = false;
                    //                    $mail->headers = $message->getHeader()->text();
                    $mail->save();


                }
            }

        }
        return 0;

    }


    //    public function handle() {
    //        $client = Client::account("default");
    //        $client->connect();
    //
    //        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
    //        $folders = $client->getFolders(false);
    //
    //        /** @var \Webklex\PHPIMAP\Folder $folder */
    //        foreach($folders as $folder){
    //            if($folder->path == "Translations") {
    //                $messages = $folder->messages()->all()->limit(17, 0)->get();
    //
    //                $this->info("Number of messages: ".$messages->count());
    //
    //                /** @var \Webklex\PHPIMAP\Message $message */
    //
    //
    //                foreach ($messages as $message) {
    //                    $newTask = false;
    //                    //                dd($message);
    //
    //                    $this->info('Sender: '.$message->getFrom());
    //                    $config = [
    //                        'indent'         => true,
    //                        'output-xhtml'   => true,
    //                        'wrap'           => 200
    //                    ];
    //
    //                    $sender = $message->getFrom();
    //
    //                    $task_posted = $message->getDate();
    //
    //                    if($sender == "Lokalise <noreply@lokalise.com>") {
    //                        $subject = $message->getSubject();
    //
    //                        if(str_starts_with($subject, "New Task")) {
    //                            $newTask = true;
    //                            $account = $message->getTo();
    //
    //                            if ($account == "cesarodev@gmail.com") {
    //
    //                                $project_id = 1;
    //                                $task_type = 1;
    //
    //                            }
    //
    //                            if ($account == "codelgado@gmail.com") {
    //
    //                                $project_id = 2;
    //                                $task_type = 4;
    //                            }
    //
    //                            //                            Parse email
    //
    //                            $tidy = new Tidy;
    //                            $clean = $tidy->repairString($message->getHTMLBody(), $config);
    //
    //                            $dom = new Dom;
    //                            $dom->load($clean);
    //
    //                            $task_info = $dom->getElementsByClass('task-information');
    //                            $i = 0;
    //                            foreach ($task_info->find('div') as $div) {
    //                                $i++;
    //                                $key = $div->find('strong');
    //                                if (trim($key->text()) == 'Title:') {
    //                                    $task_description = $key->find('a')->text();
    //                                } elseif (trim($key->text()) == 'Due date:') {
    //                                    $task_date = str_replace('Due date:', '', trim($div->text()));
    //                                }
    //                            }
    //
    //                            $task_link = $dom->getElementsByClass('language-button-container');
    //
    //                            foreach ($task_link->find('a') as $a) {
    //                                $task_link = $a->getAttribute('href');
    //                            }
    //
    //                            $messageWords = $dom->find('.language-words');
    //                            $task_words = intval(str_replace('Words to translate:', '', $messageWords->text()));
    //
    //
    //                            //                        $this->info("\tKey ".$i.": ".$key->text() . "\n");
    //                            //                        $this->info("\tLine ".$i.": ".$div->text() . "\n");
    //                        }
    //
    //
    //                    } elseif ($sender == '"Smartcat" <no-reply@sc-notification.com>') {
    //
    //                        $subject = $message->getSubject();
    //
    //                        if (str_starts_with($subject, "Invitation from DATADUCK")) {
    //                            $newTask = true;
    //                            $project_id = 3;
    //
    //                            $tidy = new Tidy;
    //                            $clean = $tidy->repairString($message->getHTMLBody(), $config);
    //
    //                            $dom = new Dom;
    //                            $dom->load($clean);
    //
    //                            //                            $this->info($message->getHTMLBody());
    //
    //                            $task_info = $dom->find('a[data-testid="project-name"]');
    //
    //                            $task_description = $task_info->text();
    //
    //                            $info_divs = $dom->getElementsByClass('section__info-text');
    //
    //                            ///
    //                            $task_link = $dom->find('a[data-testid="go-to-task"]')->getAttribute('href');
    //
    //                            //                            echo $task_link;
    //
    //                            foreach ($info_divs as $key => $div) {
    //
    //                                if(str_contains($div->text(), "Total words:")) {
    //                                    $task_words = str_replace('Total words:', '', trim($div->text()));
    //                                } elseif (str_contains($div->text(), "Deadline:")) {
    //                                    $task_date = str_replace('Deadline:', '', trim($div->text()));
    //                                } elseif (str_contains($div->text(), "Translation")) {
    //                                    $task_type = 1;
    //                                } elseif (str_contains($div->text(), "Editing")) {
    //                                    $task_type = 2;
    //                                }
    //
    //                            }
    //
    //
    //                            //
    //                            //                            $more_info = $dom->getElementsByClass('section__additional-item');
    //                            //                            echo $more_info->innerHtml();
    //                            //
    //                            //                            $dom2 = new Dom;
    //                            //                            $dom2->load($more_info);
    //                            //
    //                            //                            echo $dom2->countChildren();
    //                            //                            foreach ($more_info as $key => $div) {
    //                            //                                echo $div->firstChild()->innerHtml();
    //                            //                                if($div->firstChild()->find('span')->text() == "Invited by") {
    //                            //                                    echo "INVITED";
    //                            //                                }
    //                            //                            }
    //                            //
    //                            //
    //                            //                            $task = new Task();
    //                            //
    //                            //                            $task->project_id = $project_id;
    //                            //                            $task->description = $task_description;
    //                            //                            $task->link = $task_link;
    //                            //                            $task->type_id = $task_type;
    //                            //                            $task->words = $task_words;
    //                            //                            $task->posted_date = $task_posted;
    //
    //
    //                            //
    //                            //                            $this->info($task_description);
    //                            //                            $task_description = $task_info->find('a')->text();
    //                            //
    //                            //                            $this->info($task_description);
    //                            //
    //                            //                            $this->info($message->getHTMLBody());
    //                        }
    //
    //                    }
    //
    //                    $this->info("\tTask: ".$task_description);
    //                    $this->info("\tDue: ".$task_date);
    //                    $this->info("\tWords: ".$task_words);
    //                    $this->info("\tLink: ".$task_link);
    //                    $this->info("\tPosted: ".$task_posted);
    //
    //                }
    //
    //
    //                //
    //                //
    //                //                    $tidy = new Tidy;
    //                //                    $clean = $tidy->repairString($message->getHTMLBody(), $config);
    //                //
    //                //                    $dom = new Dom;
    //                //                    $dom->load($clean);
    //                //
    //                //                    $title1 = $dom->getElementsByClass('task-information');
    //                //                    $i = 0;
    //                //                    foreach ($title1->find('div') as $div) {
    //                //                        $i++;
    //                //                        $key = $div->find('strong');
    //                //                        if (trim($key->text()) == 'Title:') {
    //                //                            $messageTitle = $key->find('a')->text();
    //                //                            $this->info("\tTitle 1: ".$messageTitle);
    //                //                        } elseif (trim($key->text()) == 'Due date:') {
    //                //                            $this->info("\tElse");
    //                //                            $messageDate = str_replace('Due date:', '', trim($div->text()));
    //                //                            $this->info("\tDate 1: ".$messageDate);
    //                //                        } elseif (trim($key->text()) == 'Words to translate:'){
    //                //                            $messageWords = str_replace('Words to translate:', '', trim($div->text()));
    //                //                            $this->info("\tWords 1: ".$messageWords);
    //                //                        }
    //                //
    //                ////                        $this->info("\tKey ".$i.": ".$key->text() . "\n");
    //                ////                        $this->info("\tLine ".$i.": ".$div->text() . "\n");
    //                //                    }
    //                //
    //                //                    $messageWords = $dom->find('.language-words');
    //                //                    $this->info("\tWords 1: ".$messageWords->text());
    //                //
    //                //                    $this->info("\tBody: ".$clean);
    //                //                    $mail = new Mail();
    //                //                    $mail->message_id = $message->getMessageId();
    //                //                    $mail->subject = $message->getSubject();
    //                //                    $mail->from = $message->getFrom();
    //                //                    $mail->date = $message->getDate();
    //                //                    $mail->body = $clean;
    //                //                    $mail->processed = false;
    //                ////                    $mail->headers = $message->getHeader()->text();
    //                //                    $mail->save();
    //                //
    //                //                    $task = new Task();
    //                //
    //
    //
    //
    //                //                    $copy = $message->move($folder_path = "test");
    //
    //            }
    //        }
    //
    //
    //        return 0;
    //    }
}

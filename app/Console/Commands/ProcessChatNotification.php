<?php

namespace App\Console\Commands;

use App\Mail\ChatMessage;
use App\Models\Conversation;
use App\Models\TempMessage;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessChatNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-chat-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process chat email notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing chat messages');
        $tmp_messages = TempMessage::select('conversation_id')->distinct()->get();
        foreach($tmp_messages as $item) {
            $conversation = Conversation::find($item->conversation_id);
            $tutor_messages = TempMessage::where('conversation_id', $conversation->id)->where('sender_id', $conversation->parent_id)->get();
            $parent_messages = TempMessage::where('conversation_id', $conversation->id)->where('sender_id', $conversation->tutor_id)->get();
            if(count($tutor_messages)) {
                try {
                    $tutor = User::find($conversation->tutor_id);
                    $parent = User::find($conversation->parent_id);
                    Mail::to($tutor->email)->send(new ChatMessage($tutor->firstname . " " . $tutor->lastname, $parent, $tutor_messages));
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
            if(count($parent_messages)) {
                try {
                    $tutor = User::find($conversation->tutor_id);
                    $parent = User::find($conversation->parent_id);
                    Mail::to($parent->email)->send(new ChatMessage($parent->firstname . " " . $parent->lastname, $tutor, $parent_messages));
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
        TempMessage::truncate();
    }
}

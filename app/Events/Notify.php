<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class Notify implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $userId;

    /**
     * @var int
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
       
    }

    // public function broadcastAs(){
    //     //return 'private-user'.$this->user;
    //     return ['my-channel'];
    // }
    /**
     * Get the channels the event should broadcast on.
     *ss
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
        //return new PrivateChannel('private-user.'.$this->users);
        //return [new PrivateChannel("App.User.{$this->userId}")];
        return 'my-event';
    }
}

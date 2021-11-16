<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PushNotification extends Notification
{
    use Queueable;
    protected $title;
    protected $content;
    protected $img;
    protected $url;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title=null ,$content=null, $img=null, $url =null)
    {
        //
        
        $this->title = $title;
        $this->content = $content;
        $this->img = $img;
        $this->url = $url ? $url : url("");
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast', WebPushChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->content,
            'action_url' => $this->url,
            'created' => Carbon::now()->toIso8601String()
        ];
    }

    /**
     * Get the web push representation of the notification.
     *
     * @param  mixed  $notifiable
     * @param  mixed  $notification
     * @return \Illuminate\Notifications\Messages\DatabaseMessage
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($notification->title)
            ->icon($notification->img)
            ->body(strip_tags(html_entity_decode($notification->content)))
            ->action('View app', $this->url)
            ->data(['id' => $notification->id, 'url' => $this->url]);
    }
}
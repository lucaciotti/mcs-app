<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DefaultMessageNotify extends Notification
{
    // use Queueable;
    protected $title;
    protected $body;
    protected $link;
    protected $level;
    
    public function __construct($title='Notifica Generica', $body='', $link='#', $level='info')
    {
        $this->title = $title;
        $this->body = $body;
        $this->link = $link;
        $this->level = $level;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    public function toDatabase($notifiable)     //The function to database. As mentioned in the via method.
    {
        return [
            'title' => $this->title,
            'body'  => $this->body,
            'link'  => $this->link,
            'level' => $this->level,
        ];
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
            //
        ];
    }
}

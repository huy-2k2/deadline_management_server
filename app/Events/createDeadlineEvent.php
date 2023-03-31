<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class createDeadlineEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $data;

  public function __construct($data)
  {
      $this->data = $data;
  }

  public function broadcastOn()
  {
    return new channel("my-channel");
  }

  public function broadcastAs()
  {
      $class_id = $this->data['class']->id;
      return "my-event.{$class_id}";
  }
}
<?php

namespace Notabenedev\SiteDocuments\Events;

use App\DocumentCategory;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentCategoryChangePosition
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $category;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DocumentCategory $category)
    {
        $this->category = $category;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}

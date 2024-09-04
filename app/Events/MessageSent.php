<?php

namespace App\Events;

use App\Models\Mesa;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $orders;

    public function authenticate($request)
    {
        // $user = $request->user();
        // if ($user) {
        //     return $this->checkUserAuthorization($user);
        // }

        return true;
    }

    public function __construct($orders)
    {
        $orders = Order::whereHas('pedidos', function ($query) {
            $query->where('status', 0);
            $query->whereHas('producto', function ($query) {
                $query->whereIn('category_id', Auth::user()->categories->pluck('id'));
            });
        })->with(['pedidos' => function ($query) {
            $query->where('status', 0);
            $query->with(['producto' => function ($query) {
                $query->whereIn('category_id', Auth::user()->categories->pluck('id'));
            }]);
        }])->where('status', 0)->orderBy('id', 'asc')->get();

        $orders = compact('orders');
        // $this->orders = $orders;
        $this->orders = $orders;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat');
    }
}

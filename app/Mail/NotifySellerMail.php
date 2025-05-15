<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifySellerMail extends Mailable
{
    use Queueable, SerializesModels;

    // Declare necessary properties
    protected $orderDetail;

    /**
     * Create a new message instance.
     *
     * @param mixed $orderDetail
     */
    public function __construct($orderDetail)
    {
        $this->orderDetail = $orderDetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Fetch seller email from product relation
        $sellerEmail = $this->orderDetail->product->seller->email;

        // Return email to the seller
        return $this->to('mirzauzair2121@gmail.com')
                    ->subject('New Order Notification') // Customize your subject here
                    ->view('email-templates.notify-seller')
                    ->with([
                        'orderDetail' => $this->orderDetail,  // Pass the order detail to the email view
                    ]);
    }
}

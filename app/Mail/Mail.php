<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Container\Attributes\DB;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use jeemce\helpers\DBHelper;

class Mail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $otp = $this->data;
        return $this->view('emails.otp', get_defined_vars());
    }
}

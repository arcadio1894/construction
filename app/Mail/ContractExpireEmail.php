<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractExpireEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $filename;
    public $pathComplete;

    public function __construct($pathComplete, $filename)
    {
        $this->filename = $filename;
        $this->pathComplete = $pathComplete;
        $this->subject('Contratos por caducar');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.sendStockMail')
            ->attach($this->pathComplete, [
                'as' => $this->filename,
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }
}

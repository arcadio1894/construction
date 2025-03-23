<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DesfaseStockMail extends Mailable
{
    use Queueable, SerializesModels;

    public $desfases;

    public function __construct($desfases)
    {
        $this->desfases = $desfases;
    }

    public function build()
    {
        return $this->subject('🔴 Alerta: Desfase en Stock')
            ->view('email.desfase_stock');
    }
}

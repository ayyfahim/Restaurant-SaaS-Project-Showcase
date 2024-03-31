<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendStoreDocuments extends Mailable
{
    use Queueable, SerializesModels;

    protected $store;
    protected $files;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($store, $files)
    {
        //
        $this->store = $store;
        $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->from(config('app.mail_from_address'), config('app.mail_from_name'))
        ->subject('New store registered')
        ->with([
            'name' => $this->store->store_name,
            'email' => $this->store->email
            ])
        ->markdown('emails.verifyDocs');
        foreach ($this->files as $file){
            $data = Storage::disk('s3')->get($file);
            $explodedName = explode('/',$file);
            $filename = $explodedName[count($explodedName) - 1];
            $mail->attachData($data, $filename);
        }
        return $mail;
    }
}

<?php

namespace App\Mail;

use App\Models\DeleteApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeleteApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $deleteApproval;

    /**
     * Create a new message instance.
     */
    public function __construct(DeleteApproval $deleteApproval)
    {
        $this->deleteApproval = $deleteApproval;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Delete Approval Required - ' . $this->deleteApproval->model_name)
                    ->view('emails.delete-approval-notification')
                    ->with([
                        'deleteApproval' => $this->deleteApproval,
                    ]);
    }
}

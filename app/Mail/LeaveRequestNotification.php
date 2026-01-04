<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveRequest;
    public $status;
    public $comments;

    /**
     * Create a new message instance.
     */
    public function __construct(LeaveRequest $leaveRequest, $status, $comments = null)
    {
        $this->leaveRequest = $leaveRequest;
        $this->status = $status;
        $this->comments = $comments;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->status === 'approved' 
            ? 'Leave Request Approved' 
            : ($this->status === 'rejected' ? 'Leave Request Rejected' : 'New Leave Request Submitted');

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($subject)
                    ->view('emails.leave-request-notification')
                    ->with([
                        'leaveRequest' => $this->leaveRequest,
                        'status' => $this->status,
                        'comments' => $this->comments,
                    ]);
    }
}

<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Sichikawa\LaravelSendgridDriver\SendGrid;
/**
 * パスワードリセット申請時に送信するメールのクラス
 * Class PasswordReset
 * @package App\Mail
 */
class PasswordReset extends Mailable
{
    use Queueable, SerializesModels, SendGrid;
    public $user;
    public $password_reset;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, \App\PasswordReset $password_reset)
    {
        $this->user = $user;
        $this->password_reset = $password_reset;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->view('emails.passwordReset')
        ->subject('パスワード再設定のリンク')
        ->from('from@example.com')
        ->to(['to@example.com'])
        ->sendgrid([
            'personalizations' => [
                [
                    'substitutions' => [
                        ':myname' => 'kamitter',
                    ],
                ],
            ],
        ]);


    }
}

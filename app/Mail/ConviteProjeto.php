<?php

namespace App\Mail;

use App\Models\ProjetoConvite;
use App\Models\Projeto;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConviteProjeto extends Mailable
{
    use Queueable, SerializesModels;

    public ProjetoConvite $convite;
    public Projeto $projeto;
    public User $responsavel;
    public string $linkConvite;

    /**
     * Create a new message instance.
     */
    public function __construct(ProjetoConvite $convite)
    {
        $this->convite = $convite;
        $this->projeto = $convite->equipe->projeto;
        $this->responsavel = $convite->responsavel;
        $this->linkConvite = route('registro.convite', [
            'convite' => $convite->id,
            'hash' => $convite->hash()
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convite para participar do projeto: ' . $this->projeto->titulo,
            replyTo: [$this->responsavel->email => $this->responsavel->name],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.convite-projeto',
            with: [
                'convite' => $this->convite,
                'projeto' => $this->projeto,
                'responsavel' => $this->responsavel,
                'linkConvite' => $this->linkConvite,
                'equipe' => $this->convite->equipe,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
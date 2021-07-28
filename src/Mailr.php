<?php

namespace twocore\mailr;

class Mailr
{
    /**
     * 
     * @access  private
     * @var     Swift_Mailer
     */

    private $mailer;

    /**
     * 
     * @access  private
     * @var     Swift_Message
     */

    private $message;

    /**
     * Allowed Image Mime-Types
     * 
     * @var array
     */

    protected $_images = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/svg'
    ];

    /**
     * Constructor
     * 
     * @param   Swift_Mailer    $mailer
     * @param   Swift_Message   $message
     */

    public function __construct(\Swift_Mailer $mailer, \Swift_Message $message)
    {
        $this->mailer  = $mailer;
        $this->message = $message;
    }

    /**
     * Add recipients [ To: ]
     * 
     * @param   string  $email
     * @param   string  $name
     * @param   bool    $replace
     */

    public function to(string $email, string $name = null, bool $replace = true): Mailr
    {
        $method = $replace ? 'setTo' : 'addTo';

        $this->message->{$method}($email, $name);

        return $this;
    }

    /**
     * Add recipients [ Cc: ]
     * 
     * @param   string  $email
     * @param   string  $name
     * @param   bool    $replace
     */

    public function cc(string $email, string $name = null, bool $replace = true): Mailr
    {
        $method = $replace ? 'setCc' : 'addCc';

        $this->message->{$method}($email, $name);

        return $this;
    }

    /**
     * Add recipients [ Bcc: ]
     * 
     * @param   string  $email
     * @param   string  $name
     * @param   bool    $replace
     */

    public function bcc(string $email, string $name = null, bool $replace = true): Mailr
    {
        $method = $replace ? 'setBcc' : 'addBcc';

        $this->message->{$method}($email, $name);

        return $this;
    }

    /**
     * Set the ReplyTo recipient
     * 
     * @param   string  $email
     * @param   string  $name
     */

    public function replyTo(string $email, string $name = null): Mailr
    {
        $this->message->setReplyTo($email, $name);

        return $this;
    }

    /**
     * Set the message's sender [ From: ]
     * 
     * @param   string  $email
     * @param   string  $name
     */

    public function from(string $email, string $name = null): Mailr
    {
        $this->message->setFrom($email, $name);

        return $this;
    }

    /**
     * Set the message's subject
     * 
     * @param   string  $subject
     */

    public function subject(string $subject): Mailr
    {
        $this->message->setSubject($subject);

        return $this;
    }

    /**
     * Set the message's body
     * 
     * @param   string  $content
     * @param   string  $type       'text/plain' | 'text/html'
     */

    public function body(string $content, string $type = 'text/plain'): Mailr
    {
        $this->message->setBody($content, $type);

        return $this;
    }

    /**
     * Add additional message part
     * 
     * @param   string  $content
     * @param   string  $type       'text/plain' | 'text/html'
     */

    public function part(string $content, string $type): Mailr
    {
        $this->message->addPart($content, $type);

        return $this;
    }

    /**
     * Request a Read Receipt
     * 
     * @param   email   $email
     */

    public function readReceipt(string $email): Mailr
    {
        $this->message->setReadReceiptTo($email);

        return $this;
    }

    /**
     * Set the message's priority
     * 
     * @param   int $priority
     */

    public function priority(int $priority): Mailr
    {
        $this->message->setPriority($priority);

        return $this;
    }

    /**
     * Attach Files
     * 
     * @param   string  $file   Dynamically created file content or absolute path to an existing file
     * @param   string  $name   (optional) required for dynamically created content
     * @param   string  $type   (optional) required for dynamically created content
     */

    public function attach(string $file, string $name = null, string $type = null): Mailr
    {
        if ( is_file($file) && is_readable($file) )
        {
            $attachment = \Swift_Attachment::fromPath($file);

            $this->message->attach( $attachment );
        }
        elseif ($name !== null && $type !== null)
        {
            $attachment = new \Swift_Attachment($file, $name, $type);

            $this->message->attach( $attachment );
        }
        else
        {
            throw new \ErrorException('Unable to attach file: Parameters are missing or misconfigured or file doesn\'t exists!');
        }

        return $this;
    }

    /**
     * Embed Files
     * 
     * @param   string  $file   Dynamically created file content or absolute path to an existing file
     * @param   string  $name   (optional) required for dynamically created content
     * @param   string  $type   (optional) required for dynamically created content
     */

    public function embed(string $file, string $name = null, string $type = null): string
    {
        if ( is_file($file) && is_readable($file) )
        {
            if ( ! in_array(mime_content_type($file), $this->_images) )
            {
                throw new \ErrorException("The file's content type '${type}' isn't supported!");
            }

            $attachment = \Swift_Image::fromPath( $file );

            return $this->message->embed( $attachment );
        }
        elseif ($name !== null && $type !== null)
        {
            if ( ! in_array($type, $this->_images) )
            {
                throw new \ErrorException("The file's content type '${type}' isn't supported!");
            }

            $attachment = new \Swift_Image($file, $name, $type);

            return $this->message->embed( $attachment );
        }
        else
        {
            throw new \ErrorException('Unable to embed file: Parameters are missing or misconfigured or file doesn\'t exists!');
        }
    }

    /**
     * Send the message to the recipient(s)
     * 
     * @return bool|int
     */

    public function send()
    {
        return $this->mailer->send( $this->message );
    }

    /**
     * Return the current Swift_Message instance
     * 
     * @return Swift_Message
     */

    public function getMessage(): Swift_Message
    {
        return $this->message;
    }

    /**
     * Set a new Swift_Message instance
     * 
     * @param   Swift_Message   $message
     */

    public function setMessage(Swift_Message $message): void
    {
        $this->message = $message;
    }
}
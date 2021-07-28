<?php declare(strict_types=1);

namespace twocore\mailr;

use mako\application\Package;

class MailrPackage extends Package
{
    /**
     * Package's Name
     * 
     * @var string
     */

    protected $packageName = 'twocore/mailr';

    /**
     * Package's Namespace
     * 
     * @var string
     */

    protected $fileNamespace = 'mailr';

    /**
     * Register the service(s)
     * 
     * @access protected
     */

    protected function bootstrap(): void
    {
        $this->container
            ->registerSingleton(\Swift_Message::class, function ($container)
            {
                $sender = $container->get('config')
                    ->get('mailr::sender');
                
                // -- --------------------------------------------------

                $message = new \Swift_Message();

                if ( $sender['email'] !== null )
                {
                    $message->setFrom($sender['email'], $sender['name']);
                }

                return $message;
            });
        
        // ~~ ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        $this->container
            ->registerSingleton(\Swift_Mailer::class, function ($container)
            {
                $server = $container->get('config')
                    ->get('mailr::server');
                
                // -- --------------------------------------------------

                $transport = (new \Swift_SmtpTransport())
                    ->setHost( $server['host'] )
                    ->setPort( $server['port'] )
                    ->setEncryption( $server['security'] );
                
                if (null !== $server['username'] && null !== $server['password'])
                {
                    $transport->setUsername( $server['username'] );
                    $transport->setPassword( $server['password'] );
                }

                return new \Swift_Mailer($transport);
            });
        
        // ~~ ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        $this->container
            ->registerSingleton([Mailr::class, 'mailer'], Mailr::class);
    }
}
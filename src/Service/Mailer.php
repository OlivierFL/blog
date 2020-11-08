<?php

namespace App\Service;

use App\Core\Session;
use App\Exceptions\MailerException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    /**
     * @var PHPMailer
     */
    private PHPMailer $mailer;
    /**
     * @var Session
     */
    private Session $session;

    /**
     * Mailer constructor.
     *
     * @param Session $session
     *
     * @throws Exception
     */
    public function __construct(Session $session)
    {
        $this->mailer = new PHPMailer(true);
        $this->setConfig($this->mailer, $this->getConfig());
        $this->session = $session;
    }

    /**
     * @param array $data
     *
     * @throws Exception
     * @throws MailerException
     */
    public function sendEmail(array $data): void
    {
        $this->mailer->addReplyTo($data['from']);
        $this->mailer->Subject = $data['subject'];
        $this->mailer->Body = filter_var($data['content'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        try {
            $this->mailer->send();

            $this->session->addMessages('Email envoyé. Nous répondrons dans les plus brefs délais');

            return;
        } catch (Exception $e) {
            throw MailerException::send($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    private function getConfig()
    {
        return yaml_parse_file(__DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'config'.\DIRECTORY_SEPARATOR.'mail.yaml');
    }

    /**
     * @param PHPMailer $mailer
     * @param array     $config
     *
     * @throws Exception
     */
    private function setConfig(PHPMailer $mailer, array $config): void
    {
        if (isset($config['SMTP_SERVER'])) {
            $mailer->Host = $config['SMTP_SERVER'];
        }

        if (isset($config['SMTP_PORT'])) {
            $mailer->Port = $config['SMTP_PORT'];
        }

        if (isset($config['USERNAME'])) {
            $mailer->Username = $config['USERNAME'];
        }

        if (isset($config['PASSWORD'])) {
            $mailer->Password = $config['PASSWORD'];
        }

        if (isset($config['SENDER_EMAIL'])) {
            $mailer->setFrom($config['SENDER_EMAIL'], 'Blog');
            $mailer->addAddress($config['SENDER_EMAIL']);
        }

        $mailer->isSMTP();
        $mailer->CharSet = PHPMailer::CHARSET_UTF8;
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mailer->SMTPAuth = true;
        $mailer->isHTML(true);
    }
}

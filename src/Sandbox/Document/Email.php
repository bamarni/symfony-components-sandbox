<?php

namespace Sandbox\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Email
{
    private $id;

    private $email;

    private $subject;

    private $content;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('email', new Assert\NotBlank);
        $metadata->addPropertyConstraint('email', new Assert\Email);
        $metadata->addPropertyConstraint('subject', new Assert\NotBlank);
        $metadata->addPropertyConstraint('content', new Assert\NotBlank);
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    public static function createFromArray($array)
    {
        $email = new self;
        $email->id = (string) $array['_id'];
        $email->email = $array['email'];
        $email->subject = $array['subject'];
        $email->content = $array['content'];

        return $email;
    }
}

<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class ContactForm
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true,
     *     checkHost = true
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\Length(
     *     max = 1000
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=1000)
     */
    private $message;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     *
     * @return \App\Entity\ContactForm
     */
    public function setMessage(?string $message): ContactForm
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return \App\Entity\ContactForm
     */
    public function setEmail(?string $email): ContactForm
    {
        $this->email = $email;

        return $this;
    }
}

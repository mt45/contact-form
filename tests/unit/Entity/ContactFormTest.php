<?php

namespace App\Tests\Entity;

use App\Entity\ContactForm;

/**
 * Class ContactFormTest
 *
 * @package App\Tests\Entity
 */
class ContactFormTest extends \Codeception\Test\Unit
{
    public function testGetterAndSetter()
    {
        $entity = new ContactForm();

        $this->assertNull($entity->getId());

        $entity->setEmail('new@mail.com');
        $this->assertEquals('new@mail.com', $entity->getEmail());

        $entity->setMessage('Message');
        $this->assertEquals('Message', $entity->getMessage());
    }
}

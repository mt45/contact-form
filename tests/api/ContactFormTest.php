<?php

namespace App\Tests;

use Codeception\Util\HttpCode;

/**
 * Class ContactFormTest
 *
 * @package App\Tests
 */
class ContactFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \App\Tests\ApiTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected $table = 'contact_form';

    /**
     * @dataProvider provideContactForms
     *
     * @param int   $id
     * @param array $contactForm
     */
    public function testPostContactFormAction(int $id, array $contactForm)
    {
        $this->tester->haveHttpHeader('Content-Type', 'application/json');;
        $this->tester->sendPOST('/contact-form', $contactForm);
        $this->tester->seeResponseCodeIs(HttpCode::CREATED);
        $this->tester->seeResponseIsJson();
        $this->tester->seeResponseMatchesJsonType($this->getJsonType());

        $this->tester->seeInDatabase($this->table, ['id' => $id]);
    }

    /**
     * @dataProvider provideValidationContactForms
     *
     * @param int   $id
     * @param array $contactForm
     */
    public function testPostContactFormActionValidation(int $id, array $contactForm)
    {
        $this->tester->haveHttpHeader('Content-Type', 'application/json');;
        $this->tester->sendPOST('/contact-form', $contactForm);
        $this->tester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->tester->seeResponseIsJson();
        $this->tester->seeResponseMatchesJsonType(['property_path' => 'string', 'message' => 'string']);
    }

    /**
     * @depends      testPostContactFormAction
     *
     * @dataProvider provideContactForms
     *
     * @param int $id
     */
    public function testGetContactFormAction(int $id)
    {
        $this->tester->sendGET('/contact-form/'.$id);
        $this->tester->seeResponseCodeIs(HttpCode::OK);
        $this->tester->seeResponseIsJson();
        $this->tester->seeResponseMatchesJsonType($this->getJsonType());
    }

    public function testGetContactFormActionNotFound()
    {
        $this->tester->sendGET('/contact-form/7000');
        $this->tester->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $this->tester->seeResponseIsJson();
    }

    /**
     * @depends      testPostContactFormAction
     *
     * @dataProvider provideExistingContactForm
     *
     * @param int $id
     */
    public function testPutContactFormAction(int $id)
    {
        $newMessage = 'new message';

        $this->tester->sendPUT('/contact-form/'.$id, ['message' => $newMessage]);
        $this->tester->seeResponseCodeIs(HttpCode::OK);
        $this->tester->seeResponseIsJson();
        $this->tester->seeResponseMatchesJsonType($this->getJsonType());

        $this->tester->seeInDatabase($this->table, ['id' => $id, 'message' => $newMessage]);
    }

    public function testPutContactFormActionNotFound()
    {
        $this->tester->sendPUT('/contact-form/7000', []);
        $this->tester->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $this->tester->seeResponseIsJson();
    }

    /**
     * @depends      testPostContactFormAction
     *
     * @dataProvider provideValidationContactForm
     *
     * @param int   $oldId
     * @param array $contactForm
     */
    public function testPutContactFormActionValidation(int $oldId, array $contactForm)
    {
        $this->tester->sendPUT('/contact-form/'.$oldId, $contactForm);
        $this->tester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->tester->seeResponseIsJson();
        $this->tester->seeResponseMatchesJsonType(['property_path' => 'string', 'message' => 'string']);
    }

    /**
     * @depends      testPutContactFormAction
     *
     * @dataProvider provideExistingContactForm
     *
     * @param int $id
     */
    public function testDeleteContactFormAction(int $id)
    {
        $this->tester->sendDELETE('/contact-form/'.$id);
        $this->tester->seeResponseCodeIs(HttpCode::OK);
        $this->tester->seeResponseIsJson();

        $this->tester->dontSeeInDatabase($this->table, ['id' => $id]);
    }

    public function testDeleteContactFormActionNotFound()
    {
        $this->tester->sendDELETE('/contact-form/7000');
        $this->tester->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $this->tester->seeResponseIsJson();
    }

    /**
     * @return array
     */
    public function provideContactForms(): array
    {
        return [
            'Developer' => [
                1, [
                    'email'   => 'developer@mail.com',
                    'message' => 'some info',
                ],
            ],
            'Accounter' => [
                2, [
                    'email'   => 'accounter@mail.com',
                    'message' => 'some info',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideExistingContactForm(): array
    {
        return ['Developer' => $this->provideContactForms()['Developer']];
    }

    /**
     * @return array
     */
    public function provideValidationContactForms(): array
    {
        $message       = '';
        $messageLength = 1001;

        for ($i = 0; $i < $messageLength; $i++) {
            $message .= 'a';
        }

        return [
            'email is required'   => [
                1, [
                    'message' => 'asd',
                ],
            ],
            'message is required' => [
                1, [
                    'email' => 'asd@mail.com',
                ],
            ],
            'wrong email'         => [
                1, [
                    'email'   => 'asd@asd.',
                    'message' => 'asd',
                ],
            ],
            'message is too long' => [
                1, [
                    'email'   => 'asd@mail.com',
                    'message' => $message,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideValidationContactForm(): array
    {
        return ['wrong email' => $this->provideValidationContactForms()['wrong email']];
    }

    /**
     * @return array
     */
    private function getJsonType(): array
    {
        return [
            'id'      => 'integer',
            'email'   => 'string',
            'message' => 'string',
        ];
    }
}
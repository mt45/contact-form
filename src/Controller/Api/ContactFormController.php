<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\ContactForm;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ContactFormController
 *
 * @SWG\Response(
 *     response=400,
 *     description="Invalid request data"
 * )
 * @SWG\Response(
 *     response=404,
 *     description="Not found"
 * )
 * @SWG\Response(
 *     response=500,
 *     description="Internal Server Error"
 * )
 * @SWG\Tag(name="contact form")
 *
 * @package App\Controller\Api
 */
class ContactFormController extends FOSRestController
{
    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $validator;
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * ContactFormController constructor.
     *
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param \Doctrine\ORM\EntityManagerInterface                      $em
     */
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->validator = $validator;
        $this->em        = $em;
    }

    /**
     * Get contact form
     *
     * @Get("/contact-form/{id}")
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns a contact form by id",
     *     @Model(type=\App\Entity\ContactForm::class)
     * )
     *
     * @param ContactForm $contactForm
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getContactFormAction(ContactForm $contactForm): View
    {
        return $this->view($contactForm, Response::HTTP_OK);
    }

    /**
     * Save contact form
     *
     * @Post(
     *     "/contact-form",
     *     requirements={
     *          "email": "\w+",
     *          "message": "\w+"
     *     }
     * )
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="email", type="string"),
     *          @SWG\Property(property="message", maxLength=1000),
     *     )
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Created",
     *     @Model(type=\App\Entity\ContactForm::class)
     * )
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postContactFormAction(Request $request): View
    {
        $contactForm = new ContactForm();
        $contactForm->setEmail($request->get('email'));
        $contactForm->setMessage($request->get('message'));
        $validationErrors = $this->validator->validate($contactForm);

        if (\count($validationErrors) > 0) {
            return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
        }

        $this->em->persist($contactForm);
        $this->em->flush();

        return $this->view($contactForm, Response::HTTP_CREATED);
    }

    /**
     * Update contact form
     *
     * @Put("/contact-form/{id}")
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="email", type="string"),
     *          @SWG\Property(property="message", maxLength=1000),
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Updated",
     *     @Model(type=\App\Entity\ContactForm::class)
     * )
     *
     * @param ContactForm $contactForm
     * @param Request     $request
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putContactFormAction(ContactForm $contactForm, Request $request): View
    {
        $email   = $request->get('email');
        $message = $request->get('message');

        if ($email) {
            $contactForm->setEmail($email);
        }

        if ($message) {
            $contactForm->setMessage($message);
        }

        $validationErrors = $this->validator->validate($contactForm);

        if (\count($validationErrors) > 0) {
            return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
        }

        $this->em->persist($contactForm);
        $this->em->flush();

        return $this->view($contactForm, Response::HTTP_OK);
    }

    /**
     * Delete contact form
     *
     * @Delete("/contact-form/{id}")
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Deleted",
     *     @Model(type=\App\Entity\ContactForm::class)
     * )
     *
     * @param ContactForm $contactForm
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteContactFormAction(ContactForm $contactForm): View
    {
        $this->em->remove($contactForm);
        $this->em->flush();

        return $this->view($contactForm, Response::HTTP_OK);
    }
}
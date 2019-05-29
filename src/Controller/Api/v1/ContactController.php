<?php

namespace App\Controller\Api\v1;

use App\Form\ContactType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    /**
     * Creates a Contact resource
     * @Rest\Post("/contacts")
     * @param Request $request
     * @param $contactProducer
     * @return Response
     */
    public function store(Request $request, $contactProducer)
    {
        $form = $this->createForm(ContactType::class, $request->request->all());
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $contactProducer
                ->setContentType('application/json')
                ->publish(\json_encode($request->request->all()));

            return new JsonResponse('Success');
        } else {
            return new JsonResponse('Invalid request', 422);
        }
    }
}
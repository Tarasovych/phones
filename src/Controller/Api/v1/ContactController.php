<?php

namespace App\Controller\Api\v1;

use App\Entity\Phone;
use App\Entity\User;
use App\Form\ContactType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    /**
     * Creates a Contact resource
     * @Rest\Post("/contacts")
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $form = $this->createForm(ContactType::class, $request->request->all());
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('old_sound_rabbit_mq.store_contact_producer')
                ->setContentType('application/json')
                ->publish(\json_encode($request->request->all()));

            return new JsonResponse('Success');
        } else {
            return new JsonResponse('Invalid request', 422);
        }
    }
}
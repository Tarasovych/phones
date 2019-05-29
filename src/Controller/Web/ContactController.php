<?php

namespace App\Controller\Web;

use App\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    /**
     * Creates a Contact resource
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param $finder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, PaginatorInterface $paginator, $finder)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $results = $repository->sortByField($request->get('sort'));
        if ($request->get('q'))
            $results = $finder->find($request->get('q'));

        $users = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            5);
        return $this->render('contacts.html.twig', ['users' => $users]);
    }
}
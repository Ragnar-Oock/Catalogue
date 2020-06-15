<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/users")
 */
class UserController extends AbstractController
{
	/**
	 * @Route("/", name="user_index", methods={"GET"})
	 */
	public function index(UserRepository $userRepository): Response
	{
		return $this->render('admin/user/index.html.twig', [
			'users' => $userRepository->findAll(),
		]);
	}

	/**
	 * @Route("/new", name="user_new", methods={"GET","POST"})
	 */
	public function new(Request $request): Response
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute('user_index');
		}

		return $this->render('admin/user/new.html.twig', [
			'user' => $user,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="user_show", methods={"GET"})
	 */
	public function show(User $user): Response
	{
		return $this->render('admin/user/show.html.twig', [
			'user' => $user,
		]);
	}

	// /**
	//  * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
	//  */
	// public function edit(Request $request, User $user): Response
	// {
	// 	$form = $this->createForm(UserType::class, $user);
	// 	$form->handleRequest($request);

	// 	if ($form->isSubmitted() && $form->isValid()) {
	// 		$this->getDoctrine()->getManager()->flush();

	// 		return $this->redirectToRoute('user_index');
	// 	}

	// 	return $this->render('admin/user/edit.html.twig', [
	// 		'user' => $user,
	// 		'form' => $form->createView(),
	// 	]);
	// }

	/**
	 * @Route("/{user}", name="user_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, User $user): Response
	{
		if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			// dont remove the user entity as its linked to potencially many reservations we need to keep track of
			// $entityManager->remove($user);

			// only remove personnal data
			$user->setEmail('');
			$user->setFirstname('');
			$user->setLastname('');
			$user->setRoles([]);
			$entityManager->flush();
		}

		return $this->redirect($this->generateUrl('app_logout'));
	}

	/**
	 * @Route("/{user}/bannish", name="user_bannish")
	 */
	public function bannish(Request $request, User $user): Response
	{
		if ($this->isCsrfTokenValid('bannish'.$user->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$user->setRoles([]);
			$entityManager->flush();
		}

		$referer = $request->headers->get('referer');
		if ($referer && is_string($referer)) {
			return $this->redirect($referer);
		} elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
			return $this->redirectToRoute('admin_reservation_index');
		} else {
			return $this->redirectToRoute("reservation_index");
		}

		return $this->redirectToRoute('');
	}

	/**
	 * @Route("/{user}/promote", name="user_promote")
	 */
	public function promote(Request $request, User $user): Response
	{
		if ($this->isCsrfTokenValid('promote'.$user->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
			$entityManager->flush();
		}

		$referer = $request->headers->get('referer');
		if ($referer && is_string($referer)) {
			return $this->redirect($referer);
		} elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
			return $this->redirectToRoute('admin_reservation_index');
		} else {
			return $this->redirectToRoute("reservation_index");
		}

		return $this->redirectToRoute('');
	}
}

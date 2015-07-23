<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spraed\CommandUserBundle\CommandBus\AddRoleCommand;
use Spraed\CommandUserBundle\CommandBus\DisableUserCommand;
use Spraed\CommandUserBundle\CommandBus\EnableUserCommand;
use Spraed\CommandUserBundle\CommandBus\RemoveRoleCommand;
use Spraed\CommandUserBundle\CommandBus\ResetPasswordCommand;
use Spraed\CommandUserBundle\CommandBus\UpdateUserProfileCommand;
use Spraed\CommandUserBundle\Entity\User;
use Spraed\CommandUserBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author stedekay <stedekay@posteo.de>
 *
 * @Route("users")
 */
class UserAdministrationController extends Controller
{

    /**
     * @var UserRepository
     *
     * @DI\Inject("spraed.user.repository")
     */
    private $userRepository;

    /**
     * @Route("", name="spraed_user_list")
     * @Template("user/user_list.html.twig")
     * @Method("GET")
     *
     * @return array
     */
    public function getUsersAction()
    {
        $users = $this->userRepository->findAllUsers();

        return [
            'users' => $users,
        ];
    }

    /**
     * @Route("/add", name="spraed_user_add")
     * @Template("user/user_registration.html.twig")
     * @Method("GET")
     *
     * @return array
     */
    public function addUserAction()
    {
        $form = $this->createSignUpForm();

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     *
     * @Route("", name="spraed_user_signup")
     * @Template("user/user_registration.html.twig")
     * @Method("POST")
     *
     * @return array
     */
    public function signUpUserAction(Request $request)
    {
        $form = $this->createSignUpForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('command_bus')->handle($form->getData());
//            $this->utils->addFlash('success', 'user.flash.signup_success');

            return $this->redirectToRoute('get_users');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @return Form
     */
    private function createSignUpForm()
    {
        $form = $this->createForm('spraed.user_signup', null, [
            'action' => $this->generateUrl('spraed_user_signup'),
            'method' => 'POST',
        ]);

        return $form;
    }

    /**
     * @param User $user
     *
     * @Route("/{username}", name="spraed_user_details")
     * @Template("user/user_details.html.twig")
     * @Method("GET")
     *
     * @return array
     */
    public function getUserAction(User $user)
    {
        return [
            'user' => $user,
        ];
    }

    /**
     * @param User $user
     *
     * @Route("/{username}/edit", name="spraed_user_edit")
     * @Template("user/user_edit.html.twig")
     * @Method("GET")
     *
     * @return array
     */
    public function editUserAction(User $user)
    {
        $form = $this->createUserEditForm($user);

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @Route("/{username}", name="spraed_user_update")
     * @Template("user/user_edit.html.twig")
     * @Method("PUT")
     *
     * @return array
     */
    public function updateUserAction(Request $request, User $user)
    {
        $form = $this->createUserEditForm($user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('command_bus')->handle($form->getData());

            return $this->redirectToRoute('get_user', ['username' => $user->getUsername()]);
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param User $user
     *
     * @return Form
     */
    private function createUserEditForm(User $user)
    {
        $command = new UpdateUserProfileCommand($user);

        $form = $this->createForm('profile_edit', $command, [
            'action' => $this->generateUrl('spraed_user_update', ['username' => $user->getUsername()]),
            'method' => 'PUT',
        ]);

        return $form;
    }

    /**
     * @param User   $user
     * @param string $role
     *
     * @Route("/{username}/addrole/{role}", name="spraed_user_add_role")
     * @Method("PUT")
     *
     * @return Response
     */
    public function addRoleAction(User $user, $role)
    {
        $command = new AddRoleCommand($user, $role);
        $this->get('command_bus')->handle($command);

        return new Response('Added role');
    }

    /**
     * @param User   $user
     * @param string $role
     *
     * @Route("/{username}/removerole/{role}", name="spraed_user_remove_role")
     * @Method("PUT")
     *
     * @return Response
     */
    public function removeRoleAction(User $user, $role)
    {
        $command = new RemoveRoleCommand($user, $role);
        $this->get('command_bus')->handle($command);

        return new Response('Removed role');
    }

    /**
     * @param User $user
     *
     * @Route("/{username}/reset", name="spraed_user_reset_password")
     * @Method("PUT")
     *
     * @return Response
     */
    public function resetPasswordAction(User $user)
    {
        // todo: check if user is enabled

        $command = new ResetPasswordCommand($user);
        $this->get('command_bus')->handle($command);
//        $this->utils->addFlash('success', 'user.flash.reset_password');

        return $this->redirectToRoute('spraed_user_details', ['username' => $user->getUsername()]);
    }

    /**
     * @param User $user
     *
     * @Route("/{username}/enable", name="spraed_user_enable")
     * @Method("PUT")
     *
     * @return Response
     */
    public function enableUserAction(User $user)
    {
        $command = new EnableUserCommand($user);
        $this->get('command_bus')->handle($command);

        return $this->redirectToRoute('spraed_user_details', ['username' => $user->getUsername()]);
    }

    /**
     * @param User $user
     *
     * @Get("/{username}/disable", name="spraed_user_disable")
     * @Method("PUT")
     *
     * @return Response
     */
    public function disableUserAction(User $user)
    {
        $command = new DisableUserCommand($user);
        $this->get('command_bus')->handle($command);

        return $this->redirectToRoute('spraed_user_details', ['username' => $user->getUsername()]);
    }
}
<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spraed\CommandUserBundle\CommandBus\UpdatePasswordCommand;
use Spraed\CommandUserBundle\CommandBus\UpdateUserProfileCommand;
use Spraed\CommandUserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author stedekay <stedekay@posteo.de>
 *
 * @Prefix("/profile")
 */
class UserProfileController extends Controller
{

    /**
     * @Route("", name="spraed_profile_details")
     * @Template("user/profile_details.html.twig")
     * @Method("GET")
     *
     * @return array
     */
    public function getProfileAction()
    {
        return [
            'user' => $this->getUser(),
        ];
    }

    /**
     * @Route("/edit", name="spraed_profile_edit")
     * @Template("user/profile_edit.html.twig")
     * @Method("GET")
     *
     * @return array
     */
    public function editProfileAction()
    {
        $user = $this->getUser();
        $form = $this->createProfileEditForm($user);

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     *
     * @Route("", name="spraed_profile_update")
     * @Template("user/profile_edit.html.twig")
     * @Method("PUT")
     *
     * @return array
     */
    public function updateProfileAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createProfileEditForm($user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('command_bus')->handle($form->getData());

            return $this->redirectToRoute('spraed_profile_details');
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
    private function createProfileEditForm(User $user)
    {
        $command = new UpdateUserProfileCommand($user);

        $form = $this->createForm('spraed.profile_edit', $command, [
            'action' => $this->generateUrl('spraed_profile_update'),
            'method' => 'PUT',
        ]);

        return $form;
    }

    /**
     * @Route("/password/edit", name="spraed_profile_edit_password")
     * @Template("user/profile_edit_password.html.twig")
     * @Method("GET")
     *
     * @return array
     */
    public function editPasswordAction()
    {
        $user = $this->getUser();
        $form = $this->createPasswordForm($user);

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     *
     * @Route("/password", name="spraed_profile_update_password")
     * @Template("user/profile_edit_password.html.twig")
     * @Method("PUT")
     *
     * @return Response
     */
    public function updatePasswordAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createPasswordForm($user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('command_bus')->handle($form->getData());
//            $this->utils->addFlash('success', 'user.flash.password_updated');

            return $this->redirectToRoute('spraed_profile_details');
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    private function createPasswordForm(User $user)
    {
        $command = new UpdatePasswordCommand($user);

        $form = $this->createForm('profile_change_password', $command, [
            'action' => $this->generateUrl('spraed_profile_update_password'),
            'method' => 'PUT',
        ]);

        return $form;
    }
}
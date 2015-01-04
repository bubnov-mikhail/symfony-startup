<?php

namespace App\UserBundle\Controller;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use App\UserBundle\Form\Type\AddUserType;
use App\UserBundle\Form\Type\RegistrUserType;
use App\UserBundle\Form\Type\EditUserProfileType;
use App\UserBundle\Entity\User;

/**
* @Route("/settings/users")
*/
class UsersController extends Controller
{
    /**
     * @Route("/", name="user_list")
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppUserBundle:User')->findAll();

        return [
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/add", name="user_add")
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     */
    public function addAction()
    {
        $form = $this->createForm(new AddUserType());

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/registration", name="user_registration")
     * @Template()
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function registrationAction()
    {
        $user  = new User();
        $request = $this->getRequest();
        $form = $this->createForm(new RegistrUserType(), $user);

        if ('POST' === $request->getMethod()) {
            $form->handlerequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $user->setEnabled(1);
                $user->addRole('ROLE_USER');
                $em->persist($user);
                $em->flush();

                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.context')->setToken($token);

                $this->get('session')
                        ->getFlashBag()
                        ->add('notice', 'registration.complete');

                return $this->redirect($this->generateUrl('index'));
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/create", name="user_create")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("AppUserBundle:Users:add.html.twig")
     */
    public function createAction()
    {
        $entity  = new User();
        $request = $this->getRequest();
        $form    = $this->createForm(new AddUserType(), $entity);
        $form->handlerequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(1);
            $entity->addRole($form->getData()->getMyRole());
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Новый пользователь успешно добавлен');

            return $this->redirect($this->generateUrl('index'));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/{userId}", name="user_edit")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function editAction($userId)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$user = $em->getRepository('AppUserBundle:User')->find($userId)) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $form = $this->createForm(new EditUserProfileType(), $user, [
            'action' => $this->generateUrl('user_update', ["userId" => $userId]),
        ]);
        $request = $this->getRequest();
        if ('POST' === $request->getMethod()) {
            $form->handlerequest($request);
            if ($form->isValid()) {
                $user->setRoles([]);
                $user->addRole($form->getData()->getMyRole());
                $plainPassword = $form->get('plainPassword')->getData();
                $slave->setPlainPassword($plainPassword);
                $userManager = $this->container->get('fos_user.user_manager');
                $userManager->updateUser($user, true);
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'common.profile.updated');
                
                return $this->redirect($this->generateUrl('cabinet').'#userSettings');
            }
        }
        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/update/{id}", name="user_update")
     * @Template("AppUserBundle:Users:edit.html.twig")
     * @Secure(roles="ROLE_ADMIN")
     * @Method("POST")
     */
    public function updateAction($userId)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$user = $em->getRepository('AppUserBundle:User')->find($id)) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $request = $this->getRequest();
        $form = $this->createForm(new EditUserProfileType(), $user);

        if ($request->getMethod() == 'POST') {
            $form->handlerequest($request);

            if ($form->isValid()) {
                $plainPass = $form->getData()->getPlainPassword();
                $user->setRoles([]);
                $user->addRole($form->getData()->getMyRole());
                if (!empty($plainPass)) {
                    $userManager = $this->container->get('fos_user.user_manager');
                    $user->setPlainPassword($plainPass);
                    $userManager->updateUser($user, true);
                }

                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Информация пользователя успешно изменена');

                return $this->redirect($this->generateUrl('user_list'));
            }
        }

        return [
            'user' => $user,
            'id' => $id,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/delete/{id}", name="user_delete")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$user = $em->getRepository('AppUserBundle:User')->find($id)) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $request = $this->getRequest();
        $form = $this->createDeleteForm($id);

        if ('POST' === $request->getMethod()) {
            $form->handlerequest($request);
            if ($form->isValid()) {
                $em->remove($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Пользователь успешно удален');

                return $this->redirect($this->generateUrl('user_list'));
            }
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creating delete form
     * @param  integer                            $id
     * @return Symfony\Component\Form\FormBuilder
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(['id' => $id])
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}

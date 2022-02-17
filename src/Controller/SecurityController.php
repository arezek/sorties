<?php

namespace App\Controller;

use App\Entity\Participant;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Service\ResetInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //if ($this->getUser()) {
        //    return $this->redirectToRoute('main_index');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        $this->addFlash('notice', 'Vous ête déconnecté !');
        return $this->render('main/index.html.twig');
    }
    /**
     * @Route("/forgottenPassword", name="mdp")
     */
    public function forgottenPassword(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('mail', EmailType::class)
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $mail = $form->getData('mail');
            var_dump($mail);
            $em = $this->getDoctrine()->getManager();

            $participant = $em->getRepository(Participant::class)
                ->findOneBy([
                    'mail' => $mail
                ]);
            if (!$participant) {
                $this->addFlash('warning', "Cet email n'existe pas.");
                return $this->redirectToRoute("app_login");

            } else {

                $this->addFlash('warning', "Un email a été envoyé");
                return $this->redirectToRoute("main_index");
            }
        }
                    return $this->render('security/forgottenPassword.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
            }

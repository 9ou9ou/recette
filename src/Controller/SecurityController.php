<?php

namespace App\Controller;

use App\Form\ResetPassType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     *
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //if the user is logged, we make sure that he can't access the loginPage
         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }

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
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * The function make sure to send a email if the user clicked forgotPassword
     * @Route("/forgotPassword", name="app_forgotten_password")
     * @param Request $request
     * @param UserRepository $users
     * @param \Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     */
    public function forgotPassword (Request $request, UserRepository $users, \Swift_Mailer $mailer,
                                    TokenGeneratorInterface $tokenGenerator): Response {

         $form = $this->createForm(ResetPassType::class);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             //Recover the data in the form
            $donnees = $form->getData();
            $user = $users->findOneByEmail($donnees['email']);
            // if the user doesn't exist
             if ($user === null) {

            $this->addFlash('danger', 'This mail adress is unknown');
            return $this->redirectToRoute('app_login');
        }
          // Generate a token form initialisation of the password
           $token = $tokenGenerator->generateToken();

        try{
            $user->setResetToken($token);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('app_login');
        }

        // Generate the URL for the reset of password
        $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
        //handle the mail
        $message = (new \Swift_Message('Mot de passe oublié'))
            ->setFrom('koussayjebari2070@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                "Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site. Veuillez cliquer sur le lien suivant : " . $url."</p>",
                'text/html'
            )
        ;
        $mailer->send($message);

        $this->addFlash('message', 'Password reset email sent!');
        return $this->redirectToRoute('app_login');
    }

    return $this->render('security/forgotten_password.html.twig',[
        'emailForm' => $form->createView(),
        ]);
}

    /**
     * the function handle the Password resetting
     * @Route("/reset_pass/{token}", name="app_reset_password")
     * @param Request $request
     * @param string $token
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $userRepo
     * @return RedirectResponse|Response
     */
     public function resetPassword(Request $request, string $token, 
                                   UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepo) {
        // We are looking for a user with the given token
         $user = $userRepo->findOneBy(['reset_token' => $token]);
            if ($user === null) {
                 $this->addFlash('danger', 'Token Inconnu');
                  return $this->redirectToRoute('app_login');
                  }

        //If the form is sent in post method
           if ($request->isMethod('POST')) {
               $user->setResetToken(null);
               $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
               
               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->persist($user);
               $entityManager->flush();
               
               $this->addFlash('message', 'Mot de passe mis à jour');
               return $this->redirectToRoute('app_login');
            }else {
        
        return $this->render('security/reset_password.html.twig', ['token' => $token]);
    }

}

}
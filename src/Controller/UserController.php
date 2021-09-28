<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\UserInfoFormType;
use App\Form\UserAddressFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(User $user): Response
    {
        // $user = $this->em->getRepository(User::class)->findOneBy($user);
        return $this->render('home/admin.html.twig', [
            'admin' => $user
        ]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(): Response
    {
        $user = $this->getUser();
        // $user = $this->em->getRepository(User::class)->findOneBy($user);
        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/profile/info/modify", name="modify_info")
     */
    public function modifyInfo(Request $request): Response
    {
        $updateBookForm = $this->createForm(UserInfoFormType::class, $this->getUser());

        $updateBookForm->handleRequest($request);

        if ($updateBookForm->isSubmitted() && $updateBookForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/modify_profile.html.twig', [
            'modify_profile_form' => $updateBookForm->createView()
        ]);
    }

    /**
     * @Route("/profile/address/add", name="add_address")
     */
    public function addAddress(Request $request): Response
    {
        $ad = new Address();
        $updateAddressForm = $this->createForm(UserAddressFormType::class, $ad);
        $updateAddressForm->handleRequest($request);

        if ($updateAddressForm->isSubmitted() && $updateAddressForm->isValid()) {
            $address = $updateAddressForm->getData();
            $user = $this->getUser();
            $user->setAddress($address);

            $this->em->persist($address);
            $this->em->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/add_address.html.twig', [
            'add_address_form' => $updateAddressForm->createView()
        ]);
    }

    /**
     * @Route("/profile/address/delete/{id}", name="delete_address")
     */
    public function deleteAddress(Address $id): Response
    {
        $user = $this->getUser();
        $user->setAddress(null);
        $this->em->remove($id);
        $this->em->flush(); // faire l'action en BD

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/profile/address/modify/{id}", name="modify_address")
     */
    public function modifyAddress(Request $request, Address $id): Response
    {
        $updateBookForm = $this->createForm(UserAddressFormType::class, $id);

        $updateBookForm->handleRequest($request);

        if ($updateBookForm->isSubmitted() && $updateBookForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/modify_address.html.twig', [
            'modify_address_form' => $updateBookForm->createView()
        ]);
    }
}

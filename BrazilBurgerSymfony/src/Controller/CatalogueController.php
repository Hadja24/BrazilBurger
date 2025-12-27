<?php
namespace App\Controller;

use App\Entity\Burger;
use App\Entity\Menu;
use App\Entity\Extra;
use App\Form\BurgerType;
use App\Form\MenuType;
use App\Form\ExtraType;
use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use App\Repository\ExtraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CatalogueController extends AbstractController
{
    #[Route('/catalogue', name: 'app_catalogue')]
    public function index(BurgerRepository $burgerRepository, MenuRepository $menuRepository, ExtraRepository $extraRepository): Response
    {
        return $this->render('catalogue/index.html.twig', [
            'burgers' => $burgerRepository->findBy(['archived' => false]),
            'menus' => $menuRepository->findBy(['archived' => false]),
            'extras' => $extraRepository->findBy(['archived' => false]),
        ]);
    }

    #[Route('/catalogue/burger/new', name: 'app_catalogue_burger_new')]
    public function newBurger(Request $request, EntityManagerInterface $entityManager): Response
    {
        $burger = new Burger();
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($burger);
            $entityManager->flush();

            $this->addFlash('success', 'Burger créé avec succès.');

            return $this->redirectToRoute('app_catalogue');
        }

        return $this->render('catalogue/new_burger.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue/menu/new', name: 'app_catalogue_menu_new')]
    public function newMenu(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($menu);
            $entityManager->flush();

            $this->addFlash('success', 'Menu créé avec succès.');

            return $this->redirectToRoute('app_catalogue');
        }

        return $this->render('catalogue/new_menu.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue/extra/new', name: 'app_catalogue_extra_new')]
    public function newExtra(Request $request, EntityManagerInterface $entityManager): Response
    {
        $extra = new Extra();
        $form = $this->createForm(ExtraType::class, $extra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($extra);
            $entityManager->flush();

            $this->addFlash('success', 'Complément créé avec succès.');

            return $this->redirectToRoute('app_catalogue');
        }

        return $this->render('catalogue/new_extra.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue/burger/{id}/edit', name: 'app_catalogue_burger_edit')]
    public function editBurger(Request $request, Burger $burger, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Burger modifié avec succès.');

            return $this->redirectToRoute('app_catalogue');
        }

        return $this->render('catalogue/edit_burger.html.twig', [
            'burger' => $burger,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue/menu/{id}/edit', name: 'app_catalogue_menu_edit')]
    public function editMenu(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Menu modifié avec succès.');

            return $this->redirectToRoute('app_catalogue');
        }

        return $this->render('catalogue/edit_menu.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue/extra/{id}/edit', name: 'app_catalogue_extra_edit')]
    public function editExtra(Request $request, Extra $extra, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExtraType::class, $extra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Complément modifié avec succès.');

            return $this->redirectToRoute('app_catalogue');
        }

        return $this->render('catalogue/edit_extra.html.twig', [
            'extra' => $extra,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/catalogue/burger/{id}/archive', name: 'app_catalogue_burger_archive', methods: ['POST'])]
    public function archiveBurger(Request $request, Burger $burger, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('archive'.$burger->getId(), $request->request->get('_token'))) {
            $burger->setArchived(true);
            $entityManager->flush();
            $this->addFlash('success', 'Burger archivé avec succès.');
        }

        return $this->redirectToRoute('app_catalogue');
    }

    #[Route('/catalogue/menu/{id}/archive', name: 'app_catalogue_menu_archive', methods: ['POST'])]
    public function archiveMenu(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('archive'.$menu->getId(), $request->request->get('_token'))) {
            $menu->setArchived(true);
            $entityManager->flush();
            $this->addFlash('success', 'Menu archivé avec succès.');
        }

        return $this->redirectToRoute('app_catalogue');
    }

    #[Route('/catalogue/extra/{id}/archive', name: 'app_catalogue_extra_archive', methods: ['POST'])]
    public function archiveExtra(Request $request, Extra $extra, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('archive'.$extra->getId(), $request->request->get('_token'))) {
            $extra->setArchived(true);
            $entityManager->flush();
            $this->addFlash('success', 'Complément archivé avec succès.');
        }

        return $this->redirectToRoute('app_catalogue');
    }
}
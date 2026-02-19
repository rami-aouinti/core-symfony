<?php

namespace App\Controller;

use App\Repository\PlatformRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @package HomeController
 * @author  Rami Aouinti <rami.aouinti@gmail.com>
 */
final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PlatformRepository $platformRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'platforms' => $platformRepository->findBy([], ['id' => 'ASC']),
        ]);
    }
}

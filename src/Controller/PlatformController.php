<?php

namespace App\Controller;

use App\Entity\Platform;
use App\Form\PlatformType;
use App\Platform\Application\Command\CreatePlatformCommand;
use App\Platform\Application\Handler\CreatePlatformHandler;
use App\Platform\Domain\Exception\InvalidPlatformException;
use App\Repository\PlatformRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/platform')]
final class PlatformController extends AbstractController
{
    #[Route('/', name: 'app_platform_index', methods: ['GET'])]
    public function index(PlatformRepository $platformRepository): Response
    {
        return $this->render('platform/index.html.twig', [
            'platforms' => $platformRepository->findBy([], ['id' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'app_platform_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CreatePlatformHandler $createPlatformHandler): Response
    {
        $form = $this->createFormBuilder([
            'name' => '',
            'description' => '',
            'color' => '',
            'active' => false,
        ])
            ->add('name', TextType::class)
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('color', ColorType::class, [
                'required' => false,
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{name: string|null, description: string|null, color: string|null, active: bool|null} $data */
            $data = $form->getData();

            $command = new CreatePlatformCommand(
                name: (string) ($data['name'] ?? ''),
                description: (string) ($data['description'] ?? ''),
                color: (string) ($data['color'] ?? ''),
                active: (bool) ($data['active'] ?? false),
            );

            try {
                $createPlatformHandler($command);
            } catch (InvalidPlatformException $exception) {
                $form->addError(new FormError($exception->getMessage()));

                return $this->render('platform/new.html.twig', [
                    'form' => $form,
                ]);
            }

            return $this->redirectToRoute('app_platform_index');
        }

        return $this->render('platform/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_platform_show', methods: ['GET'])]
    public function show(Platform $platform): Response
    {
        return $this->render('platform/show.html.twig', [
            'platform' => $platform,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_platform_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Platform $platform, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlatformType::class, $platform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_platform_index');
        }

        return $this->render('platform/edit.html.twig', [
            'platform' => $platform,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_platform_delete', methods: ['POST'])]
    public function delete(Request $request, Platform $platform, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$platform->getId(), (string) $request->getPayload()->get('_token'))) {
            $entityManager->remove($platform);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_platform_index');
    }
}

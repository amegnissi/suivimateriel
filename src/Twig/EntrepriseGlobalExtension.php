<?php

namespace App\Twig;

use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\EntrepriseRepository;

class EntrepriseGlobalExtension extends AbstractExtension implements GlobalsInterface
{
    private $entrepriseRepository;

    public function __construct(EntrepriseRepository $entrepriseRepository)
    {
        $this->entrepriseRepository = $entrepriseRepository;
    }

    public function getGlobals(): array
    {
        // Récupérer la première entreprise enregistrée
        $entreprise = $this->entrepriseRepository->findOneBy([]);

        return [
            'entreprise' => $entreprise
        ];
    }
}
<?php

namespace App\Entity;

use App\Enum\SessionStatut;
use App\Enum\MotifAbsence;
use App\Repository\SignatureSessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SignatureSessionRepository::class)]
class SignatureSession
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Session::class, inversedBy: 'signatures')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Session $session = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $statut = null; // Ex: "présent", "absent", "retard"

    #[ORM\Column(type: 'boolean')]
    private bool $justifie = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $motifAbsence = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $motifDetails = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $heureSignature = null;

    // Getters / Setters...
}
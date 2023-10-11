<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use App\Validator\Constraints\MoviePosterExists;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class Movie
{
    public final const SLUG_FORMAT = '\d{4}-'.Requirement::ASCII_SLUG;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    #[Assert\Length(min: 2)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    #[Assert\Regex(pattern: '#'.self::SLUG_FORMAT.'#')]
    #[Assert\Length(min: 7)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotNull()]
    #[Assert\Length(min: 20)]
    private ?string $plot = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull()]
    #[Assert\GreaterThanOrEqual('1 Jan 1910')]
    #[Assert\LessThanOrEqual('+100 years')]
    private ?\DateTimeImmutable $releasedAt = null;

    #[ORM\Column(length: 255)]
    #[MoviePosterExists()]
    private ?string $poster = null;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'movies')]
    #[Assert\Count(min: 1)]
    private Collection $genres;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(string $plot): static
    {
        $this->plot = $plot;

        return $this;
    }

    public function getReleasedAt(): ?\DateTimeImmutable
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(\DateTimeImmutable $releasedAt): static
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }
}

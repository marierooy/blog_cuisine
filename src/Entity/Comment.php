<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CookingBlog $CookingBlog = null;

    #[ORM\Column(length: 255)]
    private ?string $Content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCookingBlog(): ?CookingBlog
    {
        return $this->CookingBlog;
    }

    public function setCookingBlog(?CookingBlog $CookingBlog): self
    {
        $this->CookingBlog = $CookingBlog;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }
}

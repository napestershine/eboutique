<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\Table(name="categories")
 * @Gedmo\Tree(type="nested")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="title", length=64, unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", name="lft")
     * @Gedmo\TreeLeft
     */
    private $lft;

    /**
     * @ORM\Column(type="integer", name="lvl")
     * @Gedmo\TreeLevel
     */
    private $lvl;

    /**
     * @ORM\Column(type="integer", name="rgt")
     * @Gedmo\TreeRight
     */
    private $rgt;

    /**
     * @ORM\Column(type="integer", name="root", nullable=true)
     * @Gedmo\TreeRoot
     */
    private $root;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\TreeParent
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $products;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setLft(int $lft): self
    {
        $this->lft = $lft;

        return $this;
    }

    public function getLft(): ?int
    {
        return $this->lft;
    }

    public function setLvl(int $lvl): self
    {
        $this->lvl = $lvl;

        return $this;
    }

    public function getLvl(): ?int
    {
        return $this->lvl;
    }

    public function setRgt(int $rgt): self
    {
        $this->rgt = $rgt;

        return $this;
    }

    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    public function setRoot(int $root): self
    {
        $this->root = $root;

        return $this;
    }

    public function getRoot(): ?int
    {
        return $this->root;
    }

    public function setParent(self $parent = null): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function addChild(self $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    public function removeChild(self $child): void
    {
        $this->children->removeElement($child);
    }

    public function getChildren(): ArrayCollection
    {
        return $this->children;
    }

    public function addProduct(Product $product): self
    {
        $this->products[] = $product;

        return $this;
    }

    public function removeProduct(Product $product): void
    {
        $this->products->removeElement($product);
    }

    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }
}

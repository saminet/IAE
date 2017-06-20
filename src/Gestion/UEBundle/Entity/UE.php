<?php

namespace Gestion\UEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UE
 *
 * @ORM\Table(name="ue")
 * @ORM\Entity(repositoryClass="Gestion\UEBundle\Repository\UERepository")
 */
class UE
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="intitule", type="string", length=255)
     */
    private $intitule;

    /**
     * @var float
     *
     * @ORM\Column(name="creditUnite", type="float")
     */
    private $creditUnite;

    /**
     * @ORM\ManyToMany(targetEntity="Gestion\MatiereBundle\Entity\Matiere", cascade={"persist"})
     * @ORM\JoinTable(name="ue_matiere")
     */
    private $matieres;

    /**
     * @ORM\ManyToOne(targetEntity="Gestion\AbsenceBundle\Entity\Classe", cascade={"persist"})
     */
    private $classe;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creditUnite
     *
     * @param float $creditUnite
     *
     * @return UE
     */
    public function setCreditUnite($creditUnite)
    {
        $this->creditUnite = $creditUnite;

        return $this;
    }

    /**
     * Get creditUnite
     *
     * @return float
     */
    public function getCreditUnite()
    {
        return $this->creditUnite;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     *
     * @return UE
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set classe
     *
     * @param \Gestion\AbsenceBundle\Entity\Classe $classe
     *
     * @return UE
     */
    public function setClasse(\Gestion\AbsenceBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return \Gestion\AbsenceBundle\Entity\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    public function __construct()
    {
        $this->classe = new ArrayCollection();
        $this->matieres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add matiere
     *
     * @param \Gestion\MatiereBundle\Entity\Matiere $matiere
     *
     * @return UE
     */
    public function addMatiere(\Gestion\MatiereBundle\Entity\Matiere $matiere)
    {
        $this->matieres[] = $matiere;

        return $this;
    }

    /**
     * Remove matiere
     *
     * @param \Gestion\MatiereBundle\Entity\Matiere $matiere
     */
    public function removeMatiere(\Gestion\MatiereBundle\Entity\Matiere $matiere)
    {
        $this->matieres->removeElement($matiere);
    }

    /**
     * Get matieres
     */
    public function getMatieres()
    {
        return $this->matieres;
    }

    public function setMatieres($matieres)
    {
        $this->matieres = $matieres;

        return $this;
    }


}

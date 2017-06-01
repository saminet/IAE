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
     * @ORM\Column(name="coeffUnite", type="float")
     */
    private $coeffUnite;

    /**
     * @var float
     *
     * @ORM\Column(name="creditUnite", type="float")
     */
    private $creditUnite;


    /**
     * @var string
     *
     * @ORM\Column(name="niveau", type="string", length=255)
     */
    private $Niveau;

    /**
     * @var string
     *
     * @ORM\Column(name="filiere", type="string", length=255)
     */
    private $filiere;



    /**
     * @ORM\ManyToMany(targetEntity="Gestion\MatiereBundle\Entity\Matiere", cascade={"persist"})
     */
    private $matieres;


    public function __construct()
    {
        $this->matieres = new ArrayCollection();
    }

    public function addMatiere(Matiere $matiere)
    {
        $this->matieres[] = $matiere;

        return $this;
    }

    public function removeMatiere(Matiere $matiere)
    {
        $this->matieres->removeElement($matiere);
    }

    public function getMatieres()
    {
        return $this->matieres;
    }
    
    
    
    
    
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
     * Set coeffUnite
     *
     * @param float $coeffUnite
     *
     * @return UE
     */
    public function setCoeffUnite($coeffUnite)
    {
        $this->coeffUnite = $coeffUnite;

        return $this;
    }

    /**
     * Get coeffUnite
     *
     * @return float
     */
    public function getCoeffUnite()
    {
        return $this->coeffUnite;
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
     * Set niveau
     *
     * @param string $niveau
     *
     * @return UE
     */
    public function setNiveau($niveau)
    {
        $this->Niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return string
     */
    public function getNiveau()
    {
        return $this->Niveau;
    }

    /**
     * Set filiere
     *
     * @param string $filiere
     *
     * @return UE
     */
    public function setFiliere($filiere)
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * Get filiere
     *
     * @return string
     */
    public function getFiliere()
    {
        return $this->filiere;
    }
}

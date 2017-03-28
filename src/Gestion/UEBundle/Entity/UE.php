<?php

namespace Gestion\UEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="Gestion\NiveauBundle\Entity\Niveau", cascade={"persist"})
     */
    private $idNiveau;



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
     * Set idNiveau
     *
     * @param \Gestion\NiveauBundle\Entity\Niveau $idNiveau
     *
     * @return UE
     */
    public function setIdNiveau(\Gestion\NiveauBundle\Entity\Niveau $idNiveau = null)
    {
        $this->idNiveau = $idNiveau;

        return $this;
    }

    /**
     * Get idNiveau
     *
     * @return \Gestion\NiveauBundle\Entity\Niveau
     */
    public function getIdNiveau()
    {
        return $this->idNiveau;
    }
}

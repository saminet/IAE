<?php

namespace Gestion\MatiereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matiere
 *
 * @ORM\Table(name="matiere")
 * @ORM\Entity(repositoryClass="Gestion\MatiereBundle\Repository\MatiereRepository")
 */
class Matiere
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
     * @ORM\Column(name="NomMatiere", type="string", length=255)
     */
    private $nomMatiere;

    public function __toString()
    {
        return $this->nomMatiere;
    }

    /**
     * @var float
     *
     * @ORM\Column(name="Coefficient", type="float")
     */
    private $coefficient;

    /**
     * @var float
     *
     * @ORM\Column(name="Credit", type="float")
     */
    private $credit;

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
     * Set nomMatiere
     *
     * @param string $nomMatiere
     *
     * @return Matiere
     */
    public function setNomMatiere($nomMatiere)
    {
        $this->nomMatiere = $nomMatiere;

        return $this;
    }

    /**
     * Get nomMatiere
     *
     * @return string
     */
    public function getNomMatiere()
    {
        return $this->nomMatiere;
    }

    /**
     * Set coefficient
     *
     * @param float $coefficient
     *
     * @return Matiere
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * Get coefficient
     *
     * @return float
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * Set credit
     *
     * @param float $credit
     *
     * @return Matiere
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return float
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set classe
     *
     * @param \Gestion\AbsenceBundle\Entity\Classe $classe
     *
     * @return Matiere
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
}

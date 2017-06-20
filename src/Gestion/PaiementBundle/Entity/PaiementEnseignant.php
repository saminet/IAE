<?php

namespace Gestion\PaiementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gestion\EnseignantBundle\Entity\Enseignant;

/**
 * PaiementEnseignant
 *
 * @ORM\Table(name="PaiementEnseignant")
 * @ORM\Entity(repositoryClass="Gestion\PaiementBundle\Repository\PaiementEnseignantRepository")
 */
class PaiementEnseignant
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
     * @ORM\ManyToOne(targetEntity="Gestion\EnseignantBundle\Entity\Enseignant", cascade={"persist"})
     */
    private $enseignant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTranche1", type="date")
     */
    private $dateTranche1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTranche2", type="date")
     */
    private $dateTranche2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTranche3", type="date")
     */
    private $dateTranche3;

    /**
     * @var string
     *
     * @ORM\Column(name="etatDateTranche1", type="string", length=255)
     */
    private $EtatDateTranche1;

    /**
     * @var string
     *
     * @ORM\Column(name="etatDateTranche2", type="string", length=255)
     */
    private $EtatDateTranche2;

    /**
     * @var string
     *
     * @ORM\Column(name="etatDateTranche3", type="string", length=255)
     */
    private $EtatDateTranche3;


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
     * Set dateTranche1
     *
     * @param \DateTime $dateTranche1
     *
     * @return Paiement
     */
    public function setDateTranche1($dateTranche1)
    {
        $this->dateTranche1 = $dateTranche1;

        return $this;
    }

    /**
     * Get dateTranche1
     *
     * @return \DateTime
     */
    public function getDateTranche1()
    {
        return $this->dateTranche1;
    }

    /**
     * Set dateTranche2
     *
     * @param \DateTime $dateTranche2
     *
     * @return Paiement
     */
    public function setDateTranche2($dateTranche2)
    {
        $this->dateTranche2 = $dateTranche2;

        return $this;
    }

    /**
     * Get dateTranche2
     *
     * @return \DateTime
     */
    public function getDateTranche2()
    {
        return $this->dateTranche2;
    }

    /**
     * Set dateTranche3
     *
     * @param \DateTime $dateTranche3
     *
     * @return Paiement
     */
    public function setDateTranche3($dateTranche3)
    {
        $this->dateTranche3 = $dateTranche3;

        return $this;
    }

    /**
     * Get dateTranche3
     *
     * @return \DateTime
     */
    public function getDateTranche3()
    {
        return $this->dateTranche3;
    }

    /**
     * Set enseignant
     *
     * @param \Gestion\EnseignantBundle\Entity\Enseignant $enseignant
     *
     * @return Paiement
     */
    public function setEnseignant(\Gestion\EnseignantBundle\Entity\Enseignant $enseignant = null)
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    /**
     * Get enseignant
     *
     * @return \Gestion\EnseignantBundle\Entity\Enseignant
     */
    public function getEnseignant()
    {
        return $this->enseignant;
    }

    /**
     * Set etatDateTranche1
     *
     * @param string $etatDateTranche1
     *
     * @return PaiementEnseignant
     */
    public function setEtatDateTranche1($etatDateTranche1)
    {
        $this->EtatDateTranche1 = $etatDateTranche1;

        return $this;
    }

    /**
     * Get etatDateTranche1
     *
     * @return string
     */
    public function getEtatDateTranche1()
    {
        return $this->EtatDateTranche1;
    }

    /**
     * Set etatDateTranche2
     *
     * @param string $etatDateTranche2
     *
     * @return PaiementEnseignant
     */
    public function setEtatDateTranche2($etatDateTranche2)
    {
        $this->EtatDateTranche2 = $etatDateTranche2;

        return $this;
    }

    /**
     * Get etatDateTranche2
     *
     * @return string
     */
    public function getEtatDateTranche2()
    {
        return $this->EtatDateTranche2;
    }

    /**
     * Set etatDateTranche3
     *
     * @param string $etatDateTranche3
     *
     * @return PaiementEnseignant
     */
    public function setEtatDateTranche3($etatDateTranche3)
    {
        $this->EtatDateTranche3 = $etatDateTranche3;

        return $this;
    }

    /**
     * Get etatDateTranche3
     *
     * @return string
     */
    public function getEtatDateTranche3()
    {
        return $this->EtatDateTranche3;
    }
}

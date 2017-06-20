<?php

namespace Gestion\PaiementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaiementEtudiant
 *
 * @ORM\Table(name="PaiementEtudiant")
 * @ORM\Entity(repositoryClass="Gestion\PaiementBundle\Repository\PaiementEtudiantRepository")
 */
class PaiementEtudiant
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
     * @ORM\Column(name="type_paiement", type="string", length=255)
     */
    private $typePaiement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_tranche1", type="date")
     */
    private $dateTranche1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_tranche2", type="date")
     */
    private $dateTranche2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_tranche3", type="date")
     */
    private $dateTranche3;

    /**
     * @ORM\ManyToOne(targetEntity="Gestion\PreinscriptionBundle\Entity\Etudiant", cascade={"persist"})
     */
    private $etudiant;

    /**
     * @ORM\ManyToOne(targetEntity="Gestion\AbsenceBundle\Entity\Groupe", cascade={"persist"})
     */
    private $groupe;


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
     * Set typePaiement
     *
     * @param string $typePaiement
     *
     * @return PaiementEtudiant
     */
    public function setTypePaiement($typePaiement)
    {
        $this->typePaiement = $typePaiement;

        return $this;
    }

    /**
     * Get typePaiement
     *
     * @return string
     */
    public function getTypePaiement()
    {
        return $this->typePaiement;
    }

    /**
     * Set dateTranche1
     *
     * @param \DateTime $dateTranche1
     *
     * @return PaiementEtudiant
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
     * @return PaiementEtudiant
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
     * @return PaiementEtudiant
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
     * Set etudiant
     *
     * @param string $etudiant
     *
     * @return PaiementEtudiant
     */
    public function setEtudiant($etudiant)
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * Get etudiant
     *
     * @return string
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * Set groupe
     *
     * @param \Gestion\AbsenceBundle\Entity\Groupe $groupe
     *
     * @return PaiementEtudiant
     */
    public function setGroupe(\Gestion\AbsenceBundle\Entity\Groupe $groupe = null)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return \Gestion\AbsenceBundle\Entity\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }
}

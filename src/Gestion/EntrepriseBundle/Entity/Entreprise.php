<?php

namespace Gestion\EntrepriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entreprise
 *
 * @ORM\Table(name="entreprise")
 * @ORM\Entity(repositoryClass="Gestion\EntrepriseBundle\Repository\EntrepriseRepository")
 */
class Entreprise
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
     * @ORM\Column(name="nomEntrep", type="string", length=255)
     */
    private $nomEntrep;

    /**
     * @var string
     *
     * @ORM\Column(name="raisonSoc", type="string", length=255)
     */
    private $raisonSoc;

    /**
     * @var string
     *
     * @ORM\Column(name="adresseEntrep", type="string", length=255)
     */
    private $adresseEntrep;


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
     * Set nomEntrep
     *
     * @param string $nomEntrep
     *
     * @return Entreprise
     */
    public function setNomEntrep($nomEntrep)
    {
        $this->nomEntrep = $nomEntrep;

        return $this;
    }

    /**
     * Get nomEntrep
     *
     * @return string
     */
    public function getNomEntrep()
    {
        return $this->nomEntrep;
    }

    /**
     * Set raisonSoc
     *
     * @param string $raisonSoc
     *
     * @return Entreprise
     */
    public function setRaisonSoc($raisonSoc)
    {
        $this->raisonSoc = $raisonSoc;

        return $this;
    }

    /**
     * Get raisonSoc
     *
     * @return string
     */
    public function getRaisonSoc()
    {
        return $this->raisonSoc;
    }

    /**
     * Set adresseEntrep
     *
     * @param string $adresseEntrep
     *
     * @return Entreprise
     */
    public function setAdresseEntrep($adresseEntrep)
    {
        $this->adresseEntrep = $adresseEntrep;

        return $this;
    }

    /**
     * Get adresseEntrep
     *
     * @return string
     */
    public function getAdresseEntrep()
    {
        return $this->adresseEntrep;
    }
}


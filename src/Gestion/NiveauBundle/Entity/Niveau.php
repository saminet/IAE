<?php

namespace Gestion\NiveauBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Niveau
 *
 * @ORM\Table(name="niveau")
 * @ORM\Entity(repositoryClass="Gestion\NiveauBundle\Repository\NiveauRepository")
 */
class Niveau
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
     * @ORM\Column(name="nomNiveau", type="string", length=255)
     */
    private $nomNiveau;


    /**
     * @var string
     *
     * @ORM\Column(name="nomFiliere", type="string", length=255)
     */
    private $nomFiliere;



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
     * Set nomNiveau
     *
     * @param string $nomNiveau
     *
     * @return Niveau
     */
    public function setNomNiveau($nomNiveau)
    {
        $this->nomNiveau = $nomNiveau;

        return $this;
    }

    /**
     * Get nomNiveau
     *
     * @return string
     */
    public function getNomNiveau()
    {
        return $this->nomNiveau;
    }
    

    /**
     * Set nomFiliere
     *
     * @param string $nomFiliere
     *
     * @return Niveau
     */
    public function setNomFiliere ($nomFiliere)
    {
        $this->nomFiliere = $nomFiliere;

        return $this;
    }

    /**
     * Get nomFiliere
     *
     * @return string
     */
    public function getNomFiliere()
    {
        return $this->nomFiliere;
    }
}

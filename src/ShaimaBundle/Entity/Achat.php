<?php

namespace ShaimaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;




/**
 * Achat
 *
 * @ORM\Table(name="achat")
 * @ORM\Entity(repositoryClass="ShaimaBundle\Repository\AchatRepository")
 */
class Achat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\Regex(pattern="/[A-Za-z]$/", message="saisie une chaine de charactere")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\Regex(pattern="/[A-Za-z]$/", message="saisie une chaine de charactere")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     *  @Assert\Email(message = "The email is not a valid email.")
     *
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     * @Assert\Regex(pattern="/[A-Za-z]$/", message="saisie une chaine de charactere")
     */
    private $ville;
    /**
     * @ORM\ManyToOne(targetEntity="livraison")
     * @ORM\JoinColumn(name="type",referencedColumnName="id",onDelete="CASCADE")
     */
    private $livraison;

    /**
     *  @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     *
     */
    private $libelle;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * @return mixed
     */
    public function getLivraison()
    {
        return $this->livraison;
    }

    /**
     * @param mixed $livraison
     */
    public function setLivraison($livraison)
    {
        $this->livraison = $livraison;
    }
}


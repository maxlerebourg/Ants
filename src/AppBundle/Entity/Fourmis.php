<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
/**
 * Fourmis
 *
 * @ORM\Table(name="fourmis")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FourmisRepository")
 */
class Fourmis extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
        $this->famille = new ArrayCollection();
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer")
     * @Assert\NotBlank(message="Please enter your age.", groups={"Registration", "Profile","Edition"})
     * @Assert\Length(
     *     min=1,
     *     max=3,
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $age;

    /**
     * @MaxDepth(1)
     * @var arrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Fourmis", cascade={"persist"})
     */
    protected $famille;

    /**
     * @var string
     *
     * @ORM\Column(name="race", type="string", length=255)
     * @Assert\NotBlank(message="Please enter your race.", groups={"Registration", "Profile","Edit"})
     * @Assert\Length(
     *     min=3,
     *     max=99,
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $race;

    /**
     * @var string
     * @ORM\Column(name="nourriture", type="string", length=255, nullable = true)
     *
     */
    protected $nourriture;


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
     * Set age
     *
     * @param integer $age
     *
     * @return Fourmis
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set race
     *
     * @param string $race
     *
     * @return Fourmis
     */
    public function setRace($race)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return string
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * Set nourriture
     *
     * @param string $nourriture
     *
     * @return Fourmis
     */
    public function setNourriture($nourriture)
    {
        $this->nourriture = $nourriture;

        return $this;
    }

    /**
     * Get nourriture
     *
     * @return string
     */
    public function getNourriture()
    {
        return $this->nourriture;
    }
    /**
     * Get famille
     * @return ArrayCollection
     */
    public function getFamille()
    {
        $family = $this->famille;
        $list = new ArrayCollection();
        foreach ($family as $ant){
            $list->add(['id'=>$ant->getId(), 'username'=>$ant->getUsername()]);
        }
        return $list;
    }

    /**
     * Set famille
     * @param ArrayCollection $famille
     * @return Fourmis
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;
        return $this;
    }

    /**
     * @param $famille
     * @return Fourmis
     */
    public function addFamille($famille)
    {
        $this->famille->add($famille);
        return $this;
    }

    /**
     * @param $famille
     * @return Fourmis
     */
    public function removeFamille($famille)
    {
        $this->famille->removeElement($famille);
        return $this;
    }

}


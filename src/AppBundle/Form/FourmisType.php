<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class FourmisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('age', IntegerType::class)
            ->add('race',ChoiceType::class, array(
            'choices' => array('Fourmi rouge' => 'Fourmi rouge', 'Fourmi pharaon' => 'Fourmi pharaon', 'Fourmi charpentiere' => 'Fourmis charpentiere', 'Fourmi noire' => 'Fourmi noir'),
            'preferred_choices' => array('baz')));

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getAge()
    {
        return $this->getBlockPrefix();
    }
    public function getRace()
    {
        return $this->getBlockPrefix();
    }
    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

}

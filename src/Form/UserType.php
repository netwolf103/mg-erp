<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\User\Role;
use App\Repository\User\RoleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form type class of User.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class UserType extends AbstractType
{
    private $roleRepository;

    /**
     * Init role repository.
     * 
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        $profile = $options['profile'] ?? false;

        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'readonly' => $entity->getId() ? true : false,
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'readonly' => $profile ? true : false,
                ]
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('password', PasswordType::class, [
                'empty_data' => '',
                'required' => $entity->getId() ? false : true
            ])
        ;

        if (!$profile) {
            $builder
                ->add('is_active', ChoiceType::class, [
                    'choices' => User::getStatusList()
                ])
                ->add('role', ChoiceType::class, [
                    'choices'  => $this->getAllUserRoles(),
                    'label' => 'User Role',
                    'placeholder' => '-- Please Select --',
                    'data' => $entity->getRole(),
                    'choice_label' => function(Role $role, $key, $value) {
                        return $role->getName();
                    },                                        
                ])            
            ;
        }

        $builder->add('save', SubmitType::class, ['label' => 'Save']);
    }

    /**
     * {@inheritdoc}
     */ 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'profile' => false,
        ]);
    }

    /**
     * Return all roles.
     * 
     * @return array
     */
    private function getAllUserRoles()
    {
        $roles = [];

        foreach ($this->roleRepository->findAll() as $role) {
            $roles[] = $role;
        }
        return $roles;
    }
}

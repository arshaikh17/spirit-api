<?php

namespace Edcoms\SpiritApiBundle\Form\Type;

use Edcoms\SpiritApiBundle\Form\Type\CountryChoiceType;
use Edcoms\SpiritApiBundle\Helper\OrganisationHelper;
use Edcoms\SpiritApiBundle\Helper\OrganisationTypeHelper;
use Edcoms\SpiritApiBundle\Model\Country;
use Edcoms\SpiritApiBundle\Model\Organisation;
use Edcoms\SpiritApiBundle\Model\OrganisationType as OrganisationTypeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Form type for populating a manual SPIRIT organisation.
 *
 * @author  Dimitris Charitakis <dimitris.charitakis@edcoms.co.uk>
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class OrganisationTypeType extends AbstractType
{

    /**
     * @param  OrganisationHelper  $organisationHelper  The organisation helper service.
     */
    public function __construct()
    {

    }

    /**
     * {inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    

        $choices = array();
        foreach ($options['organisation_types'] as $orgType) {
            $choices[$orgType->getId()] = $orgType->getName();
        }

        $builder
            ->add('type', ChoiceType::class, [
                'placeholder'       => '',
                'choices'           => $choices,
                'required'          => true,
            ])
            ;
    }

    /**
     * {inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrganisationTypeModel::class,
            'organisation_types' => null
        ]);
    }
}
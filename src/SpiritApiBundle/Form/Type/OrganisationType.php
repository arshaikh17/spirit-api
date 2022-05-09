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
use Edcoms\SpiritApiBundle\Form\Type\OrganisationTypeType;
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
class OrganisationType extends AbstractType
{
    /**
     * @var  OrganisationHelper
     */
    protected $organisationHelper;

    /**
     * @var  OrganisationTypeHelper
     */
    protected $organisationTypeHelper;

    /**
     * @param  OrganisationHelper  $organisationHelper  The organisation helper service.
     */
    public function __construct(OrganisationHelper $organisationHelper, OrganisationTypeHelper $organisationTypeHelper)
    {
        $this->organisationHelper = $organisationHelper;
        $this->organisationTypeHelper = $organisationTypeHelper;
    }

    /**
     * {inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $organisation = null;
        $organisationTypes = null;

        if ($options['organisation_id'] !== null) {
            $organisation = $this->organisationHelper->getById($options['organisation_id']);
        }

        $organisationTypes = $this->organisationTypeHelper->getAll();
        $options['organisation_types'] = $organisationTypes;

        $builder
            ->add('id', HiddenType::class, [
                'data' => $organisation === null ? null : $organisation->getName(),
                'disabled' => $organisation !== null,
                'required' => false               
            ]);

        //add orgType field if lookup required for manual registrations.
        if ($options['organisation_types'] !== null) {
            $orgTypeChoices = array();
            foreach ($options['organisation_types'] as $orgType) {
                $orgTypeChoices[$orgType->getName()] = $orgType->getId();
            }
        }

        $registration_type = $options['registration_type'];

        //add visual fields.
        $builder
            ->add('name', null, [
                'data' => $organisation === null ? null : $organisation->getName(),
                'disabled' => $organisation !== null,
                'required' => true,
                'label' => 'Organisation Name' 
            ])
            //commented out to allow a preselected option without confusing user - this can be added back in place of hidden field below.
            // ->add('type', ChoiceType::class, [
            //     'placeholder'       => '',
            //     'choices'           => $orgTypeChoices,
            //     'required'          => true,
            //     'data' => $organisation === null ? $registration_type : $organisation->getType()->getId(),
            //     'disabled' => $registration_type !== null,
            //     'label' => 'Organisation Type',
            // ])
            ->add('type', HiddenType::class, [
                'required'      => true,
                'data' => $organisation === null ? $registration_type : $organisation->getType()->getId(),
                'disabled' => $registration_type !== null,
            ])            
            ->add('address1', null, [
                'data' => $organisation === null ? null : $organisation->getAddress1(),
                'disabled' => $organisation !== null,
                'required' => true,
                'label' => 'Address 1'
            ])
            ->add('address2', null, [
                'data' => $organisation === null ? null : $organisation->getAddress2(),
                'disabled' => $organisation !== null,
                'required' => false,
                'label' => 'Address 2'
            ])
            ->add('address3', null, [
                'data' => $organisation === null ? null : $organisation->getAddress3(),
                'disabled' => $organisation !== null,
                'required' => false,
                'label' => 'Address 3'
            ])
            ->add('town', null, [
                'data' => $organisation === null ? null : $organisation->getTown(),
                'disabled' => $organisation !== null,
                'required' => true
            ])
            ->add('region', null, [
                'data' => $organisation === null ? null : $organisation->getRegion(),
                'disabled' => $organisation !== null,
                'required' => true
            ])
            ->add('country', CountryChoiceType::class, [
                'data' => $organisation === null ? null : $organisation->getCountry()->getId(),
                'disabled' => $organisation !== null,
                'required' => true
            ])
            ->add('postcode', null, [
                'data' => $organisation === null ? null : $organisation->getPostcode(),
                'disabled' => $organisation !== null,
                'required' => true
            ])
            ->add('telephone', null, [
                'data' => $organisation === null ? null : $organisation->getTelephone(),
                'disabled' => $organisation !== null,
                'required' => false
            ])
            ;
    }

    /**
     * {inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Organisation::class,
            'organisation_id' => null,
            'organisation_types' => $this->organisationTypeHelper->getAll(),
            'registration_type' => null,
        ]);
    }
}
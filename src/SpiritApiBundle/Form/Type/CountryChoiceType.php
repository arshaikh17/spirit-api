<?php

namespace Edcoms\SpiritApiBundle\Form\Type;

use Edcoms\SpiritApiBundle\Helper\CountryHelper;
use Edcoms\SpiritApiBundle\Model\Country;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A choice form type to handle the selection of a SPIRIT country.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class CountryChoiceType extends ChoiceType
{
    /**
     * @var CountryHelper
     */
    private $countryHelper;

    /**
     * @var  Country[]|null
     */
    private $countries = null;

    /**
     * @param  CountryHelper                    $countryHelper      The SPIRIT country helper service.
     * @param  ChoiceListFactoryInterface|null  $choiceListFactory  The form factory service.
     */
    public function __construct(CountryHelper $countryHelper, ChoiceListFactoryInterface $choiceListFactory = null)
    {
        parent::__construct($choiceListFactory);

        $this->countryHelper = $countryHelper;
    }

    /**
     * {inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $countries = $this->countryHelper->getAllAsChoices();
        $resolver->setDefaults([
            'choices' => $countries,
            'choice_label' => function ($id) use ($countries) {
                $foundCountryName = null;
                
                foreach ($countries as $countryName => $countryId) {
                    if ($countryId === $id) {
                        $foundCountryName = $countryName;
                        break;
                    }
                }
                
                return $foundCountryName ?: '';
            }
        ]);
    }

    private function getCountries()
    {
        if (null === $this->countries) {
            $this->countries = $this->countryHelper->getAll();
        }

        return $this->countries;
    }
}

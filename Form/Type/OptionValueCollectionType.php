<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\AssortmentBundle\Form\Type;

use Sylius\Bundle\AssortmentBundle\Model\Option\OptionInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\FormBuilder;

/**
 * This is special collection type, inspired by original 'collection' type
 * implementation, designed to handle option values assigned to product variant.
 * Array of OptionInterface objects should be passed as 'options' option to build proper
 * set of choice types with option values list.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class OptionValueCollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if (!isset($options['options']) ||
            !is_array($options['options']) &&
            !($options['options'] instanceof \Traversable && $options['options'] instanceof \ArrayAccess)
        ) {
            throw new FormException(
                'array or (\Traversable and \ArrayAccess) of "Sylius\Bundle\AssortmentBundle\Model\Option\OptionInterface" must be passed to collection'
            );
        }
        foreach ($options['options'] as $i => $option) {
            if (!$option instanceof OptionInterface) {
                throw new FormException('Each object passed as option list must implement "Sylius\Bundle\AssortmentBundle\Model\Option\OptionInterface"');
            }

            $builder->add((string) $i, 'sylius_assortment_option_value_choice', array(
                'label'         => $option->getName(),
                'option'        => $option,
                'property_path' => '['.$i.']'
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'options' => null
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_assortment_option_value_collection';
    }
}

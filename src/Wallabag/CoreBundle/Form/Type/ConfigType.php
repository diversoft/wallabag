<?php

namespace Wallabag\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    private $themes = array();
    private $languages = array();

    /**
     * @param array $themes    Themes come from the LiipThemeBundle (liip_theme.themes)
     * @param array $languages Languages come from configuration, array just code language as key and label as value
     */
    public function __construct($themes, $languages)
    {
        $this->themes = array_combine(
            $themes,
            array_map(function ($s) { return ucwords(strtolower(str_replace('-', ' ', $s))); }, $themes)
        );

        $this->languages = $languages;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('theme', ChoiceType::class, array(
                'choices' => array_flip($this->themes),
                'choices_as_values' => true,
                'label' => 'config.form_settings.theme_label',
            ))
            ->add('items_per_page', null, array(
                'label' => 'config.form_settings.items_per_page_label',
            ))
            ->add('reading_speed', ChoiceType::class, array(
                'label' => 'config.form_settings.reading_speed',
                'choices' => array(
                    'I read ~100 words per minute' => '0.5',
                    'I read ~200 words per minute' => '1',
                    'I read ~300 words per minute' => '1.5',
                    'I read ~400 words per minute' => '2',
                ),
            ))
            ->add('language', ChoiceType::class, array(
                'choices' => array_flip($this->languages),
                'choices_as_values' => true,
                'label' => 'config.form_settings.language_label',
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'config.form.save',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wallabag\CoreBundle\Entity\Config',
        ));
    }

    public function getBlockPrefix()
    {
        return 'config';
    }
}

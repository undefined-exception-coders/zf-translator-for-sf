<?php

namespace UEC\ZendFrameworkTranslatorForSymfony;

use Symfony\Component\Translation\TranslatorInterface as SymfonyTranslatorInterface;
use Zend\I18n\Translator\TranslatorInterface as ZendTranslatorInterface;

class Translator implements SymfonyTranslatorInterface
{
    /**
     * @var ZendTranslatorInterface
     */
    private $zendTranslator;

    /**
     * @var string
     */
    private $locale;

    /**
     * Translator constructor.
     */
    public function __construct(ZendTranslatorInterface $zendTranslator, $defaultLocale = 'en_EN')
    {
        $this->zendTranslator = $zendTranslator;
        $this->locale = $defaultLocale;
    }

    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        $result = $this->zendTranslator->translate($id, $domain, $locale ?: $this->locale);
        return strtr($result, $parameters);
    }

    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        if (false !== strpos($id, '|')) {
            list($singular, $plural) = explode('|', $id);
        } else {
            $singular = $plural = $id;
        }

        $result = $this->zendTranslator->translatePlural(
            $this->trans($singular, $parameters, $domain, $locale),
            $this->trans($plural, $parameters, $domain, $locale),
            $number,
            $domain,
            $locale ?: $this->locale
        );

        return $result;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}
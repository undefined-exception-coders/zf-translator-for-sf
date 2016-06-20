<?php

namespace UEC\ZendFrameworkTranslatorForSymfony;

use Symfony\Component\Templating\Helper\Helper;

class TranslatorHelper extends Helper
{
    /**
     * @var Translator
     */
    private $bridgeTranslator;

    /**
     * BridgeTranslatorHelper constructor.
     *
     * @param Translator $bridgeTranslator
     */
    public function __construct(Translator $bridgeTranslator)
    {
        $this->bridgeTranslator = $bridgeTranslator;
    }

    public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        return $this->bridgeTranslator->trans($id, $parameters, $domain, $locale);
    }

    public function transChoice($id, $number, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        return $this->bridgeTranslator->translatePlural($id, $number, $parameters, $domain, $locale);
    }

    public function getName()
    {
        return 'translator';
    }
}
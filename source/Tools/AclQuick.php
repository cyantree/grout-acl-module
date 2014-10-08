<?php
namespace Grout\Cyantree\AclModule\Tools;

use Cyantree\Grout\App\GroutQuick;
use Cyantree\Grout\Tools\StringTools;
use Cyantree\Grout\Translation\Translator;

class AclQuick extends GroutQuick
{
    /** @var Translator */
    public $translator;
    public $translatorDefaultTextDomain = 'default';

    public function t($message, $textDomain = null, $locale = null)
    {
        if ($textDomain === null) {
            $textDomain = $this->translatorDefaultTextDomain;
        }

        return $this->translator->translate($message, $textDomain, $locale);
    }

    public function et($message, $textDomain = null, $locale = null, $context = 'html')
    {
        return $this->e($this->t($message, $textDomain, $locale), $context);
    }

    public function p($text, $arguments)
    {
        return StringTools::parse($text, $arguments);
    }
}
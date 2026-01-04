<?php

namespace Todo\Services;

class TemplateData
{
    /** @var array */
    private static array $templateData = [];

    /**
     * @return array
     */
    public static function getTemplateData(): array
    {
        return self::$templateData;
    }

    /**
     * @param array $templateData
     * @return void
     */
    public static function setTemplateData(array $templateData): void
    {
        self::$templateData = $templateData;
    }
}

<?php

class ConfigurationForDistribution
{
    private function __construct() {
    }

    public static function create(
        string $configurationSourceName,
        string $configurationSourceAbsolutePath,
        string $systemType,
        string $configFileName,
        string $debug,
    ): ?self {
        if (!self::configurationSourceHasConfig(
            $configurationSourceAbsolutePath,
            $configFileName
        )) {
            if ($debug) {
                printNoticeMessage(
                    sprintf(
                        'No %s config file in "%s".',
                        $configFileName,
                        $configurationSourceName,
                    )
                );
            }

            return null;
        }

        $configFileAbsolutePath = sprintf(
            '%s%s%s',
            $configurationSourceAbsolutePath,
            DIRECTORY_SEPARATOR,
            $configFileName,
        );

        $configFileArray = json_decode(file_get_contents($configFileAbsolutePath), true);

        if (empty($configFileArray)) {
            printErrorMessageAndExit(
                sprintf(
                    'Config file "%s" is empty or not valid JSON. To validate it use: https://jsonlint.com/',
                    $configFileAbsolutePath,
                )
            );
        }

        $configurationForDistribution = new self();

        if (empty($configFileArray['version'])) {
            printErrorMessageAndExit(
                sprintf(
                    'Version is not defined in "%s"',
                    $configFileAbsolutePath,
                )
            );
        }

        $configurationForDistribution->setVersion($configFileArray['version']);
        $configurationForDistribution->setManualConfigurationFolderName(
            empty($configFileArray['manual_configuration_folder_name'])
            ? ''
            : $configFileArray['manual_configuration_folder_name']
        );
        $configurationForDistribution->setTemplates(
            empty($configFileArray['templates'][$systemType])
            ? []
            : $configFileArray['templates'][$systemType]
        );

        return $configurationForDistribution;
    }

    private static function configurationSourceHasConfig(
        string $configurationSourceAbsolutePath,
        string $configFileName,
    ): bool {
        foreach (new DirectoryIterator($configurationSourceAbsolutePath) as $item) {
            if ($item->isDot()) {
                continue;
            }

            if ($item->getFileName() == $configFileName) {
                return true;
            }
        }

        return false;
    }

    private function setVersion(string $version): void {
        $this->version = $version;
    }

    private function setManualConfigurationFolderName(string $manualConfigurationFolderName): void {
        $this->manualConfigurationFolderName = $manualConfigurationFolderName;
    }

    private function setTemplates(array $templates): void {
        $this->templates = $templates;
    }

    public function getVersion(): string {
        return $this->version;
    }

    /**
     * @return string Returns empty string if folder name is not defined in configuration.
     */
    public function getManualConfigurationFolderName(): string {
        return $this->manualConfigurationFolderName;
    }

    /**
     * @return array ['template_relative_path' => ['template_variable_key' => `template_variable_value`]]
     * Returns empty array if no templates are defined.
     */
    public function getTemplates(): array {
        return $this->templates;
    }
}

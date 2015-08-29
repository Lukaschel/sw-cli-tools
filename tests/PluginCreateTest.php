<?php

namespace ShopwareCli\Tests;

use Shopware\PluginCreator\Services\Generator;
use Shopware\PluginCreator\Services\IoAdapter\Dummy;
use Shopware\PluginCreator\Services\NameGenerator;
use Shopware\PluginCreator\Services\Template;
use Shopware\PluginCreator\Struct\Configuration;
use ShopwareCli\Services\IoService;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class PluginCreateTest extends \PHPUnit_Framework_TestCase
{
    protected $fileProvider = [
        'Api' => [
            'config' => 'hasApi',
            'files' => [
                "Components/Api/Resource/Resource.tpl" => "Components/Api/Resource/Test.php",
                "Controllers/Api.tpl" => "Controllers/Api/Test.php"
            ]
        ],
        'BackendController' => [
            'config' => 'hasBackend',
            'files' => [
                "Controllers/Backend.tpl" => "Controllers/Backend/SwagTest.php"
            ]
        ],
        'Backend' => [
            'config' => 'hasBackend',
            'files' => [
                "Views/backend/application/app.tpl" => "Views/backend/swag_test/app.js",
                "Views/backend/application/controller/main.tpl" => "Views/backend/swag_test/controller/main.js",
                "Views/backend/application/model/main.tpl" => "Views/backend/swag_test/model/main.js",
                "Views/backend/application/store/main.tpl" => "Views/backend/swag_test/store/main.js",
                "Views/backend/application/view/detail/container.tpl" => "Views/backend/swag_test/view/detail/container.js",
                "Views/backend/application/view/detail/window.tpl" => "Views/backend/swag_test/view/detail/window.js",
                "Views/backend/application/view/list/list.tpl" => "Views/backend/swag_test/view/list/list.js",
                "Views/backend/application/view/list/window.tpl" => "Views/backend/swag_test/view/list/window.js",
            ]
        ],
        'Command' => [
            'config' => 'hasCommands',
            'files' => [
                "Commands/Command.tpl" => "Commands/Test.php"
            ]
        ],
        'ControllerPath' => [
            'config' => 'hasBackend',
            'files' => [
                "Subscriber/ControllerPath.tpl" => "Subscriber/ControllerPath.php",
            ]
        ],
        'Default' => [
            'config' => 'hasBackend',
            'files' => [
                "Bootstrap.tpl" => "Bootstrap.php",
                "Readme.tpl" => "Readme.md",
                "LICENSE" => "LICENSE",
                "plugin.tpl" => "plugin.json",
                "Subscriber/Frontend.tpl" => "Subscriber/Frontend.php",
                "phpunit.xml.dist.tpl" => "phpunit.xml.dist",
                "tests/Test.tpl" => "tests/Test.php"
            ]
        ],
        'Filter' => [
            'config' => 'hasFilter',
            'files' => [
                "Components/SearchBundleDBAL/Condition/Condition.tpl" => "Components/SearchBundleDBAL/Condition/SwagTestCondition.php",
                "Components/SearchBundleDBAL/Condition/ConditionHandler.tpl" => "Components/SearchBundleDBAL/Condition/SwagTestConditionHandler.php",
                "Components/SearchBundleDBAL/Facet/Facet.tpl" => "Components/SearchBundleDBAL/Facet/SwagTestFacet.php",
                "Components/SearchBundleDBAL/Facet/FacetHandler.tpl" => "Components/SearchBundleDBAL/Facet/SwagTestFacetHandler.php",
                "Components/SearchBundleDBAL/CriteriaRequestHandler.tpl" => "Components/SearchBundleDBAL/SwagTestCriteriaRequestHandler.php",
                "Subscriber/SearchBundle.tpl" => "Subscriber/SearchBundle.php"
            ]
        ],
        'Frontend' => [
            'config' => 'hasFrontend',
            'files' => [
                "Controllers/Frontend.tpl" => "Controllers/Frontend/SwagTest.php",
                "Views/frontend/plugin_name/index.tpl" => "Views/frontend/swag_test/index.tpl"
            ]
        ],
        'Model' => [
            'config' => 'hasModels',
            'files' => [
                "Models/Model.tpl" => "Models/SwagTest/Test.php",
                "Models/Repository.tpl" => "Models/SwagTest/Repository.php"
            ]
        ],
        'Widget' => [
            'config' => 'hasWidget',
            'files' => [
                "Views/backend/widget/main.tpl" => "Views/backend/swag_test/widgets/swag_test.js",
                "Snippets/backend/widget/labels.tpl" => "Snippets/backend/widget/labels.ini"
            ]
        ],
    ];

    private function getConfigObject()
    {
        $config = new Configuration();

        $config->hasBackend = false;
        $config->hasApi = false;
        $config->hasWidget = false;
        $config->hasFrontend = false;
        $config->hasCommands = false;
        $config->hasModels = false;
        $config->name = 'SwagTest';

        return $config;
    }

    /**
     * Test a plugin with backend files
     */
    public function testPluginGenerator()
    {

        foreach ($this->fileProvider as $name => $provider) {
            $config = $this->getConfigObject();
            $config->$provider['config'] = true;
            $config->backendModel = 'Shopware\CustomModels\SwagTest\Test';


            $ioAdapter = new Dummy();
            $generator = new Generator($ioAdapter, $config, new NameGenerator($config), new Template());
            $generator->setOutputDirectory('');

            $generator->run();

            foreach ($provider['files'] as $file) {
                $this->assertTrue(
                    in_array($file, array_keys($ioAdapter->getFiles())),
                    "{$file} not found in generated files"
                );
            }
        }

    }
}

<?php
namespace Cookbook;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
//Чтобы настроить ServiceManager, мы можем указать имя экземпляра класса или фабрику (замыкание, обратный вызов или имя
//класса фабричного класса), которая создает экземпляр объекта, когда ServiceManager нуждается в
//нем. Мы начнем с реализации getServiceConfig(), чтобы создать фабрику, которая создает CookbookTable.
class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\CookbookTable::class => function($container) {
                    $tableGateway = $container->get(Model\CookbookTableGateway::class);
                    return new Model\CookbookTable($tableGateway);
                },
                Model\CookbookTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Cookbook());
                    //задаем имя таблицы
                    return new TableGateway('recepts', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
//Наш контроллер зависит от CookbookTable, поэтому нам нужно создать фабрику для контроллера
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\CookbookController::class => function($container) {
                    return new Controller\CookbookController(
                        $container->get(Model\CookbookTable::class)
                    );
                },
            ],
        ];
    }
}

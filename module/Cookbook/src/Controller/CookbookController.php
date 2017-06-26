<?php
namespace Cookbook\Controller;

use Cookbook\Form\CookbookForm;
use Cookbook\Form\FindForm;
use Cookbook\Model\CookbookTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
/**
 * Контроллер для операций на главной странице и поиска.
 */
class CookbookController extends AbstractActionController
{
    private $table;

    public function __construct(CookbookTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        //формируем новый объект формы поиска для вида
        $form = new FindForm();
        $form->get('submit')->setValue('New search');

        //получаем запрос id категории (если он был)
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id>0) {

                return [
                    'recepts' => $this->table->getCat($id),
                    'form' => $form//форма поиска
                ];
        }

        //Получаем запрос
        $request = $this->getRequest();
        //Если запрос не является POST-запросом, то это означает, что данные формы не отправлены,
        // и нам нужно просто отобразить форму.
        if (! $request->isPost()) {
        return new ViewModel([
            'recepts' => $this->table->fetchAll(),//Выбираем все записи из таблицы
            'form' => $form//форма поиска
            //'finds' => $this->table->findCookbook(),//результаты поиска
        ]);
        }
    }

    public function findAction()
    {
        //формируем новый объект формы поиска для вида
        $form = new FindForm();
        $form->get('submit')->setValue('New search');

        //Получаем запрос
        $request = $this->getRequest();
        //Если запрос не является POST-запросом, то это означает, что данные формы не отправлены,
        // и нам нужно просто отобразить форму.
        if (! $request->isPost()) {
            return ['form' => $form];
        }

        //если это пост запрос тогда запускаем алгоритм поиска
        if ($request->isPost()) {
            $findme = strip_tags($request->getPost('find'));//удалить HTML и PHP-теги из строки
            $findme = mb_strtolower($findme);
            $otvet ='';
            $rowset = $this->table->findCookbook();//результаты поиска

            if ($findme != '') {//Если переменная не опустела после всего:
                foreach ($rowset as $item):
                    //объединяем 2 колонки в 1 для ускорени поиска
                    $bigstr = $item->name . $item->sostav;
                    //переводим в нижний регистр для улучшения поиска
                    $bigstr = mb_strtolower($bigstr);
                    //Проверим есть ли вхождение в строке
                    $pos = strpos($bigstr, $findme);

                    if ($pos === false) {
                        //echo "Строка '$findme' не найдена в строке '$mystring1'";
                    } else {
                        $otvet .= "Строка <b>'$findme'</b> найдена в рецепте: $item->name<br>";
                        $otvet .= "Состав которого: $item->sostav<br><hr>";
                    }
                endforeach;
            }
            if ($otvet == ''){
                $otvet .= "По результатам поиска <b>'$findme'</b> ничего не найдено.";
            }

            return new ViewModel([
                'form' => $form,//форма поиска
                'finds' => $otvet,//результаты поиска
            ]);
        }
    }
}
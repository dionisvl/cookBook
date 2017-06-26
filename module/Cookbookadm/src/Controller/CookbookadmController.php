<?php
namespace Cookbookadm\Controller;

use Cookbookadm\Form\CookbookadmForm;
use Cookbookadm\Model\Cookbookadm;
use Cookbookadm\Model\CookbookadmTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
/**
 * Контроллер CRUD Действий в админке.
 */
class CookbookadmController extends AbstractActionController
{
    private $table;

    public function __construct(CookbookadmTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'recepts' => $this->table->fetchAll(),//Выбираем все записи из таблицы
        ]);
    }

    public function addAction()
    {
        //        Мы создаем экземпляр CookbookadmForm и устанавливаем ярлык на кнопку отправки
        // — «Add». Мы делаем это здесь, поскольку мы хотим повторно использовать форму
        // при редактировании рецепта и будем использовать другую метку.
        $form = new CookbookadmForm();
        $form->get('submit')->setValue('Add');

        //        Если запрос не является POST-запросом, то это означает, что данные формы не отправлены,
        // и нам нужно отобразить форму. zend-mvc позволяет при необходимости вернуть
        // массив данных вместо модели представления;
        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        //Теперь, мы создаем экземпляр Album и передаем его входной фильтр в форму;
        // кроме того, мы передаем предоставленные данные из экземпляра запроса в форму.
        $cookbookadm = new Cookbookadm();
        $form->setInputFilter($cookbookadm->getInputFilter());
        $form->setData($request->getPost());

        //Если проверка формы выполнилась не успешно, мы повторно отображаем форму. На этом этапе, форма
        // содержит информацию о том, какие поля не прошли проверку, и почему, и эта
        // информация будет передана на уровень представления.
        if (! $form->isValid()) {
            return ['form' => $form];
        }

        //Если форма прошла проверку валидации, мы извлекаем данные
        // из формы и сохраняем их в модели с помощью saveAlbum().
        $cookbookadm->exchangeArray($form->getData());
        $this->table->saveCookbookadm($cookbookadm);
        //После того, как мы сохранили новую строку рецепта, мы перенаправляемся обратно в список рецепта, используя
        // плагин контроллера Redirect.
        return $this->redirect()->toRoute('cookbookadm');
    }

    public function editAction()
    {
        //мы ищем идентификатор, который находится в соответствующем маршруте, и используем его для
        //загрузки редактируемого рецепта:
        $id = (int) $this->params()->fromRoute('id', 0);
    //params — это плагин контроллера, который обеспечивает удобный способ извлечения параметров из соответствующего
    //маршрута. Мы используем его для получения идентификатора из маршрута, который мы создали в module.config.php
    //модуля Cookbookadm. Если идентификатор равен нулю, мы перенаправляем на действие add, в противном случае мы продолжаем
    //получать сущность альбома из базы данных.
        if (0 === $id) {
            return $this->redirect()->toRoute('cookbookadm', ['action' => 'add']);
        }
        try {
            $cookbookadm = $this->table->getCookbookadm($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('cookbookadm', ['action' => 'index']);
        }

    //Метод bind() формы прикрепляет модель к форме. Это используется для двух случаях:
    //    При отображении формы исходные значения для каждого элемента извлекаются из модели.
    //    После успешной проверки в isValid(), данные из формы возвращаются в модель.
        $form = new CookbookadmForm();
        $form->bind($cookbookadm);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($cookbookadm->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveCookbookadm($cookbookadm);

        //затем перенаправляемся обратно к списку
        return $this->redirect()->toRoute('cookbookadm', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('cookbookadm');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteCookbookadm($id);
            }

            //затем перенаправляемся обратно к списку
            return $this->redirect()->toRoute('cookbookadm');
        }

        return [
            'id'    => $id,
            'cookbookadm' => $this->table->getCookbookadm($id),
        ];
    }
}
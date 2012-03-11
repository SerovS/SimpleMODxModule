Пустой модуль для CMS MODx. Реализована MVC архитектура для приложения (модуля) к MODx.
Особенности:
Использование api modx
Обращение к объекту $modx как атрибуту класса ModuleModel
Разделение на модель, контроллер и представление
Вывод ошибок и сообщений

Подключение модуля.
Модули -> Управление Моудлями -> Новый Модуль добавить код:

if(!isset($modx)) die();

$basePath = $modx->config['base_path'];
$modulePath = $basePath . 'assets/modules/simple/';

// ------------------------------------------------------------------------------
// Create Controller
// ------------------------------------------------------------------------------
$classfile = $modulePath . 'ModuleController.class.php';
if(!file_exists($classfile))
	$modx->messageQuit(sprintf('Файл %s несуществует', $classfile));

require_once($classfile);
$controller = new ModuleController($modulePath, $modx);
try {
	$controller->run();
} catch (Exception $ex){
	$modx->messageQuit($ex->getMessage());
}
return;

Где прописать свои название классов и файлов соответственно. А так же изменить путь до папки, у меня это:
$basePath . 'assets/modules/simple/';
Переименовать названия классов и имена файлов на свои.

Удалить фаил конфига config.php, поставить права на папку модкля 777. 
Редактирование параметров конфига в методе createConfig() 78 сторка ModuleModel.class.php
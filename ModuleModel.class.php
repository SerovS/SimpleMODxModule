<?php

Class ModuleModel {

    const CONTENT = 'site_content';
    const TV = 'site_tmplvar_contentvalues';

    public $modx;
    public $tv;
    private $config;

    /**
     * Определяем класс $modx как атрибут.
     * Загружаем конфиг. Все значения конфига возможны как атрибуты класса ModuleModel
     * например: $this->value1 
     */
    public function __construct($modx) {
        $this->modx = $modx;
        foreach ($this->getConfig() as $name => $value)
            $this->$name = $value[0];
    }

    
    /**
     * Функция примера. 
     */
    public function getModuleData() {
        throw new Exception('Возникла ошибка', 0);
        return 'Значение для модуля';
    }
    
    
    /**
     * Устанавливаем значение tv параметра для документа
     * @param type $id_tv ID tv параметра
     * @param type $value устанавливаемое значение
     * @param type $id_doc ID документа для которого необходимо установить значение
     */
    public function saveTv($id_tv, $value, $id_doc) {
        $tvtable = $this->modx->db->config['table_prefix'] . self::TV;
        $sql = 'select * from ' . $tvtable . ' as tv  where tv.tmplvarid=\'' . $id_tv . '\' and tv.contentid=\'' . $id_doc . '\'';
        $result = $this->modx->db->query($sql);
        $tv = $this->modx->db->getRow($result);
        if (is_numeric($tv['id'])) {
            $sql = 'update ' . $tvtable . ' as tv set value=\'' . $value . '\'  where tv.id=\'' . $tv['id'] . '\'';
            $result = $this->modx->db->query($sql);
        } else {
            $sql = 'insert into ' . $tvtable . '  (`tmplvarid` ,
                                                                        `contentid` ,
                                                                        `value`)
                                                                        values (
                                                                        \'' . $id_tv . '\',
                                                                        \'' . $id_doc . '\',
                                                                        \'' . $value . '\'
                                                                        )';
            $result = $this->modx->db->query($sql);
        }
    }

    /**
     * Отдаем массив конфигурации в модуль из файла config.php
     * @return type 
     */
    public function getConfig() {
        if (is_array($this->config))
            return $this->config;
        if (file_exists(dirname(__FILE__) . '/config.php'))
            $this->config = unserialize(require(dirname(__FILE__) . '/config.php'));
        else
            $this->createConfig();
        return $this->config;
    }

    /**
     * Создаем фаил конфигурации модуля. config.php
     * Тут задаем возможные значения и значения по умолчанию. 
     */
    protected function createConfig() {
        @fopen(dirname(__FILE__) . '/config.php', 'a') or die("Невозможно создать фаил конфигурации, поставте права на папку 777");
        $config = array(
            'value1' => array(1, 'Пример1'),
            'value2' => array(2, 'Пример2'),
            'value3' => array(3, 'Пример3'),
            'value4' => array(4, 'Пример4'),
            'value5' => array(5, 'Пример5'),
        );
        $this->setConfig($config);
        $this->saveConfig();
    }

    /**
     * Устанавливаем значение конфигурации для модуля.
     * @param <array> $arr массив значений
     */
    public function setConfig($arr) {
        foreach ($arr as $name => $value) {
            if (is_array($value))
                $this->config[$name] = $value;
            else
                $this->config[$name][0] = $value;
        }
    }

    /**
     * Сохраняем фаил конфигурации. 
     */
    public function saveConfig() {
        $f = fopen(dirname(__FILE__) . '/config.php', 'w') or die("Невозможно открыть фаил конфигурации");
        $in = '<?php' . "\n";
        $in .= 'return \'';
        $in .= serialize($this->config);
        $in .= '\';' . "\n";
        $in .= '?>';
        fwrite($f, $in);
        fclose($f);
    }

}
?>


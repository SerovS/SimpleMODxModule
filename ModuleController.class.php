<?php

require_once(dirname(__FILE__) . '/ModuleModel.class.php');
require_once(dirname(__FILE__) . '/Module.class.php');
require_once(dirname(__FILE__) . '/ModuleConfig.class.php');

class ModuleController {

    private $path; // путь до модуля
    private $moduleTitle = 'Пустой модуль';
    private $tabs = array(
        'Module' => 'Модуль',
        'ModuleConfig' => 'Настройки',
    );
    private static $msgCount = 0;
    public $model;

    public function __construct($path, $modx) {
        $this->path = $path;
        $this->model = new ModuleModel($modx);
    }

    public function run() {
        //      if ($_GET['action']=='edit' && $_GET['view'] == 'poll' && is_numeric($_GET['pollid'])) {
        //        echo poll::getPoll($_GET['pollid']);
        //      exit();
        // }


        $content = '';
        $content .= '<div class="dynamic-tab-pane-control">';
        $content .= $this->buildMenu($_GET['view']);
        $content .= '<div class="tab-page">';


        if ($_GET['view'] == 'Module') {
            $model = new Module($this->model);
            $content .= $model->run();
        } elseif ($_GET['view'] == 'ModuleConfig') {
            $model = new ModuleConfig($this->model);
            $content .= $model->run();
        } else {
            $content .= $this->viewSplash();
        }
        $content .= '</div></div>';

        $tpl = $this->getMainTemplate();
        $tpl = str_replace('[*content*]', $content, $tpl);
        echo $tpl;
    }

    private function getMainTemplate() {
        return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
                "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                 <html xmlns="http://www.w3.org/1999/xhtml" ' .
                ($this->model->modx->config['manager_direction'] == 'rtl' ? 'dir="rtl"' : '') . ' lang="' .
                $this->model->modx->config['manager_lang_attribute'] . '" xml:lang="' . $this->model->modx->config['manager_lang_attribute'] . '">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>' . $this->moduleTitle . '</title>
			<script type="text/javascript">var MODX_MEDIA_PATH = "media";</script>
			<link rel="stylesheet" type="text/css" href="media/style/MODxCarbon/style.css" />
			

                        <script src="media/script/mootools/mootools.js" type="text/javascript"></script>
                        <script type="text/javascript" src="media/script/datefunctions.js"></script>
                        <script src="../assets/modules/simple/js/jquery-1.7.min.js" type="text/javascript"></script>
                        <script src="media/script/mootools/moodx.js" type="text/javascript"></script>
		<script type="text/javascript">
		var $j = jQuery.noConflict();
			$j("#loadingmask").css( {width: "100%", height: $j("body").height(), position: "absolute", zIndex: "1000", backgroundColor: "#ffffff"} );
		</script>
                       <script src="../assets/modules/simple/js/main.js" type="text/javascript"></script>
                       <link rel="stylesheet" type="text/css" href="../assets/modules/simple/css/styles.css" />
		</head>
		<body><div class="container">
                                        [*content*]
                                        </div>
                              </body>
                              </html>            
        ';
    }

    private function viewSplash() {
        return '<div class="sectionHeader">' . 'Первая страница модуля' . '</div>' .
                '<div class="sectionBody"><div class="splash">' . "Обычно тут предлагается инсталяция или приветствие!" .
                '<p>Приветствие</p></div>
                 </div>';
    }

    /**     * ***********************************************************************
     * build Tab Menu
     */
    public function buildMenu($active = false) {
    //    if (!$active)
      //      $active = key($this->tabs);

        $buffer = '<div class="tab-row">';
        foreach ($this->tabs as $k => $v) {
            $url = self::getURL(array('a' => $_GET['a'], 'id' => $_GET['id'], 'view' => $k), false);
            if ($active == $k) {
                $buffer .= '<a href="' . $url . '" class="tab selected"><span>' . $v . '</span></a>';
            } else {
                $buffer .= '<a href="' . $url . '" class="tab"><span>' . $v . '</span></a>';
            }
        }
        $buffer .= '</div>';
        return $buffer;
    }

    /**     * ***********************************************************************
     * Helper method that adds a query string to the current url
     * @param $params associative array containing key -> value pairs
     * @param $useGet include variables from $_GET into the array
     * @param $remove variables to remove from query string
     * @return the built url
     */
    public static function getURL(array $params = array(), $useGet = true, array $remove = array()) {
        $self = $_SERVER['PHP_SELF'];
        if ($useGet)
            $params = array_merge($_GET, $params);

        foreach ($remove as $item) {
            if (isset($params[$item]))
                unset($params[$item]);
        }
        return $self . '?' . http_build_query($params, '', '&amp;');
    }

    /**     * ***********************************************************************
     * Helper method to create a removable message element
     * @param $title message title
     * @param $msg message text
     * @param $type the message type. valid values are = info, warning, error
     * @return the built message html code
     */
    public static function message($title, $msg, $type = 'info', $noClose = false) {
        self::$msgCount++;

        if ($noClose) {
            return '<div id="EP_message_' . self::$msgCount . '" class="message ' . $type . '">' .
                    '<div class="msg"><h2>' . $title . '</h2><p>' . $msg . '</p></div>' .
                    '</div><br clear="all"/>';
        } else {
            return '<div id="EP_message_' . self::$msgCount . '" class="message ' . $type . '">' .
                    '<div class="msg"><h2>' . $title . '</h2><p>' . $msg . '</p></div>' .
                    '<a href="#" onclick="$(\'EP_message_' . self::$msgCount . '\').remove(); return false;"' .
                    ' class="messageclose"><span>X</span></a></div><br clear="all"/>';
        }
    }

}

?>
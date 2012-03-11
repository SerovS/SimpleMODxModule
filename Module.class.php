<?php

Class Module {

    public $output;

    public function __construct($model) {
        $this->model = $model;
    }

    function run() {
        try {
            $this->output.= '<div class="sectionHeader">Название страницы модуля</div>
                                    <div class="sectionBody">
                                            <div style="display: inline-block; width:100%;">';
            $this->actions();
            $this->output.= $this->model->getModuleData();
            $this->output.= '</div></div>';
            return $this->output;
        } catch (Exception $e) {
            if($e->getCode()==1) {
                return ModuleController::message('Ошибка', $e->getMessage(), 'error', true);
            }else
                return ModuleController::message('Сообщение', $e->getMessage(), 'warning', false).$this->output;
                
        }
    }

    function actions() {

        if (isset($_GET['action']) && $_GET['action'] == 1) {
            $this->output.='1';
        }
    }

}

?>

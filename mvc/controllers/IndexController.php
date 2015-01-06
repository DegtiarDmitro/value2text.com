<?php
    class IndexController{
        
        //действие которое выполняется по умолчанию
        public function indexAction(){
            $fc = FrontController::getInstance();
            $model = null;
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $value = $_POST['value'];
                $lang = $_POST['lang'];
                switch($lang){
                    case 3: $model = new EnModel; break;
                    case 2: $model = new RuModel; break;
                    case 1:
                    default: $model = new UaModel;
                }
                $model->value = $value;
            } else{
                $model = new DefaultModel;
            }
            
    		$output = $model->render(INDEX_FILE);
    		$fc->setBody($output);
        }
    }
?>
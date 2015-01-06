<?php
    class FrontController{
        protected $_controller, $_action, $_params, $_body;
    	static $_instance;
        
        //паттерн Singleton
    	public static function getInstance() {
    		if(!(self::$_instance instanceof self)) 
    			self::$_instance = new self();
    		return self::$_instance;
    	}
        
        //приватный конструктор
    	private function __construct(){
    		$request = $_SERVER['REQUEST_URI'];
    		$splits = explode('/', trim($request,'/'));
    		//Какой сontroller использовать?
    		$this->setController($splits);
    		//Какой action использовать?
    		$this->setAction($splits);
    	}
        
        // метод который выполняет заданное действие для заданного контроллера
        public function route(){
    		if(class_exists($this->getController())) {
    			$rc = new ReflectionClass($this->getController());
    			if($rc->hasMethod($this->getAction())){
  					$controller = $rc->newInstance();
  					$method = $rc->getMethod($this->getAction());
  					$method->invoke($controller);
   				} else {
  					throw new Exception("Action");
   				}
    		} else {
    			throw new Exception("Controller");
    		}
        }
        
        //приватная функция которая определяет какой контроллер вызывать (вызывается только в конструкторе)
        private function setController($req){
            if (is_array($req) && !empty($req[0])){
                $controller = strtolower($req[0]);
                switch($controller){
                    case 'index': $controller = ucfirst($controller)."Controller"; break;
                    default: $controller = "IndexController";
                }
                $this->_controller = $controller;
            } else
                $this->_controller = "IndexController";
        }
        
        //приватная функция которая определяет какое действие вызывать (вызывается только в конструкторе)
        private function setAction($req){
            if (is_array($req) && !empty($req[1])){
                $action = strtolower($req[1]);
                switch($action){
                    case 'index': $action = $action."Action"; break;
                    default: $action = "indexActoin";
                }
                $this->_action = $action;
            } else
                $this->_action = "indexAction";
        }
        
        // функция сохраняет в переменную $_params, параметры которые переданные в контроллер (вызывается только в конструкторе)
        private function setParams($req){

        }
        
        public function getParams() {
    		return $this->_params;
    	}
    	public function getController() {
    		return $this->_controller;
    	}
    	public function getAction() {
    		return $this->_action;
    	}
    	public function getBody() {
    		return $this->_body;
    	}
    	public function setBody($body) {
    		$this->_body = $body;
    	}
        
    }
?>
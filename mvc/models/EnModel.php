<?php
    /*
        @class EnModel
        Класс который отвечает за преобразование введенного числа в текст на английском языке
    */
    class EnModel{
        private $units = array("one", "two", "three", "four", "five", "six", "seven", "eight", "nine");
        private $teens = array( "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen",
                                "sixteen", "seventeen", "eighteen", "nineteen");
        private $tens = array("twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety");
        private $hundred = "hundred";
        private $thousand = "thousand";
        private $million = "million";
        private $milliard = "milliard";
        public $value = null;
        public $text = "";
        
        //функция отрисовывает вид
        public function render($file) {
    		if ($this->isValidValue($this->value)){
                $this->text = $this->transformToText($this->value);
    		} else
                $this->text = "Enter a not valid value";
    		ob_start();
            include(HEAD);
    		include($file);
    		return ob_get_clean();
    	}
        
        //функция проверяет действительно ли введенное пользоватем число является целым числом
        private function isValidValue($value){
            if ($value == '0' || (preg_match("/^-{0,1}[1-9]{1,1}[0-9]{1,9}$/", $value, $matcher) && abs($matcher[0]) <= 2147483647))
                return true;
            return false;
        }
        
        //функция преобразовует целочисленное значение в текст
        private function transformToText($value){
            $tempValue = $this->prepareValue($value);
            if ($tempValue[1] == 0)
                return "zero";
            $text = "";
            $value = $tempValue[1];
            
            if ($value >= 1000000000)
                $text .= $this->getTextByDigitClass($value, 1000000000);
            if ($value >= 1000000)
                $text .= $this->getTextByDigitClass($value, 1000000);
            if ($value >= 1000)
                $text .= $this->getTextByDigitClass($value, 1000);
            if ($value >= 1)
                $text .= $this->getTextByDigitClass($value, 1);
            if ($tempValue[0])
                $text = $tempValue[0]." ".$text;
            return $text;
        }
        
        //функция которая возвращает массив: первая ячейка - это знак, вторая это само число
        private function prepareValue($value){
            $value = trim($value);
            $value = str_replace(" ", "", $value);
            $number = array();
            if ($value[0] == '-'){
                $number[] = 'minus';
                $number[] = intval(substr($value, 1));
            } else{
                $number[] = '';
                $number[] = intval(substr($value, 0));
            }
            return $number;
        }
        //функция возвращает текст полученный в резудьтате преобразования трехзначного числа
        //в зависимости от класса числа (напр. отдельно для тисяч, миллионов...)
        private function getTextByDigitClass($value, $digitClass){
            $tempText = "";
            $temp = $value / $digitClass;
            $temp %= 1000;                      //подготовили трехзначное число для обработки
            $triade = $temp;
            // витаскиваем название сотен
            if ($triade >= 100){
                $tempText .= $this->units[$triade/100 - 1]." ".$this->hundred." ";
                $triade %= 100;
            }
            //вытаскиваем название десятков или чисел с диапазона 10-19
            if ($triade >= 10){
                if (intval($triade / 10) == 1)
                    $tempText .= $this->teens[$triade - 10]." ";
                else{
                    $tempText .= $this->tens[$triade/10 - 2]."-";
                    $triade %= 10;
                }
            }
            //вытаскиваем название единиц
            if ($triade >= 1 && $triade < 10)
                    $tempText .= $this->units[$triade - 1]." ";
            //добавляем название класса
            if ($temp)
                switch($digitClass){
                    case 1000: $tempText .= $this->thousand." "; break;
                    case 1000000: $tempText .= $this->million." "; break;
                    case 1000000000: $tempText .= $this->milliard." "; break;
                }
            return $tempText;
        } 
    }
?>
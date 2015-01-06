<?php
    /*
        @class UaModel  
        Класс который отвечает за преобразование введенного числа в текст на украинском языке
    */
    class UaModel{
        private $units = array("один", "два", "три", "чотири", "п'ять", "шість", "сім", "вісім", "дев'ять");
        private $teens = array( "десять", "одинадцать", "дванадцать", "тринадцать", "чотирнадцять", "п'ятнадцать",
                                "шістнадцать", "сімнадцать", "вісімнадцать", "дев'ятнадцать");
        private $tens = array("двадцать", "тридцать", "сорок", "п'ятдесят", "шістдесят", "сімдесят", "вісімдесят", "дев'яносто");
        private $hundreds = array("сто", "двісти", "триста", "чотириста", "п'ятсот", "шістсот", "сімсот", "вісімсот", "дев'ятьсот",);
        private $thousands = array("тисяча", "тисячі", "тисяч");
        private $millions = array("мільйон", "мільйони", "мільйонів");
        private $milliards = array("мільярд", "мільярди", "мільярдів");
        private $femailThousands = array("одна", "дві");
        public $value = null;
        public $text = "";
        
        //функция отрисовывает вид
        public function render($file) {
    		if ($this->isValidValue($this->value)){
                $this->text = $this->transformToText($this->value);
    		} else
                $this->text = "Введено некоректне число або його значення не входить в дозволений дiапазон";
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
                return "нуль";
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
                $number[] = 'мінус';
                $number[] = intval(substr($value, 1));
            } else{
                $number[] = '';
                $number[] = intval(substr($value, 0));
            }
            return $number;
        }
        
        /*
            функция возвращает текст полученный в резудьтате преобразования трехзначного числа
            в зависимости от класса числа (напр. отдельно для тисяч, миллионов...)
        */
        private function getTextByDigitClass($value, $digitClass){
            $tempText = "";
            $temp = $value / $digitClass;
            $temp %= 1000;                      //подготовили трехзначное число для обработки
            $triade = $temp;
            // витаскиваем название сотен
            if ($triade >= 100){
                $tempText .= $this->hundreds[$triade/100 - 1]." ";
                $triade %= 100;
            }
            //вытаскиваем название десятков или чисел с диапазона 10-19
            if ($triade >= 10){
                if (intval($triade / 10) == 1)
                    $tempText .= $this->teens[$triade - 10]." ";
                else{
                    $tempText .= $this->tens[$triade/10 - 2]." ";
                    $triade %= 10;
                }
            }
            //вытаскиваем название единиц
            if ($triade >= 1 && $triade < 10){
                if ($digitClass == 1000 && ($triade == 1 || $triade == 2))
                    $tempText .= $this->femailThousands[$triade - 1]." ";
                else
                    $tempText .= $this->units[$triade - 1]." ";
            }
            //добавляем название класса
            if ($temp)
                switch($digitClass){
                    case 1000: $tempText .= $this->addDigitClassName($this->thousands, $triade); break;
                    case 1000000: $tempText .= $this->addDigitClassName($this->millions, $triade); break;
                    case 1000000000: $tempText .= $this->addDigitClassName($this->milliards, $triade); break;
                }
            return $tempText;
        }
        
        // функция добавляет название класса числа
        private function addDigitClassName($digitClassArr, $lastNumber){
            $tempText = "";
            switch($lastNumber){
                case 1: $tempText .= $digitClassArr[0]." "; break;
                case 2: 
                case 3:
                case 4: $tempText .= $digitClassArr[1]." "; break;
                default: $tempText .= $digitClassArr[2]." "; break;
            }
           return $tempText;
        }
    }
?>
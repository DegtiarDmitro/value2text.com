<?php
    /*
        @class RuModel
        Класс который отвечает за преобразование введенного числа в текст на русском языке
    */
    class RuModel{
        private $units = array("один", "два", "три", "четыре", "пять", "шесть", "семь", "восемь", "девять");
        private $teens = array( "десять", "одиннадцать", "двенадцать", "тринадцать", "четырнадцать", "пятнадцать",
                                "шестнадцать", "семнадцать", "восемнадцать", "девятнадцать");
        private $tens = array("двадцать", "тридцать", "сорок", "пятьдесят", "шестьдесят", "семьдесят", "восемьдесят", "девяносто");
        private $hundreds = array("сто", "двести", "триста", "четыреста", "пятьсот", "шестьсот", "семьсот", "восемьсот", "девятьсот",);
        private $thousands = array("тысяча", "тысячи", "тысяч");
        private $millions = array("миллион", "миллиона", "миллионов");
        private $milliards = array("миллиард", "миллиарда", "миллиардов");
        private $femailThousands = array("одна", "две");
        public $value = null;
        public $text = "";
        
        //функция отрисовывает вид
        public function render($file) {
    		if ($this->isValidValue($this->value)){
                $this->text = $this->transformToText($this->value);
    		} else
                $this->text = "Введено некоректное значение или значение не входящее в разрешенный диапазон";
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
        public function transformToText($value){
            $tempValue = $this->prepareValue($value);
            if ($tempValue[1] == 0)
                return "ноль";
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
                $number[] = 'минус';
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
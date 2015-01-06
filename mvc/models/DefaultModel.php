<?php
    class DefaultModel{
        
        public function render($file) {
    		/* $file - текущее представление */
    		ob_start();
            include(HEAD);
    		include($file);
    		return ob_get_clean();
    	}
    }
?>
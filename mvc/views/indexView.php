<div id="convertation">
    <div id="text"><?php echo $this->text?></div>
    <form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
        <input id="inpText" type="text" name="value" size="20" value="<? echo $this->value?>"/><br />
        <input type="submit" value="Преобразовать в текст"/>
        <button type="submit" name="lang" value="1">Ua</button>
        <button type="submit" name="lang" value="2">Ru</button>
        <button type="submit" name="lang" value="3">En</button>
    </form>
</div>
</body>
</html>
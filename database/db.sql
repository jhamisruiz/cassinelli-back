DELIMITER $
$
CREATE DEFINER=`root`@`localhost` PROCEDURE `prueba`
(IN `start` INT
(11), IN `length` INT
(11), IN `search` VARCHAR
(2000), IN `table_name` VARCHAR
(100), IN `params` VARCHAR
(2000), IN `orderby` VARCHAR
(15))
BEGIN
  SET @table_name = table_name;
  SET @start = start;
  IF (@start = '' || @start=null) THEN
  SET @start = 0;
END
IF;
    IF (@start >0) THEN
SET @start = @start-1;
END
IF;
    
    SET @length = length;
IF (@length = '' || @length=null) THEN
set @count= CONCAT('select COUNT(*) into @outvar from ',@table_name);
PREPARE countquery from @count;
execute countquery;
SET @length = @outvar;
END
IF;
    SET @search = search;
SET @order = orderby;
IF (@order = '' || @order=null) THEN
SET @order = 'ASC';
END
IF;
    SET @params = params;
SET @sql_query = CONCAT('create temporary table temp as SELECT ',@params,' FROM ',
                            @table_name,
                            ' LIMIT ', @start,',', @length,
                            ';');
PREPARE stmt1 FROM @sql_query;
EXECUTE stmt1;
SET @select = CONCAT('select * FROM temp ', ' WHERE ', @search,' ORDER BY id ', @order,';');
PREPARE stmt2 FROM @select;
EXECUTE stmt2;
END$$
DELIMITER ;

DROP FUNCTION IF EXISTS `is_closed`;

delimiter $$
CREATE  FUNCTION `is_closed`(`st` INT) RETURNS BOOL
begin
	declare res BOOL;
	IF (`st`&4)>0 THEN
		set res=TRUE;
	ELSE set res=FALSE;
	END IF;
	return(res);
end $$

delimiter ;

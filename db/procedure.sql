
DROP PROCEDURE IF EXISTS `overview1`;

delimiter $$
CREATE  PROCEDURE `overview1`(IN `uid` INT)
begin
	declare qcnt int;
	declare acnt int;
	declare fcnt int;
	declare flcnt int;
	select count(*) into qcnt from `topic` where `topic`.`author`=uid;
	select count(*) into acnt from `answer` where `answer`.`author`=uid;
	select count(*) into fcnt from `focus` where `focus`.`uid`=uid;
	select count(*) into flcnt from `follow` where `follow`.`followerid`=uid;
    select qcnt,acnt,fcnt,flcnt;
end $$

delimiter ;

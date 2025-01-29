-- Retorna toda la información del registro que coincida con el email pasado por
-- parametro siempre que no haya sido afectado por un soft delete, osea que el 
-- usuario este activo

DELIMITER $$
CREATE DEFINER=`urbanagest`@`%` PROCEDURE `login`(IN `param_email` TEXT)
SELECT * FROM users WHERE email = param_email and delete_at='0000-00-00 00:00:00'$$
DELIMITER ;

-- Actualiza nombre y apellido del usuario con el id especificado

DELIMITER $$
CREATE DEFINER=`huertaenred`@`%` PROCEDURE `users_update`(IN `param_nombre` TEXT, IN `param_apellido` TEXT, IN `param_id` INT)
UPDATE users SET first_name = param_nombre, last_name=param_apellido WHERE id=param_id$$
DELIMITER ;
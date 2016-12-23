-- 
-- default data base name
-- testapp
--

-- create migrations:
-- > php yii migrate/create create_user_table --fields="login:string(255):notNull:unique"
-- > php yii migrate/create create_billing_table --fields="user_id:integer:notNull:foreignKey(user),balance:decimal(18,4):defaultValue(0)"
-- > php yii migrate/create create_billing_operations_table --fields="user_id:integer:notNull:foreignKey(user),amount:decimal(18,4):notNull"
-- > php yii migrate/create create_invoice_table --fields="owner_id:integer:notNull:foreignKey(user),for_user_id:integer:notNull:foreignKey(user),status:integer:defaultValue(1),amount:decimal(18,4):notNull"
--

-- up migratins
-- > php yii migrate
-- down migratins
-- > php yii migrate/down

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(63) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=100;


CREATE TABLE IF NOT EXISTS `billing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'id пользователя',
  `balance` decimal(18,4) NOT NULL DEFAULT '0' COMMENT 'текущий баланс пользователя',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=100;

CREATE TABLE IF NOT EXISTS `billing_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'id пользователя',
  `amount` decimal(18,4) NOT NULL COMMENT 'положительный - зачисление, отрицательный списание',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `billing_id` (`billing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=100;

CREATE TABLE IF NOT EXISTS `invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL COMMENT 'id пользователя выставившего счёт',
  `for_user_id` int(11) NOT NULL COMMENT 'id получателя счёта',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'статус счёта, 1 - выставлен, 2 - подтверджён, 3 - отменёт выставителем, 4 - отменён получателем',
  `amount` decimal(18,4) NOT NULL COMMENT 'положительный - зачисление, отрицательный списание',
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `for_user_id` (`for_user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=100;



CREATE TABLE IF NOT EXISTS `products` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(50) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `date` datetime NOT NULL);

INSERT INTO `products` (`id`, `name`, `quantity`, `sale_price`, `date`) VALUES
(1, 'Demo Product', '48',  '500.00', '2022-06-04 16:45:51'),
(2, 'Black Forest', '120', '130.00', '2022-06-04 18:44:52'),
(3, 'White Forest', '69', '130.00',  '2022-06-04 18:48:53'),
(4, 'Swiss Roll', '120', '110.00',  '2022-06-04 19:03:23'),
(5, 'Red Velvet', '26', '125.00',  '2022-06-04 19:11:30'),
(6, 'Butterscotch', '42', '125.00',  '2022-06-04 19:13:35'),
(7, 'Cheese Cake', '107', '200.00',  '2022-06-04 19:06:38'),
(8, 'Chicken Wrap', '90', '130.00',  '2022-06-04 19:17:11'),
(9, 'Chicken Patty', '67', '80.00',  '2022-06-04 19:19:20'),
(10, 'Brown Bread', '106', '90.00',  '2022-06-04 19:20:28'),
(11, 'White Bread', '78', '80.00',  '2022-06-04 19:25:22'),
(12, 'Multi-grain Bread', '160', '100.00', '2021-04-04 19:48:01'),
(13, 'Baguette', '199', '60.00',  '2021-04-04 19:49:00');

CREATE TABLE IF NOT EXISTS `sales` (
`id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `date`) VALUES
(1, 1, 2, '1000.00', '2022-06-04'),
(2, 3, 3, '390.00', '2022-06-04'),
(3, 10, 6, '540.00', '2022-06-04'),
(4, 6, 2, '250.00', '2022-06-04'),
(5, 12, 5, '500.00', '2022-06-04'),
(6, 13, 21, '1260.00', '2022-06-04'),
(7, 7, 5, '1000.00', '2022-06-04'),
(8, 9, 2, '160.00', '2022-06-04');

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`) VALUES
(1, 'Christopher Columbus', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1),
(2, 'Alan Walker', 'user', '12dea96fec20593566ab75692c9949596833adc9', 2 ),
(3, 'Harry Maguire', 'harry', '12dea96fec20593566ab75692c9949596833adc9', 2),
(4, 'Natie Williams', 'natie', '860e0b2398eae0a0e3434b61fa805ac87bb43c6f', 2),
(5, 'Mozart', 'mozart', '286b9b7b50ab89e3397b4df540021b531f457f7f', 2);

CREATE TABLE IF NOT EXISTS `user_groups` (
`id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`) VALUES
(1, 'Admin', 1),
(2, 'User', 2);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

  ALTER TABLE `products`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `sales`
 ADD PRIMARY KEY (`id`), ADD KEY `product_id` (`product_id`);

ALTER TABLE `products`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;

ALTER TABLE `sales`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;

ALTER TABLE `products`
ADD CONSTRAINT `FK_products` ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_groups`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `group_level` (`group_level`);

ALTER TABLE `user_groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;

ALTER TABLE `sales`
ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

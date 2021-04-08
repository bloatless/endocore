create table if not exists `customers`
(
  `customer_id` int auto_increment primary key,
  `firstname` varchar(100) null,
  `lastname` varchar(100) null,
  `email` varchar(200) null
);

create table if not exists `orders`
(
  `order_id` int auto_increment primary key,
  `customer_id` int not null,
  `order_sum` decimal(10,2) null
);

create table if not exists `orders_x_products`
(
  `order_id` int not null,
  `product_id` int not null,
  primary key (`order_id`, `product_id`)
);

create table if not exists `products`
(
  `product_id` int auto_increment primary key,
  `title` varchar(200) null,
  `price` decimal(8,2) null
);

create table if not exists `users`
(
  `user_id` int auto_increment primary key,
  `username` varchar(100) null,
  `password` varchar(100) null
);

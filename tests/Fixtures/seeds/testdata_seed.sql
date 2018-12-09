insert into `customers` (`customer_id`, `firstname`, `lastname`, `email`) VALUES
  (1,'Homer','Simpson','homer@simpsons.com'),
  (2,'Marge','Simpson','marge@simpsons.com'),
  (3,'Bart','Simpson','bart@simpsons.com'),
  (4,'Lisa','Simpson','lisa@simpsons.com');

insert into `products` (`product_id`, `title`, `price`) VALUES
  (1,'Duff Beer','3.00'),
  (2,'White Shirt','10.00'),
  (3,'Red Shirt','8.00'),
  (4,'Blue Pants','35.00'),
  (5,'Blue Pants','35.00'),
  (6,'Green Dress','50.00'),
  (7,'Black Dress','90.00');

insert into `orders` (`order_id`, `customer_id`, `order_sum`) VALUES
  (1,1,230.00),
  (2,1,30.00),
  (3,2,340.00);

insert into `orders_x_products` (`order_id`, `product_id`) VALUES
  (1,2),
  (1,3),
  (1,4),
  (1,5),
  (2,1),
  (2,4),
  (3,2),
  (3,1),
  (3,5),
  (3,4);
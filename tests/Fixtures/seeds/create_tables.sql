create table if not exists `users`
(
  `user_id` int auto_increment primary key,
  `username` varchar(100) null,
  `password` varchar(100) null
);

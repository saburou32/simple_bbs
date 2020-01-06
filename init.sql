create table users (
  id int(11) not null auto_increment primary key,
  name varchar(255) not null,
  email varchar(255) not null,
  pass varchar(100) not null,
  icon varchar(255) not null,
  profile varchar(255) default null,
  created timestamp on update CURRENT_TIMESTAMP not null default CURRENT_TIMESTAMP
);

create table posts (
  id int(11) not null auto_increment primary key,
  message text not null,
  user_id int(11) not null,
  reply_post_id int(11) not null,
  picture varchar(255) default null,
  created timestamp on update CURRENT_TIMESTAMP not null default CURRENT_TIMESTAMP
);

create table follower (
  id int(11) not null auto_increment primary key,
  follow_id int(11) not null,
  follower_id int(11) not null,
  created timestamp on update CURRENT_TIMESTAMP not null default CURRENT_TIMESTAMP
);

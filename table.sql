create table fz_dowload_history (
  id int(11) not null auto_increment,
  uid int(11) not null default 0,
  page_url varchar(100) not null default '',
  payed_credit int not null default 0,
  ctime date
);



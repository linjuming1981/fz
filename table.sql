create table fz_page_down_history (
  id int(11) not null auto_increment,
  uid int(11) not null default 0,
  domain in(11) not null default '',
  page_url varchar(100) not null default '',
  css_dir char(50) not null default '',
  js_dir char(50) not null default '',
  css_bg_dir char(50) not null default '',
  payed_credit int(11) not null default 0,
  cdate date,
  primary key (id),
  key ix_uid (uid)
) dbengine=innodb default charset=utf8 auto_increment=3;


create table fz_user(
  uid int(11) not null auto_increment,
  uname char(20) not null default '',
  password char(32) not null default '',
  credit int(11) not null default 0,
  pages_num_downed int(11) not null default 0,
  cdate date,
  ctime date,
  primary key (uid),
  unique key ix_uname (uname)
) dbengine=innodb default charset=utf8 auto_increment=3;


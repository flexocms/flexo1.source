CREATE TABLE page (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  title varchar(255) default NULL,
  slug varchar(100) default NULL,
  breadcrumb varchar(160) default NULL,
  keywords varchar(255) default NULL,
  description text,
  parent_id int(11) default NULL,
  layout_file varchar(250) NOT NULL,
  behavior_id varchar(25) NOT NULL,
  status_id int(11) NOT NULL default '100',
  created_on datetime default NULL,
  published_on datetime default NULL,
  updated_on datetime default NULL,
  created_by_id int(11) default NULL,
  updated_by_id int(11) default NULL,
  position mediumint(6) default NULL,
  needs_login tinyint(1) NOT NULL default '2'
);

CREATE TABLE page_permission (
  page_id int(11) NOT NULL,
  permission_id int(11) NOT NULL
);

CREATE TABLE page_part (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  name varchar(100) default NULL,
  filter_id varchar(25) default NULL,
  content longtext,
  content_html longtext,
  page_id int(11) default NULL,
  is_protected tinyint(4) default '0'
);

CREATE TABLE page_tag (
  page_id int(11) NOT NULL,
  tag_id int(11) NOT NULL
);

CREATE TABLE permission (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  name varchar(25) NOT NULL
);

CREATE TABLE setting (
  name varchar(40) NOT NULL,
  value text NOT NULL
);

CREATE TABLE plugin_settings (
  plugin_id varchar(40) NOT NULL,
  name varchar(40) NOT NULL,
  value varchar(255) NOT NULL
);

CREATE TABLE user (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  name varchar(100) default NULL,
  email varchar(255) default NULL,
  username varchar(40) NOT NULL,
  password varchar(40) default NULL,
  language varchar(5) default NULL,
  created_on datetime default NULL,
  updated_on datetime default NULL,
  created_by_id int(11) default NULL,
  updated_by_id int(11) default NULL,
  last_login datetime default NULL
);

CREATE TABLE user_permission (
  user_id int(11) NOT NULL,
  permission_id int(11) NOT NULL
);

CREATE TABLE tag (
  id  INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  name varchar(40) NOT NULL,
  count int(11) NOT NULL
);

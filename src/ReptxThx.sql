-- Database Schema for ReptxThx
CREATE TABLE /*_*/reptxthx_interaction (
	interaction_id int unsigned not null primary key auto_increment,
	interaction_type int unsigned not null,
	interaction_sender_id int unsigned not null,
	interaction_recipient_id int unsigned null,
	interaction_page_id int unsigned not null,
	interaction_timestamp timestamp not null null default current_timestamp
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/reptxThx_Interaction_recipient_pageId on /*_*/reptxThx_Interaction (interaction_recipient_id,interaction_page_id);


CREATE TABLE /*_*/reptxthx_user (
	reptxthx_user_id int unsigned not null primary key auto_increment,
	user_id int unsigned not null,
	user_rep_value double unsigned not null,
	user_cred_value double unsigned not null,
	user_last_rep_timestamp timestamp not null null default current_timestamp,
	user_last_cred_timestamp timestamp not null null default current_timestamp
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/reptxThx_User_userId on /*_*/reptxthx_user (user_id);


CREATE TABLE /*_*/reptxthx_page (
	reptxthx_page_id int unsigned not null primary key auto_increment,
	page_id int unsigned not null,
	page_fitness_value double unsigned not null,
	user_last_fitness_timestamp timestamp not null default current_timestamp
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/reptxthx_page_pageId on /*_*/reptxthx_page (page_id);
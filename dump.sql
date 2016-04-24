create table user (
	id int(11),
	name varchar(50) not null,
	email varchar(100) not null unique key,
	password varchar(100) not null,
	active boolean default true
);

alter table user add constraint pk_user primary key (id);
alter table user modify id int(11) auto_increment;

create table project (
	id int(11),
	name varchar(50) not null,
	user_id int(11) not null,
	active boolean default true
);

alter table project add constraint pk_project primary key (id);
alter table project modify id int(11) auto_increment;
alter table project add constraint fk_project_user foreign key (user_id) references user (id) on delete no action on update no action;
alter table project add description varchar(255);

create table task (
	id int(11),
	project_id int(11),
	creator_id int(11) not null,
	performer_id int(11) not null,
	name varchar(50) not null,
	description text,
	due_date date not null,
	completed boolean default false,
	conclusion_date date,
	days_to_remember int(2),
	active boolean default true
);

alter table task add constraint pk_task primary key (id);
alter table task modify id int(11) auto_increment;
alter table task add constraint fk_task_project foreign key (project_id) references project (id) on delete no action on update no action;
alter table task add constraint fk_task_creator foreign key (creator_id) references user (id) on delete no action on update no action;
alter table task add constraint fk_task_performer foreign key (performer_id) references user (id) on delete no action on update no action;
alter table task modify name varchar(100) not null;

create table file (
	id int(11),
	task_id int(11) not null,
	name varchar(50) not null,
	active boolean default true
);

alter table file add constraint pk_file primary key (id);
alter table file modify id int(11) auto_increment;
alter table file add constraint fk_file_task foreign key (task_id) references task (id) on delete no action on update no action;
alter table file modify name varchar(100);

alter table task add priority enum('baixa', 'media', 'alta') not null after due_date;

create table contacts (
	id int(11),
	user1 int(11) not null,
	user2 int(11) not null,
	active boolean default true
);

alter table contacts add constraint pk_contacts primary key (id);
alter table contacts modify id int(11) auto_increment;
alter table contacts add constraint contacts_user1 foreign key (user1) references user (id);
alter table contacts add constraint contacts_user2 foreign key (user2) references user (id);
alter table contacts add accepted boolean default false;

create table project_users (
	id int(11),
	project_id int(11) not null,
	user_id int(11) not null
);
 
alter table project_users add constraint pk_project_users primary key (id);
alter table project_users modify id int(11) auto_increment;
alter table project_users add constraint project_users_project foreign key (project_id) references project (id);
alter table project_users add constraint project_users_user foreign key (user_id) references user (id);
alter table project_users add active boolean default true;
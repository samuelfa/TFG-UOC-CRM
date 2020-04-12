create table familiars
(
    nif      varchar(50)  not null
        primary key,
    name     varchar(150) not null,
    surname  varchar(150) not null,
    birthday date         not null,
    portrait varchar(300) not null comment '(DC2Type:url)'
)
    collate = utf8mb4_unicode_ci;

create table users
(
    nif      varchar(10)  not null comment '(DC2Type:nif)'
        primary key,
    name     varchar(150) null,
    surname  varchar(150) null,
    birthday date         null,
    portrait varchar(300) null comment '(DC2Type:url)',
    password varchar(300) not null comment '(DC2Type:password)',
    email    varchar(150) not null comment '(DC2Type:email_address)',
    role     smallint     not null comment '(DC2Type:role)',
    constraint UNIQ_D994B25EE7927C74
        unique (email)
)
    collate = utf8mb4_unicode_ci;


create table migration_versions
(
    version     varchar(14) not null
        primary key,
    executed_at datetime    not null comment '(DC2Type:datetime_immutable)'
)
    collate = utf8mb4_unicode_ci;

INSERT INTO migration_versions (version, executed_at) VALUES
         ('20200412120904', '2020-04-12 12:09:30'),
         ('20200412120906', '2020-04-12 12:09:30')
;
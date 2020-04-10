create table familiars
(
    nif      varchar(255) not null
        primary key,
    name     varchar(150) not null,
    surname  varchar(150) not null,
    birthday date         not null,
    portrait varchar(300) not null
)
    collate = utf8mb4_unicode_ci;

create table migration_versions
(
    version     varchar(14) not null
        primary key,
    executed_at datetime    not null comment '(DC2Type:datetime_immutable)'
)
    collate = utf8mb4_unicode_ci;

create table users
(
    nif      varchar(255)      not null
        primary key,
    name     varchar(150)      not null,
    surname  varchar(150)      not null,
    birthday date              not null,
    portrait varchar(300)      not null,
    password varchar(150)      not null,
    email    varchar(150)      not null,
    role     smallint unsigned not null
)
    collate = utf8mb4_unicode_ci;

INSERT INTO migration_versions (version, executed_at) VALUES ('20200315175236', '2020-04-09 14:26:05'), ('20200409142228', '2020-04-09 14:26:05');
create table customers
(
    nif      varchar(10)  not null comment '(DC2Type:nif)'
        primary key,
    name     varchar(150) null,
    surname  varchar(150) null,
    birthday date         null,
    portrait varchar(300) null comment '(DC2Type:url)',
    password varchar(300) not null comment '(DC2Type:password)',
    email    varchar(150) not null comment '(DC2Type:email_address)',
    constraint UNIQ_1BC9B76CE7927C74
        unique (email)
)
    collate = utf8mb4_unicode_ci;

create table employees
(
    nif      varchar(10)  not null comment '(DC2Type:nif)'
        primary key,
    name     varchar(150) null,
    surname  varchar(150) null,
    birthday date         null,
    portrait varchar(300) null comment '(DC2Type:url)',
    password varchar(300) not null comment '(DC2Type:password)',
    email    varchar(150) not null comment '(DC2Type:email_address)',
    role     smallint     not null,
    constraint UNIQ_C3183A4DE7927C74
        unique (email)
)
    collate = utf8mb4_unicode_ci;

create table familiars
(
    nif      varchar(50)  not null comment '(DC2Type:nif)'
        primary key,
    name     varchar(150) null,
    surname  varchar(150) null,
    birthday date         null,
    portrait varchar(300) null comment '(DC2Type:url)'
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
         ('20200412165615', '2020-04-12 12:09:30'),
         ('20200412165618', '2020-04-12 12:09:30'),
         ('20200426183813', '2020-04-26 18:39:05'),
         ('20200427225558', '2020-04-27 22:57:32'),
         ('20200502143401', '2020-05-02 16:34:32')
;

ALTER TABLE familiars ADD customer VARCHAR(255) DEFAULT NULL COMMENT '(DC2Type:nif)';
ALTER TABLE familiars ADD CONSTRAINT FK_9757873281398E09 FOREIGN KEY (customer) REFERENCES customers (nif);
CREATE INDEX IDX_9757873281398E09 ON familiars (customer);

CREATE TABLE categories (name VARCHAR(50) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
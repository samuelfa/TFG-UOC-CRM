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

ALTER TABLE familiars ADD customer VARCHAR(255) DEFAULT NULL COMMENT '(DC2Type:nif)';
ALTER TABLE familiars ADD CONSTRAINT FK_9757873281398E09 FOREIGN KEY (customer) REFERENCES customers (nif);
CREATE INDEX IDX_9757873281398E09 ON familiars (customer);

CREATE TABLE activities (id INT AUTO_INCREMENT NOT NULL, category SMALLINT NOT NULL, name VARCHAR(50) NOT NULL, start_at DATE NOT NULL, finish_at DATE NOT NULL, INDEX IDX_B5F1AFE564C19C1 (category), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE categories (id SMALLINT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_3AF346685E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE activities ADD CONSTRAINT FK_B5F1AFE564C19C1 FOREIGN KEY (category) REFERENCES categories (id);

CREATE TABLE forgot_password_emails (email_address VARCHAR(150) NOT NULL COMMENT '(DC2Type:email_address)', string VARCHAR(600) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C58BD1F79EBEB2A9 (string), PRIMARY KEY(email_address)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE forgot_password_emails CHANGE created_at created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)';

CREATE TABLE link_activities (id INT AUTO_INCREMENT NOT NULL, familiar VARCHAR(255) NOT NULL COMMENT '(DC2Type:nif)', activity INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_E27B6F108A34CA5E (familiar), INDEX IDX_E27B6F10AC74095A (activity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, familiar VARCHAR(255) NOT NULL COMMENT '(DC2Type:nif)', message LONGTEXT NOT NULL, private TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_11BA68C8A34CA5E (familiar), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE emails (id INT AUTO_INCREMENT NOT NULL, familiar VARCHAR(255) NOT NULL COMMENT '(DC2Type:nif)', subject VARCHAR(150) NOT NULL, body LONGTEXT NOT NULL, recipients LONGTEXT NOT NULL COMMENT '(DC2Type:array_email_address)', created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_4C81E8528A34CA5E (familiar), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE link_activities ADD CONSTRAINT FK_E27B6F108A34CA5E FOREIGN KEY (familiar) REFERENCES familiars (nif);
ALTER TABLE link_activities ADD CONSTRAINT FK_E27B6F10AC74095A FOREIGN KEY (activity) REFERENCES activities (id);
ALTER TABLE notes ADD CONSTRAINT FK_11BA68C8A34CA5E FOREIGN KEY (familiar) REFERENCES familiars (nif);
ALTER TABLE emails ADD CONSTRAINT FK_4C81E8528A34CA5E FOREIGN KEY (familiar) REFERENCES familiars (nif);

INSERT INTO migration_versions (version, executed_at) VALUES
('20200412165615', '2020-04-12 12:09:30'),
('20200412165618', '2020-04-12 12:09:30'),
('20200426183813', '2020-04-26 18:39:05'),
('20200427225558', '2020-04-27 22:57:32'),
('20200502160201', '2020-05-02 18:02:50'),
('20200503105501', '2020-05-03 12:56:01'),
('20200503135108', '2020-05-03 15:52:08'),
('20200503165234', '2020-05-03 18:53:34')
;
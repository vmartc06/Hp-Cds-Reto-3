DROP DATABASE IF EXISTS new_cds;
CREATE DATABASE new_cds;

USE new_cds;

CREATE TABLE Cloth_Types (
    UUID varchar(36),
    NAME varchar(255) not null,
    PRIMARY KEY (UUID)
)ENGINE=InnoDB;

CREATE TABLE Turns (
    UUID varchar(36),
    START int not null,
    END int not null,
    PRIMARY KEY (UUID)
)ENGINE=InnoDB;

CREATE TABLE Producers (
    UUID varchar(36),
    NAME varchar(255) not null,
    RATING int not null,
    CATEGORY varchar(255) not null,
    LOCATION varchar(255) not null,
    PRIMARY KEY (UUID)
)ENGINE=InnoDB;

CREATE TABLE Clothes (
    UUID varchar(36),
    TYPE varchar(36) not null,
    PRODUCED_ON date not null,
    PRODUCED_TURN varchar(36) not null,
    PRODUCED_BY varchar(36) not null,
    PRIMARY KEY (UUID),
    FOREIGN KEY (TYPE) REFERENCES Cloth_Types(UUID),
    FOREIGN KEY (PRODUCED_TURN) REFERENCES Turns(UUID),
    FOREIGN KEY (PRODUCED_BY) REFERENCES Producers(UUID)
);

CREATE TABLE Stores (
    UUID varchar(36),
    NAME varchar(255) not null,
    PRIMARY KEY (UUID)
)ENGINE=InnoDB;

CREATE TABLE Distribution_log (
    UUID varchar(36),
    ORIGIN_STORE varchar(36),
    DESTINATION_STORE varchar(36),
    TRANSACTION_DATE datetime not null,
    CLOTH varchar(36) not null,
    PRIMARY KEY (UUID),
    FOREIGN KEY (ORIGIN_STORE) REFERENCES Stores(UUID),
    FOREIGN KEY (DESTINATION_STORE) REFERENCES Stores(UUID),
    FOREIGN KEY (CLOTH) REFERENCES Clothes(UUID)
)ENGINE=InnoDB;



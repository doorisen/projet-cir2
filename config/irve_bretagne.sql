-- ----------------------------------------------------------
-- Script MYSQL pour mcd 
-- ----------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS COMMERCIALISER;
DROP TABLE IF EXISTS POINT_DE_RECHARGE;
DROP TABLE IF EXISTS STATION;
DROP TABLE IF EXISTS CONTACT;
DROP TABLE IF EXISTS RACCORDEMENT;
DROP TABLE IF EXISTS CONDITION_ACCES;
DROP TABLE IF EXISTS ENTREPRISE;
DROP TABLE IF EXISTS ENSEIGNE;
DROP TABLE IF EXISTS IMPLANTATION;
DROP TABLE IF EXISTS COMMUNE;

SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------
-- Table: COMMUNE
-- ----------------------------
CREATE TABLE COMMUNE (
  code_insee VARCHAR(5) NOT NULL,
  nom_standard VARCHAR(100) NOT NULL,
  code_postal VARCHAR(5) NOT NULL,
  dep_code VARCHAR(3) NOT NULL,
  dep_nom VARCHAR(50) NOT NULL,
  reg_code VARCHAR(3) NOT NULL,
  reg_nom VARCHAR(50) NOT NULL,
  population INT NOT NULL,
  CONSTRAINT COMMUNE_PK PRIMARY KEY (code_insee)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: IMPLANTATION
-- ----------------------------
CREATE TABLE IMPLANTATION (
  id_implantation INT NOT NULL AUTO_INCREMENT,
  implantation VARCHAR(255),
  CONSTRAINT IMPLANTATION_PK PRIMARY KEY (id_implantation)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: ENSEIGNE
-- ----------------------------
CREATE TABLE ENSEIGNE (
  id_enseigne INT NOT NULL AUTO_INCREMENT,
  nom_enseigne VARCHAR(255) NOT NULL,
  CONSTRAINT ENSEIGNE_PK PRIMARY KEY (id_enseigne)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: ENTREPRISE
-- ----------------------------
CREATE TABLE ENTREPRISE (
  nom_entreprise VARCHAR(255) NOT NULL,
  siren_entreprise VARCHAR(9),
  CONSTRAINT ENTREPRISE_PK PRIMARY KEY (nom_entreprise)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: CONDITION_ACCES
-- ----------------------------
CREATE TABLE CONDITION_ACCES (
  id_condition_acces INT NOT NULL AUTO_INCREMENT,
  condition_acces VARCHAR(255),
  CONSTRAINT CONDITION_ACCES_PK PRIMARY KEY (id_condition_acces)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: RACCORDEMENT
-- ----------------------------
CREATE TABLE RACCORDEMENT (
  id_raccordement INT NOT NULL AUTO_INCREMENT,
  raccordement VARCHAR(255),
  CONSTRAINT RACCORDEMENT_PK PRIMARY KEY (id_raccordement)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: CONTACT
-- ----------------------------
CREATE TABLE CONTACT (
  id_contact INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(255),
  telephone VARCHAR(30),
  nom_entreprise VARCHAR(255),
  CONSTRAINT CONTACT_PK PRIMARY KEY (id_contact),
  CONSTRAINT CONTACT_nom_entreprise_FK FOREIGN KEY (nom_entreprise) REFERENCES ENTREPRISE (nom_entreprise)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: STATION
-- ----------------------------
CREATE TABLE STATION (
  id_station INT NOT NULL AUTO_INCREMENT,
  id_station_local VARCHAR(50),
  id_station_itinerance VARCHAR(50),
  nom_station VARCHAR(255) NOT NULL,
  adresse_station TEXT NOT NULL,
  horaires VARCHAR(100),
  date_mise_en_service DATE,
  longitude DECIMAL(11,8) NOT NULL,
  latitude DECIMAL(10,8) NOT NULL,
  code_insee VARCHAR(5) NOT NULL,
  id_implantation INT NOT NULL,
  id_condition_acces INT NOT NULL,
  id_raccordement INT NOT NULL,
  CONSTRAINT STATION_PK PRIMARY KEY (id_station),
  CONSTRAINT STATION_code_insee_FK FOREIGN KEY (code_insee) REFERENCES COMMUNE (code_insee),
  CONSTRAINT STATION_id_implantation_FK FOREIGN KEY (id_implantation) REFERENCES IMPLANTATION (id_implantation),
  CONSTRAINT STATION_id_condition_acces_FK FOREIGN KEY (id_condition_acces) REFERENCES CONDITION_ACCES (id_condition_acces),
  CONSTRAINT STATION_id_raccordement_FK FOREIGN KEY (id_raccordement) REFERENCES RACCORDEMENT (id_raccordement)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: POINT_DE_RECHARGE
-- ----------------------------
CREATE TABLE POINT_DE_RECHARGE (
  id INT NOT NULL,
  puissance_nominale DECIMAL(7,2) NOT NULL,
  tarification TEXT,
  prise_type_ef TINYINT(1) NOT NULL,
  prise_type_2 TINYINT(1) NOT NULL,
  prise_type_combo_ccs TINYINT(1) NOT NULL,
  prise_type_chademo TINYINT(1) NOT NULL,
  prise_type_autre TINYINT(1) NOT NULL,
  gratuit TINYINT(1) NOT NULL,
  paiement_acte TINYINT(1) NOT NULL,
  paiement_cb TINYINT(1) NOT NULL,
  paiement_autre TINYINT(1) NOT NULL,
  cable_t2_attache TINYINT(1) NOT NULL,
  id_station INT NOT NULL,
  nom_entreprise VARCHAR(255) NOT NULL,
  nom_entreprise_AMENAGER VARCHAR(255) NOT NULL,
  CONSTRAINT POINT_DE_RECHARGE_PK PRIMARY KEY (id),
  CONSTRAINT POINT_DE_RECHARGE_id_station_FK FOREIGN KEY (id_station) REFERENCES STATION (id_station),
  CONSTRAINT POINT_DE_RECHARGE_nom_entreprise_FK FOREIGN KEY (nom_entreprise) REFERENCES ENTREPRISE (nom_entreprise),
  CONSTRAINT POINT_DE_RECHARGE_nom_entreprise_AMENAGER_FK FOREIGN KEY (nom_entreprise_AMENAGER) REFERENCES ENTREPRISE (nom_entreprise)
)ENGINE=InnoDB;


-- ----------------------------
-- Table: COMMERCIALISER
-- ----------------------------
CREATE TABLE COMMERCIALISER (
  id_enseigne INT NOT NULL,
  id_station INT NOT NULL,
  CONSTRAINT COMMERCIALISER_PK PRIMARY KEY (id_enseigne, id_station),
  CONSTRAINT COMMERCIALISER_id_enseigne_FK FOREIGN KEY (id_enseigne) REFERENCES ENSEIGNE (id_enseigne),
  CONSTRAINT COMMERCIALISER_id_station_FK FOREIGN KEY (id_station) REFERENCES STATION (id_station)
)ENGINE=InnoDB;


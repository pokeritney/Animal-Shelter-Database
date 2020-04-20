-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser @localhost IDENTIFIED BY 'gatech123';

DROP DATABASE IF EXISTS `cs6400_sp20_team001`;

SET
  default_storage_engine = InnoDB;

SET
  NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_sp20_team001 DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci;

USE cs6400_sp20_team001;

GRANT
SELECT
,
INSERT
,
UPDATE
,
  DELETE,
  FILE ON *.* TO 'gatechUser' @'localhost';

GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser' @'localhost';

GRANT ALL PRIVILEGES ON `cs6400_sp20_team001`.* TO 'gatechUser' @'localhost';

FLUSH PRIVILEGES;

-- Tables 
CREATE TABLE `User`(
  user_name varchar(50) Not Null,
  password varchar(60) Not Null,
  first_name varchar(100) Not Null,
  last_name varchar(100) Not Null,
  email varchar(250) Null,
  start_date datetime Not Null,
  PRIMARY KEY (user_name)
);

CREATE TABLE Volunteer(
  user_name varchar(50) Not Null,
  volunteer_phone_num varchar(50) Not Null,
  PRIMARY KEY (user_name)
);

CREATE TABLE DayWork(
  user_name varchar(50) Not Null,
  work_date date Not Null,
  hours_worked Float Not Null,
  PRIMARY KEY (user_name, work_date)
);

CREATE TABLE Employee(
  user_name varchar(50) Not Null,
  PRIMARY KEY (user_name)
);

CREATE TABLE AdminUser(
  user_name varchar(50) Not Null,
  PRIMARY KEY (user_name)
);

CREATE TABLE Adopter(
  email varchar(50) Not Null,
  primary_first_name varchar(50) Not Null,
  primary_last_name varchar(50) Not Null,
  street varchar(50) Not Null,
  city varchar(50) Not Null,
  state varchar(50) Not Null,
  zip_code varchar(50) Not Null,
  phone_number varchar(50) Not Null,
  PRIMARY KEY (email)
);

CREATE TABLE Application(
  applicationID INT NOT NULL AUTO_INCREMENT,
  application_date date Not Null,
  co_first_name varchar(50) Null,
  co_last_name varchar(50) Null,
  email varchar(50) Not Null,
  status varchar(50) Not Null DEFAULT 'pending approval',
  PRIMARY KEY (applicationID)
);

CREATE TABLE Animal(
  animalID INT NOT NULL AUTO_INCREMENT,
  name varchar(50) Not Null,
  sex varchar(50) Not Null,
  alteration_status Boolean Not Null,
  local_control Boolean Not Null,
  surrender_date date Not Null,
  surrender_reason varchar(250) Not Null,
  description varchar(1000) Not Null,
  age_months INT Not Null,
  microchip_id varchar(50) Null,
  user_name varchar(50) Not Null,
  UNIQUE(microchip_id),
  PRIMARY KEY (animalid)
);

CREATE TABLE Adoption(
  pet_id INT NOT NULL,
  application_number INT NOT NULL,
  adoption_date date Not Null,
  adoption_fee float Not Null,
  PRIMARY KEY (application_number, pet_id)
);

CREATE TABLE Species(
  species_name varchar(50) Not Null,
  capacity int Not Null,
  PRIMARY KEY (species_name)
);

CREATE TABLE BreedName(
  breed_name varchar(50) Not Null,
  species_name varchar(50) Not Null,
  PRIMARY KEY (breed_name, species_name)
);

CREATE TABLE Breed(
  pet_id int Not Null,
  species_name varchar(50) Not Null,
  breed_name varchar(50) Not Null DEFAULT 'Unknown',
  PRIMARY KEY (pet_id, species_name, breed_name)
);

CREATE TABLE VaccinationRequired(
  species_name varchar(50) Not Null,
  vaccine_name varchar(50) Not Null,
  required Boolean Not Null,
  PRIMARY KEY (species_name, vaccine_name)
);

CREATE TABLE Vaccination(
  pet_id int Not Null,
  vaccine_name varchar(50) Not Null,
  administer_date date Not Null,
  expiration_date date Not Null,
  vaccination_number varchar(50) Null,
  user_name varchar(50) Not Null,
  UNIQUE (vaccination_number),
  PRIMARY KEY (pet_id, vaccine_name, administer_date)
);

CREATE TABLE VaccinationType(
  species_name varchar(50) Not Null,
  vaccine_name varchar(50) Not Null,
  pet_id int Not Null,
  administer_date date Not Null,
  PRIMARY KEY (
    species_name,
    vaccine_name,
    pet_id,
    administer_date
  )
);

ALTER TABLE
  Volunteer
ADD
  CONSTRAINT FK_Volunteer_user_name_User_user_name FOREIGN KEY (user_name) REFERENCES `User` (user_name);

ALTER TABLE
  DayWork
ADD
  CONSTRAINT FK_DayWork_user_name_Volunteer_user_name FOREIGN KEY (user_name) REFERENCES Volunteer (user_name);

ALTER TABLE
  Employee
ADD
  CONSTRAINT FK_Employee_user_name_User_user_name FOREIGN KEY (user_name) REFERENCES `User` (user_name);

ALTER TABLE
  AdminUser
ADD
  CONSTRAINT FK_AdminUser_user_name_Employee_user_name FOREIGN KEY (user_name) REFERENCES Employee (user_name);

ALTER TABLE
  Application
ADD
  CONSTRAINT FK_Application_email_Adopter_email FOREIGN KEY (email) REFERENCES Adopter (email);

ALTER TABLE
  Animal
ADD
  CONSTRAINT FK_Animal_user_name_Employee_user_name FOREIGN KEY (user_name) REFERENCES Employee (user_name);

ALTER TABLE
  Adoption
ADD
  CONSTRAINT FK_Adoption_application_number_Application_applicationID FOREIGN KEY (application_number) REFERENCES Application (applicationID) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE
  Adoption
ADD
  CONSTRAINT FK_Adoption_pet_id_Animal_animalID FOREIGN KEY (pet_id) REFERENCES Animal (animalID) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE
  BreedName
ADD
  CONSTRAINT FK_BreedName_species_name_Species_species_name FOREIGN KEY (species_name) REFERENCES Species (species_name);

ALTER TABLE
  Breed
ADD
  CONSTRAINT FK_Breed_pet_id_Animal_animalID FOREIGN KEY (pet_id) REFERENCES Animal (animalID);

ALTER TABLE
  Breed
ADD
  CONSTRAINT FK_Breed_species_name_Species_species_name FOREIGN KEY (species_name) REFERENCES Species (species_name);

ALTER TABLE
  Breed
ADD
  CONSTRAINT FK_Breed_breed_name_BreedName_breed_name FOREIGN KEY (breed_name) REFERENCES BreedName (breed_name);

ALTER TABLE
  VaccinationRequired
ADD
  CONSTRAINT FK_VaccinationRequired_species_name_Species_species_name FOREIGN KEY (species_name) REFERENCES Species (species_name);

ALTER TABLE
  Vaccination
ADD
  CONSTRAINT FK_Vaccination_pet_id_Animal_animalID FOREIGN KEY (pet_id) REFERENCES Animal (animalID);

ALTER TABLE
  Vaccination
ADD
  CONSTRAINT FK_Vaccination_user_name_User_user_name FOREIGN KEY (user_name) REFERENCES `User` (user_name);

ALTER TABLE
  VaccinationType
ADD
  CONSTRAINT FK_VaccinationType_sv_Vaccinationrequired_sv FOREIGN KEY (species_name, vaccine_name) REFERENCES Vaccinationrequired (species_name, vaccine_name);

ALTER TABLE
  VaccinationType
ADD
  CONSTRAINT FK_VaccinationType_pidvnameadt_Vaccination_pidvnameadt FOREIGN KEY (pet_id, vaccine_name, administer_date) REFERENCES Vaccination (pet_id, vaccine_name, administer_date);

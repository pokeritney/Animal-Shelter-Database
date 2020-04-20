-- Please Note: The following sql statements uses MySQL syntax

CREATE DATABASE IF NOT EXISTS cs6400_spr20_team001 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_spr20_team001;

CREATE TABLE `User`(
	user_name varchar(50) Not Null,
	email varchar(250) Null,
	password varchar(60) Not Null,
	start_date datetime Not Null,
	first_name varchar(100) Not Null,
	last_name varchar(100) Not Null,
    PRIMARY KEY (user_name));


CREATE TABLE Volunteer(	
	user_name varchar(50) Not Null,
	volunteer_phone_num varchar(50) Not Null,
    PRIMARY KEY (user_name),
    FOREIGN KEY	(user_name) REFERENCES `User`(user_name));
    


CREATE TABLE DayWork(	
	user_name varchar(50) Not Null,
	work_date datetime Not Null,
    hours_worked Float Not Null,
	PRIMARY KEY (user_name, work_date),
    FOREIGN KEY	(user_name) REFERENCES Volunteer(user_name));



CREATE TABLE Employee(
	user_name varchar(50) Not Null,
	PRIMARY KEY (user_name),
    FOREIGN KEY	(user_name) REFERENCES `User`(user_name));
        
CREATE TABLE AdminUser(
	user_name varchar(50) Not Null,
	PRIMARY KEY (user_name),
    FOREIGN KEY	(user_name) REFERENCES Employee(user_name));



CREATE TABLE Adopter(
	email varchar(50) Not Null,
    primary_first_name varchar(50) Not Null,
    primary_last_name varchar(50) Not Null,
    phone_number varchar(50) Not Null,
    street varchar(50) Not Null,
	city varchar(50) Not Null,
	state varchar(50) Not Null,
	zip_code varchar(50) Not Null,
	PRIMARY KEY (email));



CREATE TABLE Application(	
	application_number INT NOT NULL AUTO_INCREMENT,
    email varchar(50) Not Null,
	application_date datetime Not Null,	
    status varchar(50) Not Null DEFAULT 'pending approval',
	co_first_name varchar(50) Null,
	co_last_name  varchar(50) Null,
	PRIMARY KEY (application_number),
    FOREIGN KEY (email) REFERENCES Adopter(email)
    );


CREATE TABLE Animal(	
	pet_id INT NOT NULL AUTO_INCREMENT,
    name varchar(50) Not Null,
    surrender_date datetime Not Null,
    local_control Boolean Not Null,
	surrender_reason varchar(250) Not Null,
    alteration_status Boolean Not Null,
    sex varchar(50) Not Null,
    birth_date datetime Not Null,
    description varchar(50) Not Null,
	microchip_id varchar(50) Null,
	user_name varchar(50) Not Null,
    PRIMARY KEY (pet_id),
    UNIQUE (microchip_id),
    FOREIGN KEY (user_name) REFERENCES Employee(user_name)
    );

CREATE TABLE Adoption(
	application_number INT NOT NULL,
    pet_id INT NOT NULL,
    adoption_date datetime Not Null,
    adoption_fee float Not Null,
    PRIMARY KEY (application_number, pet_id),
	FOREIGN KEY (application_number) REFERENCES Application(application_number) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (pet_id) REFERENCES Animal(pet_id) ON DELETE CASCADE ON UPDATE CASCADE
    );

CREATE TABLE Species(	
	species_name varchar(50) Not Null,
	capacity int Not Null,
    PRIMARY KEY (species_name));
    
CREATE TABLE BreedName(
    breed_name varchar(50) Not Null,
	species_name varchar(50) Not Null,
    PRIMARY KEY (breed_name),
    FOREIGN KEY (species_name) REFERENCES Species(species_name));
    
    
CREATE TABLE Breed(
	pet_id int Not Null,
    species_name varchar(50) Not Null,
    breed_name varchar(50) Not Null,
    PRIMARY KEY (pet_id, species_name, breed_name),
    FOREIGN KEY (pet_id) REFERENCES Animal(pet_id),
    FOREIGN KEY (species_name) REFERENCES Species(species_name),
    FOREIGN KEY (breed_name) REFERENCES BreedName(breed_name));


CREATE TABLE VaccinationType(
    vaccine_name varchar(50) Not Null,
	PRIMARY KEY (vaccine_name));

CREATE TABLE VaccinationRequired(
	species_name varchar(50) Not Null,
    vaccine_name varchar(50) Not Null,
    required Boolean Not Null,
    PRIMARY KEY (species_name, vaccine_name),
    Foreign Key (species_name) REFERENCES Species(species_name),
	Foreign Key (vaccine_name) REFERENCES VaccinationType(vaccine_name)
    );

CREATE TABLE Vaccination(	
	pet_id int Not Null,
	vaccine_name varchar(50) Not Null,
    administer_date datetime Not Null,
	vaccination_number varchar(50) Null,
    expiration_date datetime Not Null,
    user_name varchar(50) Not Null,
    UNIQUE (vaccination_number),
    PRIMARY KEY (pet_id, vaccine_name, administer_date),
    FOREIGN KEY (pet_id) REFERENCES Animal(pet_id),
	FOREIGN KEY (vaccine_name) REFERENCES VaccinationType(vaccine_name),	
	FOREIGN KEY (user_name) REFERENCES `User`(user_name));







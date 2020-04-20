CREATE table 'User'(
	user_name varchar(50) Not Null,
	email varchar(50) Null,
	password varchar(50) Not Null,
	start_date datetime Not Null,
	first_name varchar(50) Not Null,
	last_name varchar(50) Not Null,
    PRIMARY KEY (user_name));
    
    

CREATE table 'Volunteer'(	
	user_name varchar(50) Not Null,
	volunteer_phone_num varchar(50) Not Null,
    PRIMARY KEY (user_name),
    FOREIGN KEY	(user_name)
		REFERENCES 'User'(user_name));
    


CREATE table 'DayWork'(	
	user_name varchar(50) Not Null,
	work_date datetime Not Null,
    hours_worked Float Not Null,
	PRIMARY KEY (user_name, work_date),
    FOREIGN KEY	(user_name)
		REFERENCES 'Volunteer'(user_name));



CREATE table 'Employee'(
	user_name varchar(50) Not Null,
	PRIMARY KEY (user_name),
    FOREIGN KEY	(user_name),
		REFERENCES 'User'(user_name));
        
CREATE table 'AdminUser'(
	user_name varchar(50) Not Null,
	PRIMARY KEY (user_name),
    FOREIGN KEY	(user_name),
		REFERENCES 'Employee'(user_name));



CREATE table 'Adopter'(
	email varchar(50) Not Null,
    primary_first_name varchar(50) Not Null,
    primary_last_name varchar(50) Not Null,
    phone_number varchar(50) Not Null,
    street varchar(50) Not Null,
	city varchar(50) Not Null,
	state varchar(50) Not Null,
	zip_code varchar(50) Not Null,
	PRIMARY KEY (email));


CREATE table 'Application'(	
	application_number INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email varchar(50) Not Null,
	application_date datetime Not Null,	
    status varchar(50) Not Null,
	co_first_name varchar(50) Null,
	co_last_name  varchar(50) Null,
    FOREIGN KEY (email),
		REFERENCES 'Adopter'(email));


create table 'Adoption'(
	application_number varchar(50) Not Null,
    pet_id varchar(50) Not Null,
    adoption_date datetime Not Null,
    adoption_fee int Not Null,
    unique (application_number,pet_id),
    Foreign Key (pet_id)
		REFERENCES 'Animal'(pet_id));



CREATE table 'Animal'(	
	pet_id varchar(50) Not Null,
    name varchar(50) Not Null,
    surrender_date datetime Not Null,
    local_control Boolean Not Null,
	surrender_reason varchar(50) Not Null,
    alteration_status Boolean Not Null,
    sex varchar(50) Not Null,
    birth_date datetime Not Null,
    description varchar(50) Not Null,
	microchip_iD varchar(50) Null,
	user_name varchar(50) Not Null,
    PRIMARY KEY (pet_id),
    FOREIGN kEY (user_name)
		REFERENCES 'Employee'(user_name));
        
CREATE table 'Breed'(
	pet_id varchar(50) Not Null,
    species_name varchar(50) Not Null,
    breed varchar(50) Not Null,
    Unique (pet_id,species_namebreed),
    FOREIGN kEY (pet_id) REFERENCES 'Animal'(pet_id),
    FOREIGN kEY (species_name) REFERENCES 'Species'(species_name),
    FOREIGN kEY (breed) REFERENCES 'BreedName'(breed));



CREATE table 'Species'(	
	species_name varchar(50) Not Null,
	capacity Integer Not Null,
    PRIMARY KEY (species_name));



CREATE table 'BreedName'(
	species_name varchar(50) Not Null,
    breed_name varchar(50) Not Null,
    PRIMARY KEY (breed_name),
    FOREIGN KEY (species_name),
		REFERENCES 'Species'(species_name));
        
create table 'VaccinationRequired'(
	species_name varchar(50) not null,
    vaccine_name varchar(50) Not Null,
    requied Boolean Not Null
    UNIQUE (species_name,vaccine_name)
    Foreign Key (species_name)REFERENCES 'Species'(species_name)
	Foreign Key (vaccine_name)REFERENCES 'VaccinationType'(vaccine_name)	);


CREATE table 'VaccinationType'(
    vaccine_name varchar(50) Not Null,
	PRIMARY KEY (vaccine_name));


CREATE table 'Vaccination'(	
	pet_id varchar(50) Not Null,
	vaccine_name varchar(50) Not Null,
    administer_date datetime Not Null,
	vaccination_number varchar(50) Null,
    expiration_date datetime Not Null,
    user_name varchar(50) Null,
    UNIQUE (pet_id, vaccine_name,administer_date)
    FOREIGN KEY (pet_id)REFERENCES 'Animal'(pet_id),
	FOREIGN KEY (vaccine_name)REFERENCES 'VaccinationType'(vaccine_name),	
	FOREIGN KEY (user_name)REFERENCES 'User'(user_name));







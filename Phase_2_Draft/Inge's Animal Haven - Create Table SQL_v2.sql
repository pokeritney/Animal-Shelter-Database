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
    FOREIGN KEY	(user_name),
		REFERENCES 'User'(user_name));
    


CREATE table 'DayWork'(	
	user_name varchar(50) Not Null,
	work_date datetime Not Null,
    hours_worked Float Not Null,
	PRIMARY KEY (user_name, work_date),
    FOREIGN KEY	(user_name),
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

#are we sure we need this one? need to update Data type if so
CREATE table 'ApproveApplication'(
	application_number varchar(50) Not Null,
    user_name varchar(50) Not Null,
    status varchar(50) Not Null,
    PRIMARY KEY (application_number),
    FOREIGN KEY	(user_name),
		REFERENCES 'AdminUser'(user_name));


CREATE table 'Application'(	
	application_number varchar(50) Not Null,
    email varchar(50) Not Null,
	application_date datetime Not Null,
	primary_first_name varchar(50) Not Null,
	primary_last_name varchar(50) Not Null,
	co_first_name varchar(50) Null,
	co_last_name  varchar(50) Null,
	street varchar(50) Not Null,
	city varchar(50) Not Null,
	state varchar(50) Not Null,
	zip_code varchar(50) Not Null,
	phone_number varchar(50) Not Null,
    PRIMARY KEY (application_number),
    FOREIGN KEY (application_number),
		REFERENCES 'ApproveApplication'(application_number));

#DOUBLE CHECK TABLE REFERENCES AND KEYS HERE
CREATE table 'AddAdoption'(	
	user_name varchar(50) Not Null,
    application_number varchar(50) Not Null,
    pet_number varchar(50) Not Null,
    adoption_date datetime	Not Null,
    adoption_fee float Not Null,
    PRIMARY KEY (application_number),
    FOREIGN KEY (pet_number),
		REFERENCES 'Applcation'(application_number)
        REFERENCES 'Animal'(pet_number));

#review this one because of change volume
CREATE table 'Animal'(	
	pet_number varchar(50) Not Null,
    species_name varchar(50) Not Null,
	breed varchar(50) Not Null,
    name varchar(50) Not Null,
    surrender_date datetime Not Null,
    surrender_by_animal_control Boolean Not Null,
	surrender_reason varchar(50) Not Null,
    alteration_status Boolean Not Null,
    sex varchar(50) Not Null,
    birth_date datetime Not Null,
    description varchar(50) Not Null,
	microchip_iD varchar(50) Null,
	added_by varchar(50) Not Null,
    PRIMARY KEY (pet_number),
    FOREIGN KEY (added_by),
    FOREIGN KEY (species_name),
		REFERENCES 'Species'(species_name)
        REFERENCES 'Employee'(added_by));


#does it need a foreign key
CREATE table 'Species'(	
	species_name varchar(50) Not Null,
	capacity Integer Not Null,
    PRIMARY KEY (species_name));



CREATE table 'Breed'(
	species_name varchar(50) Not Null,
    breed_name varchar(50) Not Null,
    PRIMARY KEY (breed_name)
    FOREIGN KEY (species_name)
		REFERENCES 'Species'(species_name));

#CHECK RELATION DIAGRAM SPECIES NAME
CREATE table 'VaccinationType'(
	species_name varchar(50) Not Null,
    vaccine_name varchar(50) Not Null,
    required Boolean Not Null,
	PRIMARY KEY (species_name, vaccine_name)
    FOREIGN KEY (species_name)
		REFERENCES 'Species'(species_name));


CREATE table 'Vaccination'(	
	pet_number varchar(50) Not Null,
	vaccine_name varchar(50) Not Null,
    administer_date datetime Not Null,
	vaccination_number varchar(50) Null,
    expiration_date datetime Not Null,
    vaccination_added_by varchar(50) Null,
    PRIMARY KEY (pet_number, vaccine_name,administer_date)
    FOREIGN KEY (vaccine_name)
		REFERENCES 'Animal'(pet_number)
		REFERENCES 'VaccinationType'(vaccine_name));
        


--Get volunteer work month and year combination

SELECT comb
    FROM
    (SELECT YEAR(work_date) as y, MONTH(work_date) as m, DATE_FORMAT(work_date, '%m-%Y') as comb
    FROM daywork
    GROUP BY YEAR(work_date), MONTH(work_date), DATE_FORMAT(work_date, '%m-%Y')
    ORDER BY y, m) AS myc;
	

--Get top Volunteers

SELECT a.first_name, a.last_name, a.email, b.total
    FROM `user` a JOIN
    (SELECT user_name, SUM(hours_worked) AS total
    FROM daywork
    WHERE YEAR(work_date) = :year and MONTH(work_date) = :month
    GROUP BY user_name
    ORDER BY total DESC
    LIMIT 5) b
    ON a.user_name = b.user_name
    ORDER BY b.total DESC, a.last_name;
	
--Get volunteer information for search

SELECT a.last_name, a.first_name, a.email, b.volunteer_phone_num
    FROM `User` a INNER JOIN Volunteer b
    ON a.user_name = b.user_name
    WHERE a.last_name LIKE CONCAT('%', :last_name, '%')
    AND a.first_name LIKE CONCAT('%', :first_name, '%')
    ORDER BY a.last_name, a.first_name;
	
--Vaccination History for an animal

SELECT A.pet_id, A.vaccine_name, A.vaccination_number, A.user_name, A.administer_date, A.expiration_date, C.required
        FROM vaccination A
        JOIN vaccinationtype B ON A.pet_id = B.pet_id AND A.vaccine_name = B.vaccine_name AND A.administer_date = B.administer_date
        JOIN vaccinationrequired C ON B.species_name = C.species_name AND B.vaccine_name = C.vaccine_name;
		
--Get the vaccination records based on the ID

SELECT A.pet_id, A.vaccine_name, A.vaccination_number, A.user_name, A.administer_date, A.expiration_date, C.required
      FROM vaccination A
      JOIN vaccinationtype B ON A.pet_id = B.pet_id AND A.vaccine_name = B.vaccine_name AND A.administer_date = B.administer_date
      JOIN vaccinationrequired C ON B.species_name = C.species_name AND B.vaccine_name = C.vaccine_name
      WHERE A.pet_id= :id;
	  
--Get the vaccine name, if required by species for selection on form

SELECT species_name, vaccine_name, required
                 FROM vaccinationrequired
                 WHERE species_name = :species
                 ORDER BY species_name, vaccine_name;
				 
--Add vaccination 

INSERT INTO vaccination (vaccine_name,vaccination_number,pet_id,
                  administer_date, expiration_date, user_name)
                  VALUES (:vaccine_name,:vaccination_number,:pet_id,
                  :administer_date,:expiration_date,:user_name );


--Populate join table vaccinationtype

INSERT INTO vaccinationtype (vaccine_name, species_name, pet_id,
        administer_date)
        VALUES (:vaccine_name, :species_name, :pet_id, :administer_date);

--vaccineReminder report

SELECT V.vaccine_name, date(V.expiration_date) AS due_date, V.pet_id AS id, C.species_name, C.breed_name, A.sex, A.alteration_status, A.microchip_id, A.surrender_date, U.first_name, U.last_name
        FROM vaccination V JOIN `user` U ON V.user_name = U.user_name JOIN
        (SELECT pet_id, species_name, GROUP_CONCAT(DISTINCT breed_name ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
        FROM Breed
        GROUP BY pet_id, species_name) as C ON V.pet_id = C.pet_id
        JOIN animal A on A.animalID = V.pet_id
        WHERE YEAR(V.expiration_date) = YEAR(now())
        AND (MONTH(V.expiration_date) - MONTH(now())) >= 0
        AND (MONTH(V.expiration_date) - MONTH(now())) <= 3
        AND V.pet_id NOT IN (SELECT pet_id FROM adoption)
        ORDER BY due_date, id;
		

--datevalidate make sure a vaccination is needed

SELECT count(*) AS num
                FROM vaccination
                WHERE vaccine_name = :vaccine_name
                AND pet_id = :pet_id
                AND expiration_date > :administer_date;
				
--authenticateuser

SELECT *
                FROM user
                WHERE user_name = :username;
				
				
--isadminuser

SELECT *
                FROM adminuser
                WHERE user_name = :username;

--isemployee

SELECT *
                    FROM employee
                    WHERE user_name = :username;

--isvolunteer

SELECT *
                        FROM volunteer
                        WHERE user_name = :username;
						
--getyearmonth combo for animal control

SELECT count(animalID) AS animal_count
    FROM Animal
    WHERE YEAR(surrender_date) = :year
    AND MONTH(surrender_date) = :month
    AND local_control = TRUE;
	
--rescued Animals

SELECT A.animalID, B.species_name, B.breed_name ,A.sex, A.alteration_status, 
    A.microchip_id, A.surrender_date, DATEDIFF(C.adoption_date, A.surrender_date) AS length
    FROM Animal A INNER JOIN 
    (SELECT pet_id, species_name, GROUP_CONCAT(DISTINCT breed_name ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
    FROM Breed
    GROUP BY pet_id, species_name) as B ON A.animalID = B.pet_id
    INNER JOIN Adoption AS C ON A.animalID = C.pet_id
    WHERE YEAR(C.adoption_date) = :year
    AND MONTH(C.adoption_date) = :month
    AND DATEDIFF(C.adoption_date, A.surrender_date) >= 60
    ORDER BY length DESC;


--Get the breed based on the species

SELECT species_name, breed_name
               FROM `breedname`
               WHERE species_name = :species
               ORDER BY species_name, breed_name;


--An adoption application

SELECT A.email, A.primary_first_name, A.primary_last_name, A.phone_number,
                 A.street, A.city, A.state, A.zip_code,
                 B.applicationID, B.application_date, B.co_first_name, B.co_last_name, B.status FROM
                 adopter A JOIN application B ON A.email = B.email
                 WHERE B.status='pending approval';

--search Approved Applications

SELECT A.email, A.primary_first_name, A.primary_last_name, A.phone_number,
                     A.street, A.city, A.state, A.zip_code,
                     B.applicationID, B.application_date, B.co_first_name, B.co_last_name, B.status
                     FROM
                     adopter A JOIN application B ON A.email = B.email
                     WHERE B.status ='Approved';

					 
--Get the application record based on the ID

SELECT A.email, A.primary_first_name, A.primary_last_name, A.phone_number,
                 A.street, A.city, A.state, A.zip_code,
                 B.applicationID, B.application_date, B.co_first_name, B.co_last_name, B.status FROM
                 adopter A JOIN application B ON A.email = B.email
                 WHERE B.applicationID = :id;

--Insert an Adopter 

INSERT INTO adopter (primary_first_name,primary_last_name,email,
          phone_number,street,city,state, zip_code)
          VALUES (:primary_first_name,:primary_last_name,:email,
          :phone_number,:street,:city, :state, :zip_code);

--Create application 

INSERT INTO application (application_date, co_first_name, co_last_name,
          email)
          VALUES (:application_date, :co_first_name, :co_last_name, :email);

--Update the application status 

UPDATE application
            SET status = :status
            WHERE applicationID = :id;


--Get current animals for Dashboard

SELECT * FROM (SELECT A.animalID, A.name, B.species_name, B.breed_name, A.sex, A.alteration_status,
    A.age_months, A.description,
    A.microchip_id, A.surrender_date, A.surrender_reason, A.local_control,
    CASE WHEN A.alteration_status = TRUE AND D.outstanding = 0
    THEN 'Adoptable' ELSE 'Not Adoptable' END AS adoptability_status, E.application_number
    FROM Animal A INNER JOIN (SELECT pet_id,  species_name, GROUP_CONCAT(DISTINCT breed_name
            ORDER BY breed_name ASC SEPARATOR '/'
          ) AS breed_name
          FROM  Breed
    		GROUP BY pet_id, species_name
      ) as B ON A.animalID = B.pet_id
      LEFT JOIN
      (SELECT T.species_name, R.pet_id, T.num_required, R.num_received, T.num_required - R.num_received as outstanding
       FROM
       (SELECT species_name, count(vaccine_name) as num_required
        from vaccinationrequired
        where required = 1
        group by species_name) T join (select DISTINCT A.species_name, a.pet_id, count(distinct a.vaccine_name) as num_received
        from vaccinationtype A  join vaccinationrequired B on a.species_name = b.species_name and a.vaccine_name = b.vaccine_name
        where B.required = 1
         group by a.species_name, a.pet_id) R on T.species_name = R.species_name) AS D ON A.animalID = D.pet_id
    LEFT JOIN adoption E ON A.animalID = E.pet_id
    ) A
    WHERE A.application_number is null
    AND A.species_name like :speciesFilter
    AND A.adoptability_status like :statusFilter;
	
	
--get animals ByID for the animals detail screen

SELECT * FROM (SELECT A.animalID, A.name, B.species_name, B.breed_name, A.sex, A.alteration_status,
      A.age_months, A.description,
      A.microchip_id, A.surrender_date, A.surrender_reason, A.local_control,
      CASE WHEN A.alteration_status = TRUE AND D.outstanding =0
      THEN 'Adoptable' ELSE 'Not Adoptable' END AS adoptability_status
      FROM Animal A INNER JOIN (SELECT pet_id,  species_name, GROUP_CONCAT(DISTINCT breed_name
              ORDER BY breed_name ASC SEPARATOR '/'
            ) AS breed_name
            FROM  Breed
      		GROUP BY pet_id, species_name
        ) as B ON A.animalID = B.pet_id
        LEFT JOIN
        (SELECT T.species_name, R.pet_id, T.num_required, R.num_received, T.num_required - R.num_received as outstanding
         FROM
         (SELECT species_name, count(vaccine_name) as num_required
          from vaccinationrequired
          where required = 1
          group by species_name) T join (select DISTINCT A.species_name, a.pet_id, count(distinct a.vaccine_name) as num_received
         from vaccinationtype A  join vaccinationrequired B on a.species_name = b.species_name and a.vaccine_name = b.vaccine_name
          where B.required = 1
          group by a.species_name, a.pet_id) R on T.species_name = R.species_name) AS D ON A.animalID = D.pet_id
      LEFT JOIN adoption E ON A.animalID = E.pet_id
      AND E.application_number IS NULL) R
      WHERE R.animalID = :id;


--getCapacity

SELECT a.species_name, count(distinct a.pet_id) as inshelter, capacity
from breed a left join adoption b on a.pet_id = b.pet_id
join species c on a.species_name = c.species_name
where b.pet_id is null
group by a.species_name;

--getSpecies

SELECT A.species_name, A.capacity, CASE WHEN D.num_adopted IS NULL THEN 0 ELSE D.num_adopted END as num_adopted, CASE WHEN E.num_animals IS NULL THEN 0 ELSE E.num_animals END AS num_animals
    FROM species A LEFT JOIN (SELECT B.species_name, count(B.pet_id) num_adopted
    FROM breed B INNER JOIN Adoption C ON B.pet_id = C.pet_id
    GROUP BY B.species_name) D
    ON A.species_name = D.species_name
    LEFT JOIN (SELECT DISTINCT species_name, count(DISTINCT pet_id) as num_animals FROM breed GROUP BY species_name) E
    ON A.species_name = E.species_name
    HAVING A.capacity-num_animals+num_adopted>0;
	
--Insert a new animal with its current property values

INSERT INTO animal (name,sex,alteration_status,
          age_months,description,microchip_id, local_control, surrender_date, surrender_reason, user_name)
          VALUES (:name,:sex,:alteration_status,
          :age_months,:description,:microchip_id, :local_control, :surrender_date, :surrender_reason, :user_name );

--Update the animal with its current property values

UPDATE animal
                     SET sex = :sex,
                        alteration_status = :alteration_status,
                        microchip_id = :microchip_id
                     WHERE animalID = :id;

--delete the animals breed records

DELETE FROM breed
                    WHERE breed_name in ('Unknown', 'Mixed')
                    AND pet_id = :id;
					
					
--Get the animals breed

SELECT breed_name, pet_id, species_name
          FROM Breed
          WHERE pet_id = :id;

--Update Breed if unknown selected
INSERT IGNORE INTO breed (breed_name, pet_id, species_name)
            VALUES ('Unknown',{$this->id}, :species_name);

--Update Breed if breed selected
INSERT IGNORE INTO breed (breed_name, pet_id, species_name)
            VALUES ('Mixed',{$this->id}, :species_name);
			
--Add breed records            
INSERT IGNORE INTO breed (breed_name, pet_id, species_name)
                    VALUES (:breed_name, {$this->id}, :species_name);
		  

--addAdoption

INSERT INTO adoption (adoption_date, adoption_fee, application_number,
           pet_id)
           VALUES (:adoption_date, :adoption_fee, :application_number, :pet_id);

--monthlyAdoption report
SELECT R.y, R.m, R.month_name, R.species_name, R.breed_name, COALESCE(R.surrender_count, 0) AS surrender_count, COALESCE(R.adoption_count, 0) AS adoption_count
        FROM
        
        (
        (SELECT SC.y, SC.m, SC.month_name, SC.species_name, SC.breed_name, SC.surrender_count, AC.adoption_count
        FROM
        (SELECT YEAR(A.surrender_date) AS y, MONTH(A.surrender_date) as m, MONTHNAME(A.surrender_date) AS month_name, B.species_name, B.breed_name, COUNT(A.animalID) AS surrender_count
        FROM animal A JOIN
        (SELECT pet_id, species_name, GROUP_CONCAT( DISTINCT breed_name 
           ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
           FROM Breed
          GROUP BY pet_id, species_name) as B ON A.animalID = B.pet_id
        WHERE A.surrender_date >= (DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 12 MONTH)
        GROUP BY YEAR(A.surrender_date), MONTH(A.surrender_date), MONTHNAME(A.surrender_date), B.species_name, B.breed_name
        ORDER BY y, m, species_name, breed_name) AS SC
        
        LEFT JOIN
        
        (SELECT YEAR(A.adoption_date) AS y, MONTH(A.adoption_date) AS m, MONTHNAME(A.adoption_date) AS month_name, B.species_name, B.breed_name, COUNT(A.pet_id) AS adoption_count
        FROM adoption A JOIN
        (SELECT pet_id, species_name, GROUP_CONCAT( DISTINCT breed_name 
           ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
           FROM Breed
          GROUP BY pet_id, species_name) as B ON A.pet_id = B.pet_id
        WHERE A.adoption_date >= (DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 12 MONTH)
        GROUP BY YEAR(A.adoption_date), MONTH(A.adoption_date), MONTHNAME(A.adoption_date), B.species_name, B.breed_name
        ORDER BY y, m, species_name, breed_name
        ) AS AC
        
        ON (SC.y = AC.y AND SC.m = AC.m AND SC.species_name = AC.species_name AND SC.breed_name = AC.breed_name)
        )
        UNION 
        (
        SELECT AC.y, AC.m, AC.month_name, AC.species_name, AC.breed_name, SC.surrender_count, AC.adoption_count
        FROM
        (SELECT YEAR(A.surrender_date) AS y, MONTH(A.surrender_date) as m, MONTHNAME(A.surrender_date) AS month_name, B.species_name, B.breed_name, COUNT(A.animalID) AS surrender_count
        FROM animal A JOIN
        (SELECT pet_id, species_name, GROUP_CONCAT( DISTINCT breed_name 
           ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
           FROM Breed
          GROUP BY pet_id, species_name) as B ON A.animalID = B.pet_id
        WHERE A.surrender_date >= (DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 12 MONTH)
        GROUP BY YEAR(A.surrender_date), MONTH(A.surrender_date), MONTHNAME(A.surrender_date), B.species_name, B.breed_name
        ORDER BY y, m, species_name, breed_name) AS SC
        
        RIGHT JOIN
        
        (SELECT YEAR(A.adoption_date) AS y, MONTH(A.adoption_date) AS m, MONTHNAME(A.adoption_date) AS month_name, B.species_name, B.breed_name, COUNT(A.pet_id) AS adoption_count
        FROM adoption A JOIN
        (SELECT pet_id, species_name, GROUP_CONCAT( DISTINCT breed_name 
           ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
           FROM Breed
          GROUP BY pet_id, species_name) as B ON A.pet_id = B.pet_id
        WHERE A.adoption_date >= (DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 12 MONTH)
        GROUP BY YEAR(A.adoption_date), MONTH(A.adoption_date), MONTHNAME(A.adoption_date), B.species_name, B.breed_name
        ORDER BY y, m, species_name, breed_name
        ) AS AC
        
        ON (SC.y = AC.y AND SC.m = AC.m AND SC.species_name = AC.species_name AND SC.breed_name = AC.breed_name)
        )
        ) AS R
        ORDER BY R.y, R.m, R.species_name, R.breed_name;
		
		
--

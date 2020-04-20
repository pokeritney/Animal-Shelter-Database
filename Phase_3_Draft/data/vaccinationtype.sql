

INSERT INTO vaccinationtype (species_name, vaccine_name, pet_id, administer_date)
SELECT DISTINCT A.species_name, B.vaccine_name, A.pet_id, B.administer_date
FROM breed A, vaccination B 
WHERE A.pet_id = b.pet_id
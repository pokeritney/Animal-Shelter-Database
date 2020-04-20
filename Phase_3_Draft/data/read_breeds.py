import csv
with open('Animals.tsv') as f, open("breeds.txt", "w") as output:
    lines = csv.reader(f, delimiter="\t")
    for line in lines:
        pet = line[0]
        specie = line[1]
        breeds = line[2].split(",")
        for breed in breeds:
            output.write(pet + ',' + specie + ',' + breed + '\n')
        
    
#with open('Animals.tsv') as animals, open("breeds.txt", "w") as breeds:
#        animal = animals.readlines()
#        
#        f3.write(f1_lines[0].strip())
#        f3.write(f2.read().strip())
#        f3.writelines(f1_lines[1:])
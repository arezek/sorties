-- //______________________________________________________________________ Ville

INSERT INTO `ville`(`nom`, `code_postal`) VALUES ('Nantes','44000');
INSERT INTO `ville`(`nom`, `code_postal`) VALUES ('Rennes','35000');
INSERT INTO `ville`(`nom`, `code_postal`) VALUES ('Noirt','79000');
INSERT INTO `ville`(`nom`, `code_postal`) VALUES ('Quimper','29000');

-- //______________________________________________________________________ État
INSERT INTO `etat`(`libelle`) VALUES ('Créée');
INSERT INTO `etat`(`libelle`) VALUES ('Ouverte');
INSERT INTO `etat`(`libelle`) VALUES ('Clôturée');
INSERT INTO `etat`(`libelle`) VALUES ('Activité en cours');
INSERT INTO `etat`(`libelle`) VALUES ('Annulée');

-- //______________________________________________________________________ Campus
INSERT INTO `campus`(`nom`) VALUES ('Rennes');
INSERT INTO `campus`(`nom`) VALUES ('Nantes');
INSERT INTO `campus`(`nom`) VALUES ('Quimper');
INSERT INTO `campus`(`nom`) VALUES ('Niort');
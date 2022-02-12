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

-- //______________________________________________________________________ sortie
INSERT INTO
    `sortie` (
        `id`,
        `nom`,
        `date_heure_debut`,
        `duree`,
        `date_limite_inscription`,
        `nb_inscriptions_max`,
        `infos_sortie`,
        `etat`,
        `organisateur`
    )
VALUES
    (
        1,
        'balade en foret   ',
        '2022-02-11 00:57:00',
        '02:18:00',
        '2022-02-11 00:57:00',
        16,
        'lalalalalalal lalala lalala lalala',
        'Créée',
        'lcb@gmail.com'
    ),
    (
        2,
        'Sortie Bowling',
        '2022-02-12 22:23:00',
        '00:00:00',
        '2022-02-18 23:23:00',
        45,
        'lilililallalalallolololo',
        'Annulée',
        'lcb@gmail.com'
    ),
    (
        3,
        'ski ne famille',
        '2022-02-17 15:44:00',
        '01:00:00',
        '2022-02-16 15:44:00',
        10,
        ' ca fait mal !!',
        'Ouverte',
        'lcb@gmail.com'
    );
    (
        4,
        'Burger Mania',
        '2022-06-17 15:44:00',
        '01:00:00',
        '2022-06-16 15:44:00',
        10,
        'c*est délicieux !!!  ',
        'Activité en cours',
        'lcb@gmail.com'
    );
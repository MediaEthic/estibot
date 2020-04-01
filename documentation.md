# Ethic web service
# Documentation

## 1. Authentification (R1)
#### Endpoint : `POST /login`

**Parameters: Body**
````
{
    username: String (required),
    password: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne les données de l'utilisateur authentifié
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Utilisateur inexistant ou non trouvé
    - 500 : Erreur serveur
````

**Request**
````
SELECT T1.CODEUTILISATEUR,
       T1.NOM,
       T1.PRENOM,
       T1.SUPERVISEUR,
       T3.CODESOCIETE,
       T3.CODEETABLISSEMENT,
       T4.CODEPAYS
FROM PGUUTILISATEUR T1,
     PGUUTILISATEURGROUPE T2,
     PUSER T3,
     PSOCIETES T4
WHERE T1.CODEUTILISATEUR = :username
      AND T1.MOTDEPASSE = :password
      AND T1.CODEUTILISATEUR = T2.CODEUTILISATEUR
      AND T2.CODEGROUPE = T3.GROUPNAME
      AND T3.CODESOCIETE = T4.CODESOCIETE
      AND T3.PARDEFAUT = 1
````


## 2. Clients et prospects (R2)
#### Endpoint : `GET /thirds`

**Parameters: Body**
````
{
    company_id: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne un tableau d'objets avec la liste de tous les clients et prospects
- Failure :
    - 400 : Le champ passé en paramètre est manquant et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT TYPECOMPTE,
       NOCOMPTE,
       MOTCLE,
       RAISONSOCIALE,
       ADRESSE1,
       ADRESSE2,
       ADRESSE3,
       ADRESSE4,
       CODEPOSTAL,
       VILLE,
       PAYS,
       CODEPAYS,
       CODELANGUE,
       TELEPHONE,
       EMAIL,
       CODETVA,
       CODEDEVISE
FROM PTIERS
WHERE TYPECOMPTE IN('C', 'P')
      AND CODESOCIETE = :company_id
ORDER BY RAISONSOCIALE ASC
````


## 3. Contacts des clients (R3)
#### Endpoint : `GET /thirds/:third_id/contacts`

**Parameters: Body**
````
{
    company_id: String (required),
    third_id: Integer (unsigned) (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne un tableau d'objets avec la liste de tous les clients et prospects
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT NOCONTACT,
       NOCOMPTE,
       CIVILITE,
       NOMPRENOM,
       FONCTIONCONTACT,
       TELEPHONE,
       TELEPHONEMOBILE,
       EMAIL,
       PRINCIPAL
FROM PCONTACTS
WHERE CODESOCIETE = :company_id
      AND NOCOMPTE = :third_id
      AND TYPECOMPTE IN('C', 'P')
      AND ACTIF = 1
ORDER BY PRINCIPAL DESC,
         NOMPRENOM ASC
````


## 4. Supports d'impression (R4)
#### Endpoint : `GET /substrates`

**Parameters: Body**
````
{
    company_id: String (required),
    establishment_id: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne un tableau d'objets avec la liste des supports
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T1.IDREFSTOCK,
       T4.LIBELLE AS FAMILLE,
       T5.LIBELLE AS TYPE,
       T1.LIBELLE,
       T6.LIBELLE AS COULEUR,
       T2.GRAMMAGE,
       T7.LIBELLE AS MASSE,
       T2.LAIZEDIMENSION,
       T1.PRIXDEVIS
FROM PSTOCKMATIERESENTETE T1,
     PSTOCKMATIERESBOBINESADH T2,
     PCLASSES T3,
     PGENRES T4,
     PAPPELLATIONS T5,
     PCOULEURS T6,
     PMASSESADHESIVES T7
WHERE T1.CODESOCIETE = :company_id
      AND T1.CODEETABLISSEMENT = :establishment_id
      AND T1.DATESUSPENSION IS NULL
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T1.CODEETABLISSEMENT = T2.CODEETABLISSEMENT
      AND T1.IDREFSTOCK = T2.IDREFSTOCK
      AND T1.CODESOCIETE = T3.CODESOCIETE
      AND T1.CODECLASSE = T3.CODECLASSE
      AND T3.IDCLASSEDEBASE = '5'
      AND T2.CODEGENRE = T4.CODEGENRE
      AND T2.CODEAPPELLATION = T5.CODEAPPELLATION
      AND T2.CODECOULEUR = T6.CODECOULEUR
      AND T2.CODEMASSEADHESIVE = T7.CODEMASSEADHESIVE
ORDER BY FAMILLE,
         TYPE,
         COULEUR,
         MASSE
````


## 5. Fournisseurs des supports (R9)
#### Endpoint : `GET /substrates/suppliers`

**Parameters: Body**
````
{
    company_id: String (required),
    establishment_id: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne un tableau d'objets avec la liste des fournisseurs de supports
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T5.NOCOMPTE,
       T5.MOTCLE,
       T5.RAISONSOCIALE
FROM PSTOCKMATIERESENTETE T1,
     PSTOCKMATIERESBOBINESADH T2,
     PCLASSES T3,
     PSTOCKMATIERESFOURNISSEURS T4,
     PTIERS T5
WHERE T1.CODESOCIETE = :company_id
      AND T1.CODEETABLISSEMENT = :establishment_id
      AND T1.DATESUSPENSION IS NULL
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T1.CODEETABLISSEMENT = T2.CODEETABLISSEMENT
      AND T1.IDREFSTOCK = T2.IDREFSTOCK
      AND T1.CODECLASSE = T3.CODECLASSE
      AND T3.IDCLASSEDEBASE = '5'
      AND T2.IDREFSTOCK = T4.IDREFSTOCK
      AND T1.CODESOCIETE = T4.CODESOCIETE
      AND T1.CODEETABLISSEMENT = T4.CODEETABLISSEMENT
      AND T1.CODESOCIETE = T5.CODESOCIETE
      AND T4.NOCOMPTE = T5.NOCOMPTE
      AND T5.TYPECOMPTE = 'F'
ORDER BY RAISONSOCIALE ASC
````


## 6. Types de finitions (R10)
#### Endpoint : `GET /finishings`

**Parameters: Body**
````
{
    company_id: String (required),
    establishment_id: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne un tableau d'objets avec la liste des types de finitions
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T1.CODEOPEFAB,
       T1.LIBELLE,
       T1.CONSOMMABLE,
       T1.LISTECODECLASSECONSO,
       T1.OUTIL,
       T1.LISTECODECLASSEOUTIL
FROM POPEFABENT T1,
     POPEFABLIG T2,
     PTYPESPRODUITSFINIS T3
WHERE T1.CODESOCIETE = :company_id
      AND T1.COULEUR = '0'
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T1.CODEOPEFAB = T2.CODEOPEFAB
      AND T2.NOTYPEPF = T3.NOTYPEPF
      AND T3.LIBELLE = 'ADHESIF'
ORDER BY T1.LIBELLE
````


## 7. Postes de production (R11)
#### Endpoint : `GET /workstations/all`

**Parameters: Body**
````
{
    company_id: String (required),
    class_poste: String (nullable)
}
````

**Out**
````
- Success :
    - 200 : Retourne un tableau d'objets avec la liste des postes de production
- Failure :
    - 400 : Le champ passé en paramètre est manquant et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T1.CODEPOSTE,
       T1.CODECONFIGURATION,
       T1.LIBELLE,
       T1.LAIZEMAXIMUM,
       T1.LAIZEIMPRESSION,
       T1.AVANCEMAXIMUM,
       T1.CELLULEDEREPRISE,
       T1.CADENCE,
       T1.UNITECADENCE,
       T1.NBGROUPES,
       T1.TYPEDEPOSTE,
       T1.PASSECALAGE1,
       T1.PRIXPLAQUEOUCLICHE,
       T1.TEMPSREGLAGEBOBINE,
       T1.TEMPSCALAGEPLAQUEOUCLICHE,
       T1.TEMPSLAVAGEPARGROUPE,
       T2.TAUX2,
       T2.DATEAPPLICATION
FROM PPOSTES T1,
     PTAUX T2
WHERE T1.CODESOCIETE = '001'
      AND T1.DATESUSPENSION IS NULL
      AND T1.CODEPOSTE IN (:class_poste)
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T1.CODEPOSTE = T2.CODEPOSTE
      AND T1.CODECONFIGURATION = T2.CODECONFIGURATION
      AND T2.DATEAPPLICATION = (SELECT MAX(DATEAPPLICATION)
                                FROM PTAUX
                                WHERE CODESOCIETE = T1.CODESOCIETE
                                      AND CODEPOSTE = T1.CODEPOSTE
                                      AND CODECONFIGURATION = T1.CODECONFIGURATION)
ORDER BY T1.LIBELLE
````


## 8. Etiquettes (R13)
#### Endpoint : `GET /labels`

**Parameters: Body**
````
{
    company_id: String (required),
    third_id: Integer (unsigned) (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne un tableau d'objets avec la liste des étiquettes d'un client
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T1.REFARTICLE,
       T1.VARARTICLE,
       T1.CLIENTFINAL,
       T1.LIBELLEVARIANTE,
       T2.LAIZEDIMENSION,
       T2.AVANCEDIMENSION,
       T2.NBCOULRECTO,
       T2.QUADRI,
       T2.CODEGENRE,
       T2.CODEAPPELLATION,
       T2.CODECOULEUR,
       T2.GRAMMAGE,
       T2.ROULEAUXDE
FROM PETIENTETE T1,
     PETIADH T2,
     PTIERS T3
WHERE T1.CODESOCIETE = :company_id
      AND T1.NOCOMPTE = :third_id
      AND T1.DATESUSPENSION IS NULL
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T1.REFARTICLE = T2.REFARTICLE
      AND T1.VARARTICLE = T2.VARARTICLE
      AND T1.CODESOCIETE = T3.CODESOCIETE
      AND T1.NOCOMPTE = T3.NOCOMPTE
      AND T1.TYPECOMPTE = T3.TYPECOMPTE
      AND T3.TYPECOMPTE = 'C'
ORDER BY T1.REFARTICLE,
         T1.VARARTICLE
````


## 9. Support d'une étiquette (R14)
#### Endpoint : `GET /labels/:label_id/substrate`

**Parameters: Body**
````
{
    company_id: String (required),
    establishment_id: String (required),
    label_id: String (required),
    variant_id: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne le papier correspond à l'étiquette s'il existe
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T1.IDREFSTOCK,
       T5.LIBELLE AS FAMILLE,
       T6.LIBELLE AS TYPE,
       T1.LIBELLE,
       T7.LIBELLE AS COULEUR,
       T8.LIBELLE AS MASSE,
       T2.GRAMMAGE,
       T2.LAIZEDIMENSION,
       T1.PRIXDEVIS
FROM PSTOCKMATIERESENTETE T1,
     PSTOCKMATIERESBOBINESADH T2,
     PETIOPEFAB T3,
     PCLASSES T4,
     PGENRES T5,
     PAPPELLATIONS T6,
     PCOULEURS T7,
     PMASSESADHESIVES T8
WHERE T1.CODESOCIETE = :company_id
      AND T1.CODEETABLISSEMENT = :establishment_id
      AND T1.DATESUSPENSION IS NULL
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T1.CODEETABLISSEMENT = T2.CODEETABLISSEMENT
      AND T1.IDREFSTOCK = T2.IDREFSTOCK
      AND T2.IDREFSTOCK = T3.IDREFSTOCK
      AND T1.CODECLASSE = T4.CODECLASSE
      AND T4.IDCLASSEDEBASE = '5'        
      AND T2.CODEGENRE = T5.CODEGENRE
      AND T2.CODEAPPELLATION = T6.CODEAPPELLATION
      AND T2.CODECOULEUR = T7.CODECOULEUR
      AND T2.CODEMASSEADHESIVE = T8.CODEMASSEADHESIVE
      AND T3.NOSOLUTION > 0
      AND T3.REFARTICLE = :label_id
      AND T3.VARARTICLE = :variant_id
ORDER BY FAMILLE,
         TYPE,
         COULEUR,
         MASSE
````


## 10. Famille de supports d'une étiquette (R15)
#### Endpoint : `GET /labels/:label_id/family`

**Parameters: Body**
````
{
    company_id: String (required),
    establishment_id: String (required),
    label_id: String (required),
    variant_id: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne les familles de papier correspondant à l'étiquette
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T1.IDREFSTOCK,
       T5.LIBELLE AS FAMILLE,
       T6.LIBELLE AS TYPE,
       T1.LIBELLE,
       T7.LIBELLE AS COULEUR,
       T8.LIBELLE AS MASSE,
       T2.GRAMMAGE,
       T2.LAIZEDIMENSION,
       T1.PRIXDEVIS
FROM PSTOCKMATIERESENTETE T1,
     PSTOCKMATIERESBOBINESADH T2,
     PETIADH T3,
     PCLASSES T4,
     PGENRES T5,
     PAPPELLATIONS T6,
     PCOULEURS T7,
     PMASSESADHESIVES T8
WHERE T1.CODESOCIETE = :company_id
      AND T1.CODEETABLISSEMENT = :establishment_id
      AND T1.DATESUSPENSION IS NULL
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T1.CODEETABLISSEMENT = T2.CODEETABLISSEMENT
      AND T1.IDREFSTOCK = T2.IDREFSTOCK
      AND T1.CODECLASSE = T4.CODECLASSE
      AND T4.IDCLASSEDEBASE = '5'        
      AND T2.CODEGENRE = T5.CODEGENRE
      AND T2.CODEAPPELLATION = T6.CODEAPPELLATION
      AND T2.CODECOULEUR = T7.CODECOULEUR
      AND T2.CODEMASSEADHESIVE = T8.CODEMASSEADHESIVE
      AND T3.REFARTICLE = :label_id
      AND T3.VARARTICLE = :variant_id
      AND T3.CODEGENRE = T2.CODEGENRE
      AND T3.CODEAPPELLATION = T2.CODEAPPELLATION
ORDER BY FAMILLE,
         TYPE,
         COULEUR,
         MASSE
````


## 11. Finitions d'une étiquette (R16)
#### Endpoint : `GET /labels/:label_id/finishings`

**Parameters: Body**
````
{
    company_id: String (required),
    label_id: String (required),
    variant_id: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne les finitions possibles d'une étiquette
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T2.CODEOPEFAB,
       T2.LIBELLE,
       T2.CONSOMMABLE,
       T2.LISTECODECLASSECONSO,
       T2.OUTIL,
       T2.LISTECODECLASSEOUTIL
FROM PETIOPEFAB T1,
     POPEFABENT T2,
     POPEFABLIG T3,
     PTYPESPRODUITSFINIS T4
WHERE T1.CODESOCIETE = :company_id
      AND T1.REFARTICLE = :label_id
      AND T1.VARARTICLE = :variant_id
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T1.CODEOPEFAB = T2.CODEOPEFAB
      AND T2.COULEUR = '0'
      AND T2.CODESOCIETE = T3.CODESOCIETE
      AND T2.CODEOPEFAB = T3.CODEOPEFAB
      AND T3.NOTYPEPF = T4.NOTYPEPF
      AND T4.LIBELLE = 'ADHESIF'
ORDER BY T2.LIBELLE
````


## 12. Outils d'une étiquette (R17)
#### Endpoint : `POST /labels/dies`

**Parameters: Body**
````
{
    company_id: String (required),
    label_id: String (required),
    variant_id: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne les outils d'une étiquette
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T3.CODEOPEFAB,
       T3.LIBELLE,
       T1.IDREFSTOCK,
       T3.LISTECODECLASSEOUTIL,
       T3.LISTECODECLASSECONSO,
       T6.LIBELLE AS CLASSE
FROM PETIOPEFABMATIERES T1,
     PETIOPEFAB T2,
     POPEFABENT T3,
     PSTOCKMATIERESENTETE T4,
     PCLASSES T5,
     PCLASSESDEBASE T6
WHERE T1.CODESOCIETE = :company_id
      AND T1.REFARTICLE = :label_id
      AND T1.VARARTICLE = :variant_id
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T1.REFARTICLE = T2.REFARTICLE
      AND T1.VARARTICLE = T2.VARARTICLE
      AND T1.IDLIGNEOPEFAB = T2.IDLIGNE
      AND T2.NOSOLUTION = '0'
      AND T2.CODESOCIETE = T3.CODESOCIETE
      AND T2.CODEOPEFAB = T3.CODEOPEFAB
      AND T3.COULEUR = '0'
      AND T3.CODESOCIETE = T4.CODESOCIETE
      AND T1.IDREFSTOCK = T4.IDREFSTOCK
      AND T4.DATESUSPENSION IS NULL
      AND T4.CODESOCIETE = T5.CODESOCIETE
      AND T4.CODECLASSE = T5.CODECLASSE
      AND T5.IDCLASSEDEBASE = T6.IDCLASSEDEBASE
ORDER BY T3.CODEOPEFAB,
         T3.LIBELLE
````


## 13. Outil de finition d'une étiquette et forme de découpe (R18)
#### Endpoint : `POST /labels/die`

**Parameters: Body**
````
{
    company_id: String (required),
    reference: Integer (unsigned) (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne l'outil de finition et forme de découpe d'une étiquette
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T1.IDREFSTOCK,
       T1.REFSTOCK,
       T1.LIBELLE,
       T2.LAIZEDIMENSION,
       T2.AVANCEDIMENSION,
       T2.LAIZEENTREPOSE,
       T2.AVANCEENTREPOSE,
       T2.LAIZENBPOSES,
       T2.AVANCENBPOSES,
       T2.LISTEDESPOSTES,
       T2.OUTILDECOUPE
FROM PSTOCKMATIERESENTETE T1,
     PSTOCKMATIERESOUTILS T2
WHERE T1.CODESOCIETE = :company_id
      AND T1.SUSPENSION = 0
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T2.IDREFSTOCK = T1.IDREFSTOCK
      AND T2.IDREFSTOCK = :reference
ORDER BY T1.LIBELLE
````


## 14. Consommable de finition d'une étiquette (R19)
#### Endpoint : `POST /labels/finishings/consumables`

**Parameters: Body**
````
{
    company_id: String (required),
    reference: Integer (unsigned) (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne le consommable d'une finition d'une étiquette
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT DISTINCT T1.IDREFSTOCK,
       T1.LIBELLE,
       T1.PRIXDEVIS
FROM PSTOCKMATIERESENTETE T1
WHERE T1.CODESOCIETE = :company_id
      AND T1.IDREFSTOCK = :reference
      AND T1.DATESUSPENSION IS NULL
ORDER BY T1.LIBELLE
````


## 15. Consommables (R20)
#### Endpoint : `POST /consumables`

**Parameters: Body**
````
{
    company_id: String (required),
    establishment_id: String (required),
    class_poste: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne un tableau d'objets avec la liste des consommables d'une finition
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT T1.IDREFSTOCK,
       T1.LIBELLE,
       T1.PRIXDEVIS
FROM PSTOCKMATIERESENTETE T1
WHERE T1.CODESOCIETE = :company_id
      AND T1.CODEETABLISSEMENT = :establishment_id
      AND T1.CODECLASSE IN (:class_poste)
      AND T1.DATESUSPENSION IS NULL
ORDER BY T1.LIBELLE
````


## 16. Outils de finition et formes de découpe (R21)
#### Endpoint : `POST /dies`

**Parameters: Body**
````
{
    company_id: String (required),
    establishment_id: String (required),
    class_poste: String (required)
}
````

**Out**
````
- Success :
    - 200 : Retourne un tableau d'objets avec la liste de l'ensemble des outils
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT T1.IDREFSTOCK,
       T1.REFSTOCK,
       T1.LIBELLE,
       T2.LAIZEDIMENSION,
       T2.AVANCEDIMENSION,
       T2.LAIZEENTREPOSE,
       T2.AVANCEENTREPOSE,
       T2.LAIZENBPOSES,
       T2.AVANCENBPOSES,
       T2.LISTEDESPOSTES,
       T2.OUTILDECOUPE
FROM PSTOCKMATIERESENTETE T1,
     PSTOCKMATIERESOUTILS T2
WHERE T1.CODESOCIETE = :company_id
      AND T1.CODEETABLISSEMENT = :establishment_id
      AND T1.SUSPENSION = 0
      AND T1.CODECLASSE IN (:class_poste)
      AND T1.CODESOCIETE = T2.CODESOCIETE
      AND T1.CODEETABLISSEMENT = T2.CODEETABLISSEMENT
      AND T1.IDREFSTOCK = T2.IDREFSTOCK
ORDER BY T1.LIBELLE
````


## 17. Calages et cadences des opérations de fabrication (R23)
#### Endpoint : `POST /finishings/cadences`

**Parameters: Body**
````
{
    company_id: String (required),
    operation_id: String (nullable),
    workstation_id: String (nullable)
}
````

**Out**
````
- Success :
    - 200 : Retourne un tableau d'objets avec la liste des cadences et calages
- Failure :
    - 400 : Les champs passés en paramètre sont manquants et/ou mauvais
    - 404 : Ressource inexistante ou non trouvée
    - 500 : Erreur serveur
````

**Request**
````
SELECT CODEPOSTE,
       CODEOPEFAB,
       PASSECALAGE,
       UNITE,
       TEMPSCALAGE,
       CADENCE
FROM PPOSTESCALAGES
WHERE CODESOCIETE = :company_id
      AND CODEOPEFAB = :operation_id
      AND CODEPOSTE = :workstation_id
ORDER BY CODEPOSTE ASC,
         CODEOPEFAB ASC
````

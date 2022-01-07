drop table if exists LaureatePerson, LaureateBirthDate, LaureateBirthPlace, LaureateOrg, LaureateFoundedDate, LaureateFoundedPlace, NobelPrizes, PrizeInfo, OneAffiliation, MultAffiliations, AffiliationsLocOne, AffiliationsLocMult;

create table LaureatePerson(id int primary key, givenName varchar(100), familyName varchar(100), gender varchar(10));
create table LaureateBirthDate(id int primary key, birthDate DATE);
create table LaureateBirthPlace(id int primary key, birthCity varchar(100), birthCountry varchar(100));
create table LaureateOrg(id int primary key, orgName varchar(100));
create table LaureateFoundedDate(id int primary key, foundedDate DATE);
create table LaureateFoundedPlace(id int primary key, foundedCity varchar(100), foundedCountry varchar(100));
create table NobelPrizes(id int primary key, prizeKey varchar(100), prizeKey2 varchar(100), prizeKey3 varchar(100));
create table PrizeInfo(prizeKey varchar(100) primary key, awardYear int, category varchar(100), sortOrder int);
create table OneAffiliation(prizeKey varchar(100) primary key, affName varchar(200));
create table MultAffiliations(prizeKey varchar(100) primary key, affName varchar(200), affName2 varchar(200), affName3 varchar(200), affName4 varchar(200));
create table AffiliationsLocOne(prizeKey varchar(100) primary key, affCity varchar(100), affCountry varchar(100));
create table AffiliationsLocMult(prizeKey varchar(100) primary key, affCity varchar(100), affCountry varchar(100), affCity2 varchar(100), affCountry2 varchar(100), affCity3 varchar(100), affCountry3 varchar(100), affCity4 varchar(100), affCountry4 varchar(100));       	     				  	       	       	    

load data local infile './LaureatePerson.del' into table LaureatePerson;
load data local infile './LaureateBirthDate.del' into table LaureateBirthDate;
load data local infile './LaureateBirthPlace.del' into table LaureateBirthPlace;
load data local infile './LaureateOrg.del' into table LaureateOrg;
load data local infile './LaureateFoundedDate.del' into table LaureateFoundedDate;
load data local infile './LaureateFoundedPlace.del' into table LaureateFoundedPlace;
load data local infile './NobelPrizes.del' into table NobelPrizes;
load data local infile './PrizeInfo.del' into table PrizeInfo;
load data local infile './OneAffiliation.del' into table OneAffiliation;
load data local infile './MultAffiliations.del' into table MultAffiliations;
load data local infile './AffiliationsLocOne.del' into table AffiliationsLocOne;
load data local infile './AffiliationsLocMult.del' into table AffiliationsLocMult;


create table stateipcdata
as select STATE_OR_UT, YEAR, count(MURDER) as MURDER, count(ATTEMPT_TO_MURDER) as ATTEMP_TO_MURDER,
count(CULPABLE_HOMICIDE_NOT_AMOUNTING_TO_MURDER) as CULPABLE_HOMICIDE_NOT_AMOUNTING_TO_MURDER,
count(RAPE) as RAPE,
count(CUSTODIAL_RAPE) as CUSTODIAL_RAPE,
count(OTHER_RAPE) as OTHER_RAPE,
count(KIDNAPPING_AND_ABDUCTION) as KIDNAPPING_AND_ABDUCTION,
count(KIDNAPPING_AND_ABDUCTION_OF_WOMEN_AND_GIRLS) as KIDNAPPING_AND_ABDUCTION_OF_WOMEN_AND_GIRLS,
count(KIDNAPPING_AND_ABDUCTION_OF_OTHERS) as KIDNAPPING_AND_ABDUCTION_OF_OTHERS,
count(DACOITY) as DACOITY,
count(PREPARATION_AND_ASSEMBLY_FOR_DACOITY) as PREPARATION_AND_ASSEMBLY_FOR_DACOITY,
count(ROBBERY) as ROBBERY,
count(BURGLARY) as BURGLARY,
count(THEFT) as THEFT,
count(AUTO_THEFT) as AUTO_THEFT,
count(OTHER_THEFT) as OTHER_THEFT,
count(RIOTS) as RIOTS,
count(CRIMINAL_BREACH_OF_TRUST) as CRIMINAL_BREACH_OF_TRUST,
count(CHEATING) as CHEATING,
count(COUNTERFIETING) as COUNTERFIETING,
count(ARSON) as ARSON,
count(HURT_OR_GREVIOUS_HURT) as HURT_OR_GREVIOUS_HURT,
count(DOWRY_DEATHS) as DOWRY_DEATHS,
count(ASSAULT_ON_WOMEN_WITH_INTENT_TO_OUTRAGE_HER_MODESTY) as ASSAULT_ON_WOMEN_WITH_INTENT_TO_OUTRAGE_HER_MODESTY,
count(INSULT_TO_MODESTY_OF_WOMEN) as INSULT_TO_MODESTY_OF_WOMEN,
count(CRUELTY_BY_HUSBAND_OR_HIS_RELATIVES) as CRUELTY_BY_HUSBAND_OR_HIS_RELATIVES,
count(IMPORTATION_OF_GIRLS_FROM_FOREIGN_COUNTRIES) as IMPORTATION_OF_GIRLS_FROM_FOREIGN_COUNTRIES,
count(CAUSING_DEATH_BY_NEGLIGENCE) as CAUSING_DEATH_BY_NEGLIGENCE,
count(OTHER_IPC_CRIMES) as OTHER_IPC_CRIMES,
count(TOTAL_IPC_CRIMES) as TOTAL_IPC_CRIMES from districtipcdata group by (STATE_OR_UT, YEAR);

create view cs1160315_queryopti as select paperid from paper where upper(title) like '%QUERY%OPTIMIZATION%';

select * from ls2009candi, attendancedata where UPPER(name) like '%' || UPPER (candidate_name) || '%';

create trigger addnewcandidate as
	after insert on ls2009candi
	execute 

create or replace function newcandidateattend() return trigger as 

create table agriculture as select state_name as state, district_name as district, crop_year as year, season, sum(cast(production as float)) as production, sum(cast(area as float)) as area from agriproduction group by (state_name, district_name, crop_year, season) order by (state_name, district_name, year, season);

CREATE OR REPLACE FUNCTION maxdiv()
	RETURNS int AS
$$
BEGIN
	RETURN max(division) + 1 from attendancedata;
END;
$$
LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION addcand()
  RETURNS trigger AS
$$
BEGIN
         INSERT INTO attendancedata
         VALUES(100,maxdiv(),NEW.candidate_name,15,2,NEW.state_name,NEW.pc_name,0,0);
 
    RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER addnew
  AFTER INSERT
  ON ls2009candi
  FOR EACH ROW
  EXECUTE PROCEDURE addcand();


grant ALL on attendancedata to group_13 ;
grant ALL on statewiseattendance to group_13 ;
grant ALL on agriculture to group_13 ;
grant ALL on compiled_attendance to group_13 ;
grant ALL on distoconsti to group_13 ;
grant ALL on districtipcdata to group_13 ;
grant ALL on govtbills to group_13 ;
grant ALL on ls2009candi to group_13 ;
grant ALL on ls2014candi to group_13 ;
grant ALL on privatebills to group_13 ;
grant ALL on stateipcdata to group_13 ;
<?php 
$totquery="
drop table stateipcdata;

create view stateipcdata
as select STATE_OR_UT, YEAR, sum(MURDER) as MURDER, sum(ATTEMPT_TO_MURDER) as ATTEMP_TO_MURDER,
sum(CULPABLE_HOMICIDE_NOT_AMOUNTING_TO_MURDER) as CULPABLE_HOMICIDE_NOT_AMOUNTING_TO_MURDER,
sum(RAPE) as RAPE,
sum(CUSTODIAL_RAPE) as CUSTODIAL_RAPE,
sum(OTHER_RAPE) as OTHER_RAPE,
sum(KIDNAPPING_AND_ABDUCTION) as KIDNAPPING_AND_ABDUCTION,
sum(KIDNAPPING_AND_ABDUCTION_OF_WOMEN_AND_GIRLS) as KIDNAPPING_AND_ABDUCTION_OF_WOMEN_AND_GIRLS,
sum(KIDNAPPING_AND_ABDUCTION_OF_OTHERS) as KIDNAPPING_AND_ABDUCTION_OF_OTHERS,
sum(DACOITY) as DACOITY,
sum(PREPARATION_AND_ASSEMBLY_FOR_DACOITY) as PREPARATION_AND_ASSEMBLY_FOR_DACOITY,
sum(ROBBERY) as ROBBERY,
sum(BURGLARY) as BURGLARY,
sum(THEFT) as THEFT,
sum(AUTO_THEFT) as AUTO_THEFT,
sum(OTHER_THEFT) as OTHER_THEFT,
sum(RIOTS) as RIOTS,
sum(CRIMINAL_BREACH_OF_TRUST) as CRIMINAL_BREACH_OF_TRUST,
sum(CHEATING) as CHEATING,
sum(COUNTERFIETING) as COUNTERFIETING,
sum(ARSON) as ARSON,
sum(HURT_OR_GREVIOUS_HURT) as HURT_OR_GREVIOUS_HURT,
sum(DOWRY_DEATHS) as DOWRY_DEATHS,
sum(ASSAULT_ON_WOMEN_WITH_INTENT_TO_OUTRAGE_HER_MODESTY) as ASSAULT_ON_WOMEN_WITH_INTENT_TO_OUTRAGE_HER_MODESTY,
sum(INSULT_TO_MODESTY_OF_WOMEN) as INSULT_TO_MODESTY_OF_WOMEN,
sum(CRUELTY_BY_HUSBAND_OR_HIS_RELATIVES) as CRUELTY_BY_HUSBAND_OR_HIS_RELATIVES,
sum(IMPORTATION_OF_GIRLS_FROM_FOREIGN_COUNTRIES) as IMPORTATION_OF_GIRLS_FROM_FOREIGN_COUNTRIES,
sum(CAUSING_DEATH_BY_NEGLIGENCE) as CAUSING_DEATH_BY_NEGLIGENCE,
sum(OTHER_IPC_CRIMES) as OTHER_IPC_CRIMES,
sum(TOTAL_IPC_CRIMES) as TOTAL_IPC_CRIMES from districtipcdata group by (STATE_OR_UT, YEAR) order by STATE_OR_UT;


--------------------------------------------------------------------- Trigger 1

CREATE OR REPLACE FUNCTION maxsno(i INTEGER)
  RETURNS int AS
$$
BEGIN
  RETURN max(sno) + 1 from attendancedata where session = i;
END;
$$
LANGUAGE 'plpgsql';



CREATE OR REPLACE FUNCTION maxdiv()
	RETURNS int AS
$$
BEGIN
	RETURN max(division) + 1 from attendancedata group by division;
END;
$$
LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION addcand()
  RETURNS trigger AS
$$
DECLARE
  i INTEGER := 0; 
  j INTEGER := 0;
BEGIN

        IF NEW.position = 1 THEN
          j := maxdiv();
          LOOP
             INSERT INTO attendancedata
             VALUES(maxsno(i),j,NEW.candidate_name,15,i,NEW.state_name,NEW.pc_name,0,0);
             i := i + 1;
             EXIT WHEN i = 16;
          END LOOP;
        END IF;
    RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';


DROP TRIGGER addnew on ls2009candi;


CREATE TRIGGER addnew
  AFTER INSERT
  ON ls2009candi
  FOR EACH ROW
  EXECUTE PROCEDURE addcand();



--------------------------------------------------------------------- Constraints

delete from privatebills where short_title_of_bill is null;

alter table attendancedata add constraint loksabha check(ls = 15);
alter table attendancedata add constraint session check (session >=0 AND session <= 15);
alter table agriculture add constraint season check (season like '%Summer%' OR season like '%Winter%' OR season like '%Whole%Year%' OR season like '%Kharif%' OR season like '%Autumn%' OR season like '%Rabi%');
alter table agriculture add constraint year check (year >= 1997 AND year <= 2019);
alter table govtbills add constraint year check (year >= 1950 AND year <= 2019);
alter table districtipcdata add constraint year check (year >= 2000 AND year <= 2019);
alter table ls2009candi add constraint year check (year = 2009);
alter table ls2009candi add constraint month check (month >= 1 AND month <= 12);
alter table ls2009candi add constraint gender check(UPPER(candidate_sex) = 'M' OR UPPER(candidate_sex) = 'F' OR UPPER(candidate_sex) = '');
alter table ls2014candi add constraint year check (year = 2014);
alter table ls2014candi add constraint month check (month >= 1 AND month <= 12);
alter table privatebills add constraint year check (year >= 1950 AND year <= 2019);

alter table agriculture add constraint key1 primary key (state, district, year, season);
create index searchindex1 on attendancedata using btree (division, session);
create index searchindex2 on districtipcdata using btree (state_or_ut, district, year);
alter table privatebills add constraint key2 primary key (bill_no, year, short_title_of_bill);
create index searchindex3 on ls2009candi using btree (st_code, month, year, candidate_name);
create index searchindex4 on ls2014candi using btree (st_code, month, year, candidate_name);

";
$db = pg_connect( "host=10.17.50.115 port=5432 dbname=project1 user=group_13 password=205-265-669" );
$result=pg_query($totquery);
if(!$result){
    echo "Error<br>";
    echo $result."<br>";
    var_dump($result);
}else{
    echo "Success<br>";
    echo $result."<br>";
    var_dump($result);
}
?>
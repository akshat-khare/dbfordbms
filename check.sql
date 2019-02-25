CREATE OR REPLACE FUNCTION addcand()
  RETURNS trigger AS
$$
BEGIN
         UPDATE compiled_attendance
         SET totalsittings = totalsittings + 1
         WHERE division = NEW.division
 
    RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER addnewattendance
  AFTER INSERT
  ON attendancedata
  FOR EACH ROW
  EXECUTE PROCEDURE addattend();
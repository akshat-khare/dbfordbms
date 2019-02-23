update attendancedata
set dayssigned = 0
where dayssigned = 'M';

alter table attendancedata 
alter column dayssigned type integer
using (dayssigned::integer);

create view compiled_attendance as
select division, name, state, constituency, sum(totalsittings) as totalsittings, sum(dayssigned) as dayssigned
from attendancedata group by (division, name, state, constituency);

create view constituencies as
select pc_name from ls2009candi group by pc_name;
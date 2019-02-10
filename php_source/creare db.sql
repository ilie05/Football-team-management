create table players(player_name varchar2(20) NOT NULL, player_id number(3),CONSTRAINT player_id_pk PRIMARY KEY(player_id),position varchar2(15),
					age number(2) ,CONSTRAINT age_min CHECK(age>=18),salary number(5),CONSTRAINT salary_min CHECK(salary>=0),agent_idd number(3), 
                    CONSTRAINT agent_fk FOREIGN KEY(agent_idd) REFERENCES agents(agent_id));
				
create table coaches(coach_name varchar2(20) NOT NULL, coach_id number(3) NOT NULL,CONSTRAINT coach_id_pk PRIMARY KEY(coach_id),
					speciality varchar2(15) NOT NULL, nationality varchar2(15),salary number(4) NOT NULL, CONSTRAINT sal_min CHECK(salary>=0)); 
					
create table training(trainig_naming varchar2(20),coach number(3) NOT NULL, CONSTRAINT coach_fk FOREIGN KEY(coach) REFERENCES coaches(coach_id),
					player number(3),CONSTRAINT player_fk FOREIGN KEY(player) REFERENCES players(player_id) ON DELETE SET NULL);	

create table agents(agent_name varchar2(20), agent_id number(3),CONSTRAINT agent_id_pk PRIMARY KEY(agent_id),birth_date date, 
                    CONSTRAINT birth_date_min CHECK(birth_date<='01-jan-2000'),transfer_commission number(3),
                    CONSTRAINT comision_bw CHECK(transfer_commission BETWEEN 0 and 100));				
					
create table contracts(contract_number number(5) UNIQUE,player number(3) UNIQUE,CONSTRAINT pl_id FOREIGN KEY(player) REFERENCES players(player_id) ON DELETE CASCADE,
					contract_length number(1),CONSTRAINT len_interv CHECK(contract_length BETWEEN 1 and 5), sign_date DATE,
                    CONSTRAINT date_min CHECK(sign_date>='01-jan-2016'));


ALTER TABLE players DROP CONSTRAINT agent_fk;
ALTER TABLE players ADD CONSTRAINT agent_fk FOREIGN KEY (agent_idd) REFERENCES agents(agent_id) ON DELETE SET NULL;

ALTER TABLE training DROP CONSTRAINT coach_fk;
ALTER TABLE training ADD CONSTRAINT coach_fk FOREIGN KEY (coach) REFERENCES coaches(coach_id) ON DELETE CASCADE;


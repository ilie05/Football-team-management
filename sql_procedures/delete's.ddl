CREATE OR REPLACE PROCEDURE delete_player
    (v_player_name in PLAYERS.PLAYER_NAME%type, v_salary in players.salary%type, v_condition in players.condition%type, 
    v_agent_name in AGENTS.AGENT_NAME%type, v_training_name in TRAINING.NAMING%type, v_rows_deleted out number)
IS
    v_agent_id AGENTS.AGENT_ID%type;
    v_training_id TRAINING.TRAINING_ID%type;
    CURSOR c1 IS SELECT agent_id from AGENTS where agent_name=v_agent_name;
    CURSOR c2 IS SELECT training_id from TRAINING where NAMING=v_training_name;
BEGIN
    IF v_player_name IS NOT NULL THEN
        DELETE FROM PLAYERS WHERE player_name=v_player_name;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;

    IF v_salary IS NOT NULL THEN
        DELETE FROM PLAYERS WHERE salary=v_salary;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;
    
    IF v_condition IS NOT NULL THEN
        DELETE FROM PLAYERS WHERE condition=v_condition;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;

    IF v_agent_name IS NOT NULL THEN
        OPEN c1;
        FETCH c1 into v_agent_id;
        IF c1%ROWCOUNT=0 THEN
            RAISE_APPLICATION_ERROR (-20343 , 'Nu exista nici un agent cu asa nume');
        ELSE
            DELETE FROM PLAYERS WHERE agent_id=v_agent_id;
            v_rows_deleted := SQL%ROWCOUNT;
            CLOSE c1;
            RETURN;
        END IF;
    END IF;

    IF v_training_name IS NOT NULL THEN
        OPEN c2;
        FETCH c2 into v_training_id;
        IF c2%ROWCOUNT=0 THEN
            RAISE_APPLICATION_ERROR (-20343 , 'Nu exista nici un antrenament cu asa nume');
        ELSE
            DELETE FROM PLAYERS WHERE training_id=v_training_id;
            v_rows_deleted := SQL%ROWCOUNT;
            CLOSE c2;
            RETURN;        
        END IF ;
    END IF;
END delete_player;
/

CREATE OR REPLACE PROCEDURE delete_coach
    (v_coach_name in COACHES.COACH_NAME%type, v_speciality in COACHES.SPECIALITY %type, v_nationality in COACHES.NATIONALITY%type, v_salary in COACHES.SALARY%type, v_rows_deleted out number)
IS
BEGIN

    IF v_coach_name IS NOT NULL THEN
        DELETE FROM COACHES WHERE coach_name=v_coach_name;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;

    IF v_speciality IS NOT NULL THEN
        DELETE FROM COACHES WHERE SPECIALITY=v_speciality;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;        

    IF v_nationality IS NOT NULL THEN
        DELETE FROM COACHES WHERE NATIONALITY=v_nationality;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;     

    IF v_salary IS NOT NULL THEN
        DELETE FROM COACHES WHERE SALARY=v_salary;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;       
END delete_coach;
/



CREATE OR REPLACE PROCEDURE delete_training
    (v_naming in TRAINING.NAMING%type, v_continuance in TRAINING.CONTINUANCE%type, v_improvement in TRAINING.IMPROVEMENT%type, v_coach_name in COACHES.COACH_NAME%type, v_rows_deleted out number)
IS
    v_coach_id COACHES.COACH_ID %type;
BEGIN
    IF v_continuance IS NOT NULL THEN
        DELETE FROM TRAINING WHERE continuance=v_continuance;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;

    IF v_naming IS NOT NULL THEN
        DELETE FROM TRAINING WHERE naming=v_naming;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;
    
    IF v_improvement IS NOT NULL THEN
        DELETE FROM TRAINING WHERE improvement=v_improvement;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;
    
    IF v_coach_name IS NOT NULL THEN
        select coach_id into v_coach_id from coaches where coach_name=v_coach_name;
        DELETE FROM TRAINING WHERE coach_id=v_coach_id;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;
    
    EXCEPTION
        WHEN NO_DATA_FOUND  THEN
            RAISE_APPLICATION_ERROR(-20001, 'Nu exista un antrenor cu asa nume!');
END delete_training;
/

CREATE OR REPLACE PROCEDURE delete_agent
    (v_agent_name in AGENTS.AGENT_NAME%type, v_transfer_commisssion in  AGENTS.TRANSFER_COMMISSION%type, v_rows_deleted out number)
IS
BEGIN
    IF v_agent_name IS NOT NULL THEN
        DELETE FROM AGENTS WHERE agent_name=v_agent_name;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;
    
    IF v_transfer_commisssion is not null THEN
        DELETE FROM AGENTS WHERE transfer_commission=v_transfer_commisssion;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;
END delete_agent;
/

CREATE OR REPLACE PROCEDURE delete_contract
    (v_contract_number in CONTRACTS.CONTRACT_NUMBER%type, v_contract_length in CONTRACTS.CONTRACT_LENGTH%type, v_player_name in PLAYERS.PLAYER_NAME%type, v_rows_deleted out number)
IS
    v_player_id PLAYERS.PLAYER_ID%type;
    CURSOR c1 IS SELECT player_id from players where player_name=v_player_name;
BEGIN
    IF v_contract_number IS NOT NULL THEN
        DELETE FROM CONTRACTS WHERE contract_number=v_contract_number;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;

    IF v_contract_length IS NOT NULL THEN
        DELETE FROM CONTRACTS WHERE contract_length=v_contract_length;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;

    IF v_player_name IS NOT NULL THEN
        open c1;
        fetch c1 into v_player_id;
        IF c1%ROWCOUNT=0 THEN
            RAISE_APPLICATION_ERROR (-20343 , 'Nu exista nici un jucator cu asa nume');
        END IF ;
        CLOSE c1;
        DELETE FROM CONTRACTS WHERE player_id=v_player_id;
        v_rows_deleted := SQL%ROWCOUNT;
        RETURN;
    END IF;
END delete_contract;
/
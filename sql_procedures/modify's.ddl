CREATE OR REPLACE PROCEDURE modify_player
    (v_player_name in PLAYERS.PLAYER_NAME%type, v_new_player_name in PLAYERS.PLAYER_NAME%type, v_salary in players.salary%type, 
    v_agent_name in AGENTS.AGENT_NAME%type, v_rows_modified out number)
IS
    v_agent_id AGENTS.AGENT_ID%type;
    player_exist number;
BEGIN
    SELECT COUNT(*) into player_exist from PLAYERS where PLAYER_NAME=v_player_name;
    IF player_exist < 0 THEN
        RAISE_APPLICATION_ERROR (-20343 , 'Jucator inexistent de modificat!');
    END IF;

    IF v_new_player_name IS NOT NULL THEN
        SELECT COUNT(*) into player_exist from PLAYERS where PLAYER_NAME=v_new_player_name;
        IF player_exist > 0 THEN
            RAISE_APPLICATION_ERROR (-20343 , 'Jucator cu numele "' || v_new_player_name || '" deja exista!');
        END IF;
        UPDATE PLAYERS SET PLAYER_NAME=v_new_player_name where PLAYER_NAME=v_player_name;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;

    IF v_salary IS NOT NULL THEN
        IF v_salary <= 0 OR v_salary > 999999 THEN
            RAISE_APPLICATION_ERROR (-20343 , 'Salariul trebuie sa fie un numar positiv mai mic decat 1000000');
        END IF;
        UPDATE PLAYERS SET salary=v_salary where PLAYER_NAME=v_player_name;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;
    

    IF v_agent_name IS NOT NULL THEN
        select COUNT(*) into player_exist from AGENTS where agent_name=v_agent_name;
        IF player_exist < 0 THEN
            RAISE_APPLICATION_ERROR (-20343 , 'Nu exista nici un agent cu asa nume');
        END IF;
        select agent_id into v_agent_id from agents where agent_name=v_agent_name;
        UPDATE PLAYERS SET agent_id=v_agent_id where PLAYER_NAME=v_player_name;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;    
END modify_player;
/

CREATE OR REPLACE PROCEDURE transition_procedure
    (v_player_name in PLAYERS.PLAYER_NAME%type, v_training_naming in TRAINING.NAMING%type)
IS
    v_training_id TRAINING.TRAINING_ID%type;
    v_improvement TRAINING.improvement%type;
    player_exist number;
BEGIN
    SELECT COUNT(*) into player_exist from PLAYERS where PLAYER_NAME=v_player_name;
    IF player_exist < 0 THEN
        RAISE_APPLICATION_ERROR (-20343 , 'Jucator inexistent de modificat!');
    END IF;

    select TRAINING_ID, improvement into v_training_id, v_improvement from TRAINING where NAMING=v_training_naming;
    UPDATE players SET condition=condition+v_improvement, TRAINING_ID=v_training_id where PLAYER_NAME=v_player_name;

    exception    
        when no_data_found then
            RAISE_APPLICATION_ERROR (-20001,'Antrenamentul cu numele "' || v_training_naming || '" nu exista!' );            
END transition_procedure;
/

CREATE OR REPLACE PROCEDURE modify_coach
    ( v_coach_name in COACHES.COACH_NAME%type, v_new_coach_name in COACHES.COACH_NAME%type, v_speciality in COACHES.SPECIALITY %type, 
    v_nationality in COACHES.NATIONALITY%type, v_salary in COACHES.SALARY%type, v_rows_modified out number)
IS
    coach_exist number;
BEGIN

    SELECT COUNT(*) INTO coach_exist FROM COACHES  WHERE coach_name=v_coach_name;
    IF coach_exist < 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'Doriti sa modificati un antrenor inexistent!');
    END IF;

    IF v_new_coach_name IS NOT NULL THEN
        SELECT COUNT(*) INTO coach_exist FROM COACHES  WHERE coach_name=v_new_coach_name;
        IF coach_exist > 0 THEN
            RAISE_APPLICATION_ERROR (-20001,'Antrenorul cu numele "'|| v_new_coach_name||'" exista deja!');
        END IF;
        UPDATE COACHES SET COACH_NAME=v_new_coach_name WHERE COACH_NAME=v_coach_name;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;

    IF v_speciality IS NOT NULL THEN
        IF LENGTH(v_speciality) < 3 THEN
            RAISE_APPLICATION_ERROR (-20001,'Specialitatea antrenorului trebuie sa aiba cel putin 3 caractere!' );
        END IF;
        UPDATE COACHES SET speciality=v_speciality WHERE COACH_NAME=v_coach_name;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;        

    IF v_nationality IS NOT NULL THEN
        IF v_nationality IS NOT NULL AND LENGTH(v_nationality) < 3 THEN
            RAISE_APPLICATION_ERROR (-20001,'Nationalitatea antrenorului trebuie sa aiba cel putin 3 caractere!' );
        END IF;
        UPDATE COACHES SET NATIONALITY=v_nationality WHERE COACH_NAME=v_coach_name;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;     

    IF v_salary IS NOT NULL THEN
        IF v_salary IS NOT NULL AND v_salary < 0 THEN
            RAISE_APPLICATION_ERROR (-20001,'Salariul trebuie sa fie un numar positiv' );
        END IF;
        UPDATE COACHES SET salary=v_salary WHERE COACH_NAME=v_coach_name;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;       
END modify_coach;
/



CREATE OR REPLACE PROCEDURE modify_training
    (v_naming in TRAINING.NAMING%type, v_new_naming in TRAINING.NAMING%type, v_continuance in TRAINING.CONTINUANCE%type, v_improvement in TRAINING.IMPROVEMENT%type, v_coach_name in COACHES.COACH_NAME%type, v_rows_modified out number)
IS
    v_coach_id COACHES.COACH_ID %type;
    training_exist number;
BEGIN
    
    SELECT COUNT(*) INTO training_exist FROM TRAINING  WHERE NAMING=v_naming;
    IF training_exist < 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'Doriti sa modificati un antrenament cu nume inexistent!');
    END IF;

    IF v_new_naming IS NOT NULL THEN
        SELECT COUNT(*) INTO training_exist FROM TRAINING  WHERE NAMING=v_new_naming;
        IF training_exist > 0 THEN
            RAISE_APPLICATION_ERROR(-20001, 'antrenamentul cu numele '|| v_new_naming || ' deja exista!');
        END IF;
        IF LENGTH(v_new_naming) < 3 THEN
            RAISE_APPLICATION_ERROR (-20001,'Denumirea noului antrenamentului trebuie sa contina cel putin 3 caractere!' );
        END IF;
        UPDATE TRAINING SET NAMING=v_new_naming WHERE NAMING=v_naming;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;

    IF v_continuance IS NOT NULL THEN
        IF v_continuance < 20 or v_continuance > 240 THEN
            RAISE_APPLICATION_ERROR (-20001,'Durata unui antrenament trebuie sa apartina intervalului [20,240] minute.' );
        END IF;
        UPDATE TRAINING SET continuance=v_continuance WHERE NAMING=v_naming;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;

    IF v_improvement IS NOT NULL THEN
        IF v_improvement < 1 or v_improvement > 25 THEN
            RAISE_APPLICATION_ERROR (-20001,'Perfectionarea produsa de antrenament trebuie sa apartina intervalului [1,25].' );
        END IF;
        UPDATE TRAINING SET improvement=v_improvement WHERE NAMING=v_naming;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF; 

    IF v_coach_name IS NOT NULL THEN
        select coach_id into v_coach_id from coaches where coach_name=v_coach_name;
        UPDATE TRAINING SET coach_id=v_coach_id WHERE NAMING=v_naming; 
    END IF;

    exception    
        WHEN DUP_VAL_ON_INDEX THEN
            RAISE_APPLICATION_ERROR (-20001,'Antrenorul cu numele "'|| v_coach_name||'" este ocupat!');
        when no_data_found then
            RAISE_APPLICATION_ERROR (-20001,'Antrenorul cu numele "' || v_coach_name || '" nu exista!' );
END modify_training;
/

CREATE OR REPLACE PROCEDURE modify_agent
    (v_agent_name in AGENTS.AGENT_NAME%type, v_agent_new_name in AGENTS.AGENT_NAME%type,
    v_transfer_commisssion in  AGENTS.TRANSFER_COMMISSION%type, v_rows_modified out number)
IS
    agent_exists number;
BEGIN

    SELECT COUNT(*) INTO agent_exists FROM AGENTS  WHERE agent_name=v_agent_name;
    IF agent_exists < 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'Doriti sa modificati un agent cu nume inexistent!');
    END IF;

    IF v_agent_new_name IS NOT NULL THEN
        SELECT COUNT(*) INTO agent_exists FROM AGENTS  WHERE agent_name=v_agent_new_name;
        IF agent_exists > 0 THEN
            RAISE_APPLICATION_ERROR(-20001, 'Agentul cu numele '||v_agent_new_name||' deja exista!');
        END IF;
        UPDATE AGENTS SET agent_name=v_agent_new_name WHERE agent_name=v_agent_name;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;
    
    IF v_transfer_commisssion is not null THEN
        UPDATE AGENTS SET TRANSFER_COMMISSION=v_transfer_commisssion WHERE agent_name=v_agent_name;
        v_rows_modified := SQL%ROWCOUNT;
        RETURN;
    END IF;
END modify_agent;
/

CREATE OR REPLACE PROCEDURE modify_contract
    (v_contract_number in CONTRACTS.CONTRACT_NUMBER%type, v_contract_length in CONTRACTS.CONTRACT_LENGTH%type, v_rows_modified out number)
IS
    v_contract_id CONTRACTS.CONTRACT_NUMBER%type;
    CURSOR c1 IS SELECT contract_id from CONTRACTS where contract_number=v_contract_number;
BEGIN
    open c1;
    fetch c1 into v_contract_id;
    IF c1%ROWCOUNT=0 THEN
        RAISE_APPLICATION_ERROR (-20001 , 'Nu exista nici un contract cu asa numar.');
    END IF ;
    CLOSE c1;
    
    IF v_contract_length < 1 OR v_contract_length > 5 THEN
        RAISE_APPLICATION_ERROR (-20001 , 'Lungimea contractului trebuie sa fie in intervalul [1, 5]');
    END IF;

    UPDATE CONTRACTS SET contract_length=v_contract_length WHERE contract_id=v_contract_id;
    v_rows_modified := SQL%ROWCOUNT;
END modify_contract;
/
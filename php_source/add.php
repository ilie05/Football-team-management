<?php
echo "<h1>Choose the table you want to insert into:</h2>";
include 'tables_select.php';

//input validation function
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if(isset($_POST['table'])){

    switch ($_POST['table']) {
      case 'PLAYERS':
        $name=test_input($_POST['PLAYER_NAME']);            
        $birth_date=test_input($_POST['BIRTH_DATE']);
        $birth_date = date("d-m-Y", strtotime($birth_date));
        $salary=intval(test_input($_POST['SALARY']));
        $condition=intval(test_input($_POST['CONDITION']));
        $agent_name=test_input($_POST['AGENT_NAME']);    

        if($agent_name == "" and $salary == 0){
          $stmt="BEGIN insert_player('".$name."', '".$birth_date."', null, ".$condition.", null); END;";                
        }else if($agent_name == ""){
          $stmt="BEGIN insert_player('".$name."', '".$birth_date."', ".$salary.", ".$condition.", null); END;";
        }else if($salary == 0){
          $stmt="BEGIN insert_player('".$name."', '".$birth_date."', null, ".$condition.", '".$agent_name."'); END;";
        }else{
          $stmt="BEGIN insert_player('".$name."', '".$birth_date."', ".$salary.", ".$condition.", '".$agent_name."'); END;";
        }   

        //set these 2 methods for displaying table after statement execution
        $_POST['submit']='just set';
        $_POST['radio']='PLAYERS';
        break;
      
      case 'COACHES':
        //for inser into table COACHES
        $name=test_input($_POST['COACH_NAME']);
        $speciality=test_input($_POST['SPECIALITY']);
        $salary=intval(test_input($_POST['SALARY']));
        $national=test_input($_POST['NATIONALITY']);

        //set these 2 methods for displaying table after statement execution
        if($salary == 0 and $national == ""){
          $stmt = "BEGIN insert_coach('".$name."', '".$speciality."', null, null); END;";
        }else if($salary == 0){         
          $stmt = "BEGIN insert_coach('".$name."', '".$speciality."', '".$national."', null); END;";
        }else if($national == ""){
          $stmt = "BEGIN insert_coach('".$name."', '".$speciality."', null, ".$salary."); END;";
        }else{
          $stmt = "BEGIN insert_coach('".$name."', '".$speciality."', '".$national."', ".$salary."); END;";
        }

        $_POST['submit']='just set';
        $_POST['radio']='COACHES';            
        break;

      case 'TRAINING':
        $name=test_input($_POST['TRAINIG_NAMING']);
        $coach=test_input($_POST['COACH_NAME']);
        $continuance=intval(test_input($_POST['CONTINUANCE']));
        $improvement=intval(test_input($_POST['IMPROVEMENT']));

        if($continuance == 0){
          $stmt = "BEGIN insert_training('".$name."', null, ".$improvement.", '".$coach."'); END;";
        }else{
          $stmt = "BEGIN insert_training('".$name."', ".$continuance.", ".$improvement.", '".$coach."'); END;";
        }

        //set these 2 methods for displaying table after statement execution
        $_POST['submit']='just set';
        $_POST['radio']='TRAINING';            
        break;

      case 'AGENTS':
        $name=test_input($_POST['AGENT_NAME']);
        $commission=intval(test_input($_POST['TRANSFER_COMMISSION']));

        $stmt = "BEGIN insert_agent('".$name."', ".$commission."); END;";

        //set these 2 methods for displaying table after statement execution
        $_POST['submit']='just set';
        $_POST['radio']='AGENTS';            
        break;

      case 'CONTRACTS':
        $player_name=test_input($_POST['PLAYER_NAME']);
        $contract_length=intval(test_input($_POST['CONTRACT_LENGTH']));
        $sign_date=test_input($_POST['SIGN_DATE']);
        $sign_date = date("d-m-Y", strtotime($sign_date));
        $contract_number = intval(test_input($_POST['CONTRACT_NUMBER']));


        $stmt="BEGIN insert_contract(".$contract_number.", ".$contract_length.", '".$sign_date."', '".$player_name."'); END;";
        //set these 2 methods for displaying table after statement execution
        $_POST['submit']='just set';
        $_POST['radio']='CONTRACTS';            
        break;

      default:
        break;
    }
    
    //execute sql statement

    $objParse= @oci_parse($conn, $stmt);
    $objExecute = @oci_execute($objParse, OCI_NO_AUTO_COMMIT); 
    
    if($objExecute)  
    {  
      oci_commit($conn); //*** Commit Transaction **/  
      echo "Insertion succeed!";  
    }  
    else  
    {  
      oci_rollback($conn); //*** RollBack Transaction ***// 
      $error = oci_error($objParse); 
      //echo $error['message'];
      $e = explode("\n", $error['message']);
      //echo json_encode(['error' => htmlentities(explode(': ', $e[0])[1])]);
      echo htmlentities(explode(': ', $e[0])[1]);
    } 
}

if (isset($_POST['submit']) and isset($_POST['radio'])){
  switch($_POST['radio']){

    case 'PLAYERS':
      echo '<form class="form-input" method="post">
          <div class="container">
          Add:
            <input type="text" name="PLAYER_NAME" placeholder="PLAYER NAME" required>
            <input type="text" name="BIRTH_DATE" placeholder="BIRTH DATE" onfocus="(this.type=\'date\')" onblur="(this.type=\'text\')" required>
            <input type="number" name="SALARY" placeholder="SALARY" min="1" max="999999">
            <input type="number" name="CONDITION" placeholder="CONDITION" min="1" max="99" required>
            <input type="text" name="AGENT_NAME" placeholder="AGENT NAME">
          <input name="table" hidden="true" value="PLAYERS"/>
          <button type="submit" class="btn btn-success btn-sm">add</button>
          </div><br></form>';
      break;


    case 'COACHES':
      echo '<form class="form-input" method="post">
          <div class="container">
          Add:
              <input type="text" name="COACH_NAME" placeholder="COACH NAME" required>
              <input type="text" name="SPECIALITY" placeholder="SPECIALITY" required>
              <input type="text" name="NATIONALITY" placeholder="NATIONALITY" >
              <input type="number" name="SALARY" placeholder="SALARY" min="1" max="99999">
          <input name="table" hidden="true" value="COACHES"/>
          <button type="submit" class="btn btn-success btn-sm">add</button>
          </div><br></form>';  
      break;


    case 'TRAINING':
      echo '<form class="form-input" method="post">
          <div class="container">
            Add:
            <input type="text" name="TRAINIG_NAMING" placeholder="TRAINIG NAMING" required>
            <input type="number" name="CONTINUANCE" placeholder="CONTINUANCE" min="20" max="240">
            <input type="number" name="IMPROVEMENT" placeholder="IMPROVEMENT" min="1" max="25" required>
            <input type="text" name="COACH_NAME" placeholder="COACH_NAME" required>
            <input name="table" hidden="true" value="TRAINING"/>
            <button type="submit" class="btn btn-success btn-sm">add</button>
          </div><br></form>'; 
      break;


    case 'AGENTS':
      echo '<form class="form-input" method="post">
              <div class="container">
              Add:
              <input type="text" name="AGENT_NAME" placeholder="AGENT NAME" required>
              <input type="number" name="TRANSFER_COMMISSION" placeholder="COMMISSION" min="0" max="100" required>
              <input name="table" hidden="true" value="AGENTS"/>
              <button type="submit" class="btn btn-success btn-sm">add</button>
            </div><br></form>'; 
      break;


    case 'CONTRACTS':
      echo '<form class="form-input" method="post">
          <div class="container">
            Add:
            <input type="text" name="PLAYER_NAME" placeholder="PLAYER NAME" required>
            <input type="number" name="CONTRACT_NUMBER" placeholder="CONTRACT NUMBER" min="10000" max="99999" required>
            <input type="number" name="CONTRACT_LENGTH" placeholder="CONTRACT LENGTH" min="1" max="5" required>
            <input type="text" name="SIGN_DATE" placeholder="SIGN DATE" onfocus="(this.type=\'date\')" onblur="(this.type=\'text\')" required>
            <input name="table" hidden="true" value="CONTRACTS"/>
            <button type="submit" class="btn btn-success btn-sm">add</button>
          </div><br></form>';
      break;
      
    default:
      break;
  }   
}

include 'preview.php';
?>


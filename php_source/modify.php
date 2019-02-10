 <?php

echo "<h1>Choose the table you want to modify:</h2>";
include 'tables_select.php';

//input validation function
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if(isset($_POST['select'])&&isset($_POST['mdf_in'])&&isset($_POST['instance'])&&isset($_POST['table'])){
  $instance=test_input($_POST['instance']);
  $select=test_input($_POST['select']);
  $search=test_input($_POST['mdf_in']);

  $_POST['submit']='just set';

  switch ($_POST['table']) {
    case 'PLAYERS':
      switch ($select) {
        case 'PLAYER_NAME':
          $stmt = "BEGIN modify_player('".$instance."', '".$search."', null, null, :rows_modified); END;";
          break;
        case 'SALARY':
          $stmt = "BEGIN modify_player('".$instance."', null, ".$search.", null, :rows_modified); END;";
          break;
        case 'AGENT_NAME':
          $stmt = "BEGIN modify_player('".$instance."',null, null,  '".$search."', :rows_modified); END;";
          break; 
        case 'TRAINIG_NAMING':
          $transition = true;
          $objParse=oci_parse($conn, "SET TRANSACTION NAME 'condition_update'");
          $objExecute = @oci_execute($objParse, OCI_NO_AUTO_COMMIT);
          if($objExecute != true){
            echo "TRANSACTION FAILED FROM CONNECTION REASONS!";
            break;            
          }

          $objParse=oci_parse($conn, "BEGIN transition_procedure('".$instance."', '".$search."'); END;");
          $objExecute = oci_execute($objParse, OCI_NO_AUTO_COMMIT);
          if($objExecute != true){
            echo "after transiton_procedure error!!!";
            $error = oci_error($objParse); 
            $e = explode("\n", $error['message']);
            echo htmlentities(explode(': ', $e[0])[1]);
            break;
          }

          $objParse=oci_parse($conn, "select condition from PLAYERS where player_name='".$instance."'");
          $objExecute = @oci_execute($objParse, OCI_NO_AUTO_COMMIT);
          $condition_after_update = oci_fetch_assoc($objParse)["CONDITION"];
          
          if($condition_after_update > 99){
            oci_rollback($conn);
            echo " Transition failed, player condition cannot get over 99!";
          }else{
            oci_commit($conn);
            echo " Transition succed!";
          }
          break;                             
        default:
          break;
      }
      $_POST['radio']='PLAYERS';
      break;

    case 'COACHES':
      switch ($select) {
        case 'COACH_NAME':
          $stmt = "BEGIN modify_coach('".$instance."', '".$search."', null, null, null, :rows_modified); END;";          
          break;
        case 'SPECIALITY':
          $stmt = "BEGIN modify_coach('".$instance."',null,  '".$search."', null, null, :rows_modified); END;";          
          break;
        case 'NATIONALITY':
          $stmt = "BEGIN modify_coach('".$instance."',null, null, '".$search."', null, :rows_modified); END;";          
          break; 
        case 'SALARY':
          $stmt = "BEGIN modify_coach('".$instance."',null, null, null, ".$search.", :rows_modified); END;";          
          break;                                     
        default:
          break;
      }
      $_POST['radio']='COACHES';
      break;

    case 'TRAINING':
      switch ($select) {
        case 'TRAINIG_NAMING':
          $stmt = "BEGIN modify_training('".$instance."', '".$search."', null, null, null, :rows_modified); END;";          
          break;
        case 'TRAINING_CONTINUANCE':
          $stmt = "BEGIN modify_training('".$instance."', null, ".$search.", null, null, :rows_modified); END;";          
          break;
        case 'TRAINING_IMPROVEMENT':
          $stmt = "BEGIN modify_training('".$instance."', null, null, ".$search.", null, :rows_modified); END;";          
          break;
        case 'COACH_NAME':
          $stmt = "BEGIN modify_training('".$instance."', null, null, null, '".$search."', :rows_modified); END;";          
          break;                    
        default:        
          break;
      }
      $_POST['radio']='TRAINING';
      break;

    case 'AGENTS':
      switch ($select) {
        case 'AGENT_NAME':
          $stmt = "BEGIN modify_agent('".$instance."', '".$search."', null, :rows_modified); END;";                  
          break;
        case 'TRANSFER_COMMISSION':
          $stmt = "BEGIN modify_agent('".$instance."', null, ".$search.", :rows_modified); END;";        
          break;                
        default:
          break;
      }
      $_POST['radio']='AGENTS';  
      break;

    case 'CONTRACTS':
      $stmt = "BEGIN modify_contract(".$instance.", ".$search.", :rows_modified); END;";
      $_POST['radio']='CONTRACTS';
      break;
    
    default:
      break;
  }

// se executa mereu, cu exceptia tranzactiei
  if($transition != true){
    $objParse=oci_parse($conn, $stmt);
    oci_bind_by_name($objParse, ":rows_modified", $rows_modified);
    $objExecute = @oci_execute($objParse, OCI_NO_AUTO_COMMIT);  

    if($objExecute){
      oci_commit($conn);
      echo $rows_modified." rows Modified.";  
    }   
    else
    {  
      oci_rollback($conn);
      $error = oci_error($objParse); 
      $e = explode("\n", $error['message']);
      echo htmlentities(explode(': ', $e[0])[1]); 
    } 
  }

}


if (isset($_POST['submit']) and isset($_POST['radio'])){
  switch($_POST['radio']){
    case 'PLAYERS':
      echo '<form  method="post">
        <div class="container">
        Player\'s name whose record you want to modify:
        <input type="text" placeholder="player  name" name="instance" style="height:20.4px;"><br><br>
        Modify:
          <select name="select" class="custom-select">
            <option value="PLAYER_NAME">PLAYER NAME</option>
            <option  value="SALARY">SALARY</option>
            <option  value="AGENT_NAME">AGENT NAME</option>
            <option  value="TRAINIG_NAMING">TRAINING</option>
          </select> in
        <input type="text" name="mdf_in" style="height:20.4px;" required>
        <input name="table" hidden="true" value="PLAYERS"/>
        <button type="submit" class="btn btn-warning btn-sm">modify</button>
        </div><br></form>';
      break;


    case 'COACHES':
      echo '<form  method="post">
        <div class="container">
        Coach\'s name whose record you want to modify:
        <input type="text" placeholder="Coach name" name="instance" style="height:20.4px;"><br><br>
        Modify:
          <select name="select" class="custom-select">
            <option value="COACH_NAME">COACH NAME</option>
            <option  value="SPECIALITY">SPECIALITY</option>
            <option  value="NATIONALITY">NATIONALITY</option>
            <option  value="SALARY">SALARY</option>
          </select> in 
        <input type="text" name="mdf_in" style="height:20.4px" required>
        <input name="table" hidden="true" value="COACHES"/>
        <button type="submit" class="btn btn-warning btn-sm">modify</button>
        </div><br></form>'; 
      break;


    case 'TRAINING':
      echo '<form  method="post">
        <div class="container">
        TRAINING you want to modify:
        <input type="text" placeholder="training naming" name="instance" style="height:20.4px;"><br><br>
          <select name="select" class="custom-select">
            <option value="TRAINIG_NAMING">TRAINING NAMING</option>
            <option  value="TRAINING_CONTINUANCE">TRAINING CONTINUANCE</option>
            <option  value="TRAINING_IMPROVEMENT">TRAINING IMPROVEMENT</option>
            <option  value="COACH_NAME">COACH NAME</option>
          </select> in
        <input type="text" name="mdf_in" style="height:20.4px" required>
        <input name="table" hidden="true" value="TRAINING"/>
        <button type="submit" class="btn btn-warning btn-sm">modify</button>
        </div><br></form>'; 
      break;


    case 'AGENTS':
      echo '<form  method="post">
        <div class="container">
        Agent\'s name whose record you want to modify:
        <input type="text" placeholder="agent\'s name" name="instance" style="height:20.4px;"><br><br>
        Modify:
          <select name="select" class="custom-select">
            <option value="AGENT_NAME">AGENT NAME</option>
            <option  value="TRANSFER_COMMISSION">TRANSFER COMMISSION</option>
          </select> in 
        <input type="text" name="mdf_in" style="height:20.4px" required>
        <input name="table" hidden="true" value="AGENTS"/>
        <button type="submit" class="btn btn-warning btn-sm">modify</button>
        </div><br></form>'; 
      break;


    case 'CONTRACTS':
    echo '<form  method="post">
      <div class="container">
      Contract\'s number you want to modify:
      <input type="text" placeholder="contract\'s number" name="instance" style="height:20.4px;"><br><br>
      Modify:
        <select name="select" class="custom-select">
          <option  value="CONTRACT_LENGTH">CONTRACT LENGTH</option>
        </select> in
      <input type="text" name="mdf_in" style="height:20.4px" required>
      <input name="table" hidden="true" value="CONTRACTS"/>
      <button type="submit" class="btn btn-warning btn-sm">modify</button>
      </div><br></form>'; 
      break;
    default:
      break;
  }   
}

include 'preview.php';
?>
<?php
echo "<h1>Choose the table you want to delete from:</h2>";
include 'tables_select.php';

//input validation function
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if(isset($_POST['select']) and isset($_POST['search']) and isset($_POST['table'])){
    // ******input validation

  $column=test_input($_POST['select']);
  $delete_by_value=test_input($_POST['search']);

  switch ($_POST['table']) {
    case 'PLAYERS':
      switch ($column) {
        case 'PLAYER_NAME':
          $stmt = "BEGIN delete_player('".$delete_by_value."', null, null, null, null, :rows_deleted); END;";
          break;

        case 'SALARY':
          $stmt = "BEGIN delete_player(null, ".$delete_by_value.", null, null, null, :rows_deleted); END;";
          break;

        case 'CONDITION':
          $stmt = "BEGIN delete_player(null, null, ".$delete_by_value.", null, null, :rows_deleted); END;";
          break;
        
        case 'AGENT_NAME':
          $stmt = "BEGIN delete_player(null, null, null, '".$delete_by_value."', null, :rows_deleted); END;";
          break;

        case 'TRAINING_NAMING':
          $stmt = "BEGIN delete_player(null, null, null, null, '".$delete_by_value."', :rows_deleted); END;";
          break;

        default:
          break;
      }
      break;

    case 'COACHES':
      switch ($column) {
        case 'COACH_NAME':
          $stmt = "BEGIN delete_coach('".$delete_by_value."', null, null, null, :rows_deleted); END;";
          break;
        case 'SPECIALITY':
          $stmt = "BEGIN delete_coach(null, '".$delete_by_value."', null, null, :rows_deleted); END;";
          break;
        case 'SALARY':
          $delete_by_value=intval($delete_by_value);
          $stmt = "BEGIN delete_coach(null, null, null, ".$delete_by_value.", :rows_deleted); END;";
          break;
        case 'NATIONALITY':
          $stmt = "BEGIN delete_coach(null, null, '".$delete_by_value."', null, :rows_deleted); END;";
          break;    
        default:
          break;
      }
      break;

    case 'TRAINING':
      switch ($column) {
        case 'TRAINIG_NAMING':
          $stmt = "BEGIN delete_training('".$delete_by_value."', null, null, null, :rows_deleted); END;";
          break;
        case 'CONTINUANCE':
          $delete_by_value=intval($delete_by_value);
          $stmt = "BEGIN delete_training(null, ".$delete_by_value.", null, null, :rows_deleted); END;";
          break;
        case 'IMPROVEMENT':
          $delete_by_value=intval($delete_by_value);
          $stmt = "BEGIN delete_training(null, null,  ".$delete_by_value.", null, :rows_deleted); END;";
          break;
        case 'COACH_NAME':
          $stmt = "BEGIN delete_training(null, null, null, '".$delete_by_value."', :rows_deleted); END;";
          break;          
        default:
          break;
      }
      break;

    case "AGENTS":
      switch ($column) {
        case 'AGENT_NAME':
          $stmt = "BEGIN delete_agent('".$delete_by_value."', null, :rows_deleted); END;";        
          break;
        case 'TRANSFER_COMMISSION':
          $delete_by_value=intval($delete_by_value);
          $stmt = "BEGIN delete_agent(null, ".$delete_by_value.", :rows_deleted); END;";        
          break;       
        default:
          break;
      }
      break;
    case "CONTRACTS":
    switch ($column) {
      case 'CONTRACT_NUMBER':
        $delete_by_value=intval($delete_by_value);
        $stmt = "BEGIN delete_contract(".$delete_by_value.", null, null, :rows_deleted); END;";        
        break;
      case 'CONTRACT_LENGTH':
        $delete_by_value=intval($delete_by_value);
        $stmt = "BEGIN delete_contract(null, ".$delete_by_value.", null, :rows_deleted); END;";
        break;
      case 'PLAYER_NAME':
        $stmt = "BEGIN delete_contract(null, null, '".$delete_by_value."', :rows_deleted); END;";
        break;      
      default:
        break;
    }
    default:
      break;
  }

  $objParse=oci_parse($conn, $stmt);
  oci_bind_by_name($objParse, ":rows_deleted", $rows_deleted);
  $objExecute = @oci_execute($objParse, OCI_NO_AUTO_COMMIT); 

  if($objExecute)  
  {  
    oci_commit($conn); //*** Commit Transaction ***//  
    echo $rows_deleted." records Deleted.";  
  }  
    else  
  {  
    oci_rollback($conn); //*** RollBack Transaction ***// 
    $error = oci_error($objParse); 
    echo $error['message'];
    $e = explode("\n", $error['message']);
    echo json_encode(['error' => htmlentities(explode(': ', $e[0])[1])]);
    echo htmlentities(explode(': ', $e[0])[1]);
  }

  //set these 2 methods for displaying table after statement execution
  $_POST['submit']='just set';
  $_POST['radio']=$_POST['table']; 

}

if (isset($_POST['submit']) and isset($_POST['radio'])){
  switch($_POST['radio']){

    case 'PLAYERS':
      echo '<form  method="post">
      <div class="container">
      Delete by:
      <select name="select" class="custom-select">
        <option value="PLAYER_NAME">PLAYER NAME</option>
        <option  value="SALARY">SALARY</option>
        <option  value="CONDITION">CONDITION</option>          
        <option  value="AGENT_NAME">AGENT NAME</option>
        <option  value="TRAINING_NAMING">TRAINING NAMING</option>
      </select>
      <input type="text" name="search" style="height:20.4px" required>
      <input name="table" hidden="true" value="PLAYERS"/>
      <button type="submit" class="btn btn-danger btn-sm">delete</button>
      </div><br></form>';
      break;

    case 'COACHES':
      echo '<form  method="post">
      <div class="container">
      Delete by:
        <select name="select" class="custom-select">
          <option value="COACH_NAME">COACH NAME</option>
          <option  value="SPECIALITY">SPECIALITY</option>
          <option  value="SALARY">SALARY</option>
          <option  value="NATIONALITY">NATIONALITY</option>
        </select>
      <input type="text" name="search" style="height:20.4px" required>
      <input name="table" hidden="true" value="COACHES"/>
      <button type="submit" class="btn btn-danger btn-sm">delete</button>
      </div><br></form>';  
      break;

    case 'TRAINING':
      echo '<form  method="post">
      <div class="container">
      Delete by:
        <select name="select" class="custom-select">
          <option value="TRAINIG_NAMING">TRAINING NAMING</option>
          <option  value="CONTINUANCE">CONTINUANCE</option>
          <option  value="IMPROVEMENT">IMPROVEMENT</option>          
          <option  value="COACH_NAME">COACH</option>
        </select>
      <input type="text" name="search" style="height:20.4px" required>
      <input name="table" hidden="true" value="TRAINING"/>
      <button type="submit" class="btn btn-danger btn-sm">delete</button>
      </div><br></form>'; 
      break;

    case 'AGENTS':
      echo '<form  method="post">
      <div class="container">
      Delete by:
        <select name="select" class="custom-select">
          <option value="AGENT_NAME">AGENT NAME</option>          
          <option  value="TRANSFER_COMMISSION">TRANSFER COMMISSION</option>
        </select>
      <input type="text" name="search" style="height:20.4px" required>
      <input name="table" hidden="true" value="AGENTS"/>
      <button type="submit" class="btn btn-danger btn-sm">delete</button>
      </div><br></form>'; 
      break;

    case 'CONTRACTS':
      echo '<form  method="post">
      <div class="container">
      Delete by:
      <select name="select" class="custom-select">
        <option value="CONTRACT_NUMBER">CONTRACT NUMBER</option>        
        <option  value="CONTRACT_LENGTH">CONTRACT LENGTH</option>
        <option  value="PLAYER_NAME">PLAYER NAME</option>
      </select>
      <input type="text" name="search" style="height:20.4px" required>
      <input name="table" hidden="true" value="CONTRACTS"/>
      <button type="submit" class="btn btn-danger btn-sm">delete</button>
      </div><br></form>'; 
      break;

    default:
      break;
  }   
}

include 'preview.php';
?>
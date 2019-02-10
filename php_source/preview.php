<?php
if (isset($_POST['submit']) and isset($_POST['radio'])) {
  switch($_POST['radio']){
    case 'PLAYERS':
      echo "<table class='table table-hover'>";
      echo "<tr>";
      echo "<th>PLAYER NAME</th>";
      echo "<th>BIRTH DATE</th>";
      echo "<th>SALARY</th>";
      echo "<th>CONDITION</th>";
      echo "<th>AGENT NAME</th>";
      echo "<th>LAST TRAINING</th>";
      echo "</tr>";
      $query = "select players.player_name, players.birth_date, players.salary, players.condition, agents.agent_name, TRAINING.naming  from players left join agents on players.agent_id=agents.AGENT_ID 
        left join TRAINING on players.TRAINING_ID=TRAINING.TRAINING_ID ";
      $stmt=oci_parse($conn, $query);
      oci_execute($stmt, OCI_NO_AUTO_COMMIT);
      while(($row = oci_fetch_array($stmt, OCI_ASSOC)) != false){
        echo "<tr>";
        echo "<td>".oci_result($stmt,"PLAYER_NAME")."</td>";
        echo "<td>".oci_result($stmt,"BIRTH_DATE")."</td>";
        echo "<td>".oci_result($stmt,"SALARY")."</td>";
        echo "<td>".oci_result($stmt,"CONDITION")."</td>";
        echo "<td>".oci_result($stmt,"AGENT_NAME")."</td>";
        echo "<td>".oci_result($stmt,"NAMING")."</td>";
        echo "</tr>";
      }
      echo "</table>";
      break;

    case 'COACHES':
      echo "<table class='table table-hover'>";
      echo "<tr>";
      echo "<th>COACH NAME</th>";
      echo "<th>SPECIALITY</th>";
      echo "<th>SALARY</th>";      
      echo "<th>NATIONALITY</th>";
      echo "</tr>";
      $stmt=oci_parse($conn,"select *from coaches");
      @oci_execute($stmt, OCI_NO_AUTO_COMMIT);
      while(oci_fetch($stmt)){
        echo "<tr>";
        echo "<td>".oci_result($stmt,"COACH_NAME")."</td>";
        echo "<td>".oci_result($stmt,"SPECIALITY")."</td>";
        echo "<td>".oci_result($stmt,"SALARY")."</td>";        
        echo "<td>".oci_result($stmt,"NATIONALITY")."</td>";
        echo "</tr>";
      }
      echo "</table>";
      break;

    case 'TRAINING':
      echo "<table class='table table-hover'>";
      echo "<tr>";
      echo "<th>TRAINING NAMING</th>";
      echo "<th>TRAINING CONTINUANCE</th>";
      echo "<th>TRAINING IMPROVEMENT</th>";
      echo "<th>COACH NAME</th>";
      echo "</tr>";
      $query = "select TRAINING.NAMING, TRAINING.CONTINUANCE, TRAINING.IMPROVEMENT, COACHES.COACH_NAME from TRAINING inner join COACHES on TRAINING.COACH_ID=COACHES.COACH_ID";
      $stmt=oci_parse($conn, $query);
      oci_execute($stmt, OCI_NO_AUTO_COMMIT);
      while(($row = oci_fetch_array($stmt, OCI_ASSOC)) != false){
        echo "<tr>";
        echo "<td>".oci_result($stmt,"NAMING")."</td>";
        echo "<td>".oci_result($stmt,"CONTINUANCE")."</td>";
        echo "<td>".oci_result($stmt,"IMPROVEMENT")."</td>";
        echo "<td>".oci_result($stmt,"COACH_NAME")."</td>";       
        echo "</tr>";
      }
      echo "</table>";
      break;

    case 'AGENTS':
      echo "<table class='table table-hover'>";
      echo "<tr>";
      echo "<th>AGENT NAME</th>";
      echo "<th>TRANSFER COMMISSION</th>";
      echo "<th>Clients Number</th>";
      echo "</tr>";
      $query = "select agents.agent_name, agents.TRANSFER_COMMISSION, count(players.agent_id) player_count from agents left join players on players.agent_id=agents.agent_id group by agents.agent_name, agents.TRANSFER_COMMISSION";
      $stmt=oci_parse($conn, $query);
      @oci_execute($stmt, OCI_NO_AUTO_COMMIT);
      while(oci_fetch($stmt)){
        echo "<tr>";
        echo "<td>".oci_result($stmt,"AGENT_NAME")."</td>";
        echo "<td>".oci_result($stmt,"TRANSFER_COMMISSION")."</td>";
        echo "<td>".oci_result($stmt,"PLAYER_COUNT")."</td>";
        echo "</tr>";
      }

      $stmt=oci_parse($conn,"select *from agents");
      @oci_execute($stmt, OCI_NO_AUTO_COMMIT);

      echo "</table>";
      break;

    case 'CONTRACTS':
      echo "<table class='table table-hover'>";
      echo "<tr>";
      echo "<th>CONTRACT NUMBER</th>";
      echo "<th>CONTRACT LENGTH</th>";
      echo "<th>SIGN_DATE</th>";
      echo "<th>PLAYER NAME</th>";
      echo "</tr>";
      $query = "select contracts.contract_number, contracts.contract_length, contracts.sign_date, players.player_name from contracts inner join players on contracts.player_id=players.PLAYER_ID";
      $stmt=oci_parse($conn, $query);
      oci_execute($stmt, OCI_NO_AUTO_COMMIT);
      while(($row = oci_fetch_array($stmt, OCI_ASSOC)) != false){
        echo "<tr>";
        echo "<td>".oci_result($stmt,"CONTRACT_NUMBER")."</td>";
        echo "<td>".oci_result($stmt,"CONTRACT_LENGTH")."</td>";
        echo "<td>".oci_result($stmt,"SIGN_DATE")."</td>";
        echo "<td>".oci_result($stmt,"PLAYER_NAME")."</td>";
        echo "</tr>";
      }
      echo "</table>";
      break;
    default:
      echo "Ai ajuns unde nu trebuie";
  }
  unset($_POST['radio']);
}
?>
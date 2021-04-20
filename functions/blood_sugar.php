<?php

    include "init.php";

    if(isset($_POST['bsValue']))
    {
        $bsValue   = filter_var($_POST['bsValue'], FILTER_VALIDATE_FLOAT);
        
        $sql = "INSERT INTO blood_sugar_values(user_id,bs_value,date_time) ";
        $sql .= "VALUES(".get_current_user_id().",$bsValue,'".date('Y-m-d H:i:s')."')";
        confirm(query($sql));        

        echo getBSValues();
    }
    
    if(isset($_GET['getBsValue']))
    {        
        echo getBSValues();
    }

    function getBSValues()
    {
        $bsTableRows = '<tr>
                            <th class="col-md-2">SL No</th>';
        
        //if admin, include email also
        if(isset($_SESSION["sblms_admin"]))
            $bsTableRows .= '<th class="col-md-2">Email</th>';
        
        $bsTableRows .= '<th class="col-md-2">BS Level</th>
                        <th>Date & Time</th>
                    </tr>';
        
        if(isset($_SESSION["sblms_admin"]))        
            $query = "SELECT * FROM blood_sugar_values ORDER BY id DESC";
        else
            $query = "SELECT * FROM blood_sugar_values WHERE user_id=".get_current_user_id()." ORDER BY id DESC";
        //echo $query;

        $result = query($query);
        if (row_count($result)) {
            $slno = 1;
            while ($row = mysqli_fetch_row($result)) {
                $bsTableRows .= '<tr>
                                    <td>'.($slno++).'</td>';

                //if admin, include email also
                if(isset($_SESSION["sblms_admin"]))
                    $bsTableRows .= '<td>'.get_user_email($row[1]).'</td>';
                
                $bsTableRows .= '<td>'.$row[2].'</td>
                                <td>'.date("d-m-Y H:i:s",strtotime($row[3])).'</td>
                            </tr>';                
            }
        }
        else
        {
            $bsTableRows = '<tr>
                                <td class="col-md-12" align="center">No Data Found!</td>
                            </tr>';                
        }
        return $bsTableRows;
    }

?>
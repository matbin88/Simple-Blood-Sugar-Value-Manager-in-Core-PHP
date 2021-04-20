<?php

    include "init.php";

    if(isset($_POST['userCount']))
    {
        
        $userCount   = $_POST['userCount'];

        for($i=1;$i<=$userCount;$i++)
        {
            $userId = $_POST['user'.$i];
            $userChecked = isset($_POST['chkUser'.$i])?1:0;
            $sql = "UPDATE users SET admin=$userChecked WHERE id=$userId";
            //echo $sql.'<br>';
            confirm(query($sql));
        }        
        
        $_SESSION["successMsg"] = "Saved Successfully!";
        header("location:../manage_admins.php");
    }
    
    if(isset($_GET['getUsers']))
    {        
        echo getUsers($_GET['getUsers']);
    }

    function getUsers($mode=1)
    {
        $userTableRows = '<tr>
                            <th class="col-md-2">SL No</th>
                            <th class="">Email</th>
                            <th class="col-md-5">Full Name</th>';
        if($mode == 2)
            $userTableRows .= '<th class="col-md-1">Admin</th>';
        
        $userTableRows .= '</tr>';
        
        if($mode == 2)
            $query = "SELECT id,username,email,admin FROM users WHERE id<>".get_current_user_id()." ORDER BY username ASC";
        else
            $query = "SELECT id,username,email,admin FROM users ORDER BY username ASC";

        //echo $query;

        $result = query($query);
        if (row_count($result)) {
            $slno = 1;
            while ($row = mysqli_fetch_row($result)) {
                $userTableRows .= '<tr>
                                    <td>'.($slno).'</td>
                                    <td>'.$row[2].'</td>
                                    <td>'.$row[1].'</td>';
                if($mode == 2)
                {
                    $userTableRows .= '<td align="center"><input type="hidden" name="user'.$slno.'" value="'.$row[0].'"><input type="checkbox" name="chkUser'.$slno.'" value="'.$row[3].'" '.($row[3]==1?" checked ":"").'></td>';
                }
                
                $userTableRows .= '</tr>';      
                $slno++;         
            }
            if($mode == 2)
            {
                $userTableRows .= '<tr><td colspan="4" align="right"><input type="hidden" name="userCount" value="'.(--$slno).'"><input type="submit" class="btn btn-primary" value="Save Changes" style="float:right;"></td></tr>'; 
            }
        }
        else
        {
            $userTableRows = '<tr>
                                <td class="col-md-12" align="center">No Data Found!</td>
                            </tr>';                
        }
        return $userTableRows;
    }

?>
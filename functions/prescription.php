<?php

    include "init.php";

    if(isset($_POST['pDescription']))
    {
        $filename = "";
        $size = 0;
        $datetime = date('Y-m-d H:i:s');
        if(isset($_FILES['file']['name'])){

            /* Getting file name */
            $filename = $_FILES['file']['name'];
            $size=$_FILES['file']['size'];
         
            /* Location */
            $location = "../uploads/".strtotime($datetime).$filename;
            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);
         
            /* Valid extensions */
            $valid_extensions = array("doc","docx","txt","pdf");
         
            $response = 0;
            /* Check file extension */
            if(in_array(strtolower($imageFileType), $valid_extensions)) {
               /* Upload file */
               if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
                    $pDescription   = filter_var($_POST['pDescription'], FILTER_SANITIZE_STRING);

                    $sql = "INSERT INTO prescriptions(user_id,file_name,description,file_size,date_time) ";
                    $sql .= "VALUES(".get_current_user_id().",'$filename','$pDescription',$size,'".$datetime."')";
                    confirm(query($sql));
               }
            }
        }                

        echo getPrescriptions();
    }
    
    if(isset($_GET['getPrescriptions']))
    {        
        echo getPrescriptions();
    }

    function getPrescriptions()
    {
        $pTableRows = '<tr>
                            <th class="col-md-1">SL No</th>';
        
        //if admin, include email also
        if(isset($_SESSION["sblms_admin"]))
            $pTableRows .= '<th class="col-md-2">Email</th>';
        
        $pTableRows .= '<th class="col-md-2">File Name</th>
                        <th>Description</th>
                        <th class="col-md-2">File Size</th>
                        <th class="col-md-2">Date & Time</th>
                    </tr>';
        
        if(isset($_SESSION["sblms_admin"]))        
            $query = "SELECT * FROM prescriptions ORDER BY id DESC";
        else
            $query = "SELECT * FROM prescriptions WHERE user_id=".get_current_user_id()." ORDER BY id DESC";
        //echo $query;

        $result = query($query);
        if (row_count($result)) {
            $slno = 1;
            while ($row = mysqli_fetch_row($result)) {
                $pTableRows .= '<tr>
                                    <td>'.($slno++).'</td>';

                //if admin, include email also
                if(isset($_SESSION["sblms_admin"]))
                    $pTableRows .= '<td>'.get_user_email($row[1]).'</td>';
                
                $pTableRows .= '<td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].' Bytes</td>
                                <td>'.date("d-m-Y H:i:s",strtotime($row[5])).'</td>
                            </tr>';                
            }
        }
        else
        {
            $pTableRows = '<tr>
                                <td class="col-md-12" align="center">No Data Found!</td>
                            </tr>';                
        }
        return $pTableRows;
    }

?>
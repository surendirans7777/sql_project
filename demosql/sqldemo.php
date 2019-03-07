<?php

if(isset($_POST['search']))
{
    $valueToSearch = $_POST['valueToSearch'];
    // search in all table columns
    // using concat mysql function
    $query = "SELECT * FROM `table 1` WHERE `COL 1' like as '%".$valueToSearch."%'";
    $search_result = filterTable($query);
    
}
 else {
    $query = "SELECT * FROM `table 1`";
    $search_result = filterTable($query);
}

// function to connect and execute the query
function filterTable($query)
{
    $connect = mysqli_connect("localhost", "root", "", "sqldemo");
    $filter_Result = mysqli_query($connect, $query);
    return $filter_Result;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>PHP HTML TABLE DATA SEARCH</title>
        <style>
            table,tr,th,td
            {
                border: 1px solid black;
            }
            body
            {
                background-position: center;
                
            }
            #t
            {
                background-color: rgb(0, 255, 255);
            }
        </style>
    </head>
    <body >
        <div align="center">
        <form action="sqldemo.php" method="post" >
            <input type="text" name="valueToSearch" placeholder="Value To Search"><br><br>
            <input type="submit" name="search" value="Search"><br><br>
            
            <table id="t">
                <tr>
                    <th>sql-injection</th>
                   
                </tr>

      <!-- populate table from mysql database -->
                <?php while($row = mysqli_fetch_array($search_result)):?>
                <tr>
                  
                    <td><?php echo $row['COL 1'];?></td>
                   
                </tr>
                <?php endwhile;?>
            </table>
        </form>
        </div>
        
    </body>
</html>
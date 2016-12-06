<!DOCTYPE html>
<html>
    <head>
        <title>ATP Tennis ++</title>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    </head>

    <body>
		<h1 style="color:blue;" >ATP Random Draw Generator</h1>
        <form method="post" action="">
			<label for="l1">Number of Players</label>
			<select name="pcount">
				<option value="128">128</option>
				<option value="32">32</option>
			</select>
			<br />
            <input type="submit" name="Generate" value="Generate Draw" />  <br />
        </form>

	<?php

   //==========================================
   //Connect to DB and get data
   //==========================================

   $ini_array = parse_ini_file("TennisRahul.ini.php");
   $db_host_name = $ini_array['db_host_name']; 
   $db_name = $ini_array['db_name'];
   $db_user_name = $ini_array['db_user_name'];
   $db_user_pwd = $ini_array['db_user_pwd'];

   $players = array(); // Array of players ordered based on their ATP points
   $leftArr = array_fill(0, 64, 0); // Array of the seeded player ranking based on the draw
   $rightArr = array_fill(0, 64, 0);// Array of unseeded player ranking in random order

   try
   {
       $dbh = new PDO("mysql:host=$db_host_name;dbname=$db_name", $db_user_name, $db_user_pwd);
       if($dbh)
       {
           $stmt = $dbh->prepare("select PlayerId, FirstName, LastName, Country, ATPPoints from ATPPlayers order by ATPPoints desc");
           if ($stmt->execute())
           {
               $rows = $stmt->fetchAll();
               $players = createPlayers($rows);
               if ($_POST['pcount'] == 128)
               {
               		$leftArr = handleSeeds1to4($leftArr);
               		$leftArr = handleSeeds5to8($leftArr);
               		$leftArr = handleSeeds9to16($leftArr);
               		$leftArr = handleSeeds17to32($leftArr);
               		$leftArr = handleSeeds33to64($leftArr);
               		$rightArr = handleOpponentArray($rightArr);
               }
               else if ($_POST['pcount'] == 32)
               {
               		$leftArr = smallDraw1to4($leftArr);
               		$leftArr = smallDraw5to8($leftArr);
               		$leftArr = smallDraw9to16($leftArr);
               		$rightArr = smallDrawOpponentArray($rightArr);
               }

               //print_r($players);
               //print_r("\n");
               //print_r($leftArr);
               //print_r("\n");
               //print_r($rightArr);
               //print_r("\n");
               if ($_POST['pcount'] == 128)
               {
               
               		drawTable($leftArr, $rightArr, $players, 16, 4, 1, 128);
               		drawTable($leftArr, $rightArr, $players, 16, 4, 2, 128);
               		drawTable($leftArr, $rightArr, $players, 16, 4, 3, 128);
               		drawTable($leftArr, $rightArr, $players, 16, 4, 4, 128);
               }
               else if ($_POST['pcount'] == 32)
               {
               		drawTable($leftArr, $rightArr, $players, 4, 4, 1, 32);
               		drawTable($leftArr, $rightArr, $players, 4, 4, 2, 32);
               		drawTable($leftArr, $rightArr, $players, 4, 4, 3, 32);
               		drawTable($leftArr, $rightArr, $players, 4, 4, 4, 32);
               }
           }
           else
           {
               print_r($stmt->errorInfo());
           }

       }
    }
    catch (PDOException $e)
    {
        echo 'Connection failed: ' . $e->getMessage();
    }

   //===============================================
   //This function fills up the Players name array
   //===============================================
    function createPlayers($dbRows)
	{
        $pl = array();
        foreach($dbRows as $row)
        {
            $strN = $row[FirstName] . " " . $row[LastName];
            array_push($pl, $strN);
        }
        return $pl;
	}

   //===============================================
   //= This function handles the first 4 seeds
   //= Number1 seed always in the top half
   //= Number2 seed always in the bottom half
   //= Number3 and Number4 randomly assigned to top/bottom
   //=====================================================
    function handleSeeds1to4($lArr)
	{
        $lArr[0] = 0;
        $lArr[63] = 1;
        $i3to4 = array(31, 32);
        $a = array(2, 3);

        for($i=0; $i < 2; $i++)
        {
            $ran_Num = array_rand($a);
            $lArr[$i3to4[$i]] = $a[$ran_Num];
            unset($a[$ran_Num]);
        }
        return $lArr;

	}
	
   //===============================================
   //= This function handles the first 4 seeds for smallDraw
   //= Number1 seed always in the top half
   //= Number2 seed always in the bottom half
   //= Number3 and Number4 randomly assigned to top/bottom
   //=====================================================
    function smallDraw1to4($lArr)
	{
        $lArr[0] = 0;
        $lArr[15] = 1;
        $i3to4 = array(7, 8);
        $a = array(2, 3);

        for($i=0; $i < 2; $i++)
        {
            $ran_Num = array_rand($a);
            $lArr[$i3to4[$i]] = $a[$ran_Num];
            unset($a[$ran_Num]);
        }
        return $lArr;

	}

   //===============================================
   //This function handles the seeds 5 to 8
   //following ATP seeding rules
   //These should be in different quarters
   //===============================================
    function handleSeeds5to8($lArr)
	{
        $i5to8 = array(15, 16, 47, 48);
        $a = array(4, 5, 6, 7);

        for($i=0; $i < 4; $i++)
        {
            $ran_Num = array_rand($a);
            $lArr[$i5to8[$i]] = $a[$ran_Num];
            unset($a[$ran_Num]);
        }
        return $lArr;
	}
	
   //===============================================
   //This function handles the seeds 5 to 8 for smallDraw
   //following ATP seeding rules
   //These should be in different quarters
   //===============================================
    function smallDraw5to8($lArr)
	{
        $i5to8 = array(3, 4, 11, 12);
        $a = array(4, 5, 6, 7);

        for($i=0; $i < 4; $i++)
        {
            $ran_Num = array_rand($a);
            $lArr[$i5to8[$i]] = $a[$ran_Num];
            unset($a[$ran_Num]);
        }
        return $lArr;
	}

   //===============================================
   //This function handles the seeds 9 to 16 
   //following ATP seeding rules
   //===============================================
    function handleSeeds9to16($lArr)
	{
        $i9to16 = array(7, 8, 23, 24, 39, 40, 55, 56);
        $a = array(8, 9, 10, 11, 12, 13, 14, 15);

        for($i=0; $i < 8; $i++)
        {
            $ran_Num = array_rand($a);
            $lArr[$i9to16[$i]] = $a[$ran_Num];
            unset($a[$ran_Num]);
        }
        return $lArr;
	}
	
   //===============================================
   //This function handles the seeds 9 to 16 
   //following ATP seeding rules
   //===============================================
    function smallDraw9to16($lArr)
	{
        $i9to16 = array(1,2,5,6,9,10,13,14);
        $a = array(8, 9, 10, 11, 12, 13, 14, 15);

        for($i=0; $i < 8; $i++)
        {
            $ran_Num = array_rand($a);
            $lArr[$i9to16[$i]] = $a[$ran_Num];
            unset($a[$ran_Num]);
        }
        return $lArr;
	}
	


   //===============================================
   //This function handles the seeds 17 to 32 
   //following ATP seeding rules
   //===============================================
    function handleSeeds17to32($lArr)
	{
        $i17to32 = array(3, 4, 11, 12, 19, 20, 27, 28, 35, 36, 43, 44, 51, 52, 59, 60);
        $a = array(16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);

        for($i=0; $i < 16; $i++)
        {
            $ran_Num = array_rand($a);
            $lArr[$i17to32[$i]] = $a[$ran_Num];
            unset($a[$ran_Num]);
        }
        return $lArr;
	}

   //===============================================
   //This function handles the seeds 33 to 64 
   //following ATP seeding rules
   //===============================================
    function handleSeeds33to64($lArr)
	{
        $i33to64 = array(1,2,5,6,9,10,13,14,17,18,21,22,25,26,29,30,33,34,37,38,41,42,45,46,49,50,53,54,57,58,61,62);
        $a = array(32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63);

        for($i=0; $i < 32; $i++)
        {
            $ran_Num = array_rand($a);
            $lArr[$i33to64[$i]] = $a[$ran_Num];
            unset($a[$ran_Num]);
        }
        return $lArr;
	}
	
   //===============================================
   //This function handles the seeds 65 to 128
   //Totally random assignment
   //===============================================
    function handleOpponentArray($rArr)
    {
        $a = array_fill(0, 64, 0);
        for($i=0; $i < 64; $i++)
        {
            $a[$i] = $i + 64;
        }

        for($i=0; $i < 64; $i++)
        {
            $ran_Num = array_rand($a);
            $rArr[$i] = $a[$ran_Num];
            unset($a[$ran_Num]);
        }
        return $rArr;
    }
    
    	
   //===============================================
   //This function handles the seeds 17-32 for small draw
   //Totally random assignment
   //===============================================
    function smallDrawOpponentArray($rArr)
    {
        $a = array_fill(0, 16, 0);
        for($i=0; $i < 16; $i++)
        {
            $a[$i] = $i + 16;
        }

        for($i=0; $i < 16; $i++)
        {
            $ran_Num = array_rand($a);
            $rArr[$i] = $a[$ran_Num];
            unset($a[$ran_Num]);
        }
        return $rArr;
    }

    function drawTable($la, $ra, $pl, $rows, $cols, $qtr, $size)
	{
			if ($qtr == 1)
				echo '<table border="1" style="margin-top:10px; float: left">'; 
			else
				echo '<table border="1" style="margin-top:10px; margin-left:10px; float: left">'; 
			if ($size == 128)
			{
				$rr = ($qtr - 1) * 16;
			}
			else if ($size == 32)
			{
				$rr = ($qtr - 1) * 4;
		    }
			for($tr=1;$tr<=$rows;$tr++)
            {
			    $pl1 = substr($pl[$la[$rr]], 0, 16);
                $pl2 = substr($pl[$ra[$rr]], 0, 16);
			    echo "<tr>"; 
					$td=1;
					echo "<td align='center'>".($la[$rr] + 1)."</td>";
					$td++;
					echo "<td align='center'>".$pl1."</td>";
					$td++;
					echo "<td align='center'>".($ra[$rr] + 1)."</td>";
					$td++;
					echo "<td align='center'>".$pl2."</td>";
					
			    echo "</tr>"; 
				$rr++;
			} 
			echo "</table>";
	}
	?>

</body>
</html>

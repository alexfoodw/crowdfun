<!DOCTYPE html>
<head>
  <title>Admin Top Users Page</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
  <style> table, th, td {border: 1px solid black;}
          li {list-style: none;}
        </style>
</head>
<body>
  <h2>Crowdfun Admin</h2>
  <h3>View users with highest contribution</h3>
    <ul>
      <li><a href='admin.php'>Admin Home</a></li>
      <li><a href='admin_edit.php'>Admin Edit</a></li>
      <li><a href='admin_project_summary.php'>Project Insights</a></li>
      <li><a href='admin_user_insights.php'>User Insights</a></li>
      <li><a href='admin_top_users.php'>Top Contributing Users</a></li>
  </ul>
  <ul>
    <form name="display" action="admin_top_users.php" method="POST" >
      <li>View Top Users:</li>
      <li><input type="submit" name="users" value = "View" /></li>
    </form>
  </ul>

  <?php
    $db     = pg_connect("host=localhost port=5432 dbname=crowdfun user=postgres password=password");
    $result = pg_query($db, "SELECT u.first_name, u.last_name, f.u_email, SUM(f.amount), u.birth_country, u.since FROM funds f INNER JOIN users u ON f.u_email = u.email GROUP BY f.u_email, u.first_name, u.last_name, u.birth_country, u.since ORDER BY SUM(f.amount) DESC");
    /*
    $result = pg_query($db, "SELECT u1.first_name, u1.last_name, f1.amount, u1.email, u1.birth_country
    						 FROM funds f1 INNER JOIN users u1 ON f1.u_email = u1.email WHERE f1.amount = (select max(f1_max.amount) 
    						 					from funds f1_max 
    						 					where f1.u_email = f1_max.u_email) 
    						 ORDER BY f1.amount DESC");   // Query template
    */

    if (isset($_POST['users'])) {
      $html = "";	
      $html = "<h1>Top Users table</h1><br>
      <table>
      <tr>
      <th>first_name</th>
      <th>last_name</th>
      <th>email</th>
      <th>amount contributed</th>
      <th>country</th>
      <th>join date</th>
      </tr>";

      while ($row = pg_fetch_assoc($result)) {
        $html .= "<tr>
        <td>$row[first_name]</td>
        <td>$row[last_name]</td>
        <td>$row[u_email]</td>
		    <td>$row[sum]</td>
        <td>$row[birth_country]</td>
        <td>$row[since]</td>
        </tr>";
      }

      $html .= "</table>";
      echo $html;
  }
  ?>

  <ul>
    <form name="display" action="admin_top_users.php" method="POST" >
      <li>View Top Users by specific country:</li>
      <li><input type="text" name="birthcountry" /></li>
      <li><input type="submit" name="country" value = "View" /></li>
    </form>
  </ul>
  <?php

	$db     = pg_connect("host=localhost port=5432 dbname=crowdfun user=postgres password=password");
  /*
    $temp = pg_query($db, "SELECT u1.first_name, u1.last_name, f1.amount, u1.birth_country, u1.email
    						 FROM funds f1 INNER JOIN users u1 ON f1.u_email = u1.email WHERE u1.birth_country = '$_POST[birthcountry]' AND f1.amount = (select max(f1_max.amount) 
    						 					from funds f1_max 
    						 					where f1.u_email = f1_max.u_email) 
    						 ORDER BY f1.amount DESC");   // Query template
  */
  $temp = pg_query($db, "SELECT u.first_name, u.last_name, f.u_email, SUM(f.amount),u.birth_country, u.since FROM funds f INNER JOIN users u ON f.u_email = u.email WHERE u.birth_country = '$_POST[birthcountry]' GROUP BY f.u_email, u.first_name, u.last_name, u.since, u.birth_country ORDER BY SUM(f.amount) DESC");
	if (isset($_POST['country'])) {
	      $html = "";	
	      $html = "<h1>Top Users table by specified country</h1><br>
	      <table>
	      <tr>
	      <th>first_name</th>
	      <th>last_name</th>
	      <th>email</th>
	      <th>amount contributed</th>
        <th>country</th>
        <th>join date</th>
	      </tr>";

	      while ($row = pg_fetch_assoc($temp)) {
	        $html .= "<tr>
	        <td>$row[first_name]</td>
	        <td>$row[last_name]</td>
	        <td>$row[u_email]</td>
    			<td>$row[sum]</td>
          <td>$row[birth_country]</td>
          <td>$row[since]</td>
	        </tr>";
	      }

	      $html .= "</table>";
	      echo $html;
	  }
	 ?>

</body>
</html>
<!-- A frontend
    - Form to add a country to an existing or new continent
    - Display all countries by continent
    - Search for a country and display the continent it belongs to -->
<?php
    include("db_connect.php"); 
    
    if(isset($_POST['submit']))  
    {
    // Escape user inputs for security
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $continent = $_POST['continent'];
    
    $result = mysqli_query($conn, "SELECT * FROM country WHERE CountryName ='$country' ") or die("MySQL error: " . mysqli_error($conn) . "<hr>\nQuery: $result");
        if( mysqli_num_rows($result) > 0) 
        {//Warning - the country is allready exist 
            echo '<div class=" d-flex justify-content-center">
                <div class="alert alert-info" role="alert" style="width:400px">
                ".$country. is already exist in the Database" . mysqli_error($conn)
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
              </div>';
        }
        else
        {
             $cntry="INSERT INTO country (CountryName, ContinentID) VALUES ('$country', '$continent' )";
        }
    
   
    
    if(mysqli_query($conn, $cntry))
    {//success message - country added
        echo '<div class=" d-flex justify-content-center">
                    <div class="alert alert-success" role="alert" style="width:400px">
                    "Country added successfully."
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                  </div>';
        } 
    else
        {//Error message failed to add country
    echo '<div class=" d-flex justify-content-center">
                <div class="alert alert-warning" role="alert" style="width:400px">
                "ERROR: Could not able to execute $cntry. " . mysqli_error($conn)
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
              </div>';
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
      <title>Continent and Country Form</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      

    </head>
    <body>
    
      <!-- Header -->
      <div class="jumbotron text-center">
        <h1>Country Form</h1>
        <p>You can add a country with corresponding continent and you can search a country to see which continent it belongs</p> 
      </div>
      
      

      <!-- *****Search Bar***** -->
      
      <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="form-inline input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search country">
                        <input type="submit" value="Search" name="search_button">
                    </div>
                </form>
            </div>
        </div>
      </div>
      <br>
    
      <?php
      if(isset($_POST['search_button']))  
        {
           if(!empty($_POST['search']))
            {
            $search_value= mysqli_real_escape_string($conn,$_POST["search"]); 
            $search_qry=mysqli_query($conn,"SELECT * FROM country where CountryName like '%$search_value%' ") or die("MySQL error: " . mysqli_error($conn) . "<hr>\nQuery: $search_qry");
            if( mysqli_num_rows($search_qry) > 0) 
                { //if the country exist in the database then displaying the result
                echo '<div class="container">
                      <h2>Countries stored in Database</h2>
                      <table class="table table-hover table-light table-striped" id="table-data">
                        <thead>
                          <tr>
                            <th>Country</th>
                            <th>Continent</th>
                          </tr>
                        </thead>';
                while($rows=mysqli_fetch_array($search_qry)){ 
                $search_qry2=mysqli_query($conn,"SELECT * FROM continent where ContinentID = $rows[ContinentID] ") or die("MySQL error: " . mysqli_error($conn) . "<hr>\nQuery: $search_qry2");
                
                    while($row2=mysqli_fetch_array($search_qry2)){ 
                    echo '<tbody> 
                          <tr>
                            <td>'.$rows["CountryName"].'</td>
                            <td>'.$row2["name"].'</td>
                          </tr>';
                        }
                    }
                    echo ' </tbody>
                          </table>
                          </div>';
                }
            }
            
            else
            {//warning - if search bar is empty
                echo '<div class=" d-flex justify-content-center">
                        <div class="alert alert-warning" role="alert" style="width:400px">
                        "Please write a Country Name to search from the Database"
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                      </div>';
            }
        }
    ?>
    <hr>
    
      
      <!-- ****  Form to add country name   **** -->
      
    <div class="container p-3 card bg-light text-dark"  style="width:400px">
      <div class="card-body">
      <h3>Fill Up the Form</h3>
      <br>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="form-group">
                <label for="country" class="font-weight-bold">Country:</label>
                <input type="text" class="form-control" id="country" placeholder="Add Country" name="country">
             </div>

            <div class="form-group dropdown">
                <label for="continent" class="font-weight-bold">Select a Continent:</label>
                <select name="continent" class="form-control font-weight-bold" id="continent">
              <?php
                    $sql=mysqli_query($conn,"SELECT * FROM continent ORDER BY name") or die("MySQL error: " . mysqli_error($conn) . "<hr>\nQuery: $sql");
                    while($row=mysqli_fetch_array($sql)){
                    echo ' <option value=" '.$row["ContinentID"].' ">'.$row["name"].'</option> ';
                    }
              ?>
               </select>
            </div>
          
          <button type="submit" class="btn btn-dark font-weight-bold" name="submit">Submit</button>
        </form>
      </div>
    </div>
    <hr>
    
    
    <!-- **** Table to display all countries by continent From the Database **** -->
    
    <div class="container mt-3">
        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-dark font-weight-bold" data-toggle="collapse" data-target="#demo">Display All Stored Countries by Continent</button>
            <br>
        </div>
        
        <div id="demo" class="collapse">
            <div class="container">
              <h2>Displaying from Database</h2>
              <table class="table table-hover table-light table-striped" id="stored-data">
                <thead>
                  <tr>
                    <th>Country</th>
                    <th>Continent</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $qry=mysqli_query($conn,"SELECT * FROM country") or die("MySQL error: " . mysqli_error($conn) . "<hr>\nQuery: $qry");
                
                while($rows=mysqli_fetch_array($qry)){ 
                    $qry2=mysqli_query($conn,"SELECT * FROM continent where ContinentID = $rows[ContinentID] ") or die("MySQL error: " . mysqli_error($conn) . "<hr>\nQuery: $qry2");
                    while($row2=mysqli_fetch_array($qry2)){ 
                        echo '<tr>
                                <td>'.$rows["CountryName"].'</td>
                                <td>'.$row2["name"].'</td>
                              </tr>';
                        }
                }
                ?>
                </tbody>
              </table>
            </div>
      </div>
    </div>
    <hr>
    
    </body>
    </html>
    
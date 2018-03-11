<!--  Written on 11th March 2018.
      This script, or the contents of it, can be used however desired.
      Available to view at https://github.com/domo2797/ft-scraper-project -->
<!DOCTYPE html>
<html>
  <head>
    <title>Search the Financial Times</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,
    shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/ft.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
  	<nav class="navbar navbar-expand-lg navbar-dark bg-theme-main p-4"
      style="background-color:#fff1e5;">
  	  <div class="container">
  		  <h2 class="logo mr-3" style="white-space:nowrap;">
          <a href="/">FT Search</a>
        </h2>
  		  <div class="input-group">
          <form action="" method="GET" style="display:inherit;width:inherit;">
    		    <input type="search" class="form-control form-control-lg" name="q"
              style="padding: .375rem .75rem;" <?php if($_GET["q"] != ""){echo
              'value="'.$_GET[q].'"';} ?> placeholder="Search for articles...">
    		    <span class="input-group-btn">
    			    <button type="submit" class="btn btn-lg bg-ft-dark"
                style="border:0;" type="button">Search
              </button>
    		    </span>
          </form>
  		  </div>
  	  </div>
  	</nav>
    <?php
      $url = "http://api.ft.com/content/search/v1";
      $search_term = "";

      if(isset($_GET["q"]))
        $search_term = $_GET["q"];

      $data = '{
        "queryString": "'.$search_term.'",
        "resultContext" : {
          "aspects" :["title","lifecycle","location","summary","editorial"]
        }
      }';
      $headers = array(
        "X-Api-Key: 59cbaf20e3e06d3565778e7b2eb536ff966241dc90b41df909ed427c",
        "Content-Type: application/json"
      );

      $ci = curl_init($url);
      curl_setopt($ci, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ci, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);

      $json = curl_exec($ci);
      $json_decode = json_decode($json, true);
      $json_count = count($json_decode["results"][0]["results"]);
      $headline = $json_decode["results"][0]["results"][rand(0,$json_count-1)];
    ?>
  	<div class="container-fluid container-ft pb-5">
  	  <div class="container">
        <div class="jumbotron p-3 p-5 text-white rounded-0 bg-ft-jumbotron">
          <div class="row">
            <div class="col-md-10">
              <h1 class="display-4 font-italic">
                <?php echo $headline["title"]["title"]; ?>
              </h1>
              <p class="lead my-3">
                <?php echo $headline["summary"]["excerpt"]; ?>
              </p>
              <p class="lead mb-0">
                <a href="<?php echo $headline["location"]["uri"]; ?>"
                  class="text-white font-weight-bold">Read this on FT.com
                </a>
              </p>
            </div>
          </div>
        </div>
        <div class="container-fluid py-3 px-0">
          <?php
            $iterator = 0;

            foreach($json_decode["results"][0]["results"] as $key => $article){
              if($iterator < 50){
                echo "
                <div class=\"card\">
                  <div class=\"card-body\">
                    <h3 class=\"card-title\">
                      <a href=\"".$article["location"]["uri"]."\">"
                        .$article["title"]["title"]."
                      </a>
                    </h3>

                    <p class=\"card-text m-0\" style=\"filter:blur(3px);\">
                      This is just a small excerpt from the main article, of
                      which can be found on the FT.com website. A direct link
                      is provided above.
                    </p>

                    <p class=\"card-text m-0\">"
                      .$article["summary"]["excerpt"].
                    "</p>

                    <p class=\"card-text m-0\" style=\"filter:blur(3px);\">
                      Visit FT.com to read the rest of this article and other
                      great articles. A direct link is provided above.
                    </p>

                    <footer class=\"blockquote-footer\">
                      <small class=\"text-muted\">"
                      .$article["editorial"]["byline"].
                      "</small>
                    </footer>
                  </div>
                </div>
                <hr/>";

                $iterator++;
              } else {
                break;
              }
            }
          ?>
        </div>
  	  </div>
  	</div>
  	<footer class="footer">
  	  <div class="footer-copyright">
  		  <p>©Dominic Edwards 2018. This script is licensed under MIT.</p>
  	  </div>
  	</footer>
  </body>
  <script src="js/bootstrap.bundle.min.js" type="text/javascript"></script>
  <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
</html>

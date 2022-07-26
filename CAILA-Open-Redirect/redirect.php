<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

require('./caila.php');

// get the 'url' parameter in the url
$url = $_GET['url'];

// assume we have analytics here
// INSERT INTO analytics (url) VALUES ($_GET['url'])

// initialize CAILA
$options = array(
  'whitelistedDomains' => [
    $_SERVER['HTTP_HOST'],  // make sure that our domain is included in the whitelisted
    'facebook.com',         // whitelist facebook.com for testing purposes 
    '31.13.87.36'           // whitelist facebook's IP as well
  ], 
  'blacklistedDomains' => [
    'evilzone.com'
  ]
);
$caila = new CAILA($options);

$classification =  $caila->getURLClassification($url);
$host = $caila->getSimplifiedHost($url);

if($classification === 'whitelisted') {
  header('Location: '.$url);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Tiny MCE -->
  <script src="https://cdn.tiny.cloud/1/tfm5foww7vz4goyjlhvxlgd7rdn0jo0emir5zx3881dr1d81/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <!-- Bootstrap CSS --> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <!-- Font Awesome --> 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts --> 
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet" />
  <!-- MDB --> 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.11.0/mdb.min.css" rel="stylesheet" />
  <title>CAILA | Freedom Wall</title>
  <link rel="icon" href="images/icon.png">
  <style>
    .modal-content {
      border: none !important;
    }
    
    #warningModal .modal-header {
      background-color: #ff6700;
      color: white;
    }
    
    #dangerModal .modal-header {
      background-color: #d0342c;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container-fluid col-md-4 col-sm-12 mt-5">
    <?php if($classification === 'normal'): ?>
    <div class="card" id="warningModal" tabindex="-1" aria-labelledby="warningModal" aria-hidden="true">
        <div class="modal-content">
          <div class="modal-header d-flex justify-content-center">
            <h1 class="display-1" id="warningModal"><i class="fa-solid fa-circle-exclamation"></i></h1>
          </div>
          <div class="modal-body text-center">
            <h4>Notice!</h4>
            <div class="message">You are leaving CAILA Freedom Wall. Do you want to proceed to <?php echo $caila->getSimplifiedHost($url) ?>?</div>
          </div>
          <button type="button" class="btn btn-primary w-50 align-self-center mb-3 rounded-pill" onclick="location.href='<?php echo $url;?>'">Proceed</button>
        </div>
    </div>
    <!-- CAILA_CHECKER:NORMAL -->
    <?php elseif($classification === 'blacklisted'): ?>
    <div class="card" id="dangerModal" tabindex="-1" aria-labelledby="dangerModal" aria-hidden="true">
        <div class="modal-content">
          <div class="modal-header d-flex justify-content-center">
            <h1 class="display-1" id="dangerModal"><i class="fa-solid fa-circle-xmark"></i></h1>
          </div>
          <div class="modal-body text-center">
            <h4>Danger!</h4>
            <div class="message">You won't be able to go to this link from CAILA. This domain has been flagged for its malicious activities. <br>For your safety, CAILA will not allow this URL to be opened.</div>
          </div>
          <button type="button" class="btn btn-primary w-50 align-self-center mb-3 rounded-pill" onclick="history.back()">Go back</button>
        </div>
    </div>
    <!-- CAILA_CHECKER:BLACKLISTED -->
    <?php else: ?>
      Invalid Domain
      <!-- CAILA_CHECKER:INVALID -->
    <?php endif; ?>
  </div>
</body>
</html>

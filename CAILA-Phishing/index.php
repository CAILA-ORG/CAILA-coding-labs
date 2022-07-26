<?php
// get page number
$page = isset($_GET["page"]) ? $_GET["page"] : 0;
$limit = isset($_GET["limit"]) ? $_GET["limit"] : 10;

// fetch the posts
// we will be using an API instead of a database

// get the contents from the API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://server.caila.academy/demo-api/posts?page='.$page.'&limit='.$limit);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);

// parse the JSON output from the API
$result = json_decode($result, true);
$maxPage = $result['maxPage'];

// if there are no contents
// redirect to index page
if($page != 0 && count($result) === 0) {
  header('location: ./');
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require('./includes/header.php');?>
  </head>
  <body>
    <?php require('./includes/navbar.php') ?>
    <div class="container-fluid mt-2" id="timeline">
      <?php foreach ($result['posts'] as $post): ?>
      <div class="border-start border-secondary p-4 position-relative">
        <div class="indicator">
          <button class="btn btn-rounded btn-secondary py-0 px-2">&nbsp;</button>
        </div>
        <div class="card">
          <div class="card-header"> <?php echo $post['dateCreated']; ?> </div>
          <div class="card-body">
            <blockquote class="blockquote mb-0">
              <p> <?php echo $post['message']; ?> </p>
              <footer class="blockquote-footer"> <?php echo $post['nickname']; ?> </footer>
            </blockquote>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="container-fluid">
    <ul class="pagination">
      <li class="page-item <?php if($page == 0) echo 'disabled';?>">
      <a class="page-link" href="<?php echo $page == 0 ? '#' : '?page='.($page-1).'&limit='.$limit ?>">Previous</a>
      </li>
      <?php 
      // display only 7 pages in the pagination
      $startPage = $page - 3 >= 0 ? $page - 3 : 0;
      $endPage = $page + 3 <= $maxPage ? $page + 3 : $maxPage;
      for($i = $startPage; $i <= $endPage; $i++): 
      ?>
      <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
        <a class="page-link" href="<?php echo '?page='.$i.'&limit='.$limit; ?>">
          <?php echo $i+1; ?>
        </a>
      </li>
      <?php endfor; ?>
      <li class="page-item <?php if($page == $maxPage) echo 'disabled'?>">
        <a class="page-link" href="<?php echo $page == $maxPage ? '#' : '?page='.($page+1).'&limit='.$limit ?>">Next</a>
      </li>
    </ul>
    </div>
    <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header d-flex justify-content-center">
            <h1 class="display-1" id="warningModal"><i class="fa-solid fa-circle-exclamation"></i></h1>
          </div>
          <div class="modal-body text-center">
            <h4>Notice!</h4>
            <div class="message"></div>
          </div>
          <button type="button" class="btn btn-primary w-50 align-self-center mb-3 rounded-pill">Proceed</button>
        </div>
      </div>
    </div>
    <div class="modal fade" id="dangerModal" tabindex="-1" aria-labelledby="dangerModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header d-flex justify-content-center">
            <h1 class="display-1" id="dangerModal"><i class="fa-solid fa-circle-xmark"></i></h1>
          </div>
          <div class="modal-body text-center">
            <h4>Danger!</h4>
            <div class="message"></div>
          </div>
          <button type="button" class="btn btn-primary w-50 align-self-center mb-3 rounded-pill" data-mdb-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.11.0/mdb.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
    <script src="./caila.js"></script>
    <script>
      $(function () {
        // initialize button inside warning modal
        $('#warningModal button').on('click', () => { 
          let url = $('#warningModal button').attr('data-url') 
          let target = $('#warningModal button').attr('data-target') 
          if (target === '_blank') {
            window.open(url,'_blank');
          } else {
            location.href = url;
          }
          $('#warningModal').modal('hide')
        })
      })
      let caila = new CAILA({
        options: {
          blacklistedDomains: ['www.youtube.com'],
          whitelistedDomains: [location.host]
        },
        onNormalLink: function (url, domain, target) {
          $('#warningModal .message').html(`You are leaving CAILA Freedom Wall. Do you want to proceed to ${ domain }?` )
          $('#warningModal button').attr('data-url', url);
          $('#warningModal button').attr('data-target', target);
          $('#warningModal').modal('show')
        },
        onBlacklistedLink: function (url, domain, target) {
          $('#dangerModal .message').html(`This domain (${ domain }) has been flagged for its malicious activities. <br>For your safety, CAILA will not allow this URL to be opened.` )
          $('#dangerModal').modal('show')
        },
        onWhitelistedLink: function (url) {
          // just redirect
          location.href = url
        }
      })
      caila.protect()
    </script>
  </body>
</html>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.11.0/mdb.min.js"></script>
    <script>
    let anchorTags = document.getElementsByTagName('a');
    // this will force all anchor tags to be redirected to our redirect.php
    for (let i = 0; i < anchorTags.length; i++) {
      anchorTags[i].addEventListener('click', (e) => {
        anchorTags[i].href = './redirect.php?url=' + anchorTags[i].href
      });
    }
    </script>
  </body>
</html>

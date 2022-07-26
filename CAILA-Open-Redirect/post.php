<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require('./includes/header.php') ?>
  </head>
  <body>
    <?php require('./includes/navbar.php') ?>
    <div class="container-fluid mt-2 d-flex justify-content-center">
      <div class="card shadow-5-strong" id="createPostArea">
        <div class="card-content">
          <div class="card-header">
            <h5 class="card-title">Write a Post</h5>
          </div>
          <div class="card-body">
            <form action="https://server.caila.academy/demo-api/posts" method="post">
              <div class="form-outline mb-4">
                <input id="nickname" class="form-control" name="nickname" />
                <label class="form-label" for="nickname">Nickname</label>
              </div>
              <div class="mb-4">
                <label class="form-label" for="message">Message</label>
                <textarea class="form-control" id="message" name="message"></textarea>
              </div>
              <button type="submit" class="btn btn-secondary form-control">Save changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.11.0/mdb.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'autolink link',
      toolbar: 'undo redo | styleselect | bold italic link | alignleft aligncenter alignright alignjustify | outdent indent',
      menubar: '',
      toolbar_mode: 'floating',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      link_assume_external_targets: true,
      link_default_protocol: 'http',
    });
    </script>
  </body>
</html>

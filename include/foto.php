    <div class="foto container">
      <div class="row">
        <div class="col-md-12">
          <?php
            if($_SERVER['REQUEST_URI'] != '/kontakt') {
          ?>
            <img src="../images/nudle/<?php echo mt_rand(1, 7); ?>.jpg" alt="" class="img-responsive" />
          <?php
            }
          ?>
        </div> 
      </div>
    </div>


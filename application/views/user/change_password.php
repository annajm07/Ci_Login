
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

          <div class="row">
            <div class="col-lg-8">
              <?=$this->session->flashdata('message'); ?>
            </div>
          </div>

             <div class="row">
               <div class="col-lg-8">
                  <form action="<?= base_url('user/change_password'); ?>" method="post">
                     <div class="form-group">
                        <label for="current_password" class=" col-form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                        <?=form_error('current_password','<small class="text-danger ml-3">',' </small>'); ?>
                      </div>

                      <div class="form-group">
                        <label for="new_password" class=" col-form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                        <?=form_error('new_password','<small class="text-danger ml-3">',' </small>'); ?>
                      </div>

                      <div class="form-group">
                        <label for="repeat_password" class=" col-form-label">Repeat Password</label>
                        <input type="password" class="form-control" id="repeat_password" name="repeat_password">
                        <?=form_error('repeat_password','<small class="text-danger ml-3">',' </small>'); ?>
                      </div>

                      <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary">Change Password</button>
                 </form>
               </div>
             </div>
             
        </div>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

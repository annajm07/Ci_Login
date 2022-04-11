
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

          <div class="row">
          	<div class="col-lg">
              <?php if(validation_errors()) :?>
              <div class="alert alert-danger" role="alert"><?= validation_errors();?></div>
              <?php endif; ?>

          		<?=$this->session->flashdata('message'); ?>
          		<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal_menu">Add New SubMenu</a>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Title</th>
					      <th scope="col">Menu</th>
                <th scope="col">Url</th>
                <th scope="col">Icon</th>
                <th scope="col">Active</th>
                <th scope="col">Action</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<?php $i=1; foreach($Submenu as $sm) :?>
					    <tr>
					      <th scope="row"><?= $i; ?></th>
					      <td><?= $sm['title']; ?></td>
                <td><?= $sm['menu']; ?></td>
                <td><?= $sm['url']; ?></td>
                <td><?= $sm['icon']; ?></td>
                <td><?= $sm['is_active']; ?></td>
					      <td>
					      	<a href="" class="badge badge-success">edit</a>
					      	<a href="" class="badge badge-danger">delete</a>
					      </td>
					    </tr>
					<?php $i++; endforeach; ?>
					  </tbody>
					</table>
          	</div>		
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->


<!-- modal -->

<!-- Modal -->
<div class="modal fade" id="modal_menu" tabindex="-1" role="dialog" aria-labelledby="modal_menuLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_menuLabel">Add New SubMenu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="<?= base_url('menu/submenu'); ?>">
	      <div class="modal-body">

	        <div class="form-group">
    			<input type="text" class="form-control" id="title" name="title" placeholder="submenu title">
 			    </div> 

          <div class="form-group">
            <select name="menu_id" id="menu_id" class="form-control">
              <option value="">Select Menu</option>
              <?php foreach($menu as $m) :?>
              <option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
          <input type="text" class="form-control" id="url" name="url" placeholder="submenu url">
          </div>

          <div class="form-group">
          <input type="text" class="form-control" id="icon" name="icon" placeholder="submenu icon">
          </div>

          <div class="form-group">
             <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked="">
                <label class="form-check-label" for="is_active">Active ?</label>
             </div>
          </div>

	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" name="submit" class="btn btn-primary">Add</button>
      </form>
      </div>
    </div>
  </div>
</div>
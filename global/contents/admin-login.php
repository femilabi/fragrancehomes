<?php if(session_auth()):?>
<h4 class="widget-title">Welcome <?=@$USER->get('fullname')?></h4>
<a class="btn btn-info btn-lg btn-block" href="dashboard/">Dashboard</a>
<a class="btn btn-danger btn-lg btn-block" href="logout/">Logout</a>
<div class="clearfix">&nbsp;</div>
<?php else:?>
<h4>Login</h4>
<form method="post" action="<?php if ($APP->getPage() != 'login') echo 'login/?redirect='.urlencode(APP_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>" class="form-horizontal">
	<input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
	<input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
    <div class="clearfix">&nbsp;</div>
    <div class="form-group">
      <div class="row-fluid">
          <div class="col-xs-7">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="">
                Remeber me
              </label>
            </div>
          </div>
          <div class="col-xs-5" style="text-align:right">
            <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Login</button>
            <input name="issubmit" type="hidden" value="1" />
          </div>
      </div>
    </div>
</form>
<?php endif; ?>
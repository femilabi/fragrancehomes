<div class="c-text">
<form>
    <p>Enter your comment here:</p>
        <label for="head-title">Fullname:</label><br /><input id="head-title" name="data['head_title']" type="text" value="<?php echo($USER->get('fullname')); ?>" required />
    <div class="clearfix">&nbsp;</div>
        <label for="nav-title">Email:</label><br /><input id="nav-title" name="data['nav_title']" type="email" value="<?php echo($USER->get('email')); ?>" required />
    <div class="clearfix">&nbsp;</div>
    <label for="comment">Your comment:</label><br /><textarea name="comment['comment']" id="comment" placeholder="Comment here"></textarea>
     <div class="clearfix">&nbsp;</div>
    <button class="btn btn-success" type="submit" name="comment[action]" value="publish">Post comment</button>
    <input type="hidden" name="issubmit" value="1" />
</form>
</div>
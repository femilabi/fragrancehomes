<div class="well well-small">
	<h3>Content and design:</h3>
    <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
      <div class="btn-group">
        <a data-original-title="Font" class="btn dropdown-toggle" data-toggle="dropdown" title=""><i class="icon-font"></i><b class="caret"></b></a>
          <ul class="dropdown-menu">
          <li><a data-edit="fontName Serif" style="font-family:'Serif'">Serif</a></li><li><a data-edit="fontName Sans" style="font-family:'Sans'">Sans</a></li><li><a data-edit="fontName Arial" style="font-family:'Arial'">Arial</a></li><li><a data-edit="fontName Arial Black" style="font-family:'Arial Black'">Arial Black</a></li><li><a data-edit="fontName Courier" style="font-family:'Courier'">Courier</a></li><li><a data-edit="fontName Courier New" style="font-family:'Courier New'">Courier New</a></li><li><a data-edit="fontName Comic Sans MS" style="font-family:'Comic Sans MS'">Comic Sans MS</a></li><li><a data-edit="fontName Helvetica" style="font-family:'Helvetica'">Helvetica</a></li><li><a data-edit="fontName Impact" style="font-family:'Impact'">Impact</a></li><li><a data-edit="fontName Lucida Grande" style="font-family:'Lucida Grande'">Lucida Grande</a></li><li><a data-edit="fontName Lucida Sans" style="font-family:'Lucida Sans'">Lucida Sans</a></li><li><a data-edit="fontName Tahoma" style="font-family:'Tahoma'">Tahoma</a></li><li><a data-edit="fontName Times" style="font-family:'Times'">Times</a></li><li><a data-edit="fontName Times New Roman" style="font-family:'Times New Roman'">Times New Roman</a></li><li><a data-edit="fontName Verdana" style="font-family:'Verdana'">Verdana</a></li></ul>
        </div>
      <div class="btn-group">
        <a data-original-title="Font Size" class="btn dropdown-toggle" data-toggle="dropdown" title=""><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
          <ul class="dropdown-menu">
          <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
          <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
          <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
          </ul>
      </div>
      <div class="btn-group">
        <a data-original-title="Bold (Ctrl/Cmd+B)" class="btn" data-edit="bold" title=""><i class="icon-bold"></i></a>
        <a data-original-title="Italic (Ctrl/Cmd+I)" class="btn" data-edit="italic" title=""><i class="icon-italic"></i></a>
        <a data-original-title="Strikethrough" class="btn" data-edit="strikethrough" title=""><i class="icon-ban-circle"></i></a>
        <a data-original-title="Underline (Ctrl/Cmd+U)" class="btn" data-edit="underline" title=""><i class="icon-text-width"></i></a>
      </div>
      <div class="btn-group">
        <a data-original-title="Bullet list" class="btn" data-edit="insertunorderedlist" title=""><i class="icon-list"></i></a>
        <a data-original-title="Number list" class="btn" data-edit="insertorderedlist" title=""><i class="icon-list-alt"></i></a>
        <a data-original-title="Reduce indent (Shift+Tab)" class="btn" data-edit="outdent" title=""><i class="icon-indent-left"></i></a>
        <a data-original-title="Indent (Tab)" class="btn" data-edit="indent" title=""><i class="icon-indent-right"></i></a>
      </div>
      <div class="btn-group">
        <a data-original-title="Align Left (Ctrl/Cmd+L)" class="btn btn-info" data-edit="justifyleft" title=""><i class="icon-align-left"></i></a>
        <a data-original-title="Center (Ctrl/Cmd+E)" class="btn" data-edit="justifycenter" title=""><i class="icon-align-center"></i></a>
        <a data-original-title="Align Right (Ctrl/Cmd+R)" class="btn" data-edit="justifyright" title=""><i class="icon-align-right"></i></a>
        <a data-original-title="Justify (Ctrl/Cmd+J)" class="btn" data-edit="justifyfull" title=""><i class="icon-align-justify"></i></a>
      </div>
      <div class="clearfix">&nbsp;</div>
      <div class="btn-group">
		  <a data-original-title="Hyperlink" class="btn dropdown-toggle" data-toggle="dropdown" title=""><i class=" icon-resize-small"></i></a>
		    <div class="dropdown-menu">
			    <input class="span8" placeholder="URL" data-edit="createLink" type="text">
			    <button class="btn" type="button">Add</button>
        </div>
        <a data-original-title="Remove Hyperlink" class="btn" data-edit="unlink" title=""><i class="icon-remove"></i></a>

      </div>
      
      <div class="btn-group">
        <a data-original-title="Insert picture (or just drag &amp; drop)" class="btn" title="Insert Image" id="pictureBtn"><i class="icon-picture"></i></a>
        <input style="opacity: 0; position: absolute; top: 0px; left: 0px; width: 39px; height: 30px;" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" type="file">
      </div>
      <div class="btn-group">
        <a data-original-title="Undo (Ctrl/Cmd+Z)" class="btn" data-edit="undo" title=""><i class="icon-repeat"></i></a>
        <a data-original-title="Redo (Ctrl/Cmd+Y)" class="btn" data-edit="redo" title=""><i class="icon-random"></i></a>
      </div>
      <input style="display: none;" data-edit="inserttext" id="voiceBtn" x-webkit-speech="" type="text">
    </div>

    <div id="editor" contenteditable="true"><?php if(isset($APP) && is_object($APP)) echo($APP->get('editor_content')); ?></div>
    <textarea hidden="true" name="<?php echo $APP->get('editor_name'); ?>" data-role="editor_content" style="display:none; visibility:hidden"></textarea>
  </div>
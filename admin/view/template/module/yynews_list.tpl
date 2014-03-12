<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/yynews.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'id.title') { ?>
                <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'i.newsdate') { ?>
                <a href="<?php echo $sort_newsdate; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_newsdate; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_newsdate; ?>"><?php echo $column_newsdate; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($yynewss) { ?>
            <?php foreach ($yynewss as $yynews) { ?>
            <tr>
              <td style="text-align: center;<?php echo !$yynews['status']?"background-color:#E4E4E4":($yynews['top']?"background-color:yellow":'""'); ?>"><?php if ($yynews['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $yynews['yynews_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $yynews['yynews_id']; ?>" />
                <?php } ?></td>
              <td class="left" <?php echo !$yynews['status']?"style=background-color:#E4E4E4":($yynews['top']?"style=background-color:yellow":'""'); ?>><?php echo $yynews['title']; ?></td>
              <td class="right" <?php echo !$yynews['status']?"style=background-color:#E4E4E4":($yynews['top']?"style=background-color:yellow":'""'); ?>><?php echo $yynews['newsdate']; ?></td>
              <td class="right" <?php echo !$yynews['status']?"style=background-color:#E4E4E4":($yynews['top']?"style=background-color:yellow":'""'); ?>><?php foreach ($yynews['action'] as $action) { ?>
                [ <a href="<?php echo $yynews['action'][0]['href']; ?>"><?php echo $yynews['action'][0]['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
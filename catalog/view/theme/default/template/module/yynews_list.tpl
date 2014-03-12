<div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
        <?php echo $heading_title; ?>
    </div>
    <div class="box-content">
        <table  style="border-spacing:0px; border-style:none;">
          <tbody>
            <?php if ($yynewss) { ?>
            <?php foreach ($yynewss as $yynews) { ?>
            <tr>
             <?php if ($display_titleimage) {?> <td class="left" width="65px" ><?php echo ($yynews['titleimage']!="")? "<img width=\"64px\" high=\"64px\" src=\"{$yynews['titleimage']} \"  >":"";  ?></td><?php } ?>
              <td class="left">
                <a href="<?php echo $yynews['action'][0]['href']; ?>"><?php echo $yynews['title']; ?></a>
                <br />
                <span><?php echo $yynews['summary']; ?></span>
               <a href="<?php echo $yynews['action'][0]['href']; ?>" style="text-decoration: none;"><?php echo $yynews['action'][0]['text']; ?>...</a>
                <div sytle="position:right"><?php echo $yynews['newsdate']; ?></div>
              </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>

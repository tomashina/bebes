<?php if (!empty($product_attachs) || !empty($exten_links)) { ?>
<style>

    #tab-attach-document .table>tbody>tr>td, #tab-attach-document  .table>tbody>tr>th, .table>tfoot>tr>td,  #tab-attach-document  .table>tfoot>tr>th,  #tab-attach-document  .table>thead>tr>td,  #tab-attach-document  .table>thead>tr>th {

        vertical-align:  middle !important;


    }
    #tab-attach-document .img-responsive {
        margin: 0 auto;
    }
    #tab-attach-document small {
       color: #CAC7C7;
       vertical-align: bottom;
    }
 
</style>
<div class="<?php echo $class_panel; ?>" id="tab-attach-document">

 <?php if ($category_description) { ?>

    <p><strong>Proizvođač/ predstavnik</strong></p>
<?php echo $category_description; ?><br>
    <?php } ?>

    <?php if ($product_attachs) { ?>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
               
                <td class="text-left col-md-12" colspan="2">Dokumenti</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($product_attachs as $product_attach) : ?>
            <tr>
                <td class="text-center col-md-1 col-sm-2"><img class="img-rounded img-responsive" src="<?php echo $product_attach['thumb']; ?>" /></td>
                <td class="text-left col-md-11 col-sm-10"> 
                    <a <?php if ($product_attach['href'] != "") { ?> href="<?php echo $product_attach['href']; ?>" <?php } else { ?> class="btn-link" onclick="alert('<?php echo $attach_error_login; ?>')"  <?php } ?> title="<?php echo $attach_button_download; ?>" target="_blank" ><i class="fa fa-cloud-download"></i> <?php echo $product_attach['name']; ?> </a>
                    <span class="clearfix">
                    <small><i class="fa fa-info-circle"></i> <span class="hidden-xs"> <?php echo $attach_filesize; ?> </span><?php echo $product_attach['size']; ?></small>
                   <?php if ($show_download) { ?>
						<small><i class="fa fa-hdd-o"></i> <span class="hidden-xs"><?php echo $attach_downloaded.': '?></span> <?php echo $product_attach['download']; ?></small> 
					<?php } ?>
                   </span>
                    </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php } ?>
    <?php if ($exten_links) { ?>
    <span><strong><?php echo $external_link; ?></strong></span>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <td class="text-center  col-md-1 col-sm-2"><?php echo $attach_thumb; ?></td>
                <td class="text-left col-md-11 col-sm-10"><?php echo $attach_linkname; ?></td>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($exten_links as $exten_link) : ?>
            <tr>
                <td class="text-center col-md-1 col-sm-2"><img class="img-rounded img-responsive"  src="<?php echo $exten_link['thumb']; ?>" /></td>
                <td class="text-left col-md-11 col-sm-10" style="vertical-align: middle;"><a <?php if ($exten_link['href'] !="") { ?> href="<?php echo $exten_link['href']; ?>" target="_blank" <?php } else { ?> onclick="alert('<?php echo $attach_error_login; ?>')"  <?php } ?> title="<?php echo $attach_button_download; ?>"><i class="fa fa-cloud-download"></i> <?php echo $exten_link['name']; ?></a></td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php } ?>
</div>
<?php } ?>
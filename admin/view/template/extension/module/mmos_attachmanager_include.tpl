<div class="tab-pane" id="tab-attach-document">
    <ul class="nav nav-tabs" id="attach-document">  
        <li><a href="#internal" data-toggle="tab"><?php echo $tab_attach_internal; ?></a></li>
        <?php if($attach_info_config['extendlink'] == '1') { ?> <li><a href="#external" data-toggle="tab"><?php echo $tab_attach_external; ?></a></li> <?php } ?>

        <div class="pull-right">
            <a class="btn btn-success btn-xs" href="index.php?route=extension/module/mmos_attachmanager&token=<?php echo $token; ?>" target="_blank"><?php echo $button_config_attachments; ?></a>
        </div>
    </ul>
    <div class="tab-content"> 
        <div class="tab-pane" id="internal">  
            <div class="table-responsive">
                <table id="attach-internal" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <td class="col-sm-2 col-md-1 text-center"><?php echo $text_attach_file_product_thumb; ?></td>
                            <td class="text-left col-sm-6 col-md-9"><?php echo $text_attach_file_product_name; ?></td>
                            <td class="text-left col-sm-2 col-md-1 text-center"><i class="fa fa-lock" title="<?php echo $text_attach_file_product_login; ?>"></i> </td>
                            <td class="text-left col-sm-2 col-md-1 text-center"><?php echo $text_attach_file_product_count; ?></td>
                            <td></td>
                        </tr>            
                    </thead>  
                    <tbody>
                        <?php $attach_row = 0; ?>
                        <?php foreach ($product_attachs as $product_attach) : ?>
                        <tr id="attach-row<?php echo $attach_row; ?>">
                            <td class="text-center">
							<input type="hidden" name="product_attach[<?php echo $attach_row; ?>][filename]" value="<?php echo $product_attach['filename']; ?>" id="input-attach<?php echo $attach_row; ?>"/>
							<input type="hidden" name="product_attach[<?php echo $attach_row; ?>][product_attach_file_id]" value="<?php echo $product_attach['product_attach_file_id']; ?>"  >
							<a href="" id="thumb-acctach<?php echo $attach_row; ?>" data-toggle="attachmanager" class="img-thumbnail"><img src="<?php echo $product_attach['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" /></a>
							</td>
                            <td class="text-center"><div class="input-group">
                                    <input type="text" name="product_attach[<?php echo $attach_row; ?>][mask]" value="<?php echo $product_attach['mask']; ?>" placeholder="<?php echo $text_attach_file_product_name; ?>" class="form-control mask" />
                                    <span class="input-group-btn"><button class="btn btn-default" type="button" disabled><?php echo $product_attach['ext']; ?></button></span></div></td>
                            <td class="text-center"><input type="checkbox" name="product_attach[<?php echo $attach_row; ?>][login_required]" value="1" class="form-control" <?php echo ($product_attach['login_required'] == 1) ? 'checked':''; ?>/></td>
                            <td class="text-center"><?php echo $product_attach['download']; ?>
							<input type="hidden" name="product_attach[<?php echo $attach_row; ?>][download]" value="<?php echo $product_attach['download']; ?>"/>
							</td>
                            <td class="text-center"><button type="button" onclick="$('#attach-row<?php echo $attach_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                        <?php $attach_row++; ?>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-center"><?php echo $drapdrop; ?></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td class="text-left"><button type="button" onclick="addattachfile();" data-toggle="tooltip" title="<?php echo $button_add_attach_file_product; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                        </tr>
                    </tfoot>



                </table>
            </div> 
        </div>
        <div class="tab-pane" id="external">  
            <div class="table-responsive">
                <table id="attach-external" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <td class="text-left"><?php echo $text_attach_extend_link_name; ?></td>
                            <td class="text-left"><?php echo $text_attach_extend_link_download; ?></td>
                            <td class="text-left"><?php echo $text_attach_file_product_login; ?></td>
                            <td></td>
                        </tr>            
                    </thead>  
                    <tbody>
                        <?php $attach_exten_link = 0; ?>
                        <?php foreach ($exten_links as $exten_link) : ?>
                        <tr id="attach-exten-row<?php echo $attach_exten_link; ?>">
                            <td class="text-left"><input type="text" name="exten_link[<?php echo $attach_exten_link; ?>][link_name]" value="<?php echo $exten_link['link_name']; ?>" placeholder="<?php echo $text_attach_extend_link_name; ?>" class="form-control" /></td>
                            <td class="text-left"><input type="text" name="exten_link[<?php echo $attach_exten_link; ?>][link_download]" value="<?php echo $exten_link['link_download']; ?>" placeholder="<?php echo $text_attach_extend_link_download; ?>" class="form-control" /></td>
                            <td class="text-center"><input type="checkbox" name="exten_link[<?php echo $attach_exten_link; ?>][login_required]" value="1" class="form-control" <?php echo (isset($exten_link['login_required']) && $exten_link['login_required'] == 1) ? 'checked':''; ?>/></td>
                            <td class="text-left"><button type="button" onclick="$('#attach-exten-row<?php echo $attach_exten_link; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                        <?php $attach_exten_link++; ?>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-left"><button type="button" onclick="addattachlink();" data-toggle="tooltip" title="<?php echo $button_add_attach_exten_link; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                        </tr>
                    </tfoot>
                </table>
            </div> 
        </div>
    </div> 
</div> 
<script type="text/javascript">
    $('#attach-document a:first').tab('show');
    var attach_row = '<?php echo $attach_row; ?>';

    function addattachfile() {
        html = '<tr id="attach-row' + attach_row + '">';
        html += '<td class="text-left"><a href="" id="thumb-acctach' + attach_row + '" data-toggle="attachmanager" class="img-thumbnail"><img src="<?php echo $no_image; ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" /></a><input type="hidden" name="product_attach[' + attach_row + '][filename]" value=""id="input-attach' + attach_row + '"/></td>';
        html += '<td class="text-left"><div class="input-group"><input type="text" name="product_attach[' + attach_row + '][mask]" value="" placeholder="<?php echo $text_attach_file_product_name; ?>" class="form-control mask" /><span class="input-group-btn"><button class="btn btn-default" type="button" disabled>ext</button></span></div></td>';
        html += '<td class="text-center"><input type="checkbox" name="product_attach[' + attach_row + '][login_required]" value="1" class="form-control"/></td>';
        html += '<td class="text-left"></td>';
        html += '<td class="text-left"><button type="button" onclick="$(\'#attach-row' + attach_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';
        $('#attach-internal tbody').append(html);

        $("#thumb-acctach" + attach_row + "").trigger("click");

        $("#attach-row" + attach_row + " td div #button-object").trigger("click");

        attach_row++;
    }
  

    var attach_exten_link = '<?php echo $attach_exten_link; ?>';
    function addattachlink() {
        html = '<tr id="attach-exten-row' + attach_exten_link + '">';
        html += '<td class="text-left"><input type="text" name="exten_link[' + attach_exten_link + '][link_name]" value="" placeholder="<?php echo $text_attach_extend_link_name; ?>" class="form-control" /></td>';
        html += '<td class="text-left"><input type="text" name="exten_link[' + attach_exten_link + '][link_download]" value="" placeholder="<?php echo $text_attach_extend_link_download; ?>" class="form-control" /></td>';
        html += '<td class="text-center"><input type="checkbox" name="exten_link[' + attach_exten_link + '][login_required]" value="1" class="form-control" /></td>';
        html += '<td class="text-left"><button type="button" onclick="$(\'#attach-exten-row' + attach_exten_link + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';
        $('#attach-external tbody').append(html);
        attach_exten_link++;
    }
</script>
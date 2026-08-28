<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-category-seo-description<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_category_seo_description; ?>"><?php echo $entry_category_seo_description; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="category_seo_content[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_category_seo_description; ?>" id="input-category-seo-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($category_seo_content[$language['language_id']]) ? $category_seo_content[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_category_seo_faq; ?>"><?php echo $entry_category_seo_faq; ?></span></label>
                    <div class="col-sm-10">
	                      <div id="category-seo-faq<?php echo $language['language_id']; ?>" class="category-seo-faq-list">
	                        <div class="category-seo-faq-toolbar text-right">
	                          <button type="button" onclick="addCategorySeoFaqRow('<?php echo $language['language_id']; ?>');" data-toggle="tooltip" title="<?php echo $button_category_seo_faq_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_category_seo_faq_add; ?></button>
	                        </div>
	                        <div class="category-seo-faq-items">
	                          <?php $faq_row = 0; ?>
	                          <?php if (isset($category_seo_faq[$language['language_id']])) { ?>
	                          <?php foreach ($category_seo_faq[$language['language_id']] as $faq) { ?>
	                          <?php $faq_answer_preview = utf8_substr(trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode($faq['answer'], ENT_QUOTES, 'UTF-8')))), 0, 140); ?>
	                          <div id="category-seo-faq-row<?php echo $language['language_id']; ?>-<?php echo $faq_row; ?>" class="category-seo-faq-item">
	                            <div class="category-seo-faq-item-main">
	                              <strong class="category-seo-faq-question-text"><?php echo htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8'); ?></strong>
	                              <div class="text-muted category-seo-faq-answer-preview"><?php echo htmlspecialchars($faq_answer_preview, ENT_QUOTES, 'UTF-8'); ?></div>
	                              <span class="label label-default category-seo-faq-sort-label"><?php echo $entry_category_seo_faq_sort_order; ?>: <span class="category-seo-faq-sort-text"><?php echo (int)$faq['sort_order']; ?></span></span>
	                              <input type="hidden" class="category-seo-faq-question-input" name="category_seo_faq[<?php echo $language['language_id']; ?>][<?php echo $faq_row; ?>][question]" value="<?php echo htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8'); ?>" />
	                              <input type="hidden" class="category-seo-faq-answer-input" name="category_seo_faq[<?php echo $language['language_id']; ?>][<?php echo $faq_row; ?>][answer]" value="<?php echo htmlspecialchars($faq['answer'], ENT_QUOTES, 'UTF-8'); ?>" />
	                              <input type="hidden" class="category-seo-faq-sort-input" name="category_seo_faq[<?php echo $language['language_id']; ?>][<?php echo $faq_row; ?>][sort_order]" value="<?php echo (int)$faq['sort_order']; ?>" />
	                            </div>
	                            <div class="category-seo-faq-actions">
	                              <button type="button" onclick="openCategorySeoFaqModal('<?php echo $language['language_id']; ?>', <?php echo $faq_row; ?>, this);" data-toggle="tooltip" title="<?php echo $button_category_seo_faq_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></button>
	                              <button type="button" onclick="removeCategorySeoFaqRow(this);" data-toggle="tooltip" title="<?php echo $button_category_seo_faq_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
	                            </div>
	                          </div>
	                          <?php $faq_row++; ?>
	                          <?php } ?>
	                          <?php } ?>
	                        </div>
	                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="path" value="<?php echo $path; ?>" placeholder="<?php echo $entry_parent; ?>" id="input-parent" class="form-control" />
                  <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
                  <?php if ($error_parent) { ?>
                  <div class="text-danger"><?php echo $error_parent; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-filter"><span data-toggle="tooltip" title="<?php echo $help_filter; ?>"><?php echo $entry_filter; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" class="form-control" />
                  <div id="category-filter" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($category_filters as $category_filter) { ?>
                    <div id="category-filter<?php echo $category_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category_filter['name']; ?>
                      <input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $category_store)) { ?>
                        <input type="checkbox" name="category_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="category_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $category_store)) { ?>
                        <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                  <?php if ($error_keyword) { ?>
                  <div class="text-danger"><?php echo $error_keyword; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-top"><span data-toggle="tooltip" title="<?php echo $help_top; ?>"><?php echo $entry_top; ?></span></label>
                <div class="col-sm-10">
                  <div class="checkbox">
                    <label>
                      <?php if ($top) { ?>
                      <input type="checkbox" name="top" value="1" checked="checked" id="input-top" />
                      <?php } else { ?>
                      <input type="checkbox" name="top" value="1" id="input-top" />
                      <?php } ?>
                      &nbsp; </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-column"><span data-toggle="tooltip" title="<?php echo $help_column; ?>"><?php echo $entry_column; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="column" value="<?php echo $column; ?>" placeholder="<?php echo $entry_column; ?>" id="input-column" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-design">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left"><?php echo $entry_layout; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><?php echo $text_default; ?></td>
                      <td class="text-left"><select name="category_layout[0]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $store['name']; ?></td>
                      <td class="text-left"><select name="category_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
	          </div>
	        </form>
	        <div id="modal-category-seo-faq" class="modal fade" tabindex="-1" role="dialog">
	          <div class="modal-dialog modal-lg" role="document">
	            <div class="modal-content">
	              <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $button_cancel; ?>"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title" id="modal-category-seo-faq-title"><?php echo $text_category_seo_faq_modal_add; ?></h4>
	              </div>
	              <div class="modal-body">
	                <div class="category-seo-faq-modal-form">
	                  <div class="form-group">
	                    <label class="control-label" for="modal-category-seo-faq-question"><?php echo $entry_category_seo_faq_question; ?></label>
	                    <div>
	                      <input type="text" id="modal-category-seo-faq-question" class="form-control" />
	                    </div>
	                  </div>
	                  <div class="form-group">
	                    <label class="control-label" for="modal-category-seo-faq-answer"><?php echo $entry_category_seo_faq_answer; ?></label>
	                    <div>
	                      <textarea id="modal-category-seo-faq-answer" class="form-control"></textarea>
	                    </div>
	                  </div>
	                  <div class="form-group">
	                    <label class="control-label" for="modal-category-seo-faq-sort-order"><?php echo $entry_category_seo_faq_sort_order; ?></label>
	                    <div>
	                      <input type="text" id="modal-category-seo-faq-sort-order" class="form-control" />
	                    </div>
	                  </div>
	                </div>
	              </div>
	              <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_cancel; ?></button>
	                <button type="button" class="btn btn-primary" onclick="saveCategorySeoFaqModal();"><i class="fa fa-save"></i> <?php echo $button_category_seo_faq_save; ?></button>
	              </div>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	  <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
	  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
	  <style type="text/css">
	.category-seo-faq-answer-preview {
		margin-top: 4px;
		margin-bottom: 8px;
	}
	.category-seo-faq-toolbar {
		margin-bottom: 10px;
	}
	.category-seo-faq-item {
		display: block;
		position: relative;
		margin-bottom: 10px;
		padding: 12px 112px 12px 14px;
		border: 1px solid #dddddd;
		background: #ffffff;
		border-radius: 3px;
	}
	.category-seo-faq-item-main {
		display: block;
	}
	.category-seo-faq-question-text {
		display: block;
		margin-bottom: 4px;
	}
	.category-seo-faq-actions {
		position: absolute;
		top: 12px;
		right: 12px;
		white-space: nowrap;
	}
	.category-seo-faq-sort-label {
		display: inline-block;
		font-weight: normal;
	}
	#modal-category-seo-faq .modal-body {
		max-height: calc(100vh - 220px);
		overflow-y: auto;
	}
	.category-seo-faq-modal-form .control-label {
		display: block;
		margin-bottom: 6px;
		text-align: left;
	}
	  </style>
	  <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script> 
  <script type="text/javascript"><!--
$('input[name=\'path\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				json.unshift({
					category_id: 0,
					name: '<?php echo $text_none; ?>'
				});

				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'path\']').val(item['label']);
		$('input[name=\'parent_id\']').val(item['value']);
	}
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['filter_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter\']').val('');

		$('#category-filter' + item['value']).remove();

		$('#category-filter').append('<div id="category-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="category_filter[]" value="' + item['value'] + '" /></div>');
	}
});

$('#category-filter').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script> 
  <script type="text/javascript"><!--
	var categorySeoFaqRow = [];
	var categorySeoFaqModalState = {
		language_id: null,
		row: null,
		is_new: false,
		saved: false
	};
	var categorySeoFaqEditorReady = false;
	var categorySeoFaqText = {
		empty: <?php echo json_encode($text_category_seo_faq_empty); ?>,
		modalAdd: <?php echo json_encode($text_category_seo_faq_modal_add); ?>,
		modalEdit: <?php echo json_encode($text_category_seo_faq_modal_edit); ?>,
		sortOrder: <?php echo json_encode($entry_category_seo_faq_sort_order); ?>,
		edit: <?php echo json_encode($button_category_seo_faq_edit); ?>,
		remove: <?php echo json_encode($button_category_seo_faq_remove); ?>
	};
	<?php foreach ($languages as $language) { ?>
	categorySeoFaqRow['<?php echo $language['language_id']; ?>'] = <?php echo isset($category_seo_faq[$language['language_id']]) ? count($category_seo_faq[$language['language_id']]) : 0; ?>;
	<?php } ?>
	
	function initCategorySeoFaqEditor() {
		if (categorySeoFaqEditorReady || !$.fn.summernote) {
			return;
		}

		$('#modal-category-seo-faq-answer').summernote({
			disableDragAndDrop: true,
			height: 260,
			emptyPara: '',
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['para', ['ul', 'ol', 'paragraph']],
				['insert', ['link']],
				['view', ['codeview']]
			]
		});

		categorySeoFaqEditorReady = true;
	}
	
	function getCategorySeoFaqEditorValue() {
		if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['modal-category-seo-faq-answer']) {
			return CKEDITOR.instances['modal-category-seo-faq-answer'].getData();
		}

		if ($.fn.summernote && $('#modal-category-seo-faq-answer').next('.note-editor').length) {
			try {
				return $('#modal-category-seo-faq-answer').summernote('code');
			} catch (e) {}
		}

		return $('#modal-category-seo-faq-answer').val();
	}

	function setCategorySeoFaqEditorValue(value) {
		if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['modal-category-seo-faq-answer']) {
			CKEDITOR.instances['modal-category-seo-faq-answer'].setData(value || '');
			return;
		}

		if ($.fn.summernote && $('#modal-category-seo-faq-answer').next('.note-editor').length) {
			try {
				$('#modal-category-seo-faq-answer').summernote('code', value || '');
				return;
			} catch (e) {}
		}

		$('#modal-category-seo-faq-answer').val(value || '');
	}

	function getCategorySeoFaqPreview(html) {
		var text = $('<div/>').html(html || '').text().replace(/\s+/g, ' ');
		text = $.trim(text);

		if (text.length > 140) {
			text = text.substring(0, 137) + '...';
		}

		return text;
	}

	function renderCategorySeoFaqRow(language_id, faq_row, data) {
		var html = '';

		html += '<div id="category-seo-faq-row' + language_id + '-' + faq_row + '" class="category-seo-faq-item">';
		html += '  <div class="category-seo-faq-item-main">';
		html += '    <strong class="category-seo-faq-question-text"></strong>';
		html += '    <div class="text-muted category-seo-faq-answer-preview"></div>';
		html += '    <span class="label label-default category-seo-faq-sort-label">' + categorySeoFaqText.sortOrder + ': <span class="category-seo-faq-sort-text"></span></span>';
		html += '    <input type="hidden" class="category-seo-faq-question-input" name="category_seo_faq[' + language_id + '][' + faq_row + '][question]" value="" />';
		html += '    <input type="hidden" class="category-seo-faq-answer-input" name="category_seo_faq[' + language_id + '][' + faq_row + '][answer]" value="" />';
		html += '    <input type="hidden" class="category-seo-faq-sort-input" name="category_seo_faq[' + language_id + '][' + faq_row + '][sort_order]" value="" />';
		html += '  </div>';
		html += '  <div class="category-seo-faq-actions">';
		html += '    <button type="button" onclick="openCategorySeoFaqModal(\'' + language_id + '\', ' + faq_row + ', this);" data-toggle="tooltip" title="' + categorySeoFaqText.edit + '" class="btn btn-primary"><i class="fa fa-pencil"></i></button> ';
		html += '    <button type="button" onclick="removeCategorySeoFaqRow(this);" data-toggle="tooltip" title="' + categorySeoFaqText.remove + '" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
		html += '  </div>';
		html += '</div>';

		$('#category-seo-faq' + language_id + ' .category-seo-faq-items').append(html);
		updateCategorySeoFaqRow($('#category-seo-faq-row' + language_id + '-' + faq_row), data);
		$('[data-toggle="tooltip"]').tooltip();
	}

	function updateCategorySeoFaqRow(row, data) {
		row.find('.category-seo-faq-question-input').val(data.question || '');
		row.find('.category-seo-faq-answer-input').val(data.answer || '');
		row.find('.category-seo-faq-sort-input').val(data.sort_order || 0);
		row.find('.category-seo-faq-question-text').text(data.question || categorySeoFaqText.empty);
		row.find('.category-seo-faq-answer-preview').text(getCategorySeoFaqPreview(data.answer));
		row.find('.category-seo-faq-sort-text').text(data.sort_order || 0);
	}

	function addCategorySeoFaqRow(language_id) {
		var faq_row = categorySeoFaqRow[language_id]++;

		renderCategorySeoFaqRow(language_id, faq_row, {
			question: '',
			answer: '',
			sort_order: faq_row
		});

		openCategorySeoFaqModal(language_id, faq_row, $('#category-seo-faq-row' + language_id + '-' + faq_row + ' .btn-primary').get(0), true);
	}

	function openCategorySeoFaqModal(language_id, faq_row, button, is_new) {
		var row = button ? $(button).closest('.category-seo-faq-item') : $('#category-seo-faq-row' + language_id + '-' + faq_row);

		categorySeoFaqModalState.language_id = language_id;
		categorySeoFaqModalState.row = row;
		categorySeoFaqModalState.is_new = !!is_new;
		categorySeoFaqModalState.saved = false;

		$('#modal-category-seo-faq-title').text(categorySeoFaqModalState.is_new ? categorySeoFaqText.modalAdd : categorySeoFaqText.modalEdit);
		$('#modal-category-seo-faq-question').val(row.find('.category-seo-faq-question-input').val() || '');
		$('#modal-category-seo-faq-sort-order').val(row.find('.category-seo-faq-sort-input').val() || faq_row || 0);
		$('#modal-category-seo-faq').modal('show');

		setTimeout(function() {
			initCategorySeoFaqEditor();
			setCategorySeoFaqEditorValue(row.find('.category-seo-faq-answer-input').val() || '');
			$('#modal-category-seo-faq-question').focus();
		}, 150);
	}

	function saveCategorySeoFaqModal() {
		if (!categorySeoFaqModalState.row || !categorySeoFaqModalState.row.length) {
			return;
		}

		updateCategorySeoFaqRow(categorySeoFaqModalState.row, {
			question: $('#modal-category-seo-faq-question').val(),
			answer: getCategorySeoFaqEditorValue(),
			sort_order: $('#modal-category-seo-faq-sort-order').val()
		});

		categorySeoFaqModalState.saved = true;
		$('#modal-category-seo-faq').modal('hide');
	}
	
	function removeCategorySeoFaqRow(button) {
		$(button).closest('.category-seo-faq-item').remove();
	}

	$('#modal-category-seo-faq').on('hidden.bs.modal', function() {
		if (categorySeoFaqModalState.is_new && !categorySeoFaqModalState.saved && categorySeoFaqModalState.row) {
			categorySeoFaqModalState.row.remove();
		}

		categorySeoFaqModalState.language_id = null;
		categorySeoFaqModalState.row = null;
		categorySeoFaqModalState.is_new = false;
		categorySeoFaqModalState.saved = false;
	});
	//--></script>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>

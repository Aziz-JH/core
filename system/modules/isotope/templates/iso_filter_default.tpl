
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->actionFilter; ?>" id="<?php echo $this->formId; ?>" method="post">
<div class="formbody">
<input type="hidden" name="FORM_SUBMIT" value="<?php echo $this->formId; ?>" />
<input type="hidden" name="REQUEST_TOKEN" value="{{request_token}}" />

<?php if($this->hasSorting): ?>
<div class="sorting">
<label for="ctrl_sorting_<?php echo $this->id; ?>"><?php echo $this->sortingLabel; ?></label>
<select name="sorting" id="ctrl_sorting_<?php echo $this->id; ?>" class="select" onchange="<?php echo $this->formId; ?>.submit();">
<?php foreach( $this->sortingOptions as $option ): ?>
<option value="<?php echo $option['value']; ?>"<?php if($option['default']) echo ' selected="selected"'; ?>><?php echo $option['label']; ?></option>
<?php endforeach; ?>
</select>
</div>
<?php endif; ?>

<?php if ($this->hasLimit): ?>
<div class="limit">
<label for="ctrl_limit_<?php echo $this->id; ?>"><?php echo $this->limitLabel; ?></label>
<select name="limit" id="ctrl_limit_<?php echo $this->id; ?>" class="select" onchange="<?php echo $this->formId; ?>.submit();">
<?php foreach( $this->limitOptions as $option ): ?>
<option value="<?php echo $option['value']; ?>"<?php if($option['default']) echo ' selected="selected"'; ?>><?php echo $option['label']; ?></option>
<?php endforeach; ?>
</select>
</div>
<?php endif; ?>

<?php if ($this->hasFilters): ?>
<div class="filters">
<?php foreach( $this->filterOptions as $field => $data ): ?>
<label for="ctrl_filter_<?php echo $field; ?>_<?php echo $this->id; ?>"><?php echo $data['label']; ?></option>
<select name="filter[<?php echo $field; ?>]" id="ctrl_filter_<?php echo $field; ?>_<?php echo $this->id; ?>" class="select" onchange="<?php echo $this->formId; ?>.submit();">
<option value="">-</option>
<?php foreach( $data['options'] as $option ): ?>
<option value="<?php echo $option['value']; ?>"<?php if($option['default']) echo ' selected="selected"'; ?>><?php echo $option['label']; ?></option>
<?php endforeach; ?>
</select>
<?php endforeach; ?>
</div>
<?php endif; ?>

<noscript>
<div class="submit_container"><input type="submit" class="submit" value="<?php echo $this->slabel; ?>" /></div>
</noscript>

</div>
</form>

<?php if ($this->hasSearch): ?>
<div class="search">
<form action="<?php echo $this->actionSearch; ?>" method="get">
<input type="hidden" name="isorc" value="<?php echo $this->Input->get('isorc'); ?>" />
<label for="ctrl_keywords_<?php echo $this->id; ?>"><?php echo $this->keywordsLabel; ?></label>
<input type="text" name="keywords" id="ctrl_keywords_<?php echo $this->id; ?>" class="text" value="<?php echo ($this->keywords ? $this->keywords : $this->defaultSearchText); ?>" onblur="if (this.value=='') { this.value='<?php echo $this->defaultSearchText; ?>'; }" onfocus="if (this.value=='<?php echo $this->defaultSearchText; ?>') { this.value=''; this.select(); }" />
<input type="submit" class="submit" value="<?php echo $this->searchLabel; ?>" />
</form>
</div>
<?php endif; ?>

<a href="<?php echo $this->actionClear; ?>" class="clear_filters"><?php echo $this->clearLabel; ?></a>

<div class="clear">&nbsp;</div>

</div>
<!-- indexer::continue -->
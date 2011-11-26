<?php

defined('_JEXEC') or die;

?>

<div id="com_searchify">
    <div class="content collapsible">

        <div class="top">
            <h4 class="title legend collapsible-heading"><?php echo JText::_('Search articles'); ?></h4>

            <div id="msg" style="display:none;" class="clearfix"></div>
        </div>

        <form id='searchform' class="collapsible-content">

            <label>
                <span><?php echo JText::_('Title'); ?></span>
                <input autofocus id='title' type="text" name="title" value="">
            </label>

            <label>
                <span><?php echo JText::_('Body'); ?></span>
                <input id="body" type="text" name="body" value="">
            </label>

            <div class="daterange">
                <label>
                    <span><?php echo JText::_('From'); ?></span>
                    <input id='start_date' class="date-pick" type="text" name="start_date" value="">
                </label>

                <label>
                    <span><?php echo JText::_('To'); ?></span>
                    <input id='end_date' class="date-pick" type="text" name="end_date"
                           value="<?php echo $this->data['current_date']; ?>">
                </label>
            </div>

            <!--<label id="states">
                <span>Types</span>
                <ins><input id="states_all" type="checkbox" name="all" value="all"> <em>Select all?</em></ins>
                <ins><input id="featured" class="default-selected" type="checkbox" name="featured" value="featured" checked="checked"> Featured</ins>
                <ins><input id="published" class="default-selected" type="checkbox" name="published" value="published" checked="checked"> Published
                </ins>
                <ins><input id="archived" type="checkbox" value="archived" name="archived"> Archived</ins>
            </label>-->

            <label>
                <span><?php echo JText::_('Categories'); ?></span>
                <span id="categories"><?php echo $this->data['categories']; ?></span>
            </label>

            <label>
                <span><?php echo JText::_('Authors'); ?></span>
                <span id="authors"></span>
                <script type="text/javascript">
                    //<[[CDATA[
                    var authors = jQuery('#authors');
                    authors.wijlist({selectionMode: 'multiple'});
                    authors.wijlist('setItems', (<?php echo json_encode($this->data['authors']); ?>));
                    authors.wijlist('renderList');
                    authors.wijlist('refreshSuperPanel');
                    //]]>
                </script>
            </label>

            <div id='submit-wrapper'>
                <button id='searchformsubmit' class="right clearfix">
                    <span class="inner"><?php echo JText::_('Search'); ?></span>
                </button>
            </div>
        </form>
    </div>
    <div class="search-results-wrapper">
        <h4 class="title"><?php echo JText::_('Search results'); ?>:</h4>
        <div id="search-results"></div>
    </div>
</div>
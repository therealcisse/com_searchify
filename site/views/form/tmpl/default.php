<?php

defined('_JEXEC') or die;

?>

<div id="com_searchify">
    <div class="content collapsible">

        <form id="searchform">
            <div class="top">
                <div class="title clearfix">
                    <h4 class="inline-block title legend collapsible-heading"><?php echo JText::_('Search articles'); ?></h4>

                    <span class="languages inline-block right"> Language:
                        <select name="language" id="language">
                            <option value="*" selected="selected"><?php echo JText::_('All languages') ?></option>
                            <?php echo $this->data['languages'] ?>
                        </select>
                    </span>
                </div>
                <div id="msg"></div>
            </div>

            <div class="collapsible-content">

                <label style="margin-right: 25px;">
                    <span class="inline-block"><?php echo JText::_('Text to Search'); ?>: &nbsp;</span>
                    <input type="text" id="search" name="search">
                </label>

                <div class="box inline-block">
                    <fieldset>
                        <legend><?php echo JText::_('Select Category'); ?></legend>
                        <div>
                            <label>
                                <span>Filter by</span>
                                <select class="category" name="parent_category" id='parent_category'>
                                    <option value="0" id="all_categories"
                                            selected="selected"><?php echo JText::_('All Categories') ?></option>
                                    <?php echo $this->data['pcategories'] ?>
                                </select>
                            </label>
                            <label>
                                <span>Category</span>
                                <select class="category" name="category" id="category">
                                    <option value="0"
                                            selected="selected"><?php echo JText::_('All Categories') ?></option>
                                    <?php echo $this->data['ccategories'] ?>
                                </select>
                            </label>
                        </div>
                    </fieldset>
                </div>

                <div class="box inline-block last">
                    <fieldset class="clearfix">
                        <legend><?php echo JText::_('Date range'); ?></legend>
                        <label>
                            <span><?php echo JText::_('From'); ?></span>
                            <input id='start_date' class="date-pick" type="text" name="start_date" value="">
                        </label>

                        <label>
                            <span><?php echo JText::_('To'); ?></span>
                            <input id='end_date' class="date-pick" type="text" name="end_date"
                                   value="<?php echo $this->data['current_date']; ?>">
                        </label>
                    </fieldset>
                </div>


                <div id='submit-wrapper' class="clearfix">
                    <button id='searchformsubmit' class="right clearfix">
                        <span class="inner"><?php echo JText::_('Search'); ?></span>
                    </button>
                </div>
            </div>
        </form>


    </div>

    <div class="search-results-wrapper">
        <h4 class="title"><?php echo JText::_('Search results'); ?>:</h4>

        <div id="search-results"></div>
    </div>
</div>
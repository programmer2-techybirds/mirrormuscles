function woof_init_radios(){if(icheck_skin!='none'){jQuery('.woof_radio_term').iCheck('destroy');jQuery('.woof_radio_term').iCheck({radioClass:'iradio_'+icheck_skin.skin+'-'+icheck_skin.color,});jQuery('.woof_radio_term').unbind('ifChecked');jQuery('.woof_radio_term').on('ifChecked',function(event){jQuery(this).attr("checked",true);jQuery(this).parents('.woof_list').find('.woof_radio_term_reset').removeClass('woof_radio_term_reset_visible');jQuery(this).parents('.woof_list').find('.woof_radio_term_reset').hide();jQuery(this).parents('li').eq(0).find('.woof_radio_term_reset').eq(0).addClass('woof_radio_term_reset_visible');var slug=jQuery(this).data('slug');var name=jQuery(this).attr('name');var term_id=jQuery(this).data('term-id');woof_radio_direct_search(term_id,name,slug);});}else{jQuery('.woof_radio_term').on('change',function(event){jQuery(this).attr("checked",true);var slug=jQuery(this).data('slug');var name=jQuery(this).attr('name');var term_id=jQuery(this).data('term-id');woof_radio_direct_search(term_id,name,slug);});}
jQuery('.woof_radio_term_reset').click(function(){woof_radio_direct_search(jQuery(this).data('term-id'),jQuery(this).attr('data-name'),0);jQuery(this).parents('.woof_list').find('.checked').removeClass('checked');jQuery(this).parents('.woof_list').find('input[type=radio]').removeAttr('checked');jQuery(this).removeClass('woof_radio_term_reset_visible');return false;});}
function woof_radio_direct_search(term_id,name,slug){jQuery.each(woof_current_values,function(index,value){if(index==name){delete woof_current_values[name];return;}});if(slug!=0){woof_current_values[name]=slug;jQuery('a.woof_radio_term_reset_'+term_id).hide();jQuery('woof_radio_term_'+term_id).filter(':checked').parents('li').find('a.woof_radio_term_reset').show();jQuery('woof_radio_term_'+term_id).parents('ul.woof_list').find('label').css({'fontWeight':'normal'});jQuery('woof_radio_term_'+term_id).filter(':checked').parents('li').find('label.woof_radio_label_'+slug).css({'fontWeight':'bold'});}else{jQuery('a.woof_radio_term_reset_'+term_id).hide();jQuery('woof_radio_term_'+term_id).attr('checked',false);jQuery('woof_radio_term_'+term_id).parent().removeClass('checked');jQuery('woof_radio_term_'+term_id).parents('ul.woof_list').find('label').css({'fontWeight':'normal'});}
woof_ajax_page_num=1;if(woof_autosubmit){woof_submit_link(woof_get_submit_link());}};function woof_init_checkboxes(){if(icheck_skin!='none'){jQuery('.woof_checkbox_term').iCheck('destroy');jQuery('.woof_checkbox_term').iCheck({checkboxClass:'icheckbox_'+icheck_skin.skin+'-'+icheck_skin.color,});jQuery('.woof_checkbox_term').unbind('ifChecked');jQuery('.woof_checkbox_term').on('ifChecked',function(event){jQuery(this).attr("checked",true);woof_checkbox_process_data(this,true);});jQuery('.woof_checkbox_term').unbind('ifUnchecked');jQuery('.woof_checkbox_term').on('ifUnchecked',function(event){jQuery(this).attr("checked",false);woof_checkbox_process_data(this,false);});jQuery('.woof_checkbox_label').unbind();jQuery('label.woof_checkbox_label').click(function(){if(jQuery(this).prev().find('.woof_checkbox_term').is(':checked')){jQuery(this).prev().find('.woof_checkbox_term').trigger('ifUnchecked');jQuery(this).prev().removeClass('checked');}else{jQuery(this).prev().find('.woof_checkbox_term').trigger('ifChecked');jQuery(this).prev().addClass('checked');}
return false;});}else{jQuery('.woof_checkbox_term').on('change',function(event){if(jQuery(this).is(':checked')){jQuery(this).attr("checked",true);woof_checkbox_process_data(this,true);}else{jQuery(this).attr("checked",false);woof_checkbox_process_data(this,false);}});}}
function woof_checkbox_process_data(_this,is_checked){var tax=jQuery(_this).data('tax');var name=jQuery(_this).attr('name');var term_id=jQuery(_this).data('term-id');woof_checkbox_direct_search(term_id,name,tax,is_checked);}
function woof_checkbox_direct_search(term_id,name,tax,is_checked){var values='';var checked=true;if(is_checked){if(tax in woof_current_values){woof_current_values[tax]=woof_current_values[tax]+','+name;}else{woof_current_values[tax]=name;}
checked=true;}else{values=woof_current_values[tax];values=values.split(',');var tmp=[];jQuery.each(values,function(index,value){if(value!=name){tmp.push(value);}});values=tmp;if(values.length){woof_current_values[tax]=values.join(',');}else{delete woof_current_values[tax];}
checked=false;}
jQuery('.woof_checkbox_term_'+term_id).attr('checked',checked);woof_ajax_page_num=1;if(woof_autosubmit){woof_submit_link(woof_get_submit_link());}};function woof_init_selects(){if(is_woof_use_chosen){try{jQuery("select.woof_select, select.woof_price_filter_dropdown").chosen();}catch(e){}}
jQuery('.woof_select').change(function(){var slug=jQuery(this).val();var name=jQuery(this).attr('name');woof_select_direct_search(this,name,slug);});}
function woof_select_direct_search(_this,name,slug){jQuery.each(woof_current_values,function(index,value){if(index==name){delete woof_current_values[name];return;}});if(slug!=0){woof_current_values[name]=slug;}
woof_ajax_page_num=1;if(woof_autosubmit||jQuery(_this).within('.woof').length==0){woof_submit_link(woof_get_submit_link());}};function woof_init_mselects(){try{jQuery("select.woof_mselect").chosen();}catch(e){}
jQuery('.woof_mselect').change(function(a){var slug=jQuery(this).val();var name=jQuery(this).attr('name');if(is_woof_use_chosen){var vals=jQuery(this).chosen().val();jQuery('.woof_mselect[name='+name+'] option:selected').removeAttr("selected");jQuery('.woof_mselect[name='+name+'] option').each(function(i,option){var v=jQuery(this).val();if(jQuery.inArray(v,vals)!==-1){jQuery(this).prop("selected",true);}});}
woof_mselect_direct_search(name,slug);return true;});}
function woof_mselect_direct_search(name,slug){var values=[];jQuery('.woof_mselect[name='+name+'] option:selected').each(function(i,v){values.push(jQuery(this).val());});values=values.filter(function(item,pos){return values.indexOf(item)==pos;});values=values.join(',');if(values.length){woof_current_values[name]=values;}else{delete woof_current_values[name];}
woof_ajax_page_num=1;if(woof_autosubmit){woof_submit_link(woof_get_submit_link());}};function woof_init_author(){if(icheck_skin!='none'){jQuery('.woof_checkbox_author').iCheck({checkboxClass:'icheckbox_'+icheck_skin.skin+'-'+icheck_skin.color,});jQuery('.woof_checkbox_author').on('ifChecked',function(event){jQuery(this).attr("checked",true);woof_current_values.woof_author=get_current_checked();woof_ajax_page_num=1;if(woof_autosubmit){woof_submit_link(woof_get_submit_link());}});jQuery('.woof_checkbox_author').on('ifUnchecked',function(event){jQuery(this).attr("checked",false);woof_current_values.woof_author=get_current_checked();woof_ajax_page_num=1;if(woof_autosubmit){woof_submit_link(woof_get_submit_link());}});}else{jQuery('.woof_checkbox_author').on('change',function(event){if(jQuery(this).is(':checked')){jQuery(this).attr("checked",true);woof_current_values.woof_author=get_current_checked();woof_ajax_page_num=1;if(woof_autosubmit){woof_submit_link(woof_get_submit_link());}}else{jQuery(this).attr("checked",false);woof_current_values.woof_author=get_current_checked();woof_ajax_page_num=1;if(woof_autosubmit){woof_submit_link(woof_get_submit_link());}}});}
function get_current_checked(){var values=[];jQuery('.woof_checkbox_author').each(function(i,el){if(jQuery(this).attr("checked")=='checked'){values.push(jQuery(this).val());}});return values.join(',');}};var woof_text_do_submit=false;function woof_init_text(){jQuery('.woof_show_text_search').keyup(function(e){var val=jQuery(this).val();var uid=jQuery(this).data('uid');if(e.keyCode==13){woof_text_do_submit=true;woof_text_direct_search('woof_text',val);return true;}
if(woof_autosubmit){woof_current_values['woof_text']=val;}else{woof_text_direct_search('woof_text',val);}
if(val.length>0){jQuery('.woof_text_search_go.'+uid).show(222);}else{jQuery('.woof_text_search_go.'+uid).hide();}
if(val.length>=3&&woof_text_autocomplete){jQuery('.easy-autocomplete a').life('click',function(){if(!how_to_open_links){window.open(jQuery(this).attr('href'),'_blank');return false;}
return true;});var input_id=jQuery(this).attr('id');var options={url:function(phrase){return woof_ajaxurl;},getValue:function(element){jQuery("#"+input_id).parents('.woof_show_text_search_container').find('.woof_show_text_search_loader').hide();jQuery("#"+input_id).parents('.woof_show_text_search_container').find('.woof_text_search_go').show();return element.name;},ajaxSettings:{dataType:"json",method:"POST",data:{action:"woof_text_autocomplete",dataType:"json"}},preparePostData:function(data){jQuery("#"+input_id).parents('.woof_show_text_search_container').find('.woof_text_search_go').hide();jQuery("#"+input_id).parents('.woof_show_text_search_container').find('.woof_show_text_search_loader').show();data.phrase=jQuery("#"+input_id).val();data.auto_res_count=jQuery("#"+input_id).data('auto_res_count');data.auto_search_by=jQuery("#"+input_id).data('auto_search_by');return data;},template:{type:woof_post_links_in_autocomplete?'links':'iconRight',fields:{iconSrc:"icon",link:"link"}},list:{maxNumberOfElements:jQuery("#"+input_id).data('auto_res_count')>0?jQuery("#"+input_id).data('auto_res_count'):woof_text_autocomplete_items,onChooseEvent:function(){woof_text_do_submit=true;if(woof_post_links_in_autocomplete){return false;}else{woof_text_direct_search('woof_text',jQuery("#"+input_id).val());}
return true;},showAnimation:{type:"fade",time:333,callback:function(){}},hideAnimation:{type:"slide",time:333,callback:function(){}}},requestDelay:400};try{jQuery("#"+input_id).easyAutocomplete(options);}catch(e){console.log(e);}
jQuery("#"+input_id).focus();}});jQuery('.woof_text_search_go').life('click',function(){var uid=jQuery(this).data('uid');woof_text_do_submit=true;woof_text_direct_search('woof_text',jQuery('.woof_show_text_search.'+uid).val());});}
function woof_text_direct_search(name,slug){slug=encodeURIComponent(slug);jQuery.each(woof_current_values,function(index,value){if(index==name){delete woof_current_values[name];return;}});if(slug!=0){woof_current_values[name]=slug;}
woof_ajax_page_num=1;if(woof_autosubmit||woof_text_do_submit){woof_text_do_submit=false;woof_submit_link(woof_get_submit_link());}}
;/*!
 Chosen, a Select Box Enhancer for jQuery and Prototype
 by Patrick Filler for Harvest, http://getharvest.com
 
 Version 1.1.0
 Full source at https://github.com/harvesthq/chosen
 Copyright (c) 2011 Harvest http://getharvest.com
 
 MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md
 This file is generated by `grunt build`, do not edit it by hand.
 */

(function () {
    var $, AbstractChosen, Chosen, SelectParser, _ref,
            __hasProp = {}.hasOwnProperty,
            __extends = function (child, parent) {
                for (var key in parent) {
                    if (__hasProp.call(parent, key))
                        child[key] = parent[key];
                }
                function ctor() {
                    this.constructor = child;
                }
                ctor.prototype = parent.prototype;
                child.prototype = new ctor();
                child.__super__ = parent.prototype;
                return child;
            };

    SelectParser = (function () {
        function SelectParser() {
            this.options_index = 0;
            this.parsed = [];
        }

        SelectParser.prototype.add_node = function (child) {
            if (child.nodeName.toUpperCase() === "OPTGROUP") {
                return this.add_group(child);
            } else {
                return this.add_option(child);
            }
        };

        SelectParser.prototype.add_group = function (group) {
            var group_position, option, _i, _len, _ref, _results;
            group_position = this.parsed.length;
            this.parsed.push({
                array_index: group_position,
                group: true,
                label: this.escapeExpression(group.label),
                children: 0,
                disabled: group.disabled
            });
            _ref = group.childNodes;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                option = _ref[_i];
                _results.push(this.add_option(option, group_position, group.disabled));
            }
            return _results;
        };

        SelectParser.prototype.add_option = function (option, group_position, group_disabled) {
            if (option.nodeName.toUpperCase() === "OPTION") {
                if (option.text !== "") {
                    if (group_position != null) {
                        this.parsed[group_position].children += 1;
                    }
                    this.parsed.push({
                        array_index: this.parsed.length,
                        options_index: this.options_index,
                        value: option.value,
                        text: option.text,
                        html: option.innerHTML,
                        selected: option.selected,
                        disabled: group_disabled === true ? group_disabled : option.disabled,
                        group_array_index: group_position,
                        classes: option.className,
                        style: option.style.cssText
                    });
                } else {
                    this.parsed.push({
                        array_index: this.parsed.length,
                        options_index: this.options_index,
                        empty: true
                    });
                }
                return this.options_index += 1;
            }
        };

        SelectParser.prototype.escapeExpression = function (text) {
            var map, unsafe_chars;
            if ((text == null) || text === false) {
                return "";
            }
            if (!/[\&\<\>\"\'\`]/.test(text)) {
                return text;
            }
            map = {
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#x27;",
                "`": "&#x60;"
            };
            unsafe_chars = /&(?!\w+;)|[\<\>\"\'\`]/g;
            return text.replace(unsafe_chars, function (chr) {
                return map[chr] || "&amp;";
            });
        };

        return SelectParser;

    })();

    SelectParser.select_to_array = function (select) {
        var child, parser, _i, _len, _ref;
        parser = new SelectParser();
        _ref = select.childNodes;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            child = _ref[_i];
            parser.add_node(child);
        }
        return parser.parsed;
    };

    AbstractChosen = (function () {
        function AbstractChosen(form_field, options) {
            this.form_field = form_field;
            this.options = options != null ? options : {};
            if (!AbstractChosen.browser_is_supported()) {
                return;
            }
            this.is_multiple = this.form_field.multiple;
            this.set_default_text();
            this.set_default_values();
            this.setup();
            this.set_up_html();
            this.register_observers();
        }

        AbstractChosen.prototype.set_default_values = function () {
            var _this = this;
            this.click_test_action = function (evt) {
                return _this.test_active_click(evt);
            };
            this.activate_action = function (evt) {
                return _this.activate_field(evt);
            };
            this.active_field = false;
            this.mouse_on_container = false;
            this.results_showing = false;
            this.result_highlighted = null;
            this.allow_single_deselect = (this.options.allow_single_deselect != null) && (this.form_field.options[0] != null) && this.form_field.options[0].text === "" ? this.options.allow_single_deselect : false;
            this.disable_search_threshold = this.options.disable_search_threshold || 0;
            this.disable_search = this.options.disable_search || false;
            this.enable_split_word_search = this.options.enable_split_word_search != null ? this.options.enable_split_word_search : true;
            this.group_search = this.options.group_search != null ? this.options.group_search : true;
            this.search_contains = this.options.search_contains || false;
            this.single_backstroke_delete = this.options.single_backstroke_delete != null ? this.options.single_backstroke_delete : true;
            this.max_selected_options = this.options.max_selected_options || Infinity;
            this.inherit_select_classes = this.options.inherit_select_classes || false;
            this.display_selected_options = this.options.display_selected_options != null ? this.options.display_selected_options : true;
            return this.display_disabled_options = this.options.display_disabled_options != null ? this.options.display_disabled_options : true;
        };

        AbstractChosen.prototype.set_default_text = function () {
            if (this.form_field.getAttribute("data-placeholder")) {
                this.default_text = this.form_field.getAttribute("data-placeholder");
            } else if (this.is_multiple) {
                this.default_text = this.options.placeholder_text_multiple || this.options.placeholder_text || AbstractChosen.default_multiple_text;
            } else {
                this.default_text = this.options.placeholder_text_single || this.options.placeholder_text || AbstractChosen.default_single_text;
            }
            return this.results_none_found = this.form_field.getAttribute("data-no_results_text") || this.options.no_results_text || AbstractChosen.default_no_result_text;
        };

        AbstractChosen.prototype.mouse_enter = function () {
            return this.mouse_on_container = true;
        };

        AbstractChosen.prototype.mouse_leave = function () {
            return this.mouse_on_container = false;
        };

        AbstractChosen.prototype.input_focus = function (evt) {
            var _this = this;
            if (this.is_multiple) {
                if (!this.active_field) {
                    return setTimeout((function () {
                        return _this.container_mousedown();
                    }), 50);
                }
            } else {
                if (!this.active_field) {
                    return this.activate_field();
                }
            }
        };

        AbstractChosen.prototype.input_blur = function (evt) {
            var _this = this;
            if (!this.mouse_on_container) {
                this.active_field = false;
                return setTimeout((function () {
                    return _this.blur_test();
                }), 100);
            }
        };

        AbstractChosen.prototype.results_option_build = function (options) {
            var content, data, _i, _len, _ref;
            content = '';
            _ref = this.results_data;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                data = _ref[_i];
                if (data.group) {
                    content += this.result_add_group(data);
                } else {
                    content += this.result_add_option(data);
                }
                if (options != null ? options.first : void 0) {
                    if (data.selected && this.is_multiple) {
                        this.choice_build(data);
                    } else if (data.selected && !this.is_multiple) {
                        this.single_set_selected_text(data.text);
                    }
                }
            }
            return content;
        };

        AbstractChosen.prototype.result_add_option = function (option) {
            var classes, option_el;
            if (!option.search_match) {
                return '';
            }
            if (!this.include_option_in_results(option)) {
                return '';
            }
            classes = [];
            if (!option.disabled && !(option.selected && this.is_multiple)) {
                classes.push("active-result");
            }
            if (option.disabled && !(option.selected && this.is_multiple)) {
                classes.push("disabled-result");
            }
            if (option.selected) {
                classes.push("result-selected");
            }
            if (option.group_array_index != null) {
                classes.push("group-option");
            }
            if (option.classes !== "") {
                classes.push(option.classes);
            }
            option_el = document.createElement("li");
            option_el.className = classes.join(" ");
            option_el.style.cssText = option.style;
            option_el.setAttribute("data-option-array-index", option.array_index);
            option_el.innerHTML = option.search_text;
            return this.outerHTML(option_el);
        };

        AbstractChosen.prototype.result_add_group = function (group) {
            var group_el;
            if (!(group.search_match || group.group_match)) {
                return '';
            }
            if (!(group.active_options > 0)) {
                return '';
            }
            group_el = document.createElement("li");
            group_el.className = "group-result";
            group_el.innerHTML = group.search_text;
            return this.outerHTML(group_el);
        };

        AbstractChosen.prototype.results_update_field = function () {
            this.set_default_text();
            if (!this.is_multiple) {
                this.results_reset_cleanup();
            }
            this.result_clear_highlight();
            this.results_build();
            if (this.results_showing) {
                return this.winnow_results();
            }
        };

        AbstractChosen.prototype.reset_single_select_options = function () {
            var result, _i, _len, _ref, _results;
            _ref = this.results_data;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                result = _ref[_i];
                if (result.selected) {
                    _results.push(result.selected = false);
                } else {
                    _results.push(void 0);
                }
            }
            return _results;
        };

        AbstractChosen.prototype.results_toggle = function () {
            if (this.results_showing) {
                return this.results_hide();
            } else {
                return this.results_show();
            }
        };

        AbstractChosen.prototype.results_search = function (evt) {
            if (this.results_showing) {
                return this.winnow_results();
            } else {
                return this.results_show();
            }
        };

        AbstractChosen.prototype.winnow_results = function () {
            var escapedSearchText, option, regex, regexAnchor, results, results_group, searchText, startpos, text, zregex, _i, _len, _ref;
            this.no_results_clear();
            results = 0;
            searchText = this.get_search_text();
            escapedSearchText = searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
            regexAnchor = this.search_contains ? "" : "^";
            regex = new RegExp(regexAnchor + escapedSearchText, 'i');
            zregex = new RegExp(escapedSearchText, 'i');
            _ref = this.results_data;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                option = _ref[_i];
                option.search_match = false;
                results_group = null;
                if (this.include_option_in_results(option)) {
                    if (option.group) {
                        option.group_match = false;
                        option.active_options = 0;
                    }
                    if ((option.group_array_index != null) && this.results_data[option.group_array_index]) {
                        results_group = this.results_data[option.group_array_index];
                        if (results_group.active_options === 0 && results_group.search_match) {
                            results += 1;
                        }
                        results_group.active_options += 1;
                    }
                    if (!(option.group && !this.group_search)) {
                        option.search_text = option.group ? option.label : option.html;
                        option.search_match = this.search_string_match(option.search_text, regex);
                        if (option.search_match && !option.group) {
                            results += 1;
                        }
                        if (option.search_match) {
                            if (searchText.length) {
                                startpos = option.search_text.search(zregex);
                                text = option.search_text.substr(0, startpos + searchText.length) + '</em>' + option.search_text.substr(startpos + searchText.length);
                                option.search_text = text.substr(0, startpos) + '<em>' + text.substr(startpos);
                            }
                            if (results_group != null) {
                                results_group.group_match = true;
                            }
                        } else if ((option.group_array_index != null) && this.results_data[option.group_array_index].search_match) {
                            option.search_match = true;
                        }
                    }
                }
            }
            this.result_clear_highlight();
            if (results < 1 && searchText.length) {
                this.update_results_content("");
                return this.no_results(searchText);
            } else {
                this.update_results_content(this.results_option_build());
                return this.winnow_results_set_highlight();
            }
        };

        AbstractChosen.prototype.search_string_match = function (search_string, regex) {
            var part, parts, _i, _len;
            if (regex.test(search_string)) {
                return true;
            } else if (this.enable_split_word_search && (search_string.indexOf(" ") >= 0 || search_string.indexOf("[") === 0)) {
                parts = search_string.replace(/\[|\]/g, "").split(" ");
                if (parts.length) {
                    for (_i = 0, _len = parts.length; _i < _len; _i++) {
                        part = parts[_i];
                        if (regex.test(part)) {
                            return true;
                        }
                    }
                }
            }
        };

        AbstractChosen.prototype.choices_count = function () {
            var option, _i, _len, _ref;
            if (this.selected_option_count != null) {
                return this.selected_option_count;
            }
            this.selected_option_count = 0;
            _ref = this.form_field.options;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                option = _ref[_i];
                if (option.selected) {
                    this.selected_option_count += 1;
                }
            }
            return this.selected_option_count;
        };

        AbstractChosen.prototype.choices_click = function (evt) {
            evt.preventDefault();
            if (!(this.results_showing || this.is_disabled)) {
                return this.results_show();
            }
        };

        AbstractChosen.prototype.keyup_checker = function (evt) {
            var stroke, _ref;
            stroke = (_ref = evt.which) != null ? _ref : evt.keyCode;
            this.search_field_scale();
            switch (stroke) {
                case 8:
                    if (this.is_multiple && this.backstroke_length < 1 && this.choices_count() > 0) {
                        return this.keydown_backstroke();
                    } else if (!this.pending_backstroke) {
                        this.result_clear_highlight();
                        return this.results_search();
                    }
                    break;
                case 13:
                    evt.preventDefault();
                    if (this.results_showing) {
                        return this.result_select(evt);
                    }
                    break;
                case 27:
                    if (this.results_showing) {
                        this.results_hide();
                    }
                    return true;
                case 9:
                case 38:
                case 40:
                case 16:
                case 91:
                case 17:
                    break;
                default:
                    return this.results_search();
            }
        };

        AbstractChosen.prototype.clipboard_event_checker = function (evt) {
            var _this = this;
            return setTimeout((function () {
                return _this.results_search();
            }), 50);
        };

        AbstractChosen.prototype.container_width = function () {
            if (this.options.width != null) {
                return this.options.width;
            } else {
                return "" + this.form_field.offsetWidth + "px";
            }
        };

        AbstractChosen.prototype.include_option_in_results = function (option) {
            if (this.is_multiple && (!this.display_selected_options && option.selected)) {
                return false;
            }
            if (!this.display_disabled_options && option.disabled) {
                return false;
            }
            if (option.empty) {
                return false;
            }
            return true;
        };

        AbstractChosen.prototype.search_results_touchstart = function (evt) {
            this.touch_started = true;
            return this.search_results_mouseover(evt);
        };

        AbstractChosen.prototype.search_results_touchmove = function (evt) {
            this.touch_started = false;
            return this.search_results_mouseout(evt);
        };

        AbstractChosen.prototype.search_results_touchend = function (evt) {
            if (this.touch_started) {
                return this.search_results_mouseup(evt);
            }
        };

        AbstractChosen.prototype.outerHTML = function (element) {
            var tmp;
            if (element.outerHTML) {
                return element.outerHTML;
            }
            tmp = document.createElement("div");
            tmp.appendChild(element);
            return tmp.innerHTML;
        };

        AbstractChosen.browser_is_supported = function () {
            //fixed 05-10-2016
            //https://github.com/harvesthq/chosen/pull/1388
            //http://clip2net.com/s/3CYjCR5
            return true;
            //***
            if (window.navigator.appName === "Microsoft Internet Explorer") {
                return document.documentMode >= 8;
            }
            if (/iP(od|hone)/i.test(window.navigator.userAgent)) {
                return false;
            }
            if (/Android/i.test(window.navigator.userAgent)) {
                if (/Mobile/i.test(window.navigator.userAgent)) {
                    return false;
                }
            }
            return true;
        };

        AbstractChosen.default_multiple_text = "Select Some Options";

        AbstractChosen.default_single_text = "Select an Option";

        AbstractChosen.default_no_result_text = "No results match";

        return AbstractChosen;

    })();

    $ = jQuery;

    $.fn.extend({
        chosen: function (options) {
            if (!AbstractChosen.browser_is_supported()) {
                return this;
            }
            return this.each(function (input_field) {
                var $this, chosen;
                $this = $(this);
                chosen = $this.data('chosen');
                if (options === 'destroy' && chosen) {
                    chosen.destroy();
                } else if (!chosen) {
                    $this.data('chosen', new Chosen(this, options));
                }
            });
        }
    });

    Chosen = (function (_super) {
        __extends(Chosen, _super);

        function Chosen() {
            _ref = Chosen.__super__.constructor.apply(this, arguments);
            return _ref;
        }

        Chosen.prototype.setup = function () {
            this.form_field_jq = $(this.form_field);
            this.current_selectedIndex = this.form_field.selectedIndex;
            return this.is_rtl = this.form_field_jq.hasClass("chosen-rtl");
        };

        Chosen.prototype.set_up_html = function () {
            var container_classes, container_props;
            container_classes = ["chosen-container"];
            container_classes.push("chosen-container-" + (this.is_multiple ? "multi" : "single"));
            if (this.inherit_select_classes && this.form_field.className) {
                container_classes.push(this.form_field.className);
            }
            if (this.is_rtl) {
                container_classes.push("chosen-rtl");
            }
            container_props = {
                'class': container_classes.join(' '),
                'style': "width: " + (this.container_width()) + ";",
                'title': this.form_field.title
            };
            if (this.form_field.id.length) {
                container_props.id = this.form_field.id.replace(/[^\w]/g, '_') + "_chosen";
            }
            this.container = $("<div />", container_props);
            if (this.is_multiple) {
                this.container.html('<ul class="chosen-choices"><li class="search-field"><input type="text" value="' + this.default_text + '" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chosen-drop"><ul class="chosen-results"></ul></div>');
            } else {
                this.container.html('<a class="chosen-single chosen-default" tabindex="-1"><span>' + this.default_text + '</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off" /></div><ul class="chosen-results"></ul></div>');
            }
            this.form_field_jq.hide().after(this.container);
            this.dropdown = this.container.find('div.chosen-drop').first();
            this.search_field = this.container.find('input').first();
            this.search_results = this.container.find('ul.chosen-results').first();
            this.search_field_scale();
            this.search_no_results = this.container.find('li.no-results').first();
            if (this.is_multiple) {
                this.search_choices = this.container.find('ul.chosen-choices').first();
                this.search_container = this.container.find('li.search-field').first();
            } else {
                this.search_container = this.container.find('div.chosen-search').first();
                this.selected_item = this.container.find('.chosen-single').first();
            }
            this.results_build();
            this.set_tab_index();
            this.set_label_behavior();
            return this.form_field_jq.trigger("chosen:ready", {
                chosen: this
            });
        };

        Chosen.prototype.register_observers = function () {
            var _this = this;
            this.container.bind('mousedown.chosen', function (evt) {
                _this.container_mousedown(evt);
            });
            this.container.bind('mouseup.chosen', function (evt) {
                _this.container_mouseup(evt);
            });
            this.container.bind('mouseenter.chosen', function (evt) {
                _this.mouse_enter(evt);
            });
            this.container.bind('mouseleave.chosen', function (evt) {
                _this.mouse_leave(evt);
            });
            this.search_results.bind('mouseup.chosen', function (evt) {
                _this.search_results_mouseup(evt);
            });
            this.search_results.bind('mouseover.chosen', function (evt) {
                _this.search_results_mouseover(evt);
            });
            this.search_results.bind('mouseout.chosen', function (evt) {
                _this.search_results_mouseout(evt);
            });
            this.search_results.bind('mousewheel.chosen DOMMouseScroll.chosen', function (evt) {
                _this.search_results_mousewheel(evt);
            });
            this.search_results.bind('touchstart.chosen', function (evt) {
                _this.search_results_touchstart(evt);
            });
            this.search_results.bind('touchmove.chosen', function (evt) {
                _this.search_results_touchmove(evt);
            });
            this.search_results.bind('touchend.chosen', function (evt) {
                _this.search_results_touchend(evt);
            });
            this.form_field_jq.bind("chosen:updated.chosen", function (evt) {
                _this.results_update_field(evt);
            });
            this.form_field_jq.bind("chosen:activate.chosen", function (evt) {
                _this.activate_field(evt);
            });
            this.form_field_jq.bind("chosen:open.chosen", function (evt) {
                _this.container_mousedown(evt);
            });
            this.form_field_jq.bind("chosen:close.chosen", function (evt) {
                _this.input_blur(evt);
            });
            this.search_field.bind('blur.chosen', function (evt) {
                _this.input_blur(evt);
            });
            this.search_field.bind('keyup.chosen', function (evt) {
                _this.keyup_checker(evt);
            });
            this.search_field.bind('keydown.chosen', function (evt) {
                _this.keydown_checker(evt);
            });
            this.search_field.bind('focus.chosen', function (evt) {
                _this.input_focus(evt);
            });
            this.search_field.bind('cut.chosen', function (evt) {
                _this.clipboard_event_checker(evt);
            });
            this.search_field.bind('paste.chosen', function (evt) {
                _this.clipboard_event_checker(evt);
            });
            if (this.is_multiple) {
                return this.search_choices.bind('click.chosen', function (evt) {
                    _this.choices_click(evt);
                });
            } else {
                return this.container.bind('click.chosen', function (evt) {
                    evt.preventDefault();
                });
            }
        };

        Chosen.prototype.destroy = function () {
            $(this.container[0].ownerDocument).unbind("click.chosen", this.click_test_action);
            if (this.search_field[0].tabIndex) {
                this.form_field_jq[0].tabIndex = this.search_field[0].tabIndex;
            }
            this.container.remove();
            this.form_field_jq.removeData('chosen');
            return this.form_field_jq.show();
        };

        Chosen.prototype.search_field_disabled = function () {
            this.is_disabled = this.form_field_jq[0].disabled;
            if (this.is_disabled) {
                this.container.addClass('chosen-disabled');
                this.search_field[0].disabled = true;
                if (!this.is_multiple) {
                    this.selected_item.unbind("focus.chosen", this.activate_action);
                }
                return this.close_field();
            } else {
                this.container.removeClass('chosen-disabled');
                this.search_field[0].disabled = false;
                if (!this.is_multiple) {
                    return this.selected_item.bind("focus.chosen", this.activate_action);
                }
            }
        };

        Chosen.prototype.container_mousedown = function (evt) {
            if (!this.is_disabled) {
                if (evt && evt.type === "mousedown" && !this.results_showing) {
                    evt.preventDefault();
                }
                if (!((evt != null) && ($(evt.target)).hasClass("search-choice-close"))) {
                    if (!this.active_field) {
                        if (this.is_multiple) {
                            this.search_field.val("");
                        }
                        $(this.container[0].ownerDocument).bind('click.chosen', this.click_test_action);
                        this.results_show();
                    } else if (!this.is_multiple && evt && (($(evt.target)[0] === this.selected_item[0]) || $(evt.target).parents("a.chosen-single").length)) {
                        evt.preventDefault();
                        this.results_toggle();
                    }
                    return this.activate_field();
                }
            }
        };

        Chosen.prototype.container_mouseup = function (evt) {
            if (evt.target.nodeName === "ABBR" && !this.is_disabled) {
                return this.results_reset(evt);
            }
        };

        Chosen.prototype.search_results_mousewheel = function (evt) {
            var delta;
            if (evt.originalEvent) {
                delta = -evt.originalEvent.wheelDelta || evt.originalEvent.detail;
            }
            if (delta != null) {
                evt.preventDefault();
                if (evt.type === 'DOMMouseScroll') {
                    delta = delta * 40;
                }
                return this.search_results.scrollTop(delta + this.search_results.scrollTop());
            }
        };

        Chosen.prototype.blur_test = function (evt) {
            if (!this.active_field && this.container.hasClass("chosen-container-active")) {
                return this.close_field();
            }
        };

        Chosen.prototype.close_field = function () {
            $(this.container[0].ownerDocument).unbind("click.chosen", this.click_test_action);
            this.active_field = false;
            this.results_hide();
            this.container.removeClass("chosen-container-active");
            this.clear_backstroke();
            this.show_search_field_default();
            return this.search_field_scale();
        };

        Chosen.prototype.activate_field = function () {
            this.container.addClass("chosen-container-active");
            this.active_field = true;
            this.search_field.val(this.search_field.val());
            return this.search_field.focus();
        };

        Chosen.prototype.test_active_click = function (evt) {
            var active_container;
            active_container = $(evt.target).closest('.chosen-container');
            if (active_container.length && this.container[0] === active_container[0]) {
                return this.active_field = true;
            } else {
                return this.close_field();
            }
        };

        Chosen.prototype.results_build = function () {
            this.parsing = true;
            this.selected_option_count = null;
            this.results_data = SelectParser.select_to_array(this.form_field);
            if (this.is_multiple) {
                this.search_choices.find("li.search-choice").remove();
            } else if (!this.is_multiple) {
                this.single_set_selected_text();
                if (this.disable_search || this.form_field.options.length <= this.disable_search_threshold) {
                    this.search_field[0].readOnly = true;
                    this.container.addClass("chosen-container-single-nosearch");
                } else {
                    this.search_field[0].readOnly = false;
                    this.container.removeClass("chosen-container-single-nosearch");
                }
            }
            this.update_results_content(this.results_option_build({
                first: true
            }));
            this.search_field_disabled();
            this.show_search_field_default();
            this.search_field_scale();
            return this.parsing = false;
        };

        Chosen.prototype.result_do_highlight = function (el) {
            var high_bottom, high_top, maxHeight, visible_bottom, visible_top;
            if (el.length) {
                this.result_clear_highlight();
                this.result_highlight = el;
                this.result_highlight.addClass("highlighted");
                maxHeight = parseInt(this.search_results.css("maxHeight"), 10);
                visible_top = this.search_results.scrollTop();
                visible_bottom = maxHeight + visible_top;
                high_top = this.result_highlight.position().top + this.search_results.scrollTop();
                high_bottom = high_top + this.result_highlight.outerHeight();
                if (high_bottom >= visible_bottom) {
                    return this.search_results.scrollTop((high_bottom - maxHeight) > 0 ? high_bottom - maxHeight : 0);
                } else if (high_top < visible_top) {
                    return this.search_results.scrollTop(high_top);
                }
            }
        };

        Chosen.prototype.result_clear_highlight = function () {
            if (this.result_highlight) {
                this.result_highlight.removeClass("highlighted");
            }
            return this.result_highlight = null;
        };

        Chosen.prototype.results_show = function () {
            if (this.is_multiple && this.max_selected_options <= this.choices_count()) {
                this.form_field_jq.trigger("chosen:maxselected", {
                    chosen: this
                });
                return false;
            }
            this.container.addClass("chosen-with-drop");
            this.results_showing = true;
            this.search_field.focus();
            this.search_field.val(this.search_field.val());
            this.winnow_results();
            return this.form_field_jq.trigger("chosen:showing_dropdown", {
                chosen: this
            });
        };

        Chosen.prototype.update_results_content = function (content) {
            return this.search_results.html(content);
        };

        Chosen.prototype.results_hide = function () {
            if (this.results_showing) {
                this.result_clear_highlight();
                this.container.removeClass("chosen-with-drop");
                this.form_field_jq.trigger("chosen:hiding_dropdown", {
                    chosen: this
                });
            }
            return this.results_showing = false;
        };

        Chosen.prototype.set_tab_index = function (el) {
            var ti;
            if (this.form_field.tabIndex) {
                ti = this.form_field.tabIndex;
                this.form_field.tabIndex = -1;
                return this.search_field[0].tabIndex = ti;
            }
        };

        Chosen.prototype.set_label_behavior = function () {
            var _this = this;
            this.form_field_label = this.form_field_jq.parents("label");
            if (!this.form_field_label.length && this.form_field.id.length) {
                this.form_field_label = $("label[for='" + this.form_field.id + "']");
            }
            if (this.form_field_label.length > 0) {
                return this.form_field_label.bind('click.chosen', function (evt) {
                    if (_this.is_multiple) {
                        return _this.container_mousedown(evt);
                    } else {
                        return _this.activate_field();
                    }
                });
            }
        };

        Chosen.prototype.show_search_field_default = function () {
            if (this.is_multiple && this.choices_count() < 1 && !this.active_field) {
                this.search_field.val(this.default_text);
                return this.search_field.addClass("default");
            } else {
                this.search_field.val("");
                return this.search_field.removeClass("default");
            }
        };

        Chosen.prototype.search_results_mouseup = function (evt) {
            var target;
            target = $(evt.target).hasClass("active-result") ? $(evt.target) : $(evt.target).parents(".active-result").first();
            if (target.length) {
                this.result_highlight = target;
                this.result_select(evt);
                return this.search_field.focus();
            }
        };

        Chosen.prototype.search_results_mouseover = function (evt) {
            var target;
            target = $(evt.target).hasClass("active-result") ? $(evt.target) : $(evt.target).parents(".active-result").first();
            if (target) {
                return this.result_do_highlight(target);
            }
        };

        Chosen.prototype.search_results_mouseout = function (evt) {
            if ($(evt.target).hasClass("active-result" || $(evt.target).parents('.active-result').first())) {
                return this.result_clear_highlight();
            }
        };

        Chosen.prototype.choice_build = function (item) {
            var choice, close_link,
                    _this = this;
            choice = $('<li />', {
                "class": "search-choice"
            }).html("<span>" + item.html + "</span>");
            if (item.disabled) {
                choice.addClass('search-choice-disabled');
            } else {
                close_link = $('<a />', {
                    "class": 'search-choice-close',
                    'data-option-array-index': item.array_index
                });
                close_link.bind('click.chosen', function (evt) {
                    return _this.choice_destroy_link_click(evt);
                });
                choice.append(close_link);
            }
            return this.search_container.before(choice);
        };

        Chosen.prototype.choice_destroy_link_click = function (evt) {
            evt.preventDefault();
            evt.stopPropagation();
            if (!this.is_disabled) {
                return this.choice_destroy($(evt.target));
            }
        };

        Chosen.prototype.choice_destroy = function (link) {
            if (this.result_deselect(link[0].getAttribute("data-option-array-index"))) {
                this.show_search_field_default();
                if (this.is_multiple && this.choices_count() > 0 && this.search_field.val().length < 1) {
                    this.results_hide();
                }
                link.parents('li').first().remove();
                return this.search_field_scale();
            }
        };

        Chosen.prototype.results_reset = function () {
            this.reset_single_select_options();
            this.form_field.options[0].selected = true;
            this.single_set_selected_text();
            this.show_search_field_default();
            this.results_reset_cleanup();
            this.form_field_jq.trigger("change");
            if (this.active_field) {
                return this.results_hide();
            }
        };

        Chosen.prototype.results_reset_cleanup = function () {
            this.current_selectedIndex = this.form_field.selectedIndex;
            return this.selected_item.find("abbr").remove();
        };

        Chosen.prototype.result_select = function (evt) {
            var high, item;
            if (this.result_highlight) {
                high = this.result_highlight;
                this.result_clear_highlight();
                if (this.is_multiple && this.max_selected_options <= this.choices_count()) {
                    this.form_field_jq.trigger("chosen:maxselected", {
                        chosen: this
                    });
                    return false;
                }
                if (this.is_multiple) {
                    high.removeClass("active-result");
                } else {
                    this.reset_single_select_options();
                }
                item = this.results_data[high[0].getAttribute("data-option-array-index")];
                item.selected = true;
                this.form_field.options[item.options_index].selected = true;
                this.selected_option_count = null;
                if (this.is_multiple) {
                    this.choice_build(item);
                } else {
                    this.single_set_selected_text(item.text);
                }
                if (!((evt.metaKey || evt.ctrlKey) && this.is_multiple)) {
                    this.results_hide();
                }
                this.search_field.val("");
                if (this.is_multiple || this.form_field.selectedIndex !== this.current_selectedIndex) {
                    this.form_field_jq.trigger("change", {
                        'selected': this.form_field.options[item.options_index].value
                    });
                }
                this.current_selectedIndex = this.form_field.selectedIndex;
                evt.preventDefault();
                evt.stopPropagation();
                return this.search_field_scale();
            }
        };

        Chosen.prototype.single_set_selected_text = function (text) {
            if (text == null) {
                text = this.default_text;
            }
            if (text === this.default_text) {
                this.selected_item.addClass("chosen-default");
            } else {
                this.single_deselect_control_build();
                this.selected_item.removeClass("chosen-default");
            }
            return this.selected_item.find("span").text(text);
        };

        Chosen.prototype.result_deselect = function (pos) {
            var result_data;
            result_data = this.results_data[pos];
            if (!this.form_field.options[result_data.options_index].disabled) {
                result_data.selected = false;
                this.form_field.options[result_data.options_index].selected = false;
                this.selected_option_count = null;
                this.result_clear_highlight();
                if (this.results_showing) {
                    this.winnow_results();
                }
                this.form_field_jq.trigger("change", {
                    deselected: this.form_field.options[result_data.options_index].value
                });
                this.search_field_scale();
                return true;
            } else {
                return false;
            }
        };

        Chosen.prototype.single_deselect_control_build = function () {
            if (!this.allow_single_deselect) {
                return;
            }
            if (!this.selected_item.find("abbr").length) {
                this.selected_item.find("span").first().after("<abbr class=\"search-choice-close\"></abbr>");
            }
            return this.selected_item.addClass("chosen-single-with-deselect");
        };

        Chosen.prototype.get_search_text = function () {
            if (this.search_field.val() === this.default_text) {
                return "";
            } else {
                return $('<div/>').text($.trim(this.search_field.val())).html();
            }
        };

        Chosen.prototype.winnow_results_set_highlight = function () {
            var do_high, selected_results;
            selected_results = !this.is_multiple ? this.search_results.find(".result-selected.active-result") : [];
            do_high = selected_results.length ? selected_results.first() : this.search_results.find(".active-result").first();
            if (do_high != null) {
                return this.result_do_highlight(do_high);
            }
        };

        Chosen.prototype.no_results = function (terms) {
            var no_results_html;
            no_results_html = $('<li class="no-results">' + this.results_none_found + ' "<span></span>"</li>');
            no_results_html.find("span").first().html(terms);
            this.search_results.append(no_results_html);
            return this.form_field_jq.trigger("chosen:no_results", {
                chosen: this
            });
        };

        Chosen.prototype.no_results_clear = function () {
            return this.search_results.find(".no-results").remove();
        };

        Chosen.prototype.keydown_arrow = function () {
            var next_sib;
            if (this.results_showing && this.result_highlight) {
                next_sib = this.result_highlight.nextAll("li.active-result").first();
                if (next_sib) {
                    return this.result_do_highlight(next_sib);
                }
            } else {
                return this.results_show();
            }
        };

        Chosen.prototype.keyup_arrow = function () {
            var prev_sibs;
            if (!this.results_showing && !this.is_multiple) {
                return this.results_show();
            } else if (this.result_highlight) {
                prev_sibs = this.result_highlight.prevAll("li.active-result");
                if (prev_sibs.length) {
                    return this.result_do_highlight(prev_sibs.first());
                } else {
                    if (this.choices_count() > 0) {
                        this.results_hide();
                    }
                    return this.result_clear_highlight();
                }
            }
        };

        Chosen.prototype.keydown_backstroke = function () {
            var next_available_destroy;
            if (this.pending_backstroke) {
                this.choice_destroy(this.pending_backstroke.find("a").first());
                return this.clear_backstroke();
            } else {
                next_available_destroy = this.search_container.siblings("li.search-choice").last();
                if (next_available_destroy.length && !next_available_destroy.hasClass("search-choice-disabled")) {
                    this.pending_backstroke = next_available_destroy;
                    if (this.single_backstroke_delete) {
                        return this.keydown_backstroke();
                    } else {
                        return this.pending_backstroke.addClass("search-choice-focus");
                    }
                }
            }
        };

        Chosen.prototype.clear_backstroke = function () {
            if (this.pending_backstroke) {
                this.pending_backstroke.removeClass("search-choice-focus");
            }
            return this.pending_backstroke = null;
        };

        Chosen.prototype.keydown_checker = function (evt) {
            var stroke, _ref1;
            stroke = (_ref1 = evt.which) != null ? _ref1 : evt.keyCode;
            this.search_field_scale();
            if (stroke !== 8 && this.pending_backstroke) {
                this.clear_backstroke();
            }
            switch (stroke) {
                case 8:
                    this.backstroke_length = this.search_field.val().length;
                    break;
                case 9:
                    if (this.results_showing && !this.is_multiple) {
                        this.result_select(evt);
                    }
                    this.mouse_on_container = false;
                    break;
                case 13:
                    evt.preventDefault();
                    break;
                case 38:
                    evt.preventDefault();
                    this.keyup_arrow();
                    break;
                case 40:
                    evt.preventDefault();
                    this.keydown_arrow();
                    break;
            }
        };

        Chosen.prototype.search_field_scale = function () {
            var div, f_width, h, style, style_block, styles, w, _i, _len;
            if (this.is_multiple) {
                h = 0;
                w = 0;
                style_block = "position:absolute; left: -1000px; top: -1000px; display:none;";
                styles = ['font-size', 'font-style', 'font-weight', 'font-family', 'line-height', 'text-transform', 'letter-spacing'];
                for (_i = 0, _len = styles.length; _i < _len; _i++) {
                    style = styles[_i];
                    style_block += style + ":" + this.search_field.css(style) + ";";
                }
                div = $('<div />', {
                    'style': style_block
                });
                div.text(this.search_field.val());
                $('body').append(div);
                w = div.width() + 25;
                div.remove();
                f_width = this.container.outerWidth();
                if (w > f_width - 10) {
                    w = f_width - 10;
                }
                return this.search_field.css({
                    'width': w + 'px'
                });
            }
        };

        return Chosen;

    })(AbstractChosen);

}).call(this);

;!function(t,r){"use strict";function i(i,e,o){var s=this,n=i.get(0);s.duration=e.duration,s.opacity=e.opacity,s.isShown=!1,s.jqTargetOrg=i,t.isWindow(n)||9===n.nodeType?s.jqTarget=t("body"):"iframe"===n.nodeName.toLowerCase()||"frame"===n.nodeName.toLowerCase()?(s.jqWin=t(n.contentWindow),s.elmDoc=n.contentWindow.document,s.jqTarget=t("body",s.elmDoc),s.isFrame=!0):s.jqTarget=i,s.jqWin=s.jqWin||t(window),s.elmDoc=s.elmDoc||document,s.isBody="body"===s.jqTarget.get(0).nodeName.toLowerCase(),o&&(o.jqProgress&&(o.timer&&clearTimeout(o.timer),o.jqProgress.remove(),delete o.jqProgress),o.reset(!0),o.jqOverlay.stop()),s.jqOverlay=(o&&o.jqOverlay||t('<div class="'+d+'" />').css({position:s.isBody?"fixed":"absolute",left:0,top:0,display:"none",cursor:"wait"}).appendTo(s.jqTarget).on("touchmove",function(){return!1})).css({backgroundColor:e.fillColor,zIndex:e.zIndex}),(s.jqProgress=e.progress===!1?r:"function"==typeof e.progress?e.progress.call(s.jqTarget,e):h(s))&&s.jqProgress.css({position:s.isBody?"fixed":"absolute",display:"none",zIndex:e.zIndex+1,cursor:"wait"}).appendTo(s.jqTarget).on("touchmove",function(){return!1}),s.callAdjust=function(t){return t.adjustProgress?function(){t.adjustProgress(),t.adjust()}:function(){t.adjust()}}(s),s.avoidFocus=function(r){return function(i){return t(r.elmDoc.activeElement).blur(),i.preventDefault(),!1}}(s),s.avoidScroll=function(t){return function(r){return function(r){r.scrollLeft(t.scrLeft).scrollTop(t.scrTop)}(t.isBody?t.jqWin:t.jqTarget),r.preventDefault(),!1}}(s),o&&(o.timer&&clearTimeout(o.timer),o=r)}function e(r,e){var o=t.extend({duration:200,opacity:.6,zIndex:9e3},e);return o.fillColor=o.fillColor||o.color||"#888",r.each(function(){var r=t(this);r.data(a,new i(r,o,r.data(a))),"function"==typeof o.show&&r.off(g,o.show).on(g,o.show),"function"==typeof o.hide&&r.off(c,o.hide).on(c,o.hide)})}function o(r,i){return r.each(function(){var r,o=t(this);(i||!(r=o.data(a)))&&(r=e(o,i).data(a)),r.show()})}function s(r){return r.each(function(){var r=t(this).data(a);r&&r.hide()})}function n(t,i,o){var s,n=t.length?t.eq(0):r;if(n&&(s=n.data(a)||e(n).data(a),s.hasOwnProperty(i)))return null!=o&&(s[i]=o),s[i]}var a="plainOverlay",d=a.toLowerCase(),g=d+"show",c=d+"hide",h=function(){function i(i,e,o,s){return s=s===r?";":s,t.map(i,function(r){return t.map(e,function(t){return(o||"")+t+r}).join(s)}).join(s)}var e,o="jQuery-"+a,s=["-webkit-","-moz-","-ms-","-o-",""],n=o+"-progress",d="."+n+"{"+i(["box-sizing:border-box"],["-webkit-","-moz-",""])+";width:100%;height:100%;border-top:3px solid #17f29b;"+i(["border-radius:50%"],s)+";-webkit-tap-highlight-color:rgba(0,0,0,0);transform:translateZ(0);box-shadow:0 0 1px rgba(0,0,0,0);"+i(["animation-name:"+o+"-spin","animation-duration:1s","animation-timing-function:linear","animation-iteration-count:infinite"],s)+"}"+i(["keyframes "+o+"-spin{from{"+i(["transform:rotate(0deg)"],s)+"}to{"+i(["transform:rotate(360deg)"],s)+"}}"],s,"@","")+"."+n+"-legacy{width:100%;height:50%;padding-top:25%;text-align:center;white-space:nowrap;*zoom:1}."+n+"-legacy:after,."+n+'-legacy:before{content:" ";display:table}.'+n+"-legacy:after{clear:both}."+n+"-legacy div{width:18%;height:100%;margin:0 1%;background-color:#17f29b;float:left;visibility:hidden}."+n+"-1 div."+n+"-1,."+n+"-2 div."+n+"-1,."+n+"-2 div."+n+"-2,."+n+"-3 div."+n+"-1,."+n+"-3 div."+n+"-2,."+n+"-3 div."+n+"-3{visibility:visible}",g=function(){var t=Math.min(300,.9*(this.isBody?Math.min(this.jqWin.width(),this.jqWin.height()):Math.min(this.jqTarget.innerWidth(),this.jqTarget.innerHeight())));this.jqProgress.width(t).height(t),this.showProgress||this.jqProgress.children("."+n).css("borderTopWidth",Math.max(3,t/30))},c=function(t){var r=this;r.timer&&clearTimeout(r.timer),r.progressCnt&&r.jqProgress.removeClass(n+"-"+r.progressCnt),r.isShown&&(r.progressCnt=!t&&r.progressCnt<3?r.progressCnt+1:0,r.progressCnt&&r.jqProgress.addClass(n+"-"+r.progressCnt),r.timer=setTimeout(function(){r.showProgress()},500))};return function(i){var s,a;return"boolean"!=typeof e&&(e=function(){function t(t,r){return!!~(""+t).indexOf(r)}function i(i){var e;for(e in i)if(!t(i[e],"-")&&a[i[e]]!==r)return!0;return!1}function e(t){var r=t.charAt(0).toUpperCase()+t.slice(1),e=(t+" "+g.join(r+" ")+r).split(" ");return i(e)}var o,s,n=document.createElement("modernizr"),a=n.style,d="Webkit Moz O ms",g=d.split(" "),c={},h={}.hasOwnProperty,l=h!==r&&h.call!==r?function(t,r){return h.call(t,r)}:function(t,i){return i in t&&t.constructor.prototype[i]===r};c.borderradius=function(){return e("borderRadius")},c.cssanimations=function(){return e("animationName")},c.csstransforms=function(){return!!e("transform")},o=!1;for(s in c)if(l(c,s)&&!c[s]()){o=!0;break}return a.cssText="",n=null,o}()),i.elmDoc.getElementById(o)||(i.elmDoc.createStyleSheet?(a=i.elmDoc.createStyleSheet(),a.owningElement.id=o,a.cssText=d):(a=(i.elmDoc.getElementsByTagName("head")[0]||i.elmDoc.documentElement).appendChild(i.elmDoc.createElement("style")),a.type="text/css",a.id=o,a.textContent=d)),e?(s=t('<div><div class="'+n+'-legacy"><div class="'+n+'-3" /><div class="'+n+'-2" /><div class="'+n+'-1" /><div class="'+n+'-2" /><div class="'+n+'-3" /></div></div>'),i.showProgress=c):s=t('<div><div class="'+n+'" /></div>'),i.adjustProgress=g,s}}();i.prototype.show=function(){var i,e,o,s,n,a=this;a.reset(!0),i=a.jqTarget.get(0).style,a.orgPosition=i.position,e=a.jqTarget.css("position"),"relative"!==e&&"absolute"!==e&&"fixed"!==e&&a.jqTarget.css("position","relative"),a.orgOverflow=i.overflow,o=a.jqTarget.prop("clientWidth"),s=a.jqTarget.prop("clientHeight"),a.jqTarget.css("overflow","hidden"),o-=a.jqTarget.prop("clientWidth"),s-=a.jqTarget.prop("clientHeight"),a.addMarginR=a.addMarginB=0,0>o&&(a.addMarginR=-o),0>s&&(a.addMarginB=-s),a.isBody?(a.addMarginR&&(a.orgMarginR=i.marginRight,a.jqTarget.css("marginRight","+="+a.addMarginR)),a.addMarginB&&(a.orgMarginB=i.marginBottom,a.jqTarget.css("marginBottom","+="+a.addMarginB))):(a.addMarginR&&(a.orgMarginR=i.paddingRight,a.orgWidth=i.width),a.addMarginB&&(a.orgMarginB=i.paddingBottom,a.orgHeight=i.height)),a.jqActive=r,n=t(a.elmDoc.activeElement),a.isBody&&!a.isFrame?a.jqActive=n.blur():a.jqTarget.has(n.get(0)).length&&n.blur(),a.jqTarget.focusin(a.avoidFocus),function(t){a.scrLeft=t.scrollLeft(),a.scrTop=t.scrollTop(),t.scroll(a.avoidScroll)}(a.isBody?a.jqWin:a.jqTarget),a.jqWin.resize(a.callAdjust),a.callAdjust(),a.isShown=!0,a.jqOverlay.stop().fadeTo(a.duration,a.opacity,function(){a.jqTargetOrg.trigger(g)}),a.jqProgress&&(a.showProgress&&a.showProgress(!0),a.jqProgress.fadeIn(a.duration))},i.prototype.hide=function(){var t=this;t.isShown&&(t.jqOverlay.stop().fadeOut(t.duration,function(){t.reset(),t.jqTargetOrg.trigger(c)}),t.jqProgress&&t.jqProgress.fadeOut(t.duration))},i.prototype.adjust=function(){var t,r;this.isBody?(t=this.jqWin.width(),r=this.jqWin.height(),this.jqOverlay.width(t).height(r),this.jqProgress&&this.jqProgress.css({left:(t-this.jqProgress.outerWidth())/2,top:(r-this.jqProgress.outerHeight())/2})):(this.addMarginR&&(t=this.jqTarget.css({paddingRight:this.orgMarginR,width:this.orgWidth}).width(),this.jqTarget.css("paddingRight","+="+this.addMarginR).width(t-this.addMarginR)),this.addMarginB&&(r=this.jqTarget.css({paddingBottom:this.orgMarginB,height:this.orgHeight}).height(),this.jqTarget.css("paddingBottom","+="+this.addMarginB).height(r-this.addMarginB)),t=Math.max(this.jqTarget.prop("scrollWidth"),this.jqTarget.innerWidth()),r=Math.max(this.jqTarget.prop("scrollHeight"),this.jqTarget.innerHeight()),this.jqOverlay.width(t).height(r),this.jqProgress&&(t=this.jqTarget.innerWidth(),r=this.jqTarget.innerHeight(),this.jqProgress.css({left:(t-this.jqProgress.outerWidth())/2+this.scrLeft,top:(r-this.jqProgress.outerHeight())/2+this.scrTop})))},i.prototype.reset=function(t){var r=this;t&&(r.jqOverlay.css("display","none"),r.jqProgress&&r.jqProgress.css("display","none")),r.isShown&&(r.jqTarget.css({position:r.orgPosition,overflow:r.orgOverflow}),r.isBody?(r.addMarginR&&r.jqTarget.css("marginRight",r.orgMarginR),r.addMarginB&&r.jqTarget.css("marginBottom",r.orgMarginB)):(r.addMarginR&&r.jqTarget.css({paddingRight:r.orgMarginR,width:r.orgWidth}),r.addMarginB&&r.jqTarget.css({paddingBottom:r.orgMarginB,height:r.orgHeight})),r.jqTarget.off("focusin",r.avoidFocus),r.jqActive&&r.jqActive.length&&r.jqActive.focus(),function(t){t.off("scroll",r.avoidScroll).scrollLeft(r.scrLeft).scrollTop(r.scrTop)}(r.isBody?r.jqWin:r.jqTarget),r.jqWin.off("resize",r.callAdjust),r.isShown=!1)},t.fn[a]=function(t,r,i){return"show"===t?o(this,r):"hide"===t?s(this):"option"===t?n(this,r,i):e(this,t)}}(jQuery);
jQuery(function() {

    $.foldLeft = function(obj, iterator, memo, context) {
        var initial = (memo !== void 0);
        if (obj == null) obj = [];
        $.each(obj, function(index, value, list) {
            if (!initial) {
                memo = value;
                initial = true;
            } else {
                memo = iterator.call(context, memo, value, index, list);
            }
        });
        if (!initial) throw new TypeError("Reduce of empty array with no initial value");
        return memo;
    };

    /*
     $.sortBy = function(obj, iterator, context) {
     return $.pluck($.map(obj,
     function(value, index, list) {
     return {
     value : value,
     criteria : iterator.call(context, value, index, list)
     };
     }).sort(function(left, right) {
     var a = left.criteria, b = right.criteria;
     return a < b ? -1 : a > b ? 1 : 0;
     }), 'value');
     };

     // Convenience version of a common use case of `map`: fetching a property.
     $.pluck = function(obj, key) {
     return $.map(obj, function(value) {
     return value[key];
     });
     };

     $.groupBy = function(array, by) {
     var result = {};
     var iter = $.isFunction(by) ? by : function(obj) {
     return obj[by];
     };
     $.each(array, function(value, index) {
     var key = iter(value, index);
     (result[key] || (result[key] = [])).push(value);
     });
     return result;
     };


     $.fn.check = function(value) {
     if (value === true || value === false) {
     // Set the value of the checkbox
     return $(this).each(function() {
     this.checked = value;
     });
     } else if (value === undefined || value === 'toggle') {
     // Toggle the checkbox
     return $(this).each(function() {
     this.checked = !this.checked;
     });
     }
     };*/

    var firstSearchDone = false;

    $('#com_searchify .collapsible').collapsible();

    /**
     * Enables or disables any matching elements.
     */
    $.fn.enable = function(b) {
        if (b === undefined) {
            b = true;
        }
        return this.each(function() {
            this.disabled = !b;
        });
    };

    $.fn.doBusy = function(fn) {
        $('<span id="loading"></span>').prependTo(this);
        this.enable(false);

        var $ctx = this;
        fn(function onDoneWorking() {
            $ctx.enable();
            $ctx.parent().find('#loading').remove();
        });
    };

    Date.firstDayOfWeek = 0;
    Date.format = 'yy-mm-dd';

    $('.date-pick').datepicker({
        changeMonth: true,
        numberOfMonths: 3,
        changeYear: true,
        showWeek: true,
        maxDate: new Date().asString(),
        dateFormat: Date.format, //'yy-mm-dd',
        onSelect: function(selectedDate, instance) {
            var opt = this.id == 'start_date' ? 'minDate' : 'maxDate';
            var incr = this.id == 'start_date' ? 1 : -1;
            if (selectedDate) {
                var date = $.datepicker.parseDate(
                    instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                    selectedDate, instance.settings);

                $('.date-pick').not(this).datepicker('option', opt, date.addDays(incr).asString());
            }
        }
    });
    $('#start_date').datepicker('option', 'maxDate', String(-1));


    $('#categories').jstree({
        plugins : [
            'themes', 'html_data', 'ui', 'checkbox'
        ],
        themes: {
            theme: 'apple'
        },
        checkbox : {
            two_state: true,
            override_ui: true
        }
    })
        .bind("loaded.jstree", function (event, data) {
            $('#categories').jstree('open_all');
        });

    function printErrorMsg(it, errorMsg) {
        window.clearTimeout(window.validationTimeout);

        var msg = $('#msg');

        msg.addClass('error').html('<span>' + errorMsg + '</span>').slideDown();
        window.validationTimeout = window.setTimeout(function() {
            msg.removeClass('error').slideUp('slow').empty();
        }, 7000);
        it && window.setTimeout(function() {
            it.focus();
        }, 400);

        console.log(errorMsg);
        return false;
    }

    (function setUpValidation() {

        function validateEmpty(id, errorMsg) { //todo: add to printErrorMsg
            var it = $('#' + id),
                val = it.val();

            val = val != null ? val.trim() : '';

            if (val == '') {
                return printErrorMsg(it, errorMsg);
            }

            return true;
        }

        window.isStartDateValid = function isStartDateValid() {
            return validateEmpty('start_date', "La date de début est necessaire") && ($('#start_date').val().isDate() || printErrorMsg($('#StartDate'), "La date de début est invalide"));
        };

        window.isEndDateValid = function isEndDateValid() {
            return validateEmpty('end_date', "La date de fin est necessaire") && ($('#end_date').val().isDate() || printErrorMsg($('#EndDate'), "La date de fin est invalide"));
        };
    }).call(this);

    $('#searchform').submit(function() {
        if (window.isStartDateValid()
            && window.isEndDateValid()) $('#searchformsubmit').doBusy(doSearch);

        return false; //Dont submit normally
    });

    function doSearch(done) {

        /*
         function getSelectedAuthors() {
         var ret = [];
         $($('#authors').data('wijlist').items).each(function() {
         if (this.selected) ret.push(this['value']);
         });
         return  ret;
         }

         function getSelectedStates() {
         var ret = {};
         $('#states :checkbox')+each(function() {
         ret[this.name] = Boolean(this.checked);
         });
         return  ret;
         }



         function getSelectedCategories() {
         var ret = [];
         $('#categories').jstree('get_selected').each(function() {
         ret.push($(this).attr('data-key'));
         });
         return  ret;
         }*/

        function asCategories(articles) {
            var __done = [];
            return $.foldLeft(articles, function(categories, article) {
                var catid = article.category_id;
                if (! __done[catid]) {
                    __done[article.category_id] = true;
                    categories.push({
                        id: catid,
                        alias: article.category_alias,
                        title: article.category_title,
                        articles: $.grep(articles, function(it) {
                            return catid === it.category_id;
                        })
                    });
                }
                return categories
            }, []);
        }


        var startDate = $('#start_date').datepicker('getDate'),
            endDate = $('#end_date').datepicker('getDate'),
            //authors = getSelectedAuthors(),
            //categories = getSelectedCategories(),
            //states = getSelectedStates(),
            params = {
                search : encodeURIComponent($('#search').val()),
                start_date : encodeURIComponent(startDate),
                end_date :  encodeURIComponent(endDate),
                language: encodeURIComponent($('#language').val()),
                category: encodeURIComponent($('#category').val())
                //authors :  authors
                //states : states
                //categories :  categories
            };

        $.ajax({
            url: baseurl + (lang ? ('/' + lang) : '') + '?' + $.param({option: 'com_searchify', task: 'results.display', format: 'raw'}),
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify(params)
        }).success(
            function(data) {
                $(searchResultsToHTML({
                        categories: asCategories(data['results']),
                        article_href: function (id, alias, catid, cat_alias) {
                            return this.category_href(catid, cat_alias) + '/' + id + '-' + alias;
                        },
                        category_href: function (id, alias) {
                            return baseurl + (lang ? ('/' + lang) : '') + '/' + id + '-' + alias;
                        }
                    }
                )).appendTo($('#search-results').empty());

                //Change form title to Modifier les parametres, change submit button text to regenerer les
                firstSearchDone || $('#com_searchify .collapsible').find('h4').html('<a href="#" class="collapsible-heading-toggle"><span class="collapsible-heading-status"></span>Modify search parameters</a>');


                $('#com_searchify .collapsible-heading').trigger('collapse');
                firstSearchDone = true;

                done(true);
            }).error(function() {
                printErrorMsg(null, 'There was an error');
                done(false);
            });
    }

    $('#parent_category').change(
        function() {
            var self = $(this);
            var parent_id = Number(self.val());
            $.getJSON(
                baseurl + (lang ? ('/' + lang) : ''),
                {
                    option: 'com_searchify',
                    task: 'categories.display',
                    format: 'raw',
                    parent_id: encodeURIComponent(parent_id)
                },
                function(categories) {
                    var allcatLabel = self.find('#all_categories').html(),
                        html = '<option id="all_categories" value="0">' + allcatLabel + '</option>';

                    for (var __i = 0,__len = categories.length; __i < __len; __i++)
                        html += '<option value="' + categories[__i]['id'] + '">' + categories[__i]['title'] + '</option>';

                    $('#category').html(html);
                });
        });

    /*$('#states_all').click(function() {
     if (this.checked) {
     $('#states :checkbox').not(this).check(true);
     } else {
     $('#states :checkbox').not('.default-selected').check(false);
     }
     });*/
})
    ;

function searchResultsToHTML(results) {
    var __fn = function(__obj) {
        if (!__obj) __obj = {};
        var __out = [], __capture = function(callback) {
            var out = __out, result;
            __out = [];
            callback.call(this);
            result = __out.join('');
            __out = out;
            return __safe(result);
        }, __sanitize = function(value) {
            if (value && value.ecoSafe) {
                return value;
            } else if (typeof value !== 'undefined' && value != null) {
                return __escape(value);
            } else {
                return '';
            }
        }, __safe, __objSafe = __obj.safe, __escape = __obj.escape;
        __safe = __obj.safe = function(value) {
            if (value && value.ecoSafe) {
                return value;
            } else {
                if (!(typeof value !== 'undefined' && value != null)) value = '';
                var result = new String(value);
                result.ecoSafe = true;
                return result;
            }
        };
        if (!__escape) {
            __escape = __obj.escape = function(value) {
                return ('' + value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            };
        }
        (function() {
            (function() {
                var category, ccounter, print_article, print_category, _i, _len, _ref;
                var __bind = function(fn, me) {
                    return function() {
                        return fn.apply(me, arguments);
                    };
                };
                print_article = __bind(function(article, counter) {
                    return __capture(__bind(function() {
                        __out.push('\n  <li class="article">\n    <div class="wrapper">\n      <h3>');
                        __out.push(__sanitize(counter++));
                        __out.push('. <a href="');
                        __out.push(__sanitize(this.article_href(article.id, article.alias, article.category_id, article.category_alias)));
                        __out.push('">');
                        __out.push(__sanitize(article.title));
                        __out.push('</a>\n      </h3>\n      <p>created by <strong style="font-weight:bold;">');
                        __out.push(__sanitize(article.username));
                        __out.push('</strong>\n      <span class="date" data-date="');
                        __out.push(__sanitize(article.created));
                        __out.push('" title="');
                        __out.push(__sanitize(article.created));
                        __out.push('">');
                        __out.push(__sanitize(jQuery.relativeDate(Date.parse(article.created))));
                        return __out.push('</span></p>\n    </div>\n  </li>\n');
                    }, this));
                }, this);
                __out.push('\n');
                print_category = __bind(function(category, counter) {
                    return __capture(__bind(function() {
                        var acounter, article, _i, _len, _ref;
                        __out.push('\n  <ul class="category">\n    ');
                        __out.push(__sanitize(counter++));
                        __out.push('. <a href="');
                        __out.push(__sanitize(this.category_href(category.id, category.alias)));
                        __out.push('">');
                        __out.push(__sanitize(category.title));
                        __out.push('</a>\n    ');
                        acounter = 1;
                        __out.push('\n    ');
                        _ref = category.articles;
                        for (_i = 0,_len = _ref.length; _i < _len; _i++) {
                            article = _ref[_i];
                            __out.push('\n      ');
                            __out.push(__sanitize(print_article(article, acounter++)));
                            __out.push('\n    ');
                        }
                        return __out.push('\n  </ul>\n');
                    }, this));
                }, this);
                __out.push('\n');
                if (this.categories.length) {
                    __out.push('\n  ');
                    ccounter = 1;
                    __out.push('\n  ');
                    _ref = this.categories;
                    for (_i = 0,_len = _ref.length; _i < _len; _i++) {
                        category = _ref[_i];
                        __out.push('\n    ');
                        __out.push(__sanitize(print_category(category, ccounter++)));
                        __out.push('\n  ');
                    }
                    __out.push('\n');
                } else {
                    __out.push('\n  <div class="center">No articles</div>\n');
                }
            }).call(this);

        }).call(__obj);
        __obj.safe = __objSafe,__obj.escape = __escape;
        return __out.join('');
    };
    return __fn(results);
}
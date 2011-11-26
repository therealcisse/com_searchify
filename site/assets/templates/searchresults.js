(function() {
  this.ecoTemplates || (this.ecoTemplates = {});
  this.ecoTemplates["searchresults"] = function(__obj) {
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
        var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
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
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
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
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
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
    __obj.safe = __objSafe, __obj.escape = __escape;
    return __out.join('');
  };
}).call(this);

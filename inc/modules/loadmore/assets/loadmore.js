(function ($) {
  if (!$("#ensp_loadmore").length) return;

  var current_page = 1;
  const post_type = ajax_object["post_type"];
  const ajaxurl = ajax_object["ajax_url"];
  const elPosts = $(".ensp-ajax-posts .row-posts");

  var data = {
    action: "loadmore",
    post_type: post_type,
    page: current_page,
    max_pages: max_pages,
    query: {
      order: "",
      taxonomies: {},
      module: ajax_object["module"],
    },
  };

  //INIT FIELD
  function init() {
    var ord = url_get_param("ord");
    if (ord == null) {
      ord = "desc";
    }
    $(
      '.ensp-posts-filters [data-type="order"] input[value="' + ord + '"]'
    ).prop("checked", true);
    data.query.order = ord;

    var GetParams = {
      audience: url_get_param("audience-id"),
      topic: url_get_param("topic-id"),
      type: url_get_param("type-id"),
      program: url_get_param("program-id"),
      language: url_get_param("language-id"),
      location: url_get_param("location-id"),
    };

    Object.keys(GetParams).forEach((key) => {
      if (GetParams[key] != null) {
        $(
          '.ensp-posts-filters [data-taxonomy="' +
            key +
            '-id"] input[value="' +
            GetParams[key] +
            '"]'
        ).prop("checked", true);
        data.query.taxonomies[key] = GetParams[key];
      }
    });
  }

  init();

  function url_update_param(param, val) {
    if (val == "desc") {
      url_delete_param(param);
      return;
    }

    var url = new URL(window.location.href);
    var search_params = url.searchParams;
    search_params.set(param, val);
    url.search = search_params.toString();
    url = url.toString();
    history.pushState("", "", url);
  }

  function url_delete_param(param) {
    var url = new URL(window.location.href);
    var search_params = url.searchParams;
    search_params.delete(param);
    url.search = search_params.toString();
    url = url.toString();
    history.pushState("", "", url);
  }

  function url_get_param(param) {
    var url = new URL(window.location.href);
    return url.searchParams.get(param);
  }

  function url_init_param() {
    $('.input-group[data-type="order"]').each(function () {
      var value = $(this).find("input:checked").val();

      if (value == "desc") return;

      if (value != undefined) {
        url_update_param("ord", value);
      }
    });

    $('.input-group[data-type="taxonomy"]').each(function () {
      var tax = $(this).attr("data-taxonomy").split("_").pop();
      var value = $(this).find("input:checked").val();

      if (value != undefined) {
        url_update_param(tax, value);
      }
    });
  }

  url_init_param();

  //REMOVE
  $(".ensp-posts-filters .btn-remove").click(function () {
    $(this).parent().find("input").prop("checked", false); //uncheck this
    var tax = $(this).closest(".input-group").attr("data-taxonomy");
    delete data.query.taxonomies[tax.replace("-id", "")]; //remove array

    //url remove param
    var taxParam = tax.split("_").pop();
    url_delete_param(taxParam);

    get_ajaxposts_update(); //Display Posts
  });

  //CHANGE ORDER
  $('.ensp-posts-filters [name="order"]').change(function () {
    url_update_param("ord", this.value); //url update
    data.query.order = url_get_param("ord"); //push new val to data

    get_ajaxposts_update(); //Display Posts
  });

  //CHANGE TAXONOMY
  $('.ensp-posts-filters [data-type="taxonomy"] input:radio').change(
    function () {
      var tax = $(this).closest(".input-group").attr("data-taxonomy");
      var taxParam = tax.split("_").pop();

      url_update_param(taxParam, this.value); //url update
      data.query.taxonomies[tax.replace("-id", "")] = url_get_param(taxParam); //push new val to data

      get_ajaxposts_update(); //Display Posts
    }
  );

  //Hide/Show Button Load More
  button_load_more($("#ensp_loadmore"));

  function button_load_more(el) {
    el.text("Load more");
    if (current_page >= max_pages) {
      el.hide();
    } else {
      el.show();
    }
  }

  /**
   * AJAX ACTION
   */
  function get_ajaxposts_update() {
    current_page = 0;
    data.page = current_page;

    $.ajax({
      url: ajaxurl,
      data: data,
      type: "POST",
      success: function (response) {
        if (response) {
          elPosts.html("");
          elPosts.append(response.posts);
          current_page++;
          data.page = current_page;
          max_pages = response.max_pages;

          button_load_more($("#ensp_loadmore"));
        }
      },
    });
  }

  $("#ensp_loadmore").click(function (e) {
    e.preventDefault();
    var button = $(this);
    button.text("Loading...");

    $.ajax({
      url: ajaxurl,
      data: data,
      type: "POST",
      success: function (response) {
        if (response) {
          elPosts.append(response.posts);
          current_page++;
          data.page = current_page;
          max_pages = response.max_pages;

          button_load_more(button);
        }
      },
    });
  });
})(jQuery);

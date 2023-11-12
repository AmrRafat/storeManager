$(function () {
  "use strict";
  /* Rules:
  -- Testing
  -- General
  -- Users
  -- Cats
  -- Subcats
  -- Items
  -- Records
  -- Installments
  -- Bills
  -- Calcs
  */
  // ======================================================
  // Testing Rules
  // ======================================================
  // $("body").click(function (e) {
  //   var target = $(e.target);
  //   console.log(target[0]);
  // });
  // // ======================================================
  // General Rules
  // ======================================================
  // Hide placeholder on form focus
  $("[placeholder]")
    .focus(function () {
      $(this).attr("data-text", $(this).attr("placeholder"));
      $(this).attr("placeholder", "");
    })
    .blur(function () {
      $(this).attr("placeholder", $(this).attr("data-text"));
    });
  // Make default value of date input
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);
  var today = now.getFullYear() + "-" + month + "-" + day;
  $('input[type="date"]').val(today);
  // Add astrisk on required field (*)
  // $("input , select").each(function () {
  //   if ($(this).attr("required") == "required") {
  //     $(this).after('<span class="astrisk">*</span>');
  //   }
  // });
  // Checking items for remaining amount
  $(window).ready(function () {
    $.ajax({
      type: "POST",
      url: "includes/functions/General/checkItems.php",
      success: function (html) {
        if (html == 1) {
          $("li.settings").addClass("about");
        } else if (html1 == 0) {
          $("li.settings").removeClass("about");
        }
      },
    });
    // Preparing any cascading dropdown menus
    if (window.location.search.includes("&")) {
      var takencatid = $("#cat").val();
      var myurl = window.location.search.split("&")[1].split("=");
      if (myurl[0] == "itemcode") {
        var itemcode = myurl[1];
      }
      if (takencatid != 0 && itemcode) {
        $.ajax({
          type: "POST",
          url: "includes/functions/General/catSubcatControl.php",
          data: {
            catid1: takencatid,
            itemcode: itemcode,
          },
          success: function (html) {
            $("#subcat").html(html);
          },
        });
      } else {
        if (!($(location).attr("search").indexOf("&s=") > 0)) {
          $("#subcat").html(
            '<option value="0">برجاء اختيار القسم أولا</option>'
          );
        }
        $("#item1").html('<option value="0">برجاء اختيار القسم أولا</option>');
      }
    }
    $("#cat").on("change", function (e) {
      e.stopPropagation();
      var catid = $(this).val();
      if (catid != 0) {
        $.ajax({
          type: "POST",
          url: "includes/functions/General/catSubcatControl.php",
          data: "catid=" + catid,
          success: function (html) {
            $("#subcat").html(html);
          },
        });
      } else {
        $("#subcat").html('<option value="0">برجاء اختيار القسم أولا</option>');
        $("#item1").html('<option value="0">برجاء اختيار القسم أولا</option>');
      }
    });
    $("#subcat").on("change", function (e) {
      e.stopPropagation();
      var subcatid = $(this).val();
      if (subcatid != 0) {
        $.ajax({
          type: "POST",
          url: "includes/functions/General/catSubcatControl.php",
          data: "subcatid=" + subcatid,
          success: function (html) {
            $("#item1").html(html);
          },
        });
      } else {
        $("#item1").html(
          '<option value="0">برجاء اختيار القسم الفرعى أولا</option>'
        );
      }
    });
  });
  // Changing Data of available amount upon changing [This works in Records and Installments Pages]
  $("#code").change(function (e) {
    e.stopPropagation();
    var itemCode = $("#code").val();
    $.ajax({
      type: "POST",
      url: "includes/functions/General/codeChange.php",
      data: {
        code: itemCode,
      },
      success: function (html) {
        var neededData = JSON.parse(html);
        $(".avail").val(neededData["availAmount"]);
        $(".start .amount").attr("max", neededData["availAmount"]);
        $("#cat").val(neededData["cat_id"]);
        if ($("#cat").val() != 0) {
          $.ajax({
            type: "POST",
            url: "includes/functions/General/codeChange.php",
            data: {
              cat: neededData["cat_id"],
              subcat1: neededData["subcat_id"],
            },
            success: function (html1) {
              $("#subcat").html(html1);
              if ($("#subcat").val() != 0) {
                $.ajax({
                  type: "POST",
                  url: "includes/functions/General/codeChange.php",
                  data: {
                    subcat: neededData["subcat_id"],
                    item: neededData["item_code"],
                  },
                  success: function (html2) {
                    $("#item1").html(html2);
                  },
                });
              }
            },
          });
        }
      },
    });
  });
  $("#item1").on("click", function (e) {
    e.stopPropagation();
  });
  $("#item1").change(function (e) {
    e.stopPropagation();
    var itemCode = $("#item1").val();
    $.ajax({
      type: "POST",
      url: "includes/functions/General/codeChange.php",
      data: {
        code: itemCode,
      },
      success: function (html) {
        var neededData = JSON.parse(html);
        $(".avail").val(neededData["availAmount"]);
        $(".start .amount").attr("max", neededData["availAmount"]);
        $("#cat").val(neededData["cat_id"]);
      },
    });
  });
  $("#item1").change(function (e) {
    e.stopPropagation();
    var item = $("#item1").val();
    if (item == 0) {
      $("#code option").removeAttr("selected");
      $("#code option[value|='0']").attr("selected", "selected");
    } else {
      $("#code").val(item);
    }
  });
  // Navbar selected tab
  if ($(".title").val() == "users") {
    $(".navbar a").removeClass("active");
    $(".usersPage").addClass("active");
  } else if ($(".title").val() == "records") {
    $(".navbar a").removeClass("active");
    $(".recordsPage").addClass("active");
  } else if ($(".title").val() == "insta") {
    $(".navbar a").removeClass("active");
    $(".instaPage").addClass("active");
  }
  // ======================================================
  // Users Rules
  // ======================================================
  // NONE
  // ======================================================
  // Cats Rules
  // ======================================================
  // Showing form of adding new cat
  $(".newCat").click(function () {
    if ($(".catAdd").hasClass("active") == false) {
      $(".newCat").removeClass("btn-primary");
      $(".newCat").addClass("btn-success");
      $(".catAdd").addClass("active");
      $(".catAdd input").focus();
    }
  });
  // Hide form of adding new cat
  $(".btnEnd").click(function () {
    $(".catAdd input").val("");
    $(".catAdd").removeClass("active");
    $(".newCat").removeClass("btn-success");
    $(".newCat").addClass("btn-primary");
    if ($(".error").attr("style") == "display: block;") {
      $(".error").removeAttr("style", "display: block;");
      $(".error").attr("style", "display: none;");
    }
    if ($(".success").attr("style") == "display: block;") {
      $(".success").removeAttr("style", "display: block;");
      $(".success").attr("style", "display: none;");
    }
  });
  // Validate and submit the data into DB
  function classAdding() {
    $(".btnAdd").click(function (e) {
      e.preventDefault();
      var cat = $(".catAdd input").val();
      if (cat != "") {
        $.ajax({
          type: "POST",
          url: "includes/functions/Cats/newCat.php",
          data: {
            cat: cat,
          },
          success: function (html) {
            $(".catAdd input").focus();
            if (html == "0") {
              $(".error").removeAttr("style", "display: none;");
              $(".error").attr("style", "display: block;");
            } else {
              $(".success").removeAttr("style", "display: none;");
              $(".success").attr("style", "display: block;");
              $(".cats").html(html);
              setTimeout(function () {
                $(".success").removeAttr("style", "display: block;");
                $(".success").attr("style", "display:none;");
                $(".catAdd input").val("");
                $(".catAdd input").focus();
              }, 1000);
            }
          },
        });
      } else {
        $(".errorEmpty").removeAttr("style", "display: none;");
        $(".errorEmpty").attr("style", "display: block;");
      }
    });
  }
  classAdding();
  // Hide error upon change in input
  $(".catAdd input").keyup(function () {
    $(".error").removeAttr("style", "display: block;");
    $(".error").attr("style", "display: none;");
    $(".errorEmpty").removeAttr("style", "display: block;");
    $(".errorEmpty").attr("style", "display: none;");
  });
  // ======================================================
  // Subcats Rules
  // ======================================================
  // Showing adding subcat form
  $(".newSubcat").click(function () {
    if ($(".newSubcatForm").hasClass("active") == false) {
      $(".newSubcat").removeClass("btn-primary");
      $(".newSubcat").addClass("btn-success");
      $(".newSubcatForm").addClass("active");
    }
  });
  // Hiding adding subcat form
  $(".subcatEnd").click(function () {
    $(".newSubcatForm input").val("");
    $(".newSubcatForm select").val(0);
    $(".newSubcat").removeClass("btn-success");
    $(".newSubcat").addClass("btn-primary");
    $(".newSubcatForm").removeClass("active");
    location.reload();
  });
  // Validating and adding data to DB
  $(".subcatAdd").click(function (e) {
    e.preventDefault();
    var cat = $(".newSubcatForm select").val();
    var subcat = $(".newSubcatForm input").val();
    if (subcat != "") {
      $.ajax({
        type: "POST",
        url: "includes/functions/Subcats/newSubcat.php",
        data: {
          cat: cat,
          subcat: subcat,
        },
        success: function (html) {
          if (html == "0") {
            $(".error").removeAttr("style", "display: none;");
            $(".error").attr("style", "display: block;");
          } else if (html == "1") {
            $(".error1").removeAttr("style", "display: none;");
            $(".error1").attr("style", "display: block;");
          } else {
            $(".success").removeAttr("style", "display: none;");
            $(".success").attr("style", "display: block;");
            $(".subcatShow tbody").html(html);
            setTimeout(function () {
              $(".success").removeAttr("style", "display: block;");
              $(".success").attr("style", "display:none;");
              $(".newSubcatForm input").val("");
              $(".newSubcatForm select").val(cat);
              $(".newSubcatForm input").focus();
            }, 1000);
          }
        },
      });
    } else {
      $(".error2").removeAttr("style", "display: none;");
      $(".error2").attr("style", "display: block;");
    }
  });
  // Hiding error msgs upon any change
  $(".newSubcatForm input").keyup(function () {
    $(".error").removeAttr("style", "display: block;");
    $(".error").attr("style", "display: none;");
    $(".error1").removeAttr("style", "display: block;");
    $(".error1").attr("style", "display: none;");
    $(".error2").removeAttr("style", "display: block;");
    $(".error2").attr("style", "display: none;");
  });
  $(".newSubcatForm select").change(function () {
    $(".error").removeAttr("style", "display: block;");
    $(".error").attr("style", "display: none;");
    $(".error1").removeAttr("style", "display: block;");
    $(".error1").attr("style", "display: none;");
    $(".error2").removeAttr("style", "display: block;");
    $(".error2").attr("style", "display: none;");
  });
  // Sort By options
  // == Sort by subcats
  function subcatsSorting() {
    $("span.subCat").click(function () {
      if ($("span.subCat").hasClass("asc")) {
        $("span.subCat").removeClass("asc");
        $("span.subCat").addClass("desc");
        $.ajax({
          type: "POST",
          url: "includes/functions/Subcats/sortingSubs.php",
          data: {
            way: "desc",
          },
          success: function (html) {
            $(".subcatShow tbody").html(html);
          },
        });
      } else {
        $("span.mainCat").removeClass("asc");
        $("span.mainCat").removeClass("desc");
        $("span.subCat").removeClass("desc");
        $("span.subCat").addClass("asc");
        $.ajax({
          type: "POST",
          url: "includes/functions/Subcats/sortingSubs.php",
          data: {
            way: "asc",
          },
          success: function (html) {
            $(".subcatShow tbody").html(html);
          },
        });
      }
    });
  }
  subcatsSorting();
  // == Sort by cats
  function catsSorting() {
    $("span.mainCat").click(function () {
      if ($("span.mainCat").hasClass("asc")) {
        $("span.mainCat").removeClass("asc");
        $("span.mainCat").addClass("desc");
        $.ajax({
          type: "POST",
          url: "includes/functions/Subcats/sortingSubs.php",
          data: {
            way: "maindesc",
          },
          success: function (html) {
            $(".subcatShow tbody").html(html);
          },
        });
      } else {
        $("span.subCat").removeClass("asc");
        $("span.subCat").removeClass("desc");
        $("span.mainCat").removeClass("desc");
        $("span.mainCat").addClass("asc");
        $.ajax({
          type: "POST",
          url: "includes/functions/Subcats/sortingSubs.php",
          data: {
            way: "mainasc",
          },
          success: function (html) {
            $(".subcatShow tbody").html(html);
          },
        });
      }
    });
  }
  catsSorting();
  // ======================================================
  // Items Rules
  // ======================================================
  // Show All Items Option
  $(".showAll").on("click", function () {
    $.ajax({
      type: "POST",
      url: "includes/functions/Items/showAll.php",
      success: function (html) {
        $(".card-body").html(html);
      },
    });
  });
  // Sorting Options after showing items
  // Sorting By Codes For All Items
  $(document).on("click", "th.codes", function () {
    if ($("th.codes").hasClass("codeASC")) {
      $("th.codes").removeClass("codeASC");
      $("th.codes").addClass("codeDESC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "codeDESC",
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    } else {
      $("th.cats").removeClass("catASC");
      $("th.cats").removeClass("catDESC");
      $("th.subcats").removeClass("subcatASC");
      $("th.subcats").removeClass("subcatDESC");
      $("th.names").removeClass("nameASC");
      $("th.names").removeClass("nameDESC");
      $("th.codes").removeClass("codeDESC");
      $("th.codes").addClass("codeASC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "codeASC",
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    }
  });
  // Sorting By Codes For Items In Cats
  $(document).on("click", "th.catcodes", function () {
    var catCode = $("table.table").data("cat");
    if ($("th.catcodes").hasClass("codeASC")) {
      $("th.catcodes").removeClass("codeASC");
      $("th.catcodes").addClass("codeDESC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "catcodeDESC",
          cat: catCode,
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    } else {
      $("th.catsubcats").removeClass("subcatASC");
      $("th.catsubcats").removeClass("subcatDESC");
      $("th.catnames").removeClass("nameASC");
      $("th.catnames").removeClass("nameDESC");
      $("th.catcodes").removeClass("codeDESC");
      $("th.catcodes").addClass("codeASC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "catcodeASC",
          cat: catCode,
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    }
  });
  // Sorting By Names For All Items
  $(document).on("click", "th.names", function () {
    if ($("th.names").hasClass("nameASC")) {
      $("th.names").removeClass("nameASC");
      $("th.names").addClass("nameDESC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "nameDESC",
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    } else {
      $("th.cats").removeClass("catASC");
      $("th.cats").removeClass("catDESC");
      $("th.subcats").removeClass("subcatASC");
      $("th.subcats").removeClass("subcatDESC");
      $("th.codes").removeClass("codeASC");
      $("th.codes").removeClass("codeDESC");
      $("th.names").removeClass("nameDESC");
      $("th.names").addClass("nameASC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "nameASC",
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    }
  });
  // Sorting By Names For Items In Cats
  $(document).on("click", "th.catnames", function () {
    var catCode = $("table.table").data("cat");
    if ($("th.catnames").hasClass("nameASC")) {
      $("th.catnames").removeClass("nameASC");
      $("th.catnames").addClass("nameDESC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "catnameDESC",
          cat: catCode,
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    } else {
      $("th.catsubcats").removeClass("subcatASC");
      $("th.catsubcats").removeClass("subcatDESC");
      $("th.catcodes").removeClass("codeASC");
      $("th.catcodes").removeClass("codeDESC");
      $("th.catnames").removeClass("nameDESC");
      $("th.catnames").addClass("nameASC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "catnameASC",
          cat: catCode,
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    }
  });
  // Sorting By Cats For All Items
  $(document).on("click", "th.cats", function () {
    if ($("th.cats").hasClass("catASC")) {
      $("th.cats").removeClass("catASC");
      $("th.cats").addClass("catDESC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "catDESC",
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    } else {
      $("th.codes").removeClass("codeASC");
      $("th.codes").removeClass("codeDESC");
      $("th.subcats").removeClass("subcatASC");
      $("th.subcats").removeClass("subcatDESC");
      $("th.names").removeClass("nameASC");
      $("th.names").removeClass("nameDESC");
      $("th.cats").removeClass("catDESC");
      $("th.cats").addClass("catASC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "catASC",
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    }
  });
  // Sorting By Subcats For All Items
  $(document).on("click", "th.subcats", function () {
    if ($("th.subcats").hasClass("subcatASC")) {
      $("th.subcats").removeClass("subcatASC");
      $("th.subcats").addClass("subcatDESC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "subcatDESC",
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    } else {
      $("th.codes").removeClass("codeASC");
      $("th.codes").removeClass("codeDESC");
      $("th.cats").removeClass("catASC");
      $("th.cats").removeClass("catDESC");
      $("th.names").removeClass("nameASC");
      $("th.names").removeClass("nameDESC");
      $("th.subcats").removeClass("subcatDESC");
      $("th.subcats").addClass("subcatASC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "subcatASC",
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    }
  });
  // Sorting By Subcats For Items In Cats
  $(document).on("click", "th.catsubcats", function () {
    var catCode = $("table.table").data("cat");
    if ($("th.catsubcats").hasClass("subcatASC")) {
      $("th.catsubcats").removeClass("subcatASC");
      $("th.catsubcats").addClass("subcatDESC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "catsubcatDESC",
          cat: catCode,
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    } else {
      $("th.catcodes").removeClass("codeASC");
      $("th.catcodes").removeClass("codeDESC");
      $("th.catnames").removeClass("nameASC");
      $("th.catnames").removeClass("nameDESC");
      $("th.catsubcats").removeClass("subcatDESC");
      $("th.catsubcats").addClass("subcatASC");
      $.ajax({
        type: "POST",
        url: "includes/functions/Items/showAll.php",
        data: {
          way: "catsubcatASC",
          cat: catCode,
        },
        success: function (html) {
          $(".card-body").html(html);
        },
      });
    }
  });
  // ======================================================
  // Records Rules
  // ======================================================
  // New Record Rules
  // ================
  // Showing New Record Form
  $("span.new-record").click(function () {
    if ($(".new-record-form").hasClass("active") == false) {
      $("span.new-record").removeClass("btn-primary");
      $("span.new-record").addClass("btn-success");
      $(".new-record-form").addClass("active");
    }
  });
  // Close new record form
  $(".close-new-record").click(function () {
    $(".start form input").not(".avail").val("");
    $(".avail").val("المتاح");
    $(".start form select#code, .start form select#cat").val(0);
    $(".start form select#subcat, .start form select#item1").empty();
    $(".start form select#subcat, .start form select#item1").append(
      '<option selected value="0">برجاء اختيار القسم أولا</option>'
    );
    $(".new-record").removeClass("btn-success");
    $(".new-record").addClass("btn-primary");
    $(".new-record-form").removeClass("active");
  });
  // Validate and submit data into DB
  $(".submit-button").click(function (e) {
    e.preventDefault();
    var code = $("#code").val();
    var cat = $("#cat").val();
    var subcat = $("#subcat").val();
    var name = $("#item1").val();
    var avail_amount = $(".avail").val();
    var amount = $(".start .amount").val();
    var unitPrice = $(".start .unit-price").val();
    var totalPrice = $(".start .total-price").val();
    amount = parseFloat(amount);
    avail_amount = parseFloat(avail_amount);
    if (
      code == 0 ||
      cat == 0 ||
      subcat == 0 ||
      name == 0 ||
      amount == "" ||
      unitPrice == "" ||
      totalPrice == ""
    ) {
      $(".errorMsg").removeAttr("style");
      $(".errorMsg").prop("style", false);
    } else if (avail_amount < amount) {
      $(".errorMsgAmount").removeAttr("style");
      $(".errorMsgAmount").prop("style", false);
    } else {
      $.ajax({
        type: "POST",
        url: "includes/functions/Records/formSubmit.php",
        data: {
          code: code,
          cat: cat,
          subcat: subcat,
          name: name,
          amount: amount,
          unitPrice: unitPrice,
          totalPrice: totalPrice,
        },
        success: function (html) {
          $(".showing-data").html(html);
          $.ajax({
            type: "POST",
            url: "includes/functions/General/checkItems.php",
            success: function (html1) {
              if (html1 == 1) {
                $("li.settings").addClass("about");
              } else if (html1 == 0) {
                $("li.settings").removeClass("about");
              }
            },
          });
          returningItem();
        },
      });
      $(".successMsg").removeAttr("style");
      $(".successMsg").prop("style", false);
      setTimeout(function () {
        $(".successMsg").prop("style", true);
        $(".successMsg").attr("style", "display:none;");
        $(".start form input").not(".avail").val("");
        $(".avail").val("المتاح");
        $(".start form select#code, .start form select#cat").val(0);
        $(".start form select#subcat, .start form select#item1").empty();
        $(".start form select#subcat, .start form select#item1").append(
          '<option selected value="0">برجاء اختيار القسم أولا</option>'
        );
        $(".start form select, .start form input").blur();
      }, 1000);
      checkTotal();
    }
  });
  // Remove error Msg upon changing
  $(".start form input, .start form select").on("change keyup", function () {
    $(".errorMsg").prop("style", true);
    $(".errorMsg").attr("style", "display:none;");
    $(".errorMsgAmount").prop("style", true);
    $(".errorMsgAmount").attr("style", "display:none;");
  });
  // Calculating Total Price
  function checkTotal() {
    $(".unit-price, .amount").on("click change focus keyup", function (e) {
      e.stopPropagation();
      var unitPrice = $(".unit-price").val();
      var amount = $(".amount").val();
      var total = amount * unitPrice;
      $(".total-price").val(total);
    });
  }
  checkTotal();
  // Return Option
  function returningItem() {
    $(".returnItem").on("click", function () {
      var id = $(this).parent().parent().data("id");
      $.ajax({
        type: "POST",
        url: "includes/functions/Records/old.php",
        data: {
          no: 3,
          id: id,
        },
        success: function (html2) {
          $(".showing-data").html(html2);
          $.ajax({
            type: "POST",
            url: "includes/functions/General/checkItems.php",
            success: function (html1) {
              if (html1 == 1) {
                $("li.settings").addClass("about");
              } else if (html1 == 0) {
                $("li.settings").removeClass("about");
              }
            },
          });
          returningItem();
        },
      });
    });
  }
  returningItem();
  // =================
  // Old Records Rules
  // =================
  // Showing old logs
  $(".old-logs").click(function () {
    var fromDate = $("#from").val();
    var toDate = $("#to").val();
    if (fromDate != "" && toDate != "") {
      if (toDate >= fromDate) {
        $.ajax({
          type: "POST",
          url: "includes/functions/Records/old.php",
          data: {
            no: 1,
            from_date: fromDate,
            to_date: toDate,
          },
          success: function (html) {
            $(".old-logs-data").html(html);
            returning();
          },
        });
      } else {
        $(".old-logs-data").html(
          '<div class="text-center alert alert-danger">برجاء جعل التاريخ الاول أقدم من التاريخ الثانى حتى يتم العرض بنجاح</div>'
        );
      }
    } else {
      $(".old-logs-data").html(
        '<div class="text-center alert alert-info">برجاء تحديد كلا من التاريخين لعرض النتائج</div>'
      );
    }
  });
  // Return Option
  function returning() {
    $(".return").on("click", function () {
      var id = $(this).parent().parent().data("id");
      $.ajax({
        type: "POST",
        url: "includes/functions/Records/old.php",
        data: {
          no: 2,
          id: id,
        },
        success: function (html) {
          $(".old-logs-data").html(html);
          $.ajax({
            type: "POST",
            url: "includes/functions/General/checkItems.php",
            success: function (html) {
              if (html == 1) {
                $("li.settings").addClass("about");
              } else if (html1 == 0) {
                $("li.settings").removeClass("about");
              }
            },
          });
          returning();
        },
      });
    });
  }
  returning();
  // ======================================================
  // Installments Rules
  // ======================================================
  // User Form
  // =========
  // Showing new installment user
  $("span.new-user").click(function () {
    if ($(".new-user-form").hasClass("active") == false) {
      $("span.user-record").removeClass("btn-primary");
      $("span.user-record").addClass("btn-success");
      $(".new-user-form").addClass("active");
      $(".new-user-form input").focus();
    }
  });
  // Closing new installment user
  $(".close-new-user").click(function () {
    $(".new-user-form input").val("");
    $(".new-user").removeClass("btn-success");
    $(".new-user").addClass("btn-primary");
    $(".new-user-form").removeClass("active");
  });
  // Validate the data first
  $(".submit-user-button").click(function (e) {
    e.preventDefault();
    var name = $(".new-user-form input").val();
    if (name == "") {
      $(".errorMsg").removeAttr("style");
      $(".errorMsg").prop("style", false);
    } else {
      $.ajax({
        type: "POST",
        url: "includes/functions/Installments/newInstaUser.php",
        data: {
          name: name,
        },
        success: function (html) {
          $(".showing-data").html(html);
          $(".new-user-form input").val("");
          $(".new-user-form input").focus();
        },
      });
    }
  });
  // Remove ErrorMsg by changing input
  $(".new-user-form input").keyup(function () {
    $(".errorMsg").prop("style", true);
    $(".errorMsg").attr("style", "display:none;");
  });
  // ==========
  // Money Form
  // ==========
  // Showing Receiving Money form
  $("span.receive-btn").click(function () {
    if ($(".receive").hasClass("active") == false) {
      $("span.receive-btn").removeClass("btn-primary");
      $("span.receive-btn").addClass("btn-success");
      $(".receive").addClass("active");
      $(".receive input.money").focus();
    }
  });
  // Closing Receiving Money form
  $(".money-close").click(function () {
    $(".receive input").val("");
    $(".error").prop("style", true);
    $(".error").attr("style", "display:none;");
    $(".receive-btn").removeClass("btn-success");
    $(".receive-btn").addClass("btn-primary");
    $(".receive").removeClass("active");
  });
  // Validate Data on Receiving money
  $(".money-done").click(function (e) {
    e.stopPropagation();
    var money = $(".receive input.money").val();
    if (money == "") {
      money = "";
    } else {
      money = parseFloat(money);
    }
    var userid = $(".receive .userID").val();
    if (money <= 0 || money == "") {
      $(".error").removeAttr("style");
      $(".error").prop("style", false);
    } else {
      $.ajax({
        type: "POST",
        url: "includes/functions/Installments/money.php",
        data: {
          money: money,
          ID: userid,
        },
        success: function (html) {
          $(".installments-money table tbody").html(html);
          var total = $("#total").val();
          var done = $("#done").val();
          var remain = $("#remain").val();
          total = parseFloat(total);
          done = parseFloat(done);
          remain = parseFloat(remain);
          var newDone = done + money;
          var newRemain = total - newDone;
          $("#done").val(newDone);
          $("#remain").val(newRemain);
          $(".success").removeAttr("style");
          $(".success").prop("style", false);
          setTimeout(function () {
            $(".success").prop("style", true);
            $(".success").attr("style", "display:none;");
            $(".receive input").val("");
            $(".receive input").blur();
            // location.reload();
          }, 1000);
          editMoney();
          closeEditMoney();
          doneEditMoney();
        },
      });
    }
  });
  // Remove error msg by changing value
  $(".receive input").keyup(function () {
    $(".error").prop("style", true);
    $(".error").attr("style", "display:none;");
  });
  // ===============
  // Edit Money Form
  // ===============
  // Showing edit money form
  function editMoney() {
    $("tbody span.editM").click(function () {
      var anID = $(this).attr("class").match(/\d+/);
      var mID = anID[0];
      var el = $("tr#" + mID);
      if (!el.hasClass("active")) {
        $("tr.editM").removeClass("active");
        el.addClass("active");
        $("span.editM#" + mID).removeClass("btn-primary");
        $("span.editM#" + mID).addClass("btn-success");
      }
    });
  }
  editMoney();
  // Closing edit money form
  function closeEditMoney() {
    $(".edit-money-close").click(function () {
      var anID = $(this).attr("class").match(/\d+/);
      var mID = anID[0];
      var el = $("tr#" + mID);
      var oldMoney = $("tr#" + mID + " input.oldMoney").val();
      $("tr#" + mID + " input").val(oldMoney);
      $("span.editM").removeClass("btn-success");
      $("span.editM").addClass("btn-primary");
      el.removeClass("active");
      $("tr.bad").removeClass("active");
    });
  }
  closeEditMoney();
  // Validate Data on editing money
  function doneEditMoney() {
    $(".money-done-edit").click(function (e) {
      e.stopPropagation();
      var anID = $(this).attr("class").match(/\d+/);
      var mID = anID[0];
      var oldMoney = $("tr#" + mID + " input.oldMoney").val();
      var newMoney = $("tr#" + mID + " input.new").val();
      var userid = $("tr#" + mID + " .userID").val();
      var Mid = mID;
      oldMoney = parseFloat(oldMoney);
      newMoney = parseFloat(newMoney);
      if (newMoney > 0 && newMoney != oldMoney) {
        $.ajax({
          type: "POST",
          url: "includes/functions/Installments/money.php",
          data: {
            newMoney: newMoney,
            MiD: Mid,
            ID: userid,
          },
          success: function (html) {
            $(".installments-money table tbody").html(html);
            var total = $("#total").val();
            var done = $("#done").val();
            var remain = $("#remain").val();
            total = parseFloat(total);
            done = parseFloat(done);
            remain = parseFloat(remain);
            var newDone = done - oldMoney + newMoney;
            var newRemain = total - newDone;
            $("#done").val(newDone);
            $("#remain").val(newRemain);
            $(".success").removeAttr("style");
            $(".success").prop("style", false);
            setTimeout(function () {
              $(".success").prop("style", true);
              $(".success").attr("style", "display:none;");
              $(".receive input").val("");
              $(".receive input").blur();
              // location.reload();
            }, 1000);
          },
        });
      } else {
        $("tr.bad." + mID).addClass("active");
      }
    });
  }
  doneEditMoney();
  // Deleting money received from db
  $(".delM").on("click", function (e) {
    e.stopPropagation();
    var moneyid = $(this).data("moneyid");
    var oldMoney = $(`tr#${moneyid.trim()} input.oldMoney`).val();
    $.ajax({
      type: "POST",
      url: "includes/functions/Installments/money.php",
      data: {
        moneyid: moneyid,
      },
      success: function (html) {
        $(".installments-money table tbody").html(html);
        var total = $("#total").val();
        var done = $("#done").val();
        var remain = $("#remain").val();
        total = parseFloat(total);
        done = parseFloat(done);
        remain = parseFloat(remain);
        var newDone = done - oldMoney;
        var newRemain = total - newDone;
        $("#done").val(newDone);
        $("#remain").val(newRemain);
        $(".success").removeAttr("style");
        $(".success").prop("style", false);
        setTimeout(function () {
          $(".success").prop("style", true);
          $(".success").attr("style", "display:none;");
          $(".receive input").val("");
          $(".receive input").blur();
          // location.reload();
        }, 1000);
      },
    });
  });

  // Remove error msg upon changing input
  $("tr input").keyup(function () {
    $("tr.bad").removeClass("active");
  });
  // =================
  // Installment Items
  // =================
  // Changing value of total installments price
  // Total Price
  $(".insta-unit-price, .amount").on("click change keyup focus", function (e) {
    e.stopPropagation();
    var unitPrice = $(".insta-unit-price").val();
    var amount = $(".amount").val();
    var total = amount * unitPrice;
    $(".insta-total-price").attr("value", total);
  });
  // Validate Adding istallment item
  $(".addInstaItem").click(function (ev) {
    ev.stopPropagation();
    $(".newItemAdd").on("submit", function (e) {
      e.preventDefault();
    });
    var code = $("#code").val();
    var cat = $("#cat").val();
    var subcat = $("#subcat").val();
    var name = $("#item1").val();
    var avail_amount = $(".avail").val();
    var amount = $(".instaItem .amount").val();
    var unitPrice = $(".instaItem .unit-price").val();
    var totalPrice = $(".instaItem .total-price").val();
    var totalInstaPrice = $(".instaItem .insta-total-price").val();
    var userid = $("#userid").val();
    amount = parseFloat(amount);
    avail_amount = parseFloat(avail_amount);
    if (
      code == 0 ||
      cat == 0 ||
      subcat == 0 ||
      name == 0 ||
      amount == "" ||
      unitPrice == "" ||
      totalPrice == "" ||
      totalInstaPrice == ""
    ) {
      $(".errorMsg").removeAttr("style");
      $(".errorMsg").prop("style", false);
    } else if (avail_amount < amount) {
      $(".errorMsgAmount").removeAttr("style");
      $(".errorMsgAmount").prop("style", false);
    } else {
      $.ajax({
        type: "POST",
        url: "includes/functions/Installments/instaItem.php",
        data: {
          add: "1",
          code: code,
          cat: cat,
          subcat: subcat,
          name: name,
          amount: amount,
          unitPrice: unitPrice,
          totalPrice: totalPrice,
          totalInstaPrice: totalInstaPrice,
          userid: userid,
        },
        success: function (html) {
          if (html == 1) {
            $("li.settings").addClass("about");
          }
          $(".successMsg").removeAttr("style");
          $(".successMsg").prop("style", false);
          setTimeout(function () {
            $(".successMsg").prop("style", true);
            $(".successMsg").attr("style", "display:none;");
            $(".newItemAdd")[0].reset();
            $(".insta-total-price, .total-price").removeAttr("value");
            $(".instaItem select#subcat, .instaItem select#item1").append(
              '<option selected value="0">برجاء اختيار القسم أولا</option>'
            );
          }, 1000);
        },
      });
    }
  });
  // Remove Error Msgs Upon chaning values
  $(".instaItem input, .instaItem select").change(function () {
    $(".errorMsg").prop("style", true);
    $(".errorMsg").attr("style", "display:none;");
    $(".errorMsgAmount").prop("style", true);
    $(".errorMsgAmount").attr("style", "display:none;");
  });
});
// Removing an installmnet item
$(".delLog").click(function (e) {
  e.stopPropagation();
  var logID = $(this).data("log");
  $.ajax({
    type: "POST",
    url: "includes/functions/Installments/instaItem.php",
    data: {
      add: "2",
      logID: logID,
    },
    success: function () {
      $(".delItem").addClass("active");
      setTimeout(function () {
        location.reload();
      }, 1000);
    },
  });
});
// ======================================================
// Bills Page
// ======================================================
// Open new bill form
$(".addBill").on("click", function () {
  if (!$("form.newBillForm").hasClass("active")) {
    $("form.newBillForm").addClass("active");
    $(".addBill").removeClass("btn-primary");
    $(".addBill").addClass("btn-success");
    $(".newBillForm #name").focus();
  }
});
// Close new bill form
$(".endNewBill").on("click", function () {
  $("form.newBillForm")[0].reset();
  $(".error").removeClass("active");
  $("form.newBillForm").removeClass("active");
  $(".addBill").removeClass("btn-success");
  $(".addBill").addClass("btn-primary");
});
// Validate and add data into DB
$(".newBillBtn").on("click", function (ev) {
  ev.stopPropagation();
  $("form.newBillForm").on("submit", function (e) {
    e.preventDefault();
  });
  var date = $("form.newBillForm #date").val();
  var name = $("form.newBillForm #name").val();
  var amount = $("form.newBillForm #amount").val();
  if (name == "" || amount == "" || date == "") {
    $(".error").addClass("active");
  } else {
    amount = parseFloat(amount);
    $.ajax({
      type: "POST",
      url: "includes/functions/Bills/billsManager.php",
      data: {
        no: "1",
        date: date,
        name: name,
        amount: amount,
      },
      success: function (html) {
        $(".dataShow").html(html);
        $("form.newBillForm")[0].reset();
        $(".newBillForm #name").focus();
        deleteData();
        $(".success").addClass("active");
        setTimeout(() => {
          $(".success").removeClass("active");
        }, 1000);
      },
    });
  }
});
// Hide error msg upon change in input
$("form.newBillForm input").on("change keyup", function () {
  $(".error").removeClass("active");
});
// Data list functions
// Delete bill
function deleteData() {
  $(".delBill").on("click", function (e) {
    e.stopPropagation();
    var billID = $(this).parent().parent().data("log");
    $.ajax({
      type: "POST",
      url: "includes/functions/Bills/billsManager.php",
      data: {
        no: "0",
        billID: billID,
      },
      success: function (html) {
        $(".dataShow").html(html);
        $(".successDel").addClass("active");
        deleteData();
        setTimeout(() => {
          $(".successDel").removeClass("active");
        }, 1000);
      },
    });
  });
}
deleteData();
// ======================================================
// Calcs Rules
// ======================================================
// Stops default of form
$(".spendingsForm").on("submit", function (e) {
  e.preventDefault();
});
// Validate and add spendings into DB
function addSpending() {
  $(".spendingAdd").on("click", function (e) {
    e.stopPropagation();
    // Catch variables values
    var name = $(".spending_name").val();
    var date = $(".date").val();
    var amount = $(".amount").val();
    // Validate and submit into DB
    if (name != "" && date != "" && amount != "") {
      $.ajax({
        type: "POST",
        url: "includes/functions/Calcs/calcsManager.php",
        data: {
          app: 1,
          name: name,
          date: date,
          amount: amount,
        },
        success: function (html) {
          $(".spendingsForm")[0].reset();
          $(".spendingsForm input:first-of-type").focus();
          $(".data").html(html);
          $(".spendingMsg").addClass("active");
          delSpending();
          setTimeout(() => {
            $(".spendingMsg").removeClass("active");
          }, 1000);
        },
      });
    }
  });
}
addSpending();
// Delete spending from DB
function delSpending() {
  $(document).on("click", ".spendingDel", function (e) {
    // $(".spendingDel").on("click", function (e) {
    e.stopPropagation();
    var id = $(this).parent().parent().data("id");
    $.ajax({
      type: "POST",
      url: "includes/functions/Calcs/calcsManager.php",
      data: {
        app: 2,
        id: id,
      },
      success: function (html) {
        $(".data").html(html);
        $(".spendingDelMsg").addClass("active");
        delSpending();
        setTimeout(() => {
          $(".spendingDelMsg").removeClass("active");
        }, 1000);
      },
    });
  });
}
delSpending();
// Change year of months in monthly calcs
$(".changeYear").on("click", function (e) {
  e.stopPropagation();
  var year = $("#year").val();
  location.href = "?application=month&selectedYear=" + year;
});
// Change year in yearly calcs
$(".changeYearCalc").on("click", function (e) {
  e.stopPropagation();
  var year = $(".yearChanging").val();
  location.href = "?application=year&year=" + year;
});
// ======================================================

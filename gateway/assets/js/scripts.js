jQuery(document).ready(function ($) {
  var date_input1 = $(".monthly_date_picker");
  date_input1.datepicker("remove");
  var maxdate = new Date(date_input1.expiry_date);
  var mindate = new Date(date_input1.purchased_date);
  var options1 = {
    singleDatePicker: true,
    showDropdowns: true,
    autoApply: true,
    locale: {
      format: "yyyy-mm-dd",
    },
    startDate: mindate,
    endDate: maxdate,
  };

  date_input1.datepicker(options1);
  var date_input1 = $(".yearly_date_picker");
  date_input1.datepicker("remove");
  var maxdate = new Date(date_input1.expiry_date);
  var mindate = new Date(date_input1.purchased_date);
  var options1 = {
    singleDatePicker: true,
    showDropdowns: true,
    autoApply: true,
    locale: {
      format: "yyyy-mm-dd",
    },
    startDate: mindate,
    endDate: maxdate,
  };

  date_input1.datepicker(options1);
  $(".show-more button").on("click", function () {
    var $this = $(this);
    var $content = $this.parent().prev("p.content");
    var linkText = $this.text().toUpperCase();

    if (linkText === "SHOW MORE") {
      linkText = "Show less";
      $content.switchClass("hideContent", "showContent", 400);
    } else {
      linkText = "Show more";
      $content.switchClass("showContent", "hideContent", 400);
    }

    $this.text(linkText);
  });
  $(".unsub_purchase").on("click", function () {
    var purchase_id = $(this).data("pur_id");
    var user_id = $("#unsub_user_id").val();
    var formData = { purchase_id: purchase_id, user_id: user_id };
    $.ajax({
      type: "POST",
      url: "http://astromarts.com/gateway/unsubscribe_mypack.php",
      data: formData,
      dataType: "json",
      success: function (response) {
        if (response == 200) {
          swal({ title: "", text: "Product Unsubscribed", type: "success" }, function () {
            window.location.reload();
          });
        } else {
          swal({ title: "", text: "Product not Unsubscribed", type: "success" }, function () {
            window.location.reload();
          });
        }
      },
    });
  });
  $(".modal").on("hidden.bs.modal", function () {
    $(this).removeData();
  });
  if ($(".content").outerHeight() < $(".content").prop("scrollHeight")) {
    $(".show-more").show();
  } else {
    $(".show-more").hide();
  }
  $(".toggle-calendar").pignoseCalendar({
    toggle: true,
    select: function (date, context) {
      var message = $(this).data("date");
      var formData = { calendar_date: message, action: "general_calendar_details", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };
      $.ajax({
        type: "POST",
        url: API_URL,
        data: JSON.stringify(formData),
        dataType: "json",
        success: function (response) {
          var $target;
          if (response.returncode == 200) {
            $target = context.calendar.parent().next().show().html(message);
            $target = context.calendar
              .parent()
              .next()
              .show()
              .append(",<br>" + response.returndata.calendar_content);
          } else {
            $target = context.calendar.parent().next().show().html(message);
            $target = context.calendar.parent().next().show().append("");
          }
        },
      });
    },
  });
  $("#tob").datetimepicker({
    format: "hh:mm:ss a",
  });
  function get_common_category(current, date, content_type, horoscope_type, common_type) {
    // var horoscope_type="horoscope";
    user_id = $("#user_id").val();
    var product_id = $("#product_id").val();
    var product_name = $("#product_name").val();
    var weekno = getWeekOfMonth(current);
    // alert(weekno);
    if (content_type == "weekly") {
      var formData = {
        user_id: user_id != "" ? user_id : 0,
        action: "get_common_horoscope_content",
        date: date,
        weekno: "week " + weekno,
        product_id: product_id,
        content_type: content_type,
        horoscope_type: horoscope_type,
        oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72",
        common_type: common_type,
      };
    } else {
      var formData = {
        user_id: user_id != "" ? user_id : 0,
        action: "get_common_horoscope_content",
        date: date,
        product_id: product_id,
        content_type: content_type,
        horoscope_type: horoscope_type,
        oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72",
        common_type: common_type,
      };
    }
    $.ajax({
      type: "POST",
      url: API_URL,
      data: JSON.stringify(formData),
      dataType: "json",
      success: function (result) {
        if (result.returncode == "200") {
          $(".bubble_click").removeClass("active");
          $(".change_content").data("date", date);
          $(".change_content").data("content_type", content_type);
          $(".change_content").data("common_type", common_type);
          $(".change_content").data("common_content", "yes");
          if (content_type == "daily") {
            var returndata = result.returndata;
            $(".daily_content_change").html(returndata.content);
            location.href = "#daily_auto";
          }
          if (content_type == "weekly") {
            var returndata = result.returndata;
            if (common_type == "this week") {
              var curr = new Date();
            } else if (common_type == "prev week") {
              var curr = new Date();
              var temp = curr.getDate() - 7;
              curr = new Date(curr.setDate(temp));
            } else if (common_type == "next week") {
              var curr = new Date();
              var temp = curr.getDate() + 7;
              curr = new Date(curr.setDate(temp));
            } // get current date
            var first = curr.getDate() - curr.getDay() + 1; // First day is the day of the month - the day of the week
            var last = first + 6; // last day is the first day + 6
            var firstday = new Date(curr.setDate(first));
            var lastday = new Date(curr.setDate(last));
            firstday = String(firstday.getDate()).padStart(2, "0") + " " + firstday.toLocaleString("default", { month: "short" }) + " " + firstday.getFullYear();
            lastday = String(lastday.getDate()).padStart(2, "0") + " " + lastday.toLocaleString("default", { month: "short" }) + " " + lastday.getFullYear();
            $(".weekly_content_change").html(returndata.content);
            $(".card-title-weekly").html(product_name + " Weekly " + horoscope_type.charAt(0).toUpperCase() + horoscope_type.slice(1) + " (" + firstday + " - " + lastday + ")");
            location.href = "#weekly_auto";
          }
          if (content_type == "monthly") {
            if (common_type == "this month") {
              var curr = new Date();
            } else if (common_type == "prev month") {
              var curr = new Date();
              var temp = curr.getMonth() - 1;
              curr = new Date(curr.setMonth(temp));
            } else if (common_type == "next month") {
              var curr = new Date();
              var temp = curr.getMonth() + 1;
              curr = new Date(curr.setMonth(temp));
            }
            var monthname = curr.toLocaleString("default", { month: "long" }) + ", " + curr.getFullYear();
            var returndata = result.returndata;
            $(".monthly_content_change").html(returndata.content);
            $(".card-title-monthly").html(product_name + " Monthly " + horoscope_type.charAt(0).toUpperCase() + horoscope_type.slice(1) + " (" + monthname + ")");
            location.href = "#monthly_auto";
          }
          if (content_type == "yearly") {
            if (common_type == "this year") {
              var curr = new Date();
            } else if (common_type == "prev year") {
              var curr = new Date();
              var temp = curr.getFullYear() - 1;
              curr = new Date(curr.setFullYear(temp));
            } else if (common_type == "next year") {
              var curr = new Date();
              var temp = curr.getFullYear() + 1;
              curr = new Date(curr.setFullYear(temp));
            }
            var year = curr.getFullYear();
            var returndata = result.returndata;
            $(".yearly_content_change").html(returndata.content);
            $(".card-title-yearly").html(product_name + " Yearly " + horoscope_type.charAt(0).toUpperCase() + horoscope_type.slice(1) + " (" + year + ")");
            location.href = "#yearly_auto";
          }
          if ($("." + content_type + "_content_change").outerHeight() < $("." + content_type + "_content_change").prop("scrollHeight")) {
            $(".show-more").show();
          } else {
            $(".show-more").hide();
          }
        } else if (result.returncode == 201) {
          alert("Coming soon...");
          $(".common_category").removeClass("active");
        } else if (result.returncode == 202) {
          $(".common_category").removeClass("active");
          // $('#'+content_type+'_tab').removeClass('show');
          // $('#'+content_type+'_tab').removeClass('active');
          if (content_type == "daily") {
            if (common_type == "prev day") {
              $("#planId").html('<option selected disabled value="">-Select One-</option><option value="prev day" selected >Prev day</option><option value="next day">Next day</option>');
            } else {
              $("#planId").html('<option selected disabled value="">-Select One-</option><option value="prev day">Prev day</option><option value="next day" selected>Next day</option>');
            }
          } else if (content_type == "weekly") {
            if (common_type == "prev week") {
              $("#planId").html('<option disabled value="">-Select One-</option><option value="prev week" selected>Prev week</option><option value="next week">Next week</option>');
            } else {
              $("#planId").html('<option disabled value="">-Select One-</option><option value="prev week">Prev week</option><option value="next week" selected>Next week</option>');
            }
          } else if (content_type == "monthly") {
            if ((common_type = "prev month")) {
              $("#planId").html('<option disabled value="">-Select One-</option><option value="prev month" selected>Prev month</option><option value="next month">Next month</option>');
            } else {
              $("#planId").html('<option disabled value="">-Select One-</option><option value="prev month">Prev month</option><option value="next month" selected>Next month</option>');
            }
          } else if (content_type == "yearly") {
            if ((common_type = "prev year")) {
              $("#planId").html('<option disabled value="">-Select One-</option><option value="prev year" selected>Prev year</option><option value="next year">Next year</option>');
            } else {
              $("#planId").html('<option disabled value="">-Select One-</option><option value="prev year">Prev year</option><option value="next year" selected>Next year</option>');
            }
          }
          show_billing_form(product_name);
          // swal({title:'', text:"Purchase the product to access see the content..!", type:'warning'}, function() {window.location = "./home.php";});
        }
      },
    });
  }
  $(".common_category").click(function () {
    $(".common_category").removeClass("active");
    $(this).addClass("active");

    var current = new Date($(this).data("date"));
    var common_type = $(this).data("common_type");
    //   alert(common_type);
    var content_type = $(this).data("type");
    var date = current.getFullYear() + "-" + String(current.getMonth() + 1).padStart(2, "0") + "-" + String(current.getDate()).padStart(2, "0");
    get_common_category(current, date, content_type, "horoscope", common_type);
    // var content_type = $(this).data("content_type");
    // alert($(this).data('date'));
  });
  function getWeekOfMonth(date) {
    let adjustedDate = date.getDate() + date.getDay();
    let prefixes = ["0", "1", "2", "3", "4", "5"];
    return parseInt(prefixes[0 | (adjustedDate / 7)]) + 1;
  }

  //horoscope_script_start
  function show_billing_form(product_name = "") {
    user_id = $("#user_id").val();
    var formData = { user_id: user_id != "" ? user_id : 0, action: "get_user_details", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };
    $.ajax({
      type: "POST",
      url: API_URL,
      data: JSON.stringify(formData),
      dataType: "json",
      success: function (result) {
        if (result.returncode == "200") {
          data = result.returndata;
          $("#choosePlanpayment").modal("show");
          $(".horo_nameId").val(data.user_name);
          $(".horo_emaiId").val(data.email_id);
          $(".horo_mobileId").val(data.msisdn);
          if (product_name) {
            $(".horo_signId").val(product_name);
          } else {
            $(".horo_signId").val(data.zodiac_sign);
          }

          $(".horo_genderId").val(data.gender);
          $(".horo_placeId").val(data.birth_place);
          $(".horo_dobId").val(data.DOB);
          $(".horo_countrycode").val(data.country_code);
          // $('#payment-btn').trigger('click');
          // $('#payment-btn').click(function(){
          // $('.cd-error-message').removeClass('is-visible');
          $("#for_payment").val("horo_");
          // var name = $('.horo_nameId').val();
          // var email_id = $('.horo_emaiId').val();
          // var mobile_no = $('.horo_mobileId').val();
          // var dob = $('.horo_dobId').val();
          // var place_of_birth = $('.horo_placeId').val();
          // var gender = $('.horo_genderId').val();
          var zodiac_sign = $(".horo_signId").val();
          var plan = $(".horo_planId").val();
          // var country = $('.horo_countrycode').val();
          // var errmsg = 0;
          // if (name == '')
          // {
          // 	$('.reg-name-error-message').addClass('is-visible').text('Please Enter Name');
          // 	errmsg = 1;
          // }
          // if (plan == ''|| plan=='null' || plan==null)
          // {
          // 	$('.reg-plan-error-message').addClass('is-visible').text('Please Select a Plan');
          // 	errmsg = 1;
          // }
          // if( email_id == '')
          // {
          // 	$('.reg-email-error-message').addClass('is-visible').text('Please Enter Email ID');
          // 	errmsg = 1;
          // }
          // else if(email_id!='')
          // {
          // if(!validateEmail(email_id))
          // {
          // 	$('.reg-email-error-message').addClass('is-visible').text('Please Enter Valid Mail ID');
          // 	errmsg = 1;
          // }
          // }
          // if (mobile_no == '')
          // {
          // 	$('.reg-mobileno-error-message').addClass('is-visible').text('Please Enter Mobile No');
          // 	errmsg = 1;
          // }
          // else if(mobile_no!='')
          // {
          // 	if (isNaN(mobile_no))
          // 	{
          // 		$('.reg-mobileno-error-message').addClass('is-visible').text('Please Enter Valid Mobile Number');
          // 		errmsg = 1;
          // 	}
          // 	else if ((country == '91') && mobile_no.length != 10) {
          // 		$('.reg-mobileno-error-message').addClass('is-visible').text('Mobile Number looks incorrect');
          // 		errmsg = 1;
          // 	}else if (country == '971' && mobile_no.length != 9) {
          // 		$('.reg-mobileno-error-message').addClass('is-visible').text('Mobile Number looks incorrect');
          // 		errmsg = 1;
          // 	}
          // }
          // if (dob == '')
          // {
          // 	$('.reg-dob-error-message').addClass('is-visible').text('Please Enter DOB');
          // 	errmsg = 1;
          // }
          // if( place_of_birth == '')
          // {
          // 	$('.reg-pob-error-message').addClass('is-visible').text('Please Enter POB');
          // 	errmsg = 1;
          // }
          // if( gender == '' || gender=='null' || gender==null)
          // {
          // 	$('.reg-gender-error-message').addClass('is-visible').text('Please Select Gender');
          // 	errmsg = 1;
          // }
          // if( zodiac_sign == '' || zodiac_sign=='null' || zodiac_sign==null)
          // {
          // 	$('.reg-sign-error-message').addClass('is-visible').text('Please Select Sign');
          // 	errmsg = 1;
          // }
          // if (errmsg == 0)
          // {
          // $('#choosePlan').modal('hide');
          // $('#non_register').modal('hide');

          $(".package").html("Horoscope " + plan + " - " + zodiac_sign);

          if (plan == "weekly" || plan == "prev week" || plan == "next week") {
            $(".pricepoint").html("@AED7/Week+vat");
          } else if (plan == "monthly" || plan == "prev month" || plan == "next month") {
            $(".pricepoint").html("@AED30/Month+vat");
          } else if (plan == "daily" || plan == "prev day" || plan == "next day") {
            $(".pricepoint").html("@AED1/Day+vat");
          }
          // }
          // });
        } else if (result.returncode == "201") {
          $("#choosePlan").modal("show");
          data = result.returndata;
          $("#payment-btn").click(function () {
            $(".cd-error-message").removeClass("is-visible");
            var name = $(".horo_nameId").val();
            var email_id = $(".horo_emaiId").val();
            var country_code = $(".horo_countrycode").val();
            var mobile_no = $(".horo_mobileId").val();
            var dob = $(".horo_dobId").val();
            var place_of_birth = $(".horo_placeId").val();
            var gender = $(".horo_genderId").val();
            var zodiac_sign = $(".horo_signId").val();
            var errmsg = 0;
            if (name == "") {
              $(".reg-name-error-message").addClass("is-visible").text("Please Enter Name");
              errmsg = 1;
            }
            if (email_id == "") {
              $(".reg-email-error-message").addClass("is-visible").text("Please Enter Email ID");
              errmsg = 1;
            } else if (email_id != "") {
              if (!validateEmail(email_id)) {
                $(".reg-email-error-message").addClass("is-visible").text("Please Enter Valid Mail ID");
                errmsg = 1;
              }
            }
            if (mobile_no == "") {
              $(".reg-mobileno-error-message").addClass("is-visible").text("Please Enter Mobile No");
              errmsg = 1;
            } else if (mobile_no != "") {
              if (isNaN(mobile_no)) {
                $(".reg-mobileno-error-message").addClass("is-visible").text("Please Enter Valid Mobile Number");
                errmsg = 1;
              } else if ((country_code == "91" && mobile_no.length != 10) || (country_code == "971" && mobile_no.length != 9)) {
                $(".reg-mobileno-error-message").addClass("is-visible").text("Mobile Number looks incorrect");
                errmsg = 1;
                $("#otp").val("");
              }
            }
            if (dob == "") {
              $(".reg-dob-error-message").addClass("is-visible").text("Please Enter DOB");
              errmsg = 1;
            }
            if (place_of_birth == "") {
              $(".reg-pob-error-message").addClass("is-visible").text("Please Enter POB");
              errmsg = 1;
            }
            if (gender == "" || gender == "null" || gender == null) {
              $(".reg-gender-error-message").addClass("is-visible").text("Please Select Gender");
              errmsg = 1;
            }
            if (zodiac_sign == "" || zodiac_sign == "null" || zodiac_sign == null) {
              $(".reg-sign-error-message").addClass("is-visible").text("Please Select Sign");
              errmsg = 1;
            }
            if (errmsg == 0) {
              var forpage = $(this).data("for");

              $("#choosePlan").modal("hide");
              $("#non_register").modal("show");
              $(".close_password").click(function () {
                $("#passwordId").val("");
                $("#non_register").modal("hide");
                $("#choosePlan").modal("show");
              });
              $("#register_button12").attr("data-for", forpage);
              $(".popup_password").addClass(forpage + "passwordId");
            }
          });
        }
      },
      error: function (result) {
        console.log("An error occurred.");
        console.log(result);
      },
    });
  }
  $(".horoscope_content_redirect").click(function () {
    var current = new Date();

    var type = $(this).data("type");
    var date = current.getFullYear() + "-" + String(current.getMonth() + 1).padStart(2, "0") + "-" + String(current.getDate()).padStart(2, "0");
    var content_type = $(this).data("content_type");
    var user_id = $("#user_id").val();
    var formData = { user_id: user_id != "" ? user_id : 0, content_type: content_type, date: date, action: "get_purchase_detail", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };

    // alert('working1');

    $.ajax({
      type: "POST",
      url: API_URL,
      data: JSON.stringify(formData),
      dataType: "json",
      success: function (response) {
        if (response.returncode == 200) {
          var returndata = response.returndata;
          window.location.replace(APPLICATION_URL + "gateway/horoscope-content.php?product_id=" + returndata.product_id + "&product_name=" + returndata.product_name + "&content_type=" + content_type + "#" + content_type + "_auto");
        } else if (response.returncode == 201) {
          if (content_type == "weekly") {
            $("#planId").html('<option selected disabled value="">-Select One-</option><option value="weekly" selected>Weekly</option><option value="monthly">Monthy</option><option value="yearly">Yearly</option>');
          }
          if (content_type == "monthly") {
            $("#planId").html('<option selected disabled value="">-Select One-</option><option value="weekly">Weekly</option><option value="monthly" selected>Monthy</option><option value="yearly">Yearly</option>');
          }
          if (content_type == "yearly") {
            $("#planId").html('<option selected disabled value="">-Select One-</option><option value="weekly">Weekly</option><option value="monthly">Monthy</option><option value="yearly" selected>Yearly</option>');
          }
          show_billing_form();
          // swal({title:'', text:"Purchase the product to access see the content..!", type:'warning'}, function() {window.location = "./home.php";});
        }
      },
      error: function (response) {
        console.log("An error occurred.");
        console.log(response);
      },
    });
  });

  function get_content(content_type, date, type) {
    // var content_type = $(this).data("boxtype");
    // alert($(this).data("boxtype"));
    var user_id = $("#user_id").val();
    var product_id = $("#product_id").val();
    var product_name = $("#product_name").val();
    var formData = { user_id: user_id != "" ? user_id : 0, product_id: product_id, content_type: content_type, date: date, action: "get_horoscope_details", horoscope_type: type, oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };

    // alert('working1');

    $.ajax({
      type: "POST",
      url: API_URL,
      data: JSON.stringify(formData),
      dataType: "json",
      success: function (response) {
        // alert('working2');
        if (response.returncode == 200) {
          $(".nav-link").removeClass("active");
          $(".tab-pane").removeClass("show");
          $(".tab-pane").removeClass("active");
          $("#" + content_type + "-tab").addClass("active");
          $("#" + content_type + "_tab").addClass("show");
          $("#" + content_type + "_tab").addClass("active");
          $(".change_content").data("date", date);
          $(".change_content").data("content_type", content_type);
          $(".content_bread").html(content_type.charAt(0).toUpperCase() + content_type.slice(1));
          // alert('working3');
          if (content_type == "daily") {
            var returndata = response.returndata;
            $(".daily_content_change").html(returndata.content);
            // M d, Y
            var return_date = new Date(date);
            var return_date = return_date.toLocaleString("default", { month: "short" }) + " " + String(return_date.getDate()).padStart(2, "0") + ", " + return_date.getFullYear();

            $(".card-title-daily").html(product_name + " " + "daily " + type + " - (" + return_date + ")");
            location.href = "#daily_auto";
          }
          if (content_type == "weekly") {
            // alert('working4');
            $(".show_box").show();
            var returndata = response.returndata;
            var purchase_data = response.purchased_data;
            $(".weekly_content_change").html(returndata.content);
            // l, d M Y
            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var return_date = new Date(date);
            var return_date = weekday[return_date.getDay()] + ", " + String(return_date.getDate()).padStart(2, "0") + " " + return_date.toLocaleString("default", { month: "short" }) + " " + return_date.getFullYear();

            $(".card-title-weekly").html(product_name + " " + "weekly " + type + " " + return_date + "");

            var maxdate = new Date(purchase_data.expiry_date);
            var mindate = new Date(purchase_data.purchased_date);
            var today = new Date(date);
            var sweekday = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            var k = 1;
            for (var d = mindate; d <= maxdate; d.setDate(d.getDate() + 1)) {
              if (
                d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, "0") + "-" + String(d.getDate()).padStart(2, "0") ==
                today.getFullYear() + "-" + String(today.getMonth() + 1).padStart(2, "0") + "-" + String(today.getDate()).padStart(2, "0")
              ) {
                $(".bubble_" + k).html(sweekday[d.getDay()] + "<br>&nbsp" + String(d.getDate()).padStart(2, "0"));
                $(".bubble_" + k).data("date", d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, "0") + "-" + String(d.getDate()).padStart(2, "0"));
                $(".bubble_" + k).addClass("active");
                // $('.weekly_bubble').append('<li><a class="active" data-date="'+d.getFullYear() + "-" + String(d.getMonth()+1).padStart(2,'0') + "-" + String(d.getDate()).padStart(2,'0')+'">'+sweekday[d.getDay()]+' </a></li>');
              } else {
                $(".bubble_" + k).html(sweekday[d.getDay()] + "<br>&nbsp" + String(d.getDate()).padStart(2, "0"));
                $(".bubble_" + k).data("date", d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, "0") + "-" + String(d.getDate()).padStart(2, "0"));
                $(".bubble_" + k).removeClass("active");
                // $('.bubble_'+k).addClass('active');
                // $('.weekly_bubble').append('<li><a data-date="'+d.getFullYear() + "-" + String(d.getMonth()+1).padStart(2,'0') + "-" + String(d.getDate()).padStart(2,'0')+'">'+sweekday[d.getDay()]+'</a></li>');
              }
              k++;
            }
            location.href = "#weekly_auto";
          }
          if (content_type == "monthly") {
            var returndata = response.returndata;
            var purchase_data = response.purchased_data;
            $(".monthly_content_change").html(returndata.content);
            // M d, Y
            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

            var return_date = new Date(date);
            var return_date = weekday[return_date.getDay()] + ", " + String(return_date.getDate()).padStart(2, "0") + " " + return_date.toLocaleString("default", { month: "short" }) + " " + return_date.getFullYear();
            $(".card-title-monthly").html(product_name + " " + "monthly " + type + " " + return_date + "");

            var date_input1 = $(".monthly_date_picker");
            date_input1.datepicker("remove");
            var maxdate = new Date(purchase_data.expiry_date);
            var mindate = new Date(purchase_data.purchased_date);
            var options1 = {
              singleDatePicker: true,
              showDropdowns: true,
              autoApply: true,
              locale: {
                format: "yyyy-mm-dd",
              },
              startDate: mindate,
              endDate: maxdate,
            };

            date_input1.datepicker(options1);
            location.href = "#monthly_auto";
          }
          if (content_type == "yearly") {
            var returndata = response.returndata;
            var purchase_data = response.purchased_data;
            $(".yearly_content_change").html(returndata.content);
            // M d, Y
            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

            var return_date = new Date(date);
            var return_date = weekday[return_date.getDay()] + ", " + String(return_date.getDate()).padStart(2, "0") + " " + return_date.toLocaleString("default", { month: "short" }) + " " + return_date.getFullYear();
            $(".card-title-yearly").html(product_name + " " + "yearly " + type + " " + return_date + "");

            var date_input1 = $(".yearly_date_picker");
            date_input1.datepicker("remove");
            var maxdate = new Date(purchase_data.expiry_date);
            var mindate = new Date(purchase_data.purchased_date);
            var options1 = {
              singleDatePicker: true,
              showDropdowns: true,
              autoApply: true,
              locale: {
                format: "yyyy-mm-dd",
              },
              startDate: mindate,
              endDate: maxdate,
            };

            date_input1.datepicker(options1);
            location.href = "#yearly_auto";
          }
        } else if (response.returncode == 201) {
          alert("Coming soon...");
        } else if (response.returncode == 202) {
          $("#" + content_type + "_tab").removeClass("show");
          $("#" + content_type + "_tab").removeClass("active");
          if (content_type == "weekly") {
            $("#planId").html('<option selected disabled value="">-Select One-</option><option value="weekly" selected>Weekly</option><option value="monthly">Monthy</option><option value="yearly">Yearly</option>');
          }
          if (content_type == "monthly") {
            $("#planId").html('<option selected disabled value="">-Select One-</option><option value="weekly">Weekly</option><option value="monthly" selected>Monthy</option><option value="yearly">Yearly</option>');
          }
          if (content_type == "yearly") {
            $("#planId").html('<option selected disabled value="">-Select One-</option><option value="weekly">Weekly</option><option value="monthly">Monthy</option><option value="yearly" selected>Yearly</option>');
          }
          $("#signId").val(product_name);
          show_billing_form(product_name);
          // swal({title:'', text:"Purchase the product to access see the content..!", type:'warning'}, function() {window.location = "./home.php";});
        }
        if ($("." + content_type + "_content_change").outerHeight() < $("." + content_type + "_content_change").prop("scrollHeight")) {
          $(".show-more").show();
        } else {
          $(".show-more").hide();
        }
      },
      error: function (response) {
        console.log("An error occurred.");
        console.log(response);
      },
    });
  }

  $(".content_change").click(function () {
    $(".change_content").removeClass("active");
    $(".common_category").removeClass("active");
    var current = new Date();
    var date = current.getFullYear() + "-" + String(current.getMonth() + 1).padStart(2, "0") + "-" + String(current.getDate()).padStart(2, "0");
    var content_type = $(this).data("boxtype");
    $(".change_content").data("date", date);
    $(".change_content").data("content_type", content_type);
    $(".change_content").data("common_content", "no");

    get_content(content_type, date, "horoscope");
  });

  $(".bubble_click").click(function () {
    $(".change_content").removeClass("active");
    $(".common_category").removeClass("active");
    var current = new Date($(this).data("date"));

    var date = current.getFullYear() + "-" + String(current.getMonth() + 1).padStart(2, "0") + "-" + String(current.getDate()).padStart(2, "0");
    var content_type = "weekly";
    $(".change_content").data("date", date);
    $(".change_content").data("content_type", content_type);
    $(".change_content").data("common_content", "no");

    get_content(content_type, date, "horoscope");
  });

  $(".date_getter").change(function () {
    $(".change_content").removeClass("active");
    $(".common_category").removeClass("active");
    var current = new Date($(this).val());
    $(".change_content").data("date", date);
    $(".change_content").data("content_type", content_type);
    $(".change_content").data("common_content", "no");

    var date = current.getFullYear() + "-" + String(current.getMonth() + 1).padStart(2, "0") + "-" + String(current.getDate()).padStart(2, "0");
    var content_type = $(this).data("content");

    get_content(content_type, date, "horoscope");
  });

  $(".change_content").click(function () {
    $(".change_content").removeClass("active");
    $(this).addClass("active");
    var current = new Date($(this).data("date"));
    var is_common = $(this).data("common_content");
    // alert($(this).data('common_content'));
    var type = $(this).data("type");
    var date = current.getFullYear() + "-" + String(current.getMonth() + 1).padStart(2, "0") + "-" + String(current.getDate()).padStart(2, "0");
    var content_type = $(this).data("content_type");
    if (is_common == "no") {
      get_content(content_type, date, type);
    } else {
      var common_type = $(this).data("common_type");
      get_common_category(current, date, content_type, type, common_type);
    }
  });
  //horoscope_script end

  $(".register_btn").click(function () {
    $("#signin").modal("hide");
    //$('#register').modal({backdrop: 'static', keyboard: false}, 'show');
    $("#register").modal("show");
    /* swal('', 'OTP Sent to Your Mobile Number/E-mail', 'success');
		 $(".sweet-alert").css('background-color', 'lightpink'); */
  });

  $(".signin_btn").click(function () {
    $("#register").modal("hide");
    $("#signin").modal("show");
  });
  $(".signin_forgot_pwd").click(function () {
    $("#signin").modal("hide");
    $("#forgot_password").modal("show");
  });
  $(".panchang_date").click(function () {
    $("#panchang_date").modal("show");
  });
  /* $(".privacy").click(function() {
		
		$("#dialog").dialog();
	
	}); */
  $(".panchang_location").click(function () {
    $("#panchang_date").modal("hide");
    $("#panchang_location").modal("show");
  });
  /* $("#dropdownUser1").click(function() {
		$('.dropdown-toggle').dropdown('toggle');
	}); */
  $(".register_button").click(function (e) {
    e.preventDefault();
    $(".cd-error-message").removeClass("is-visible");
    var for_name = $(this).data("for");

    var name = $("." + for_name + "nameId").val();
    var email_id = $("." + for_name + "emaiId").val();
    var mobile_no = $("." + for_name + "mobileId").val();
    var Password = $("." + for_name + "passwordId").val();
    var dob = $("." + for_name + "dobId").val();
    var tob = $("." + for_name + "tobId").val();
    var place_of_birth = $("." + for_name + "placeId").val();
    var gender = $("." + for_name + "genderId").val();
    var zodiac_sign = $("." + for_name + "signId").val();
    var country_code = $("." + for_name + "countrycode").val();
    var errmsg = 0;
    if (name == "") {
      $(".reg-name-error-message").addClass("is-visible").text("Please Enter Name");
      errmsg = 1;
    }
    if (email_id == "") {
      $(".reg-email-error-message").addClass("is-visible").text("Please Enter Email ID");
      errmsg = 1;
    } else if (email_id != "") {
      if (!validateEmail(email_id)) {
        $(".reg-email-error-message").addClass("is-visible").text("Please Enter Valid Mail ID");
        errmsg = 1;
      }
    }
    if (mobile_no == "") {
      $(".reg-mobileno-error-message").addClass("is-visible").text("Please Enter Mobile No");
      errmsg = 1;
    } else if (mobile_no != "") {
      if (isNaN(mobile_no)) {
        $(".reg-mobileno-error-message").addClass("is-visible").text("Please Enter Valid Mobile Number");
        errmsg = 1;
      } else if (country_code == "91" && mobile_no.length != 10) {
        $(".reg-mobileno-error-message").addClass("is-visible").text("Mobile Number looks incorrect");
        errmsg = 1;
      } else if (country_code == "971" && mobile_no.length != 9) {
        $(".reg-mobileno-error-message").addClass("is-visible").text("Mobile Number looks incorrect");
        errmsg = 1;
      }
    }
    if (Password == "") {
      $(".reg-password-error-message").addClass("is-visible").text("Please Enter Password");
      errmsg = 1;
    } else {
      var pass_strength = checkStrength(Password);
      if (pass_strength != "Strong") {
        $(".reg-password-error-message").addClass("is-visible").text("Invalid Password");
        return false;
      }
    }
    if (dob == "") {
      $(".reg-dob-error-message").addClass("is-visible").text("Please Enter DOB");
      errmsg = 1;
    }
    if (place_of_birth == "") {
      $(".reg-pob-error-message").addClass("is-visible").text("Please Enter POB");
      errmsg = 1;
    }
    if (gender == "" || gender == "null" || gender == null) {
      $(".reg-gender-error-message").addClass("is-visible").text("Please Select Gender");
      errmsg = 1;
    }
    if (zodiac_sign == "" || zodiac_sign == "null" || zodiac_sign == null) {
      $(".reg-sign-error-message").addClass("is-visible").text("Please Select Sign");
      errmsg = 1;
    }
    if (errmsg == 0) {
      $(".cd-error-message").removeClass("is-visible");
      var formData = {
        country_code: country_code,
        user_name: name,
        mobile_no: mobile_no,
        mail: email_id,
        password: Password,
        DOB: dob,
        TOB: tob,
        place: place_of_birth,
        gender: gender,
        zodiac_sign: zodiac_sign,
        action: "register",
        for: "register",
        oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72",
      };
	  console.log(formData);
      $.ajax({
        type: "POST",
        url: API_URL,
        data: JSON.stringify(formData),
        dataType: "json",
        success: function (response) {
          if (response.returncode == 200) {
            var response_data = response.returndata;
            var user_id = response_data.user_id;

            $("#user_id").val(user_id);
            $("#for").val("register");

            $("#register").modal("hide");
            $("#non_register").modal("hide");
            $("#non_register").attr("id", "non_register1");
            $("#otp_form").modal("show");
            swal("", "OTP Sent to Your Mobile Number/E-mail", "success");
            otp_counter();
            $(".otp_submit").click(function (e) {
              e.preventDefault();
              var user_id = $("#user_id").val();
              var otp = $("#otp1").val() + $("#otp2").val() + $("#otp3").val() + $("#otp4").val();

              if (otp == "") {
                swal("", "Please Enter OTP", "warning");
              } else if (otp.length != "4") {
                swal("", "Please Enter Valid OTP", "warning");
              } else {
                $(".cd-error-message").removeClass("is-visible");
                validate_otp(user_id, otp, $("#for").val(), Password, for_name, mobile_no);
              }
            });
            $(".register_form").trigger("reset");
            $(".popup_password").val("");
            //OTP Resend Start
          }
          if (response.returncode == 201) {
            swal("", response.returnmessage, "warning");
            $(".register_form").trigger("reset");
          }
        },
        error: function (response) {
          console.log("An error occurred.");
          console.log(response);
        },
      });
    }
  });

  $(".payment-button").click(function () {
    $(".payment-button").removeClass("active");
    $(this).addClass("active");
    var paym = $(this).data("paymethod");
    $(".paymethod").val(paym);
  });

  // $(".billing_redirection").click(function(){

  // 	var payment = $(".paymethod").val();
  // 	var type = $("#for_payment").val();

  //         if(typeof payment=="undefined"||payment==''){
  //             // alert("enter payment method");
  // 			swal('', "SELECT PAYMENT METHOD", 'warning');
  //             // mschool_alert('Oops !',"SELECT PAYMENT METHOD",'WARNING','');
  //         }
  //         else{

  // 			if(type=="horo_")
  // 			{
  // 				 $('.horo_payment_details').submit()
  // 			}
  // 			else if(type=="aaq_")
  // 			{
  // 				 $('.payment_details').submit()
  // 			}
  // 			else
  // 			{
  // 				$('.tta_payment_details').submit()
  // 			}
  //         }
  // });

  $(".billing_redirection").click(function () {
    var payment = $(".paymethod").val();
    var type = $("#for_payment").val();

    if (typeof payment == "undefined" || payment == "") {
      // alert("enter payment method");
      swal("", "SELECT PAYMENT METHOD", "warning");
      // mschool_alert('Oops !',"SELECT PAYMENT METHOD",'WARNING','');
    } else if (payment == "razorpay") {
      // alert(type);
      if (type == "horo_") {
        $(".horo_payment_details").submit();
      } else if (type == "aaq_") {
        $(".payment_details").submit();
      } else {
        $(".tta_payment_details").submit();
      }
    } else if (payment == "dubilling") {
      var country_code = $("." + type + "countrycode").val();
      //alert(country_code);
      var mobile = $("." + type + "mobileId").val();
      //alert(mobile);
      var sesmobile = $("#mob_no_ses").val();
      //alert(sesmobile);
      var user_id = $("#user_id").val();
      var signid = $("." + type + "signId").val();
      var product_id = $("." + type + "planId").val();
      if (country_code == "971") {
        if (mobile != "" && mobile.length == 9) {
          if (sesmobile == country_code + mobile) {
            if (type == "horo_") {
              $(".horo_payment_details").submit();
            } else if (type == "aaq_") {
              $(".payment_details").submit();
            } else {
              $(".tta_payment_details").submit();
            }
            // var formData = { msisdn: country_code+mobile,keyword:"SUB AM",validity:"1",mode:"wap" };
            // $.ajax({
            // 	type: "POST",
            // 	url: "http://52.77.82.47/sm_dipl/gateway/astro_sub.php",
            // 	data: JSON.stringify(formData),
            // 	dataType: 'json',
            // 	success: function(response)
            // 	{
            // 		var formData = { action: 'dubilling_payment_update',user_id:user_id,for:type,product_ids:product_id,signId:signid,source:"web",billing_response:response, };
            // 		$.ajax({
            // 			type: "POST",
            // 			url: API_URL,
            // 			data: JSON.stringify(formData),
            // 			dataType: 'json',
            // 			success: function(result)
            // 			{
            // 				if(result.returncode=='200'){

            // 					window.location.href = './payment-status.php?status=success'
            // 				}
            // 				else{

            // 					window.location.href = './payment-status.php?status=error'
            // 				}
            // 			}
            // 		});
            // 	}
            // });
          } else {
            swal("", "Registered mobile no and given mobile no not match", "warning");
          }
        } else {
          swal("", "Invalid number", "warning");
        }
      } else {
        swal("", "Invalid country code", "warning");
      }
    }
  });

  // $(".otp_submit").click(function(e)
  // 	{
  // 		e.preventDefault();
  // 		var user_id = $('#user_id').val();
  // 		var otp = $('#otp1').val()+ $('#otp2').val()+ $('#otp3').val()+ $('#otp4').val();

  // 		if (otp == "" ) {
  // 			swal('', 'Please Enter OTP', 'warning');
  // 		}else if(otp.length!='4')
  // 		{
  // 			swal('', 'Please Enter Valid OTP', 'warning');
  // 		}

  // 		else {
  // 			$('.cd-error-message').removeClass('is-visible');
  // 			validate_otp(user_id,otp, $('#for').val(),"","","");
  // 		}
  // 	});

  $(".resend_otp").click(function (e) {
    e.preventDefault();
    var user_id = $("#user_id").val();
    generate_resend_otp(user_id, $("#for").val());
    $("#otp").val("");
  });

  $(".fest_month").click(function () {
    var date = $(this).data("date");
    console.log("data-date::" + date);
    var now = new Date(date);
    if ($(this).hasClass("pre_month")) {
      var month_fest = "previous";
      now.setMonth(now.getMonth() - 1);
      current = now;
    } else {
      var month_fest = "next";
      //current = now.getFullYear(), String((now.getMonth()+1)).padStart(2,'0');
      now.setMonth(now.getMonth() + 1);
      current = now;
    }
    console.log("current::" + current);
    var month = current.toLocaleString("default", { month: "long" }) + " - " + current.getFullYear();
    console.log("month::" + month);

    $(".festival_month_year").html(month);

    var fest_month = current.getFullYear() + "-" + String(current.getMonth() + 1).padStart(2, "0") + "-" + String(current.getDate()).padStart(2, "0");
    console.log("fest_month::" + fest_month);
    fest_month.toString();
    $(".fest_month").data("date", fest_month);

    var formData = { action: "get_festival_data", month_type: month_fest, fest_date: fest_month };
    $.ajax({
      type: "POST",
      url: API_URL,
      data: JSON.stringify(formData),
      dataType: "json",
      success: function (response) {
        if (response.returncode == 200) {
          var festival_data = response.returndata;
          console.log(festival_data);
          var k = 1;
          $(".festival_reset").html("");
          $.each(festival_data, function (i, item) {
            if (k % 2 == 0) {
              $(".festival_reset").append(
                '<div class="cardinfo row "><div class="col-md-8"> <span>' +
                  item.festival_date +
                  "</span><h5>" +
                  item.festival_name +
                  '</h5></div><div class="col-md-4"><img src="./assets/images/festival_data/' +
                  item.festival_image +
                  '"></div> </div>'
              );
            } else {
              $(".festival_reset").append(
                '<div class="cardinfo row "><div class="col-md-4"><img src="./assets/images/festival_data/' +
                  item.festival_image +
                  '"></div><div class="col-md-8"> <span>' +
                  item.festival_date +
                  "</span><h5>" +
                  item.festival_name +
                  "</h5></div> </div>"
              );
            }
            k++;
          });
        } else {
          $(".festival_reset").html('<div class="cardinfo row "><div class="col-md-8"> <span></span><h5>No Festivals Found</h5></div> </div>');
        }
      },
      error: function (response) {
        console.log("An error occurred.");
        console.log(response);
      },
    });
  });

  $(".sign_btn").on("click", function (e) {
    e.preventDefault();
    console.log("for_name::::" + for_page);
    $(".cd-error-message").removeClass("is-visible");
    var mobile_no = $("#moboremailId").val();

    var passwd = $("#idpassword").val();
    var country_code = $(".countrycode_signin").val();
    var errmsg = 0;
    if (mobile_no == "") {
      $(".reg-mobileno-error-message").addClass("is-visible").text("Please Enter Mobile Number/Email");
      errmsg = 1;
    }
    if (mobile_no != "") {
      if (country_code == "91" && mobile_no.length != 10) {
        $(".reg-mobileno-error-message").addClass("is-visible").text("Mobile Number looks incorrect");
        errmsg = 1;
      } else if (country_code == "971" && mobile_no.length != 9) {
        $(".reg-mobileno-error-message").addClass("is-visible").text("Mobile Number looks incorrect");
        errmsg = 1;
      }
    }

    if (passwd == "") {
      $(".signin-pwd-error-message").addClass("is-visible").text("Please Enter Password");
      errmsg = 1;
    } else {
      var pass_strength = checkStrength(passwd);
      if (pass_strength != "Strong") {
        $(".signin-pwd-error-message").addClass("is-visible").text("Invalid Password");
        return false;
      }
    }

    if (errmsg == 0) {
      if ($("#remember-me").is(":checked")) {
        localStorage.countrycode = country_code;
        localStorage.usrname = mobile_no;
        localStorage.pass = passwd;
        localStorage.chkbx = 1;
      } else {
        localStorage.countrycode = "";
        localStorage.usrname = "";
        localStorage.pass = "";
        localStorage.chkbx = 0;
      }

      $(".cd-error-message").removeClass("is-visible");

      var formData = { country_code: country_code, mobile_no: mobile_no, password: passwd, source: "WEB", action: "login", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };
      $.ajax({
        type: "POST",
        url: API_URL,
        data: JSON.stringify(formData),
        dataType: "json",
        success: function (response) {
          if (response.returncode == 200) {
            console.log("signin Yes::::" + response.returndata);
            if (response.returndata == "YES") {
              swal({ title: "", text: "Do you need to close your existing session and continue with the New Login?", type: "success", showCancelButton: true, closeOnConfirm: false }, function (isConfirm) {
                if (isConfirm) {
                  var formData = { country_code: country_code, mobile_no: mobile_no, password: passwd, source: "WEB", action: "login_verify", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };

                  $.ajax({
                    type: "POST",
                    url: API_URL,
                    data: JSON.stringify(formData),
                    dataType: "json",
                    success: function (response) {
                      if (response.returncode == 200) {
                        var response_data = response.returndata;

                        var user_id = response_data.user_id;
                        $("#user_id").val(user_id);
                        if (response_data.role == "astrologer") {
                          $.post(
                            "./set_session.php",
                            {
                              user_id: response_data.user_id,
                              mobile_no: country_code + response_data.msisdn,
                              country_code: country_code,
                              session_code: response_data.session_code,
                              splash_screen: "1",
                              user_role: response_data.role,
                              my_astro_id: response_data.ast_id,
                              parent_id: response_data.parent_id,
                              image_path: response_data.image_path,
                            },
                            function (session_data) {
                              window.location.href = "./astro-profile.php";
                              //console.log('ssde::');
                            }
                          );
                        } else {
                          $.post(
                            "./set_session.php",
                            {
                              user_id: response_data.user_id,
                              mobile_no: country_code + response_data.msisdn,
                              country_code: country_code,
                              session_code: response_data.session_code,
                              splash_screen: "1",
                              parent_id: response_data.parent_id,
                              image_path: response_data.image_path,
                            },
                            function (session_data) {
                              window.location.href = "./home.php";
                            }
                          );
                        }
                      } else if (response.returncode == 201) {
                        swal("", response.returnmessage, "warning");
                      }
                    },
                    error: function (response) {
                      console.log("An error occurred.");
                      console.log(response);
                    },
                  });
                } else {
                  console.log("verify_before::" + for_page);
                  window.location.href = "./home.php";
                }
              });
            } else {
              var formData = { country_code: country_code, mobile_no: mobile_no, password: passwd, source: "WEB", action: "login_verify", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };

              $.ajax({
                type: "POST",
                url: API_URL,
                data: JSON.stringify(formData),
                dataType: "json",
                success: function (response) {
                  if (response.returncode == 200) {
                    var response_data = response.returndata;
                    console.log("response_data::+" + response_data);
                    if (response_data.role == "astrologer") {
                      $.post(
                        "./set_session.php",
                        {
                          user_id: response_data.user_id,
                          mobile_no: country_code + response_data.msisdn,
                          country_code: country_code,
                          session_code: response_data.session_code,
                          splash_screen: "1",
                          user_role: response_data.role,
                          my_astro_id: response_data.ast_id,
                          parent_id: response_data.parent_id,
                          image_path: response_data.image_path,
                        },
                        function (session_data) {
                          window.location.href = "./astro-profile.php";
                          //console.log('ssde::');
                        }
                      );
                    } else {
                      $.post(
                        "./set_session.php",
                        {
                          user_id: response_data.user_id,
                          mobile_no: country_code + response_data.msisdn,
                          country_code: country_code,
                          session_code: response_data.session_code,
                          splash_screen: "1",
                          parent_id: response_data.parent_id,
                          image_path: response_data.image_path,
                        },
                        function (session_data) {
                          if (for_page == "aaq_") {
                            window.location.href = "./ask-question.php";
                          } else if (for_page == "horo_") {
                            // window.location.reload();
                            show_billing_form();
                          } else {
                            window.location.href = "./home.php";
                          }
                        }
                      );
                    }

                    console.log("verify_strat::" + for_page);
                  } else if (response.returncode == 201) {
                    swal("", response.returnmessage, "warning");
                  }
                },
                error: function (response) {
                  console.log("An error occurred.");
                  console.log(response);
                },
              });
            }
          } else {
            swal("", response.returnmessage, "warning");
          }
        },
        error: function (response) {
          console.log("An error occurred.");
          console.log(response);
        },
      });
    } else {
      // alert('false');
      return false;
    }
  });

  $(".forgot_password_btn").click(function () {
    event.preventDefault();
    $(".otp").val("");
    $(".cd-error-message").removeClass("is-visible");
    var mobile_no = $("#reset_mobno").val();
    //var reset_email = $('#reset_email').val();
    var country_code = $(".countrycode_forgot").val();
    var errmsg = 0;

    if (mobile_no == "") {
      $(".reset-mob-error-message").addClass("is-visible").text("Please Enter Mobile Number/Email");
      errmsg = 1;
    }
    if (mobile_no != "") {
      if (country_code == "91" && mobile_no.length != 10) {
        $(".reset-mob-error-message").addClass("is-visible").text("Mobile Number looks incorrect");
        errmsg = 1;
      } else if (country_code == "971" && mobile_no.length != 9) {
        $(".reset-mob-error-message").addClass("is-visible").text("Mobile Number looks incorrect");
        errmsg = 1;
      }
    }
    if (errmsg == 0) {
      var formData = { country_code: country_code, mobile_no: mobile_no, action: "forgot_password", for: "forgot_password", source: "WEB", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };
      $.ajax({
        type: "POST",
        url: API_URL,
        data: JSON.stringify(formData),
        dataType: "json",
        success: function (response) {
          if (response.returncode == 200) {
            var response_data = response.returndata;
            var user_id = response_data.user_id;
            swal("", "OTP Sent to Your Mobile Number/E-mail", "success");
            $("#forgot_password").modal("hide");
            $("#otp_form").modal("show");
            $("#mobilenumber").html(mobile_no);
            //OTP Resend Start
            $("#mobile_no").val(mobile_no);
            $("#user_id").val(user_id);
            $("#for").val("forgot_password");
            $(".otp").val("");
            otp_counter();
            $(".otp_submit").click(function (e) {
              e.preventDefault();
              var user_id = $("#user_id").val();
              var otp = $("#otp1").val() + $("#otp2").val() + $("#otp3").val() + $("#otp4").val();

              if (otp == "") {
                swal("", "Please Enter OTP", "warning");
              } else if (otp.length != "4") {
                swal("", "Please Enter Valid OTP", "warning");
              } else {
                $(".cd-error-message").removeClass("is-visible");
                validate_otp(user_id, otp, $("#for").val(), "", "", "");
              }
            });
          } else {
            swal("", response.returnmessage, "warning");
          }
        },
      });
    }
  });

  $(".reset_password_btn").on("click", function (e) {
    e.preventDefault();
    $(".cd-error-message").removeClass("is-visible");
    var passwd = $("#reset-password").val();
    var confirm_passwd = $("#reset-confirm-password").val();
    var mobile_no = $("#mobile_no").val();
    var user_id = $("#user_id").val();
    var errmsg = 0;
    if (passwd == "") {
      //$('.reset-pwd-error-message').addClass('is-visible').text('Please Enter Password');
      swal("", "Please Enter Password", "warning");
      return false;
    }
    if (confirm_passwd == "") {
      //$('.reset-confirm-pwd-error-message').addClass('is-visible').text('Please Enter Password');
      swal("", "Please Enter Confirm Password", "warning");
      return false;
    }
    if (errmsg == 0) {
      if (confirm_passwd != passwd) {
        swal("", "Passwords doesn't match", "warning");
        return false;
      }
      var pass_strength = checkStrength(passwd);
      var confirm_pass_strength = checkStrength(confirm_passwd);

      if (pass_strength != "Strong") {
        swal("", "Invalid Password", "warning");
        return false;
        /* $('.reset-pwd-error-message').addClass('is-visible').text('Invalid Password');	
                    return false; */
      }

      $.post(API_URL, JSON.stringify({ user_id: user_id, mobile_no: mobile_no, new_password: passwd, confirm_password: confirm_passwd, action: "reset_password", source: "WEB", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" }), function (response) {
        if (response.returncode == 200)
          swal({ title: "", text: "Password Reset Successfully..!", type: "success" }, function () {
            window.location = "./home.php";
          });
        else swal("", response.returnmessage, "warning");
      });
    }
  });

  $("#profile_update_btn").click(function (event) {
    event.preventDefault();

    $(".pob").removeAttr("disabled");
    $(".sign").removeAttr("disabled");
    $(".email").removeAttr("disabled");
    // $('.msisdn').removeAttr("disabled")
    $("#profile_update_btn").hide();
    $("#profile_save_btn").css("visibility", "visible");
  });

  $("#profile_save_btn").on("click", function (event) {
    event.preventDefault();

    //if(validate()){
    var place_of_birth = $("#profile_pob").val();
    var profile_sign = $("#profile_sign").val();
    var profile_email = $("#profile_email").val();
    var profile_msisdn = $("#profile_msisdn").val();

    $.post("profile_submit.php", { profile_pob: place_of_birth, profile_sign: profile_sign, profile_email: profile_email, profile_msisdn: profile_msisdn, source: "WEB", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" }, function (response) {
      var res = $.parseJSON(response);
      if (res.returncode == 200) {
        swal({ title: "", text: "Your profile  has been updated", type: "success" }, function () {
          window.location.reload();
        });
      }
    });
    //}
  });

  $("#wizard-picture").bind("change", function (e) {
    e.preventDefault();
    var pimgsize = this.files[0].size;
    var pimgtype = this.files[0].type;
    if (pimgtype != "image/png" && pimgtype != "image/jpeg" && pimgtype != "image/jpg") {
      $(this).val("");
      swal("", "Hey! We noticed that you have uploaded an invalid image type", "warning");
      return false;
    } else if (pimgsize > 2000000) {
      $(this).val("");
      swal("", "Profile image size should be less than 2MB", "warning");
    } else {
      var formData = new FormData($("#pro_pic_upload_form")[0]);
      formData.append("onlypropic", 1);
      formData.append("source", "WEB");
      $.ajax({
        url: "profile_submit.php",
        type: "POST",
        data: formData,
        success: function (data) {
          var obj = $.parseJSON(data);

          if (obj.returncode == 200) {
            $.post("./set_session.php", { image_path: obj.profile_img }, function (session_data) {
              $(".profile_image").attr("src", obj.profile_img);
              swal({ title: "", text: "Your profile picture has been updated", type: "success" }, function () {
                window.location.reload();
              });
            });
          } else {
            swal({ title: "", text: obj.returnmessage, type: "warning" }, function () {
              //window.location.href = "settings.php";
            });
          }
        },
        cache: false,
        contentType: false,
        processData: false,
      });
    }
  });

  $(".astro_list").on("click", ".astro_call", function () {
    event.preventDefault();
    user_id = $("#user_id").val();
    if (user_id == "") {
      $(".astro_book_appoinment").hide();
      swal({ title: "", text: "Kindly register to book an appoinment", type: "warning" }, function () {
        window.location.reload();
      });
    } else {
      $("#astroAppoinment-confirm").hide();
      $(".astroAppoinment_info").show();
      $(".astroAppoinment_info_parent").show();
      $("#astroAppoinment-thankyou").hide();
      $(".slot_info").hide();
      var astro_id = $(this).data("astro_id");
      var astro_name = $(this).data("astro_name");
      var astro_language = $(this).data("astro_language");
      var astro_method = $(this).data("astro_method");
      var astro_experience = $(this).data("astro_experience");
      var astro_img = $(this).data("astro_img");
      var astro_details =
        '<div class="row astro-info d-flex align-items-center"><div class="col-md-4"><div class="avatar text-center"><img src="' +
        astro_img +
        '" width="100%" height="200" alt="Astrologer consultation online"><br/><h5> Astro ' +
        astro_name +
        ' </h5>    </div></div><div class="col-md-4 astro-info-list"><ul><li><i class="fa fa-language"></i> ' +
        astro_language +
        '</li><li><i class="fa fa-asterisk"></i> ' +
        astro_method +
        '</li></ul></div><div class="col-md-4 astro-info-list"><ul><li><i class="fa fa-graduation-cap"></i> ' +
        astro_experience +
        ' Years</li><li><i class="fa fa-thumbs-up"></i> 198</li></ul></div></div>';

      var formData = { astro_id: astro_id, action: "get_astro_available_date", source: "WEB", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };
      $.ajax({
        type: "POST",
        url: API_URL,
        data: JSON.stringify(formData),
        dataType: "json",
        success: function (response) {
          astro_details += '<h5 class="pt-3"> Available Dates : </h5><hr/><div class="astro_appoinment_date flex-wrap d-flex flex-row justify-content-start align-items-center py-4">';

          if (response.returncode == 200) {
            var astro_avail_date = response.returndata;
            console.log("get_astro_available_date" + JSON.stringify(astro_avail_date));
            $.each(astro_avail_date, function (i, item) {
              astro_details +=
                '<div class="p-2 d-flex flex-column justify-content-center align-items-center datebox avl_date" data-available-date=' +
                item.avl_date +
                '><div class="date_avail"><span class="text-danger ">' +
                item.day_name +
                '</span><h4 class="available_date" >' +
                item.date_val +
                "</h4><span>" +
                item.avail_mon +
                "</span></div></div>";
            });
            astro_details += "</div>";
            $(".astroAppoinment_info").on("click", ".date_avail", function () {
              $(".date_avail").removeClass("active");
              $(this).addClass("active");
            });
            $(".astroAppoinment_info").on("click", ".avl_date", function () {
              $(".slot_info").show();
              var available_date = $(this).data("available-date");
              var formData1 = { astro_id: astro_id, avail_date: available_date, action: "get_astro_avail_date_timings", source: "WEB", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };

              $.ajax({
                type: "POST",
                url: API_URL,
                data: JSON.stringify(formData1),
                dataType: "json",
                success: function (response) {
                  if (response.returncode == 200) {
                    //vignesh change
                    var morning_array = {};
                    var evening_array = {};
                    $(".morning").html("");
                    $(".evening").html("");
                    var astro_avail_date_timing = response.returndata;
                    $.each(astro_avail_date_timing, function (i, item) {
                      if (item.slot_id < 25) {
                        morning_array[i] = item;
                      } else {
                        evening_array[i] = item;
                      }
                    });
                    console.log("working" + morning_array);
                    if (!$.isEmptyObject(morning_array)) {
                      $(".morning_show").show();
                      $.each(morning_array, function (i, item) {
                        if (item.status == "booked") {
                          $(".morning").append(' <a  class="btn btn-light btn-sm avl_timings_booked" data-avail_id=' + item.avl_id + " >" + item.display_time + "</a>");
                        } else {
                          $(".morning").append(' <a  class="btn btn-light btn-sm avl_timings" data-avail_id=' + item.avl_id + " data-tta_avail_date = " + item.avl_date + "  >" + item.display_time + "</a>");
                        }
                      });
                    } else {
                      $(".morning_show").hide();
                    }
                    if (!$.isEmptyObject(evening_array)) {
                      $(".evening_show").show();
                      $.each(evening_array, function (i, item) {
                        if (item.status == "booked") {
                          $(".evening").append(' <a class="btn btn-light btn-sm avl_timings_booked" data-avail_id=' + item.avl_id + "> " + item.display_time + "</a>");
                        } else {
                          $(".evening").append(' <a  class="btn btn-light btn-sm avl_timings" data-avail_id=' + item.avl_id + " data-tta_avail_date = " + item.avl_date + "> " + item.display_time + "</a>");
                        }
                      });
                    } else {
                      $(".evening_show").hide();
                    }
                    $(".slot_info").append("</div> ");
                  }
                },
              });
            });
          }
          /* else{
						 swal('', response.returnmessage, 'warning');
					}  */
          $(".astroAppoinment_info").html(astro_details);
        },
      });
    }
  });

  $(".slot_info").on("click", ".avl_timings", function () {
    if ($(this).hasClass("btn-success")) {
      $(".avl_timings").removeClass("btn-success");
      $(".avl_timings").addClass("btn-light");
      $(this).removeClass("btn-success");
      $(this).addClass("btn-light");
    } else if ($(this).hasClass("btn-light")) {
      $(".avl_timings").removeClass("btn-success");
      $(".avl_timings").addClass("btn-light");
      $(this).removeClass("btn-light");
      $(this).addClass("btn-success");
    }
  });
  $(".slot_info").on("click", ".book_appoinment", function () {
    user_id = $("#user_id").val();
    var avail_id = $(".avl_timings.btn-success").data("avail_id");

    var tta_avail_date = $(".avl_timings.btn-success").data("tta_avail_date");
    var tta_avail_timings = $(".avl_timings.btn-success").text();
    if (tta_avail_timings == "") {
      swal("", "Please Choose a Slot", "warning");
      return false;
    }
    $("#tta_avail_id").val(avail_id);
    $("#tta_avail_date").val(tta_avail_date);
    $("#tta_avail_time").val(tta_avail_timings);
    $(".astroAppoinment_info").hide();
    $(".slot_info").hide();
    $(".book_message").html("Your Astrologer fixed for " + tta_avail_date + " - " + tta_avail_timings + "");
    $("#astroAppoinment-confirm").show();
  });
  $("#astroAppoinment-confirm").on("click", ".book_app_confirm", function () {
    $("#astroAppoinment").modal("hide");
    $("#astroAppoinment-confirm").hide();
    $("#for_payment").val("tta_");
    $(".package").html("Talk to Astrologer");
    $("#choosePlanpayment").modal("show");
  });

  $("#searchId").on("keyup", function () {
    event.preventDefault();
    var astro_search_value = $(this).val();

    if (astro_search_value.length > 2 || astro_search_value.length == 0) {
      var formData = { astro_search: astro_search_value, action: "get_astrologer_details", source: "WEB", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };
      $.ajax({
        type: "POST",
        url: API_URL,
        data: JSON.stringify(formData),
        dataType: "json",
        success: function (response) {
          if (response.returncode == 200) {
            $(".astro_list").html("");

            var astro_det = response.returndata;
            $.each(astro_det, function (i, item) {
              if (item.image_path == null) {
                item.image_path = APPLICATION_URL + "gateway/assets/images/profile/avatar.png";
              }

              $(".astro_list").append(
                '<div class="col"><div class="card h-100"><div class="card-top align-items-center d-flex"><div class="avatar"><img src="' +
                  item.image_path +
                  '" width="100%" height="200" alt="Astrologer consultation online"></div><div class="name-content"><h5 class="cust-name astroname" > ' +
                  item.ast_name +
                  '</h5><div><img src="./assets/images/icons/ic-language.svg" alt="Language" title="Language" width="16" height="16" class="me-1"> ' +
                  item.language +
                  '</div><div><img src="./assets/images/icons/rupee-indian.png" alt="Rupee" title="Rupee" width="16" height="16" class="me-1">' +
                  item.method +
                  '</div></div></div><div class="card-body d-flex justify-content-between align-items-center"><div class="float-start"><ul><li><i class="fa fa-graduation-cap"></i> ' +
                  item.experience +
                  ' Years</li><li><i class="fa fa-thumbs-up"></i> 198</li></ul></div><div class="float-end text-center"><div class="d-flex justify-content-center align-items-center"><div data-bs-toggle="modal" data-bs-target="#astroAppoinment" class="astro_call" data-astro_id="' +
                  item.ast_id +
                  '" data-astro_name="' +
                  item.ast_name +
                  '" data-astro_language="' +
                  item.language +
                  '" data-astro_method="' +
                  item.method +
                  '" data-astro_experience="' +
                  item.experience +
                  '" data-astro_img="' +
                  item.image_path +
                  '" > Call <a ><img src="./assets/images/icons/phone_icon.png" width="30px"></a></div></div></div></div></div></div>'
              );
            });
          } else {
            //swal('', response.returnmessage, 'warning');
            $(".astro_list").html("");
          }
        },
      });
    }
  });

  function validate_otp(user_id, otp_code, type, password, for_name, mobile) {
    for_page = for_name;
    console.log("user_id::" + user_id);
    $.post(API_URL, JSON.stringify({ user_id: user_id, otp_code: otp_code, action: "validate_otp", for: type, source: "WEB", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" }), function (response) {
      if (response.returncode == 200) {
        if (type == "register") {
          var message;
          if (for_page == "horo_") {
            message = "Registered Successfully..!  Proceed to Pay";
          } else if (for_page == "aaq_") {
            message = "Registered Successfully..!  Now you can ask your question";
          } else {
            message = "Registered Successfully..! Kindly Sign In to Proceed";
          }
          swal({ title: "", text: message, type: "success" }, function () {
            console.log("for_name::::" + for_name);
            if (for_page == "horo_" || for_page == "aaq_") {
              console.log("mobile no :::" + mobile);
              $("#moboremailId").val(mobile);
              $("#idpassword").val(password);
              $(".sign_btn").trigger("click");
              $("#otp_form").modal("hide");
            } else {
              window.location = "./home.php";
            }
          });
          $(".otp").val("");
        } else if (type == "forgot_password") {
          $(".otp").val("");
          $("#otp_form").modal("hide");
          $("#reset_password").modal("show");
        }
      } else if (response.returncode == 201) {
        if (response.returndata.resend_count >= 3) {
          //On 3rd count
          swal(
            {
              title: "",
              text: "Retry & Resend Count Exceeded..!",
              type: "warning",
            },
            function () {
              window.location = "./home.php";
            }
          );
        } else {
          $(".otp").val("");
          swal(
            {
              title: "",
              text: "Retry Count Exceeded..! Do you want to Resend OTP again?",
              type: "warning",
            },
            function () {
              generate_resend_otp(user_id, type);
            }
          );
        }
      } else if (response.returncode == 202) {
        swal("", "Incorrect OTP", "warning");
        $(".otp").val("");
      }
    });
  }

  function generate_resend_otp(user_id, type) {
    $.post(API_URL, JSON.stringify({ user_id: user_id, action: "resend_otp", for: type, source: "WEB", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" }), function (response) {
      if (response.returncode == 200) {
        swal("", "OTP Sent to Your Mobile Number/E-mail", "success");
        otp_counter();
      } else if (response.returncode == 202) {
        swal("", "Retry & Resend Count Exceeded..!", "warning");
        //sleep(3000);
        window.location = "./home.php";
      }
    });
  }

  function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  function checkStrength(password) {
    var strength = 0;
    if (password.length < 5) {
      return "Too short";
    }
    if (password.length > 10) {
      return "Too Length";
    }
    if (password.length >= 5) strength += 1;
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 2;
    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 2;
    var regex = new RegExp("^[a-zA-Z0-9]+$"); //Spl Charaters Restrict
    if (!regex.test(password)) {
      strength -= 1;
    }
    if (strength < 2) {
      return "Weak";
    } else if (strength == 2) {
      return "Good";
    } else {
      return "Strong";
    }
  }
});
function otp_counter() {
  console.log("counter_start");
  /* $('#resend_otp_click').removeClass('resend_otp');
		$('#resend_otp_click').attr('disabled'); */
  $("#resend_otp_click").hide();
  var timer = (duration = 59),
    minutes,
    seconds;
  display = document.querySelector("#time");
  if (display.textContent > "00") {
    var highestTimeoutId = setTimeout(";");
    for (var i = 0; i < highestTimeoutId; i++) {
      clearTimeout(i);
    }
  }

  //startTimer(oneMinute, display);
  var time_stopper = setInterval(function () {
    seconds = parseInt(timer % 60, 10);

    seconds = seconds < 10 ? "0" + seconds : seconds;
    display.textContent = "00:" + seconds;

    if (--timer < 0) {
      timer = duration;
      /* $('#resend_otp_click').addClass('resend_otp');

                $('#resend_otp_click').attr('enabled'); */
      $("#resend_otp_click").show();
      clearInterval(time_stopper);
    }
  }, 1000);
}

$(document).ready(function () {
  $(".form-control").keypress(function (event) {
    return event.charCode != 32;
  });
  $(".otp").keyup(function (event) {
    if ($(this).val() != "") {
      $(this).next(".otp").focus();
    } else {
      $(this).prev(".otp").focus();
    }
    return false;
  });

  get_panchang_details();
  var date_input_panchang = $('input[name="date_panchang"]'); //our date input has the name "date"

  var options_panchang = {
    format: "yyyy-mm-dd",
    todayHighlight: true,
    autoclose: true,
  };

  date_input_panchang.datepicker(options_panchang);

  var date_input = $('input[name="date"]'); //our date input has the name "date"
  // var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
  var maxdate = new Date($(date_input).data("max_date"));
  var options = {
    format: "yyyy-mm-dd",
    // container: container,
    todayHighlight: true,
    autoclose: true,
    endDate: maxdate,
  };

  date_input.datepicker(options);

  var date_input_aaq = $('input[name="aaq_date_of_birth"]');
  var maxdate = new Date($(date_input_aaq).data("max_date"));
  var options_aaq = {
    format: "yyyy-mm-dd",
    todayHighlight: true,
    autoclose: true,
    endDate: maxdate,
  };

  date_input_aaq.datepicker(options_aaq);

  var date_input1 = $('input[name="horo_date_of_birth"]');
  var maxdate = new Date($(date_input1).data("max_date"));
  //   var mindate = new Date($(date_input1).data("mindate"));
  var options1 = {
    format: "yyyy-mm-dd",
    todayHighlight: true,
    autoclose: true,
    endDate: maxdate,
  };

  date_input1.datepicker(options1);
  if (localStorage.chkbx == 1) {
    $("#countrycode").val(localStorage.countrycode);
    $("#moboremailId").val(localStorage.usrname);
    $("#idpassword").val(localStorage.pass);

    $("#remember-me").prop("checked", true);
  } else {
    $("#remember-me").prop("checked", false);
  }
});

function onlyNumberKey(evt) {
  // Only ASCII character in that range allowed
  var ASCIICode = evt.which ? evt.which : evt.keyCode;
  if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) return false;
  return true;
}

function get_panchang_details() {
  var cur_date = new Date().getDate();
  var cur_month = new Date().getMonth() + 1;
  var cur_year = new Date().getFullYear();
  var today = cur_year + "-" + cur_month + "-" + cur_date;
  var location_name = $("#panchang_location option:selected").val();
  var panchang_date_data = $("#panchagam_date").val();

  var panchang_date = panchang_date_data == null || panchang_date_data == "" ? today : panchang_date_data;
  var panchang_date_data1 = new Date(panchang_date);

  var panchang_location = location_name == "Choose..." ? "tamil" : location_name;

  $("#panchangam_ch_date").html(panchang_date_data1.toLocaleString("default", { month: "short" }) + " " + String(panchang_date_data1.getDate()).padStart(2, "0") + ", " + panchang_date_data1.getFullYear());

  var panchang_locate = $("#panchang_location option:selected").attr("data-location_name");
  var panchang_location_name = panchang_locate == null || panchang_locate == "" ? "Tamil Nadu, Chennai" : panchang_locate;

  $("#panchangam_ch_loc").html(panchang_location_name);

  var formData = { language: panchang_location, date: panchang_date, action: "get_panchang_details", source: "WEB", oauth: "7ff7c3ed4e791da7e48e1fbd67dd5b72" };
  $.ajax({
    type: "POST",
    url: API_URL,
    data: JSON.stringify(formData),
    dataType: "json",
    success: function (response) {
      if (response.returncode == 200) {
        var time_val = "";
        var panchang_details = JSON.stringify(response.returndata);
        //console.log('panchang_details ::'+panchang_details );
        var panchang_details_arr = JSON.parse(panchang_details);
        var panchang_details_head = {};
        var panchang_details_content = {};
        var j = 0;
        $.each(panchang_details_arr, function (i, item) {
          if (j >= 2 && j <= 7) {
            panchang_details_head[i] = item;
          } else if (j >= 8 && i != "is_active") {
            panchang_details_content[i] = item;
          }
          j++;
        });

        var html = '<div class="hover-info"><ul class="list-group list-group-horizontal-sm">';
        $.each(panchang_details_head, function (i, item) {
          if (item != null) {
            if (i == "sunrise" || i == "moonrise") {
              time_val = "AM";
            } else if (i == "sunset" || i == "moonset") {
              time_val = "PM";
            } else {
              time_val = "";
            }
            html += '<li class="list-group-item">' + i.charAt(0).toUpperCase() + i.slice(1).split("_").join(" ") + "<br /><small>" + item + "" + time_val + "</small> </li>";
          }
        });

        html += '</ul> </div><table class="table table-hover" style="margin-top: 70px;"> <tbody>';

        $.each(panchang_details_content, function (i, item) {
          console.log("panchang_details_content" + item);
          if (item != null && item != "" && i != "added_on") {
            html += "<tr><td><strong>" + i.charAt(0).toUpperCase() + i.slice(1).split("_").join(" ") + "</strong></td><td> " + item + "<br><br></td></tr>";
          }
        });

        html += "</tbody> </table>";
        $("#panchangam").html(html);

        $("#panchang_location").modal("hide");
        $("#panchang_date").modal("hide");
      } else {
        $("#panchangam").html("");
        $("#panchang_location").modal("hide");
        $("#panchang_date").modal("hide");
      }
    },
  });
}

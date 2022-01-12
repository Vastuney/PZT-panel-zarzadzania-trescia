$(document).ready(function () {

    function feedback(feedback, text) {
      if(feedback == "error") {
        jQuery('body')
        .prepend('<div class="alert alert-danger active">'+ text +'<button type="button" onClick="closeAlert()" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button></div>');
      } else {
        jQuery('body')
        .prepend('<div class="alert alert-primary active">'+ text +'<button type="button" onClick="closeAlert()" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button></div>');
        setTimeout(function() {
          location.reload();
        }, 5000);
      }
    }

    $("form.ajax").submit(function (event) {
        event.preventDefault();
        var data = new FormData(this);
        var array = [];
        $(this).find(':input:not(:disabled)').prop('disabled',true);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "../lib/main.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: function (data) {
                data = $.parseJSON(data);
                if(data['return'] == 'location') {
                  console.log("test");
                  window.location.href = data['text'];
                } else {
                  console.log(data['return'] + data["text"]);
                  feedback(data['return'], data['text']);
                }
            },
            error: function (e) {
                console.log("ERROR : ", e);
            }
        });
    });
    $('.delete').click(function(){
      var target = $(this).attr("for");
      var id = $(this).attr("data-id");
      if (confirm('Na pewno chcesz usunąć plik?')) {
        $.ajax({
            type: "POST",
            url: "../lib/main.php",
            data: {
              method: "deleteFile",
              type: target, // < note use of 'this' here
              id: id
            },
            timeout: 800000,
            success: function (data) {
                data = $.parseJSON(data);
                if(data['return'] == "location") {
                  window.location.href = data['text'];
                } else {
                  console.log(data['return'] + data["text"]);
                  feedback(data['return'], data['text']);
                }
            },
            error: function (e) {
                console.log("ERROR : ", e);
            }
        });
      }
    });
    $('.share').click(function(){
      var target = $(this).attr("data-target");
      var targetId = $(this).attr("for");
      var userid = $(this).attr("data-id");
      $.ajax({
          type: "POST",
          url: "../lib/main.php",
          data: {
            method: "share",
            type: target,
            targetId: targetId,
            id: userid
          },
          timeout: 800000,
          success: function (data) {
              data = $.parseJSON(data);
              if(data['return'] == "location") {
                window.location.href = data['text'];
              } else {
                console.log(data['return'] + data["text"]);
                feedback(data['return'], data['text']);
              }
          },
          error: function (e) {
              console.log("ERROR : ", e);
          }
      });
    });
    $('.unshare').click(function(){
      var target = $(this).attr("data-target");
      var targetId = $(this).attr("for");
      var userid = $(this).attr("data-id");
      $.ajax({
          type: "POST",
          url: "../lib/main.php",
          data: {
            method: "unshare",
            type: target,
            targetId: targetId,
            id: userid
          },
          timeout: 800000,
          success: function (data) {
              data = $.parseJSON(data);
              if(data['return'] == "location") {
                window.location.href = data['text'];
              } else {
                console.log(data['return'] + data["text"]);
                feedback(data['return'], data['text']);
              }
          },
          error: function (e) {
              console.log("ERROR : ", e);
          }
      });
    });
    $('.deleteNotifications').click(function(){
      if (confirm('Na pewno chcesz usunąć powiadomienia?')) {
        $.ajax({
            type: "POST",
            url: "../lib/main.php",
            data: {
              method: "deleteNotifications"
            },
            timeout: 800000,
            success: function (data) {
                data = $.parseJSON(data);
                if(data['return'] == "location") {
                  window.location.href = data['text'];
                } else {
                  console.log(data['return'] + data["text"]);
                  feedback(data['return'], data['text']);
                }
            },
            error: function (e) {
                console.log("ERROR : ", e);
            }
        });
      }
    });


});

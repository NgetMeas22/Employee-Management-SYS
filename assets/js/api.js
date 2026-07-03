// =============== Display Data ===================
loadData();
function loadData() {
  $.ajax({
    url: "select.php",
    type: "GET",
    success: function (data) {
      $("#display").html(data);
    },
  });
}

// ============= Preview Image =================
$("#image").change(function () {
  let file = this.files[0];
  if (file) {
    $("#preview").attr("src", URL.createObjectURL(file)).show();
  }
});

//============== Clear Form ====================

function clearForm() {
  $("#formSubmit")[0].reset();
  $("#preview").attr("src", "").hide();
}

// ============= Form Insert/Update =============

$("#formSubmit").submit(function (e) {
  e.preventDefault();

  let formdata = new FormData();

  formdata.append("id", $("#id").val());
  formdata.append("title", $("#name").val());
  formdata.append("price", $("#price").val());
  formdata.append("qty", $("#qty").val());
  formdata.append("img", $("#image")[0].files[0]);

  let url = $("#id").val() == "" ? "insert.php" : "update.php";

  $.ajax({
    url: url,
    type: "POST",
    data: formdata,
    processData: false,
    contentType: false,

    success: function (success) {
      alert(success);
      loadData();
      clearForm();
    },
  });
});

// =============== Delete ================
$(document).on("click", ".delete", function () {
  let id = $(this).data("id");
  let image = $(this).data("image");

  $.ajax({
    url: "delete.php",
    type: "POST",
    data: {
      id: id,
      image: image,
    },
    success: function (success) {
      alert(success);
      loadData();
    },
  });
});

// ================= Cancel Button =================
$("#cancelBtn").click(function () {
  clearForm();
});

// ================= UPDATE =================

$(document).on("click", ".edit", function () {
  $("#id").val($(this).data("id"));
  $("#name").val($(this).data("title"));
  $("#price").val($(this).data("price"));
  $("#qty").val($(this).data("qty"));
  $("#preview").attr("src", $(this).data("image"));
  $("#changeBtn").val("Update");
});

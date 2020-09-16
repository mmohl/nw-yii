var tags = {};
var variantIndex = 0;
var colSize = 12;

var input = document.getElementById("variant0");

tags["0"] = new Tagify(input, {
  dropdown: {
    classname: "color-blue",
    enabled: 0, // show the dropdown immediately on focus
    maxItems: 5,
    position: "text", // place the dropdown near the typed text
    closeOnSelect: false, // keep the dropdown open after selecting a suggestion
    highlightFirst: true,
  },
});

$(document).on("click", ".variant-add-sub", ({ target }) => {
  $(`button.variant-add-sub[data-index="${variantIndex}"]`).attr(
    "disabled",
    "disabled"
  );
  $(`button.btn-danger[data-index="${variantIndex}"]`).attr(
    "disabled",
    "disabled"
  );
  variantIndex += 1;
  colSize -= 1;
  let btn = $(target).closest("button");
  let colParent = $(btn).parent().parent();

  $(colParent).prev().append(`
      <div data-row-index="${variantIndex}" class="row sub">
        <div class="col-lg-${colSize}">
          <input type="text" id="variant${variantIndex}">
        </div>
        <div class="col-lg-1" style="z-index: 10;">
          <div class="btn-group mr-2" role="group" aria-label="First group">
              <button data-index="${variantIndex}" type="button" data-toggle="tooltip" data-placement="top" title data-original-title="Tambah Sub Varian" type="button" class="btn btn-primary variant-add-sub"><span class="fas fa-plus"></span></button>
              <button type="button" data-toggle="tooltip" data-placement="top" title data-original-title="Hapus semua varian" type="button" class="btn btn-warning"><span class="fas fa-eraser"></span></button>
              <button data-index="${variantIndex}" type="button" data-toggle="tooltip" data-placement="top" title data-original-title="Hapus semua varian" type="button" class="btn btn-danger"><span class="fas fa-times"></span></button>
          </div>
        </div>
      </div>
    `);

  // console.dir(tmp);
  setTimeout(() => {
    let tmp = document.getElementById(`variant${variantIndex}`);
    tags[`${variantIndex}`] = new Tagify(tmp, {
      dropdown: {
        classname: "color-blue",
        enabled: 0, // show the dropdown immediately on focus
        maxItems: 5,
        position: "text", // place the dropdown near the typed text
        closeOnSelect: false, // keep the dropdown open after selecting a suggestion
        highlightFirst: true,
      },
    });
  }, 100);
});

$(document).on("click", ".btn-danger", ({ target }) => {
  let btn = $(target).closest("button");
  let index = $(btn).data("index");

  $(`div.row[data-row-index="${index}"]`).remove();

  variantIndex -= 1;
  colSize += 1;

  delete tags[variantIndex];

  $(`button.variant-add-sub[data-index="${variantIndex}"]`).removeAttr(
    "disabled"
  );
  $(`button.btn-danger[data-index="${variantIndex}"]`).removeAttr("disabled");
});
